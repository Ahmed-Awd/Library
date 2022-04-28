<?php

use App\Models\Item;
use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionValue;
use App\Services\GeoDistance;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Google\Cloud\Translate\V2\TranslateClient;

//function translate($text)
//{
//    $swedish_tr = new GoogleTranslate('sv');
//    $result = json_encode([
//        "en" => $text,
//        "sv" => $swedish_tr->translate($text)
//    ]);
//    return $result;
//}

function translate($text)
{
    $translate = new TranslateClient([
        "key" => "AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg"
    ]);
//    $ar = $translate->translate($text, ['target' => 'ar'])['text'];
    $sv = $translate->translate($text, ['target' => 'sv'])['text'];
    $json = json_encode([
        "en" => $text,
        "sv" => $sv,
    ]);
    return $json;
}

function translateToEn($text)
{
    $translate = new TranslateClient([
        "key" => "AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg"
    ]);
    $en = $translate->translate($text, ['source' => 'sv', 'target' => 'en'])['text'];
    $json = json_encode([
        "en" => $en,
        "sv" => $text,
    ]);
    return $json;
}


function imageStore($file, $resize = true): string
{
    $image = $file;
    $img = Image::make($image->getRealPath());
    if ($resize == true) {
        $img->resize(360, 360, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
    $img->stream();
    $name = date("Y/m/d") . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
    Storage::disk('public')->put($name, $img, 'public');
    return $name;
}

function storeLogo($file): string
{
    $image = $file;
    $img = Image::make($image->getRealPath());
    $img->stream();
    $name = "min_pizza.png";
    Storage::disk('public')->put($name, $img, 'public');
    return $name;
}

function getCurrentDayNumber($day): int
{
    if ($day == "Saturday") {
        return 1;
    }
    if ($day == "Sunday") {
        return 2;
    }
    if ($day == "Monday") {
        return 3;
    }
    if ($day == "Tuesday") {
        return 4;
    }
    if ($day == "Wednesday") {
        return 5;
    }
    if ($day == "Thursday") {
        return 6;
    }
    if ($day == "Friday") {
        return 7;
    }
}

function getCurrency($restaurant)
{
    if (isset($restaurant->city->country->currency_code)) {
        return $restaurant->city->country->currency_code;
    }
    return "SEK";
}

function dateFilter($records, $range, $date_by)
{
    $weekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY);
    $weekEnd = Carbon::now()->endOfWeek(Carbon::FRIDAY);
    $now = Carbon::now();
    if ($range == "today") {
        $records = $records->whereDate($date_by, Carbon::today());
    }
    if ($range == "yesterday") {
        $records = $records->whereDate($date_by, Carbon::yesterday());
    }
    if ($range == "this-week") {
        $records = $records->whereBetween($date_by, [$weekStart, $weekEnd]);
    }
    if ($range == "prev-week") {
        $records = $records->whereBetween($date_by, [$weekStart->subWeek(), $weekEnd->subWeek()]);
    }
    if ($range == "this-month") {
        $records = $records->whereMonth($date_by, $now->month)->whereYear($date_by, $now->year);
    }
    if ($range == "prev-month") {
        $records = $records->whereMonth($date_by, $now->subMonth()->month)->whereYear($date_by, $now->year);
    }
    if (Str::contains($range, ',')) {
        $dates = explode(",", $range);
        $records = $records
            ->whereBetween($date_by, [Carbon::parse($dates[0]), Carbon::parse($dates[1])->addDay()]);
    }

    return $records;
}

function sendSms($message, $number)
{

    $token = "Fvy7dGLULxlgiyeSqL6f7JumpRQ88ks6V6oNqmZq";
    $url = 'https://api.smsapi.se/sms.do';
    $params = array(
        'to' => $number,         //destination number
        'from' => 'Min pizza',          //sendername made in https://portal.smsapi.se/sms_settings/sendernames
        'message' => $message,    //message content
        'format' => 'json',
    );

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $params);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $token"
    ));

    $content = curl_exec($c);
    $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);
    if ($http_status != 200) {
        sendSms($message, $number);
    }
    curl_close($c);
    return $content;
}

function setNearest($current, $lat, $lng, $radius)
{
    $records = $current->addSelect(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(lat)) * cos(radians(lng) - radians(" . $lng . "))
                                + sin(radians(" . $lat . ")) * sin(radians(lat))) AS distance"));

    $records = $records->having('distance', '<', $radius);
    return $records;
}

function setNearestRestaurants($current, $lat, $lng, $radius)
{
    $records = $current->addSelect(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(lat)) * cos(radians(lng) - radians(" . $lng . "))
                                + sin(radians(" . $lat . ")) * sin(radians(lat))) AS distance"),DB::raw('IFNULL(restaurant_settings.max_delivery_distance,0) as max_delivery_distances'))
                                ->leftJoin('restaurant_settings', 'restaurants.id', '=', 'restaurant_settings.restaurant_id');

    $records = $records->having('distance', '<', $radius);
    $records = $records->havingRaw('distance <= max_delivery_distances');
    return $records;
}

function setNearestFollows($current, $lat, $lng)
{
    $records = $current->addSelect(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(lat)) * cos(radians(lng) - radians(" . $lng . "))
                                + sin(radians(" . $lat . ")) * sin(radians(lat))) AS distance"));

    return $records;
}
function calcDeliveryFee($restaurant, $address, $data)
{
    $final['code'] = 200;
    $result = resolve(GeoDistance::class)->calculate(
        [$restaurant->lat, $restaurant->lng],
        [$address->lat, $address->lng]
    );
    if ($result['distance'] == -1) {
        $final["message"] = Lang::get('messages.order.distance__too_far');
        $final["code"] = 400;
        $final["order"] = null;
        return $final;
    }

    $final['value'] = resolve(OrderService::class)->calculateDeliveryFee(
        $restaurant,
        $result['distance'],
        $data['sub_total']
    );
    return $final;
}

function searchIt($result, $columns, $value, $orWhere = false)
{
    if ($orWhere == true) {
        return $result->orWhere(function ($query) use ($columns, $value) {
            $query = $query->where($columns[0], 'LIKE', '%' . $value . '%');
            foreach ($columns as $column) {
                $query = $query->orWhere($column, 'LIKE', '%' . $value . '%');
            }
        });
    }
    return $result->where(function ($query) use ($columns, $value) {
        $query = $query->where($columns[0], 'LIKE', '%' . $value . '%');
        foreach ($columns as $column) {
            $query = $query->orWhere($column, 'LIKE', '%' . $value . '%');
        }
    });
}

function swish($url, $data, $method)
{
    $headers = array();
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'p12');
    curl_setopt($ch, CURLOPT_SSLCERTPASSWD, '147147');
    curl_setopt($ch, CURLOPT_SSLCERT, 'swish.p12');
    curl_setopt($ch, CURLOPT_CAINFO, 'Swish_TLS_RootCA.pem');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data_string = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)));
    $response1 = curl_exec($ch);
    $response["status"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $response["response"] = json_decode($response1);

    return $response;
    curl_close($ch);
}

function curl($method, $checkoutUrl, $headers, $requestJson)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestJson);
    curl_setopt($curl, CURLOPT_URL, $checkoutUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $rawResponse = curl_exec($curl);
    info($rawResponse);
    $response = json_decode($rawResponse);
    return $response;

}

function apiKey()
{
    $accesstoken = config('services.bambora.access_token');//<<YOUR-ACCESS-TOKEN>>
    $merchantNumber = config('services.bambora.merchant_number');//<<YOUR-MERCHANT-NUMBER>>
    $secrettoken = config('services.bambora.secret_token');//<<YOUR-SECRET-TOKEN>>

    $apiKey = base64_encode($accesstoken . "@" . $merchantNumber . ":" . $secrettoken
    );
    return $apiKey;
}

function bambora($endpointUrl, $method, $length, $requestJson = null)
{
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . $length,
        'Accept: application/json',
        'Authorization: Basic ' . apiKey()
    );
    $response = curl($method, $endpointUrl, $headers, $requestJson);
    return $response;
}

function order($order)
{
    $order->currency_code = getCurrency($order->restaurant);
    if ($order->orderItem) {
        foreach ($order->orderItem as $orderItem) {
            if (!is_null($orderItem->template_selected_options)) {
                $template_selected_option_category = OptionCategory::withTrashed()
                    ->where('id', $orderItem
                        ->template_selected_options->default_primary_option_item
                        ->option_category_id)->first();
                $template_selected_option_category->option_values = $orderItem
                    ->template_selected_options;
                $template_selected_option_category->option_values
                    ->template_secondary_sptions_details = array();
                $option_category_ids = array();
                foreach (
                    $orderItem->template_selected_options
                        ->template_secondary_sptions_details as $selected_option
                ) {
                    if (
                        array_search(
                            $selected_option->default_secondary_option_item->secondary_option_id,
                            $option_category_ids
                        ) === false
                    ) {
                        array_push(
                            $option_category_ids,
                            $selected_option->default_secondary_option_item->secondary_option_id
                        );
                    }
                }
                $option_categories = OptionCategory::withTrashed()
                    ->whereIn('id', $option_category_ids)->get();
                $selected_option_categories = array();
                foreach ($option_categories as $option_category) {
                    $selected_option_category = $option_category;
                    $option_values = array();
                    foreach (
                        $orderItem->template_selected_options
                            ->template_secondary_sptions_details as $selected_option
                    ) {
                        if (
                            $selected_option->default_secondary_option_item
                                ->secondary_option_id == $option_category->id
                        ) {
                            $option_values[] = $selected_option;
                        }
                    }
                    $selected_option_category->option_values = $option_values;
                    $selected_option_categories[] = $selected_option_category;
                    $option_values = null;
                }
                $template_selected_option_category->option_values->template_secondary_sptions_details =
                    $selected_option_categories;
                $orderItem->template_selected_options = array();
                $orderItem->template_selected_options = $template_selected_option_category;
            } elseif (!is_null($orderItem->selected_options)) {
                $selected_options = $orderItem->selected_options;
                $option_category_ids = array();
                foreach ($selected_options as $selected_option) {
                    if (
                        array_search(
                            $selected_option->option_value->option_category_id,
                            $option_category_ids
                        ) === false
                    ) {
                        array_push(
                            $option_category_ids,
                            $selected_option->option_value->option_category_id
                        );
                    }
                }
                $option_categories = OptionCategory::withTrashed()
                    ->whereIn('id', $option_category_ids)->get();
                $selected_option_categories = array();
                foreach ($option_categories as $option_category) {
                    $selected_option_category = null;
                    $selected_option_category = $option_category;
                    $option_values = array();
                    foreach ($orderItem->selected_options as $selected_option) {
                        if ($selected_option->option_value->option_category_id == $option_category->id) {
                            $option_values[] = $selected_option;
                        }
                    }
                    $selected_option_category->option_values = $option_values;
                    $selected_option_categories[] = $selected_option_category;
                    $option_values = null;
                }
                $orderItem->selected_option_categories = $selected_option_categories;
            }
        }
    }

    return $order;
}

function reoder($order)
{
    if ($order->orderItem) {
        $item_ids = array();
        foreach ($order->orderItem as $orderItem) {
            array_push(
                $item_ids,
                $orderItem->item->id
            );
        }
        $new_items = Item::whereIn('id', $item_ids)->get();
        foreach ($order->orderItem as $orderItem) {
            if (!is_null($orderItem->template_selected_options)) {
                $template_selected_option_category = OptionCategory::where('id', $orderItem
                    ->template_selected_options->default_primary_option_item
                    ->option_category_id)->first();
                $template_selected_option_category->option_values = $orderItem
                    ->template_selected_options;
                $new_primary_option_value = OptionValue::
                where('id', $orderItem
                    ->template_selected_options->default_primary_option_item
                    ->id)->first();
                if (
                    $new_primary_option_value->price != $orderItem
                        ->template_selected_options->default_primary_option_item
                        ->price
                ) {
                    $template_selected_option_category->option_values->change_price = true;
                    $template_selected_option_category->option_values
                        ->total_price = $new_primary_option_value
                            ->price * $orderItem
                            ->template_selected_options->quantity;
                } else {
                    $template_selected_option_category->option_values->change_price = false;
                }

                $template_selected_option_category->option_values
                    ->default_primary_option_item = $new_primary_option_value;

                $template_selected_option_category->option_values
                    ->template_secondary_sptions_details = array();
                $option_category_ids = array();
                $option_values_ids = array();
                foreach (
                    $orderItem->template_selected_options
                        ->template_secondary_sptions_details as $selected_option
                ) {
                    if (
                        array_search(
                            $selected_option->default_secondary_option_item->secondary_option_id,
                            $option_category_ids
                        ) === false
                    ) {
                        array_push(
                            $option_category_ids,
                            $selected_option->default_secondary_option_item->secondary_option_id
                        );
                    }

                    array_push(
                        $option_values_ids,
                        $selected_option->default_secondary_option_item->secondary_option_value_id
                    );
                }
                $option_categories = OptionCategory::
                whereIn('id', $option_category_ids)->get();
                $new_option_values = OptionValue::withTrashed()
                    ->whereIn('id', $option_values_ids)->get();
                $new_option_secondary_values = OptionSecondary::
                whereIn('secondary_option_value_id', $option_values_ids)
                    ->where('primary_option_value_id', $new_primary_option_value->id)->get();
                $selected_option_categories = array();
                foreach ($option_categories as $option_category) {
                    $selected_option_category = $option_category;
                    $option_values = array();
                    foreach (
                        $orderItem->template_selected_options
                            ->template_secondary_sptions_details as $selected_option
                    ) {
                        $new_selected_option = $selected_option;
                        $new_option_secondary_value = null;
                        $new_option_value = null;
                        if (
                            $selected_option->default_secondary_option_item
                                ->secondary_option_id == $option_category->id
                        ) {
                            $new_option_secondary_value = $new_option_secondary_values
                                ->where('secondary_option_value_id', $selected_option
                                    ->default_secondary_option_item
                                    ->secondary_option_value_id)
                                ->where('primary_option_value_id', $selected_option
                                    ->default_secondary_option_item
                                    ->primary_option_value_id)
                                ->first();
                            $new_option_value = $new_option_values
                                ->where('id', $selected_option->default_secondary_option_item
                                    ->secondary_option_value_id)->first();

                            if ($new_option_secondary_value) {
                                if ($new_option_secondary_value->use_default) {
                                    if (
                                        $new_option_value->price != $selected_option
                                            ->default_secondary_option_item
                                            ->secondary_option_value->price
                                    ) {
                                        $new_selected_option->change_price = true;
                                        $new_selected_option->total_price = $selected_option
                                                ->quantity * $new_option_value
                                                ->price;
                                    } else {
                                        $new_selected_option->change_price = false;
                                    }
                                } else {
                                    if (
                                        $new_option_secondary_value->price != $selected_option
                                            ->default_secondary_option_item
                                            ->price
                                    ) {
                                        $new_selected_option->change_price = true;
                                        $new_selected_option->total_price = $selected_option
                                                ->quantity * $new_option_secondary_value
                                                ->price;
                                    } else {
                                        $new_selected_option->change_price = false;
                                    }
                                }
                                $new_selected_option->default_secondary_option_item = $new_option_secondary_value;
                                $new_selected_option->default_secondary_option_item
                                    ->secondary_option_value = $new_option_value;
                            }
                            $option_values[] = $new_selected_option;
                            $new_selected_option = null;
                        }
                    }
                    $selected_option_category->option_values = $option_values;
                    $selected_option_categories[] = $selected_option_category;
                    $option_values = null;
                }
                $template_selected_option_category->option_values->template_secondary_sptions_details =
                    $selected_option_categories;
                $orderItem->template_selected_options = array();
                $orderItem->template_selected_options = $template_selected_option_category;
            } elseif (!is_null($orderItem->selected_options)) {
                $selected_options = $orderItem->selected_options;
                $option_category_ids = array();
                $option_value_ids = array();
                foreach ($selected_options as $selected_option) {
                    if (
                        array_search(
                            $selected_option->option_value->option_category_id,
                            $option_category_ids
                        ) === false
                    ) {
                        array_push(
                            $option_category_ids,
                            $selected_option->option_value->option_category_id
                        );
                    }
                    array_push(
                        $option_value_ids,
                        $selected_option->option_value->id
                    );
                }
                $option_categories = OptionCategory::
                whereIn('id', $option_category_ids)->get();
                $option_vlaues = OptionValue::
                whereIn('id', $option_value_ids)->get();
                $selected_option_categories = array();
                foreach ($option_categories as $option_category) {
                    $selected_option_category = null;
                    $selected_option_category = $option_category;
                    $option_values = array();

                    foreach ($orderItem->selected_options as $selected_option) {
                        if ($selected_option->option_value->option_category_id == $option_category->id) {
                            $new_selected_option = $selected_option;
                            $new_option_value = $option_vlaues
                                ->where('id', $selected_option->option_value->id)->first();
                            if ($new_option_value->price != $selected_option->option_value->price) {
                                $new_selected_option->change_price = true;
                                $new_selected_option->total_price = $selected_option->quantity * $new_option_value
                                        ->price;
                            } else {
                                $new_selected_option->change_price = false;
                            }
                            $new_selected_option->option_value = $new_option_value;
                            $option_values[] = $selected_option;
                        }
                    }
                    $selected_option_category->option_values = $option_values;
                    $selected_option_categories[] = $selected_option_category;
                    $option_values = null;
                }
                $orderItem->selected_option_categories = $selected_option_categories;
            }
            $new_item = $new_items->where('id', $orderItem->item->id)->first();

            if ($new_item->currentOffer()->exists()) {
                $offer = $new_item->currentOffer;
                $item_price = $offer->new_price;
            } else {
                $item_price = $new_item->price;
            }
            if ($orderItem->unit_price != intval($item_price)) {
                $orderItem->change_price = true;
                $orderItem->unit_price = $item_price;
                $orderItem->price = $item_price * $orderItem->quantity;
                if ($new_item->tax) {
                    $orderItem->total = $orderItem->price;
                    $orderItem->tax = $new_item->tax;
                }
            } else {
                $orderItem->change_price = false;
            }

            $orderItem->item = $new_item;
        }
    }
    return $order;
}
