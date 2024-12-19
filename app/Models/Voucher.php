<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_value',
        'start_date',
        'end_date',
        'terms_and_conditions',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $dates = ['start_date', 'end_date'];
    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_voucher');
    }

    public function isUsedByUser(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    public function decrementUsage()
    {
        if ($this->usage_limit > 0) {
            $this->usage_limit--;
            $this->used_count++;
            $this->save();

            if ($this->usage_limit === 0) {
                $this->status = 'inactive';
                $this->save();
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today())
            ->where('usage_limit', '>', 0);
    }

    public function scopeInactive($query)
    {
        return $query->whereDate('end_date', '<', Carbon::today())
            ->orWhereDate('start_date', '>', Carbon::today())
            ->orWhere('usage_limit', '=', 0);
    }

    public function scopeValid($query)
    {
        return $query->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function getStatusAttribute()
    {
        return $this->isActive() ? 'active' : 'inactive';
    }

    public function isActive()
    {
        $today = Carbon::today();
        return $today->between($this->start_date, $this->end_date) && $this->usage_limit > 0;
    }

    public function updateStatus()
    {
        $today = Carbon::today();
        $newStatus = $this->isActive() ? 'active' : 'inactive';

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class, 'voucher_code', 'code');
    }

    // scopeActive: Mengambil semua voucher yang aktif berdasarkan tanggal.
    // scopeInactive: Mengambil semua voucher yang tidak aktif berdasarkan tanggal.
    // getStatusAttribute: Menentukan status berdasarkan tanggal.
    // isActive: Cek apakah voucher aktif berdasarkan tanggal hari ini.
    // updateStatus: Method untuk memperbarui status voucher berdasarkan tanggal.
}
