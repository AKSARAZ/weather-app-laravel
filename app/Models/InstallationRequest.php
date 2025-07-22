<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallationRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Kolom yang diisi oleh User
        'user_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'city',
        'daily_energy_wh',

        // --- KOLOM BARU YANG DIISI OLEH ADMIN ---
        'status',
        'recommended_panel_wp',
        'admin_notes',
    ];

    /**
     * Mendapatkan user yang memiliki permintaan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}