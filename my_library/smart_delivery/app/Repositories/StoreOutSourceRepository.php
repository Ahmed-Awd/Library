<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreOutSourceRepository implements StoreOutSourceRepositoryInterface
{
    private Store $store;
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function get($filter)
    {
        return $this->store->with('owner')->with('type')
        ->where($filter)->paginate(15);
    }

    public function show($store)
    {
        return $store->load(['owner', 'type','setting']);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data) {
            $id = DB::table('users')->insertGetId([
                "name" => $data["owner_name"],
                "username" => $data["owner_username"],
                "email" => $data["owner_email"],
                "password" => bcrypt(Str::random(10)),
                "created_at" =>  Carbon::now(),
            ]);

            DB::table('stores')->insertOrIgnore([
                "name" => $data["store_name"],
                "lng" => $data["location"]['lng'],
                "lat" => $data["location"]['lat'],
                "address" => $data["address"],
                "default_delivery_time" => $data["default_delivery_time"],
                "owner_id" => $id,
                "type_id" => $data["activity_type"],
                "outsource_id" => auth()->user()->id,
                "created_at" =>  Carbon::now(),
            ]);

            $user = User::find($id);
            $user->assignRole('owner');
        });
    }

    public function update($store, $data)
    {
        $store->update([
            "name" => $data["store_name"],
            "lng" => $data["location"]['lng'],
            "lat" => $data["location"]['lat'],
            "address" => $data["address"],
            "default_delivery_time" => $data["default_delivery_time"],
            "type_id" => $data["activity_type"],
        ]);

        $store->owner->update([
            "name" => $data["owner_name"],
            "username" => $data["owner_username"],
            "email" => $data["owner_email"]
        ]);
    }

    public function mail($email_address)
    {
        Mail::send('test', ['token' => Str::random(60)], function (Message $message) use ($email_address) {
            $message->from('theimaginaryking@gmail.com');
            $message->to($email_address);
            $message->subject("reset password");
        });
    }

    public function delete($store)
    {
        $store->delete();
    }
}
