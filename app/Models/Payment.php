<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['subscriber_id','plan_id','razor_order_id','razor_payment_id','razor_signature','status','paid_at'])]
class Payment extends Model
{
    
}
