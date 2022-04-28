<?php

namespace App\Repositories;

use App\Models\ItemOffer;

class ItemOfferRepository implements ItemOfferRepositoryInterface
{
    private ItemOffer $itemOffer;

    public function __construct(ItemOffer $itemOffer)
    {
        $this->itemOffer = $itemOffer;
    }

    public function get($search, $order)
    {
        $query =  $this->itemOffer;
        if ($search != '') {
            $query = $query->whereHas('item', function ($query) use ($search) {
                return $query->where('name', 'LIKE', '%'.$search.'%');
            });
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        }
        return $query->with('item:id,name,price,image,rank')->paginate(15);
    }

    public function show($itemOffer)
    {
        return $this->itemOffer->where('id', $itemOffer->id)->with('item:id,name,price,image,rank')->first();
    }

    public function store($data, $item)
    {
        $this->itemOffer->updateOrinsert(["item_id" => $item], $data);
    }

    public function delete($itemOffer)
    {
        $itemOffer->delete();
    }
}
