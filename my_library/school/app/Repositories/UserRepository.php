<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Image;

class UserRepository implements UserRepositoryInterface
{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get($role)
    {
        if($role == "all"){
            return $this->user->all();
        }
        try{
            return $this->user->role($role)->get();
        }catch (\Exception $error){
            return [];
        }
    }

    public function show($user)
    {
        return $user;
    }

    public function delete($user)
    {
        $user->delete();
    }

    public function store($data)
    {
        $record['name']         = $data['name'];
        $record['username']     = $data['username'];
        $record['id_number']    = $data['id_number'];
        $record['email']        = $data['email'];
        $record['category_id']  = $data['category_id'];
//        $record['language_id'] = $data['language_id'];
        $record['branch_id']    = $data['branch_id'];
        $record['phone']        = $data['phone'] ?? "";
        $record['address']      = $data['address'] ?? "";
        $record['nationality']  = $data['nationality'] ?? "";
        $record['password']     = Hash::make($data['password']);
        $record['is_suspended'] = 0;
        $record['status']       = 1;
        if(isset($data['image'])){
            $data['image'] = $this->imageStore($data);
        }
        $current = $this->user->create($record);
        $current->assignRole($data['role']);
    }

    public function update($user,$data)
    {
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

        if(isset($data['image'])){
            $data['image'] = $this->imageStore($data);
        }
        $user->update($data);
    }

    public function suspend($user)
    {
        $user->update(['is_suspended' => 1]);
    }

    public function unsuspend($user)
    {
        $user->update(['is_suspended' => 0]);
    }

    public function imageStore($request): string
    {
        $image = $request['image'];
        $name = time().'.'.$image->extension();
        $img = Image::make($image->path());
        $filePath = public_path('/thumbnails');
        $img->resize(110, 110, function ($const) {$const->aspectRatio();})->save($filePath.'/'.$name);
        $filePath = public_path('/images');
        $image->move($filePath, $name);
        return $name;
    }
}
