<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = ["code", "discount_value", "start_date", "end_date", "terms_and_conditions", "usage_limit", "used_count"];

    public function isActive()
    {
        $currentDate = Carbon::now(); // Mendapatkan tanggal saat ini

        // Cek apakah tanggal saat ini berada dalam rentang tanggal
        return $currentDate->isBetween($this->start_date, $this->end_date, null, '[]');
    }
}
