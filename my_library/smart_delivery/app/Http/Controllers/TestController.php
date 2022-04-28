<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function removeNumber($number)
    {
        if (config('app.env') == 'local') {
            User::where('phone', $number)->update(["phone" => null]);
        }
        return "done";
    }

    public function testSMS($number)
    {
        $api_key  = 'ee3dd083d1130e6047054681b9f26450';
        $title    = '8507013986';//'8507013986';
        $sentto = $number;
        $body = array('api_key' => $api_key, 'title' => $title, 'text' => "we are testing messages",
            'sentto' => $sentto,'sms_lang' => 2,'report' => 1,'response_type' => 'json');
        $response = Http::post('https://www.turkeysms.com.tr/api/v3/gonder/add-content', $body);
        return $response;
    }

    public function getCurrentLang(): string
    {
        $locale = App::getLocale();
        return $locale;
    }

    public function userByNumber($number)
    {
        return User::where('phone', $number)->firstOrFail();
    }

    public function testNumber(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        $validated['customer_mobile'] = str_replace(' ', '', $validated['customer_mobile']);
        $validated['customer_mobile'] = str_replace('+', '', $validated['customer_mobile']);
        return $validated;
    }

    public function getMeToken(User $user): string
    {
            $token = $user->createToken('auth_token', ['*'], null)->plainTextToken; //nothing
            return $token;
    }

    public function testOrders()
    {
        return Order::where('created_at', '<', Carbon::now()->subDay())->orderBy('id', 'asc')->paginate(15);
    }

    public function getOrderStatus()
    {
        return OrderStatus::all();
    }
}
