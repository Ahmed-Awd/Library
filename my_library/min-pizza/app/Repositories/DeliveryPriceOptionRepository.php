<?php

namespace App\Repositories;

use App\Models\DeliveryPriceOption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\DeliveryPriceOptionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DeliveryPriceOptionRepository implements DeliveryPriceOptionRepositoryInterface
{
    private DeliveryPriceOption $deliveryPriceOption;

    public function __construct(DeliveryPriceOption $deliveryPriceOption)
    {
        $this->deliveryPriceOption = $deliveryPriceOption;
    }

    public function store($id, $data)
    {
        $this->deliveryPriceOption->insertOrIgnore([
                "type" => $data["delivery_type"],
                "value" => $data["delivery_value"] ?? 0,
                "kilometer" => $data["delivery_kilometer"] ?? 0,
                "restaurant_id" => $id,
                "created_at" =>  Carbon::now(),
        ]);
    }

    public function delete($id)
    {
        $this->deliveryPriceOption->where('restaurant_id', $id)->delete();
    }
}
