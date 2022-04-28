<?php

use App\Services\SettingsServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stichoza\GoogleTranslate\GoogleTranslate;

function sendingSms($user, $text)
{
    $api_key  = 'ee3dd083d1130e6047054681b9f26450';
    $title    = '8507013986';//'8507013986';
    $sentto = $user->country_code . $user->phone;
    $body = array('api_key' => $api_key, 'title' => $title, 'text' => $text,
        'sentto' => $sentto,'sms_lang' => 2,'report' => 1,'response_type' => 'json');
    $response = Http::post('https://www.turkeysms.com.tr/api/v3/gonder/add-content', $body);
    return $response;
}

function sendingNewPhoneSms($user, $text)
{
    $api_key  = 'ee3dd083d1130e6047054681b9f26450';
    $title    = '8507013986';//'8507013986';
    $sentto = $user->new_country_code . $user->new_phone;
    $body = array('api_key' => $api_key, 'title' => $title, 'text' => $text,
        'sentto' => $sentto,'sms_lang' => 2,'report' => 1,'response_type' => 'json');
    $response = Http::post('https://www.turkeysms.com.tr/api/v3/gonder/add-content', $body);
    return $response;
}

/**
 * @throws ErrorException
 */
function translate($text)
{
    $arabic = new GoogleTranslate('ar');
    $turkish = new GoogleTranslate('tr');
    $result = json_encode([
        "en" => $text,
        "ar" => $arabic->translate($text),
        "tr" => $turkish->translate($text),
    ]);
    return $result;
}

function sendingSmsTest($sentto, $text)
{
    $api_key  = 'ee3dd083d1130e6047054681b9f26450';
    $title    = '8507013986';//'8507013986';
    $body = array('api_key' => $api_key, 'title' => $title, 'text' => $text,
        'sentto' => $sentto,'sms_lang' => 2,'report' => 1,'response_type' => 'json');
    $response = Http::post('https://www.turkeysms.com.tr/api/v3/gonder/add-content', $body);
    return $response;
}

if (!function_exists('settings')) {
    function settings(?string $key = null)
    {
        if (is_null($key)) {
            return resolve(SettingsServiceInterface::class);
        }
        return resolve(SettingsServiceInterface::class)->get($key);
    }
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

if (!function_exists('dateFilter')) {
    function dateFilter($records, $range, $date_by)
    {
        if ($range == false) {
            return $records;
        }
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

    function setNearestDrivers($current, $lat, $lng)
    {
        $radius = (int)settings('radius_of_order');
        $records = $current->addSelect(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(lat)) * cos(radians(lng) - radians(" . $lng . "))
                                + sin(radians(" . $lat . ")) * sin(radians(lat))) AS distance"));
        $records = $records->having('distance', '<', $radius);
        return $records;
    }

    function setNearestOrders($current, $lat, $lng)
    {
        $radius = (int)settings('radius_of_order');
        $records = $current->addSelect(DB::raw("6371 * acos(cos(radians(" . $lat . "))
                                * cos(radians(store_lat)) * cos(radians(store_lng) - radians(" . $lng . "))
                                + sin(radians(" . $lat . ")) * sin(radians(store_lat))) AS distance"));
        $records = $records->having('distance', '<', $radius);
        return $records;
    }

    function getDistanceBetween($startLat, $startLng, $endLat, $endLng, $earthRadius = 6371000)
    {
        $latFrom = deg2rad($startLat);
        $lonFrom = deg2rad($startLng);
        $latTo = deg2rad($endLat);
        $lonTo = deg2rad($endLng);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return (int)($angle * $earthRadius);
    }

    function saveImage($image): string
    {
        $img = Image::make($image->getRealPath());
        $img->resize(360, 360, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        $name = date("Y/m/d") . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
        Storage::disk('public')->put($name, $img, 'public');
        return $name;
    }

}
