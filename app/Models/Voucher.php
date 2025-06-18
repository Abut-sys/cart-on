<?php

namespace App\Models;

use App\Notifications\VoucherLimitNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
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

    public function checkouts()
    {
        return $this->hasMany(Checkout::class, 'voucher_code', 'code');
    }

    public function claimVoucher()
    {
        return $this->hasMany(ClaimVoucher::class);
    }

    public function claimedUsers()
    {
        return $this->belongsToMany(User::class, 'claim_voucher');
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
        return $query->active();
    }

    public function isUsedByUser(User $user)
    {
        return UserVoucher::where('user_id', $user->id)
            ->where('voucher_id', $this->id)
            ->exists();
    }

    public function isActive()
    {
        return Carbon::today()->between($this->start_date, $this->end_date)
            && $this->used_count < $this->usage_limit;
    }

    public function updateStatus()
    {
        $newStatus = $this->isActive() ? 'active' : 'inactive';
        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }

    public function incrementUsage()
    {
        if ($this->used_count < $this->usage_limit) {
            $this->increment('used_count');
        }
    }

    public function decrementUsage()
    {
        if ($this->usage_limit > 0) {
            $this->decrement('usage_limit');

            if ($this->usage_limit === 0) {
                $this->status = 'inactive';
                $this->save();

                $admins = User::where('role', 'admin')->get();

                foreach ($admins as $admin) {
                    $admin->notify(new VoucherLimitNotification($this));
                }
            }
        }
    }

    public function calculateDiscount($subtotal)
    {
        if ($this->type === 'percentage') {
            return round(max(0, $subtotal * ($this->discount_value / 100)), 2);
        }

        if ($this->type === 'fixed') {
            return max(0, min($this->discount_value, $subtotal));
        }

        return 0;
    }
}
