<?php

namespace App\Models;

use App\Models\Scopes\Subtotal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected static function booted()
    {
       static::addGlobalScope(new Subtotal);
    }

    public function scopeBetweenDate($query, $startdate=null, $endDate=null)
    {
        if (is_null($startdate) && is_null($endDate)) {
            return $query;
        }
        if (!is_null($startdate) && is_null($endDate)) {
            return $query->where('created_at', ">=", $startdate);
        }
        if (is_null($startdate) && !is_null($endDate)) {
            return $query->where('created_at', "<=", Carbon::parse($endDate)->addDays(1));
        }

        return $query->where('created_at', ">=", $startdate)
                    ->where('created_at', "<=", Carbon::parse($endDate)->addDays(1));
    }
}
