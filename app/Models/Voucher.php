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
    /**
     * Scope untuk mengambil voucher aktif berdasarkan tanggal
     */
    public function scopeActive($query)
    {
        return $query->whereDate('start_date', '<=', Carbon::today())
            ->whereDate('end_date', '>=', Carbon::today());
    }

    /**
     * Scope untuk mengambil voucher yang tidak aktif berdasarkan tanggal
     */
    public function scopeInactive($query)
    {
        return $query->whereDate('end_date', '<', Carbon::today())
            ->orWhereDate('start_date', '>', Carbon::today());
    }

    /**
     * Accessor untuk mendapatkan status aktif atau tidak aktif berdasarkan tanggal
     */
    public function getStatusAttribute()
    {
        return $this->isActive() ? 'active' : 'inactive';
    }

    /**
     * Menentukan apakah voucher aktif berdasarkan tanggal
     */
    public function isActive()
    {
        $today = Carbon::today();
        return $today->between($this->start_date, $this->end_date);
    }

    /**
     * Update status manual berdasarkan tanggal
     */
    public function updateStatus()
    {
        // Mendapatkan tanggal hari ini
        $today = Carbon::today();

        // Menentukan status aktif atau tidak berdasarkan tanggal
        $newStatus = $today->between($this->start_date, $this->end_date) ? 'active' : 'inactive';

        // Hanya update status jika berbeda dengan status sebelumnya
        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->save();
        }
    }

    // scopeActive: Mengambil semua voucher yang aktif berdasarkan tanggal.
    // scopeInactive: Mengambil semua voucher yang tidak aktif berdasarkan tanggal.
    // getStatusAttribute: Menentukan status berdasarkan tanggal.
    // isActive: Cek apakah voucher aktif berdasarkan tanggal hari ini.
    // updateStatus: Method untuk memperbarui status voucher berdasarkan tanggal.
}
