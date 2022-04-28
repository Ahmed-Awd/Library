<?php

namespace App\Repositories;

use App\Jobs\SendSms;
use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class DiscountCodeRepository implements DiscountCodeRepositoryInterface
{
    private DiscountCode $discountCode;

    public function __construct(DiscountCode $discountCode)
    {
        $this->discountCode = $discountCode;
    }

    public function get($search, $order)
    {
        $query = $this->discountCode;
        if ($search != '') {
            $query = $query->whereHas('user', function ($query) use ($search) {
                return $query->where('name', 'LIKE', '%'.$search.'%');
            });
            $query = searchIt($query, ['type','code'], $search, true);
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        return $query->with('user:id,name', 'restaurant:id,name')->paginate(15);
    }

    public function myCodes()
    {
        $user = Auth::user();
        $codes = $user->availableCodes;
        return $codes;
    }

    public function show($discountCode)
    {
        return $this->discountCode->where('id', $discountCode->id)->with('user:id,name', 'restaurant:id,name')->first();
    }

    public function getMyCode($discountCode)
    {
        return $this->discountCode->where('code', $discountCode)->with('restaurant:id,name')->first();
    }

    public function store($data)
    {
        $data['code'] = strtoupper(Str::random('10'));
        $data['usage_left'] = $data['max_usage'];
        $this->discountCode->create($data);
        $user = User::find($data['user_id']);
        $msg = $this->message($data, $user);
        SendSms::dispatch($user, $msg);
    }

    public function message($data, $user)
    {
        $data['amount'] = $data['type'] == 'rate' ? $data['amount'] . " %" : $data['amount'];
        $msg = Lang::get('messages.discount_code.sms_rate', [
            "name" => $user->name,
            "code" => $data['code'],
            "amount" => $data['amount'],
            "start" => $data['start_date'],
            "end" => $data['end_date'],
            "min_price" => $data['min_order_price'],
            "times" => $data['max_usage'],
        ]);
        return $msg;
    }

    public function update($discountCode, $data)
    {
        $data['usage_left'] = $data['max_usage'];
        $discountCode->update($data);
        $result = $this->discountCode->where('id', $discountCode->id)->firstOrFail()->toArray();
        $user = User::find($result['user_id']);
        $msg = $this->message($result, $user);
        SendSms::dispatch($user, $msg);
    }

    public function delete($discountCode)
    {
        $discountCode->delete();
    }
}
