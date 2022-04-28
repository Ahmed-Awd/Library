<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class MenuRepository implements MenuRepositoryInterface
{
    private Menu $menu;
    private Restaurant $restaurant;
    private Item $item;

    public function __construct(Menu $menu, Restaurant $restaurant, Item $item)
    {
        $this->menu = $menu;
        $this->restaurant = $restaurant;
        $this->item = $item;
    }

    public function menu($restaurant, $token)
    {
        $menu =  $this->menu->where('restaurant_id', $restaurant->id)->select('id', 'restaurant_id')
            ->with(
                'restaurant',
                'categories:id,name,menu_id',
                'categories.items:id,category_id,name,description,currency,image,price,is_available,rank',
                'categories.items.currentOffer'
            )->first();
        if ($token != null) {
            try {
                $user = PersonalAccessToken::findToken($token)->tokenable_id;
                if (User::find($user)->hasRole('owner')) {
                    $menu =  $this->menu->where('restaurant_id', $restaurant->id)->select('id', 'restaurant_id')
                        ->with(
                            'restaurant',
                            'categories:id,name,menu_id',
                            'categories.allItems:id,category_id,name,description,currency,image,price,is_available',
                            'categories.allItems.currentOffer'
                        )->first();
                }
            } catch (\Exception $exception) {
                return $menu;
            }
            foreach ($menu->categories as $category) {
                foreach ($category->items as $item) {
                    $item->is_favourite = $this->isFavourite($user, $item);
                    $item->restaurant_id = $restaurant->id;
                }
            }
        }
        return $menu;
    }

    public function categories($restaurant)
    {
        return $restaurant->menu->categories;
    }

    public function createCategory($restaurant, $data)
    {
        $restaurant->menu->categories()->create($data);
    }

    public function showCategory($category)
    {
        return $category;
    }

    public function updateCategory($category, $data)
    {
        $category->update($data);
    }

    public function deleteCategory($category)
    {
        $category->delete();
    }

    public function items($category)
    {
        return $this->item->where('category_id', $category->id)->with('currentOffer')->get();
    }

    public function allItems($restaurant)
    {
        $ids =  $restaurant->menu->categories->pluck('id')->toArray();
        return Item::whereIn('category_id', $ids)->with('currentOffer')->get();
    }

    public function createItem($category, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = imageStore($data['image']);
        }
        return $category->allItems()->create($data);
    }

    public function showItem($item, $token)
    {
        $item = Item::where('id', $item->id)->with('tax:id,name,percentage', 'currentOffer')->first();
        if ($token != null) {
            $user = PersonalAccessToken::findToken($token);
            if ($user) {
                $user = $user->tokenable_id;
                $item->is_favourite = $this->isFavourite($user, $item);
            } else {
                $item->is_favourite = false;
            }
        }
        $item->restaurant_id = $item->category ? $item->category->menu->restaurant_id : '';
        $item->restaurant_name = $item->category ? $item->category->menu->restaurant->name : '';
        return $item;
    }

    public function isFavourite($user, $item): bool
    {
        $exists = $item->followers->contains($user);
        if ($exists) {
            return true;
        }
        return false;
    }

    public function updateItem($item, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = imageStore($data['image']);
        }
        $item->update($data);
    }

    public function updateRank($item, $rank)
    {
        Item::where('id', $item)->update(['rank' => $rank]);
    }

    public function deleteItem($item)
    {
        $item->delete();
    }

    public function assignItem($item, $option)
    {
        $item->options()->syncWithoutDetaching($option);
    }

    public function unAssignItem($item, $option)
    {
        $item->options()->detach($option);
    }

    public function unAssignItemAll($item)
    {
        $item->options()->detach();
    }

    public function changeAvailability($item): bool
    {
        $item->is_available = !$item->is_available;
        $item->save();
        return $item->is_available;
    }

    public function favOrUnFavItem($item): bool
    {
        $exist = $item->followers()->where('user_id', Auth::user()->id)->first();
        $user = Auth::user()->id;
        isset($exist) ? $item->followers()->detach($user) : $item->followers()->attach($user);
        if ($exist) {
            return false;
        } else {
            return true;
        }
    }
}
