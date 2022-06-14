<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class DuesPurchase extends Model
{
    protected $table='dues_purchase';

    public static function proccessApprovedPayment($duePurchaseId, $cost)
    {
        $duePurchase = static::find($duePurchaseId);
        $specs = json_decode($duePurchase->specifications, true);
        
        if (!isset($specs['under_review'])) {
            return;
        }
        
        $specs['your_money'] += $cost;
        $specs['under_review'] -= $cost;
        $specs['remain_purchase'] -= $cost;

        $duePurchase->specifications = json_encode($specs);
        $duePurchase->save();
    }
    
    public static function proccessRejectedPayment($duePurchaseId, $cost)
    {
        $duePurchase = static::find($duePurchaseId);
        $specs = json_decode($duePurchase->specifications, true);

        if (!isset($specs['under_review'])) {
            return;
        }
        
        $specs['under_review'] -= $cost;

        $duePurchase->specifications = json_encode($specs);
        $duePurchase->save();
    }
}