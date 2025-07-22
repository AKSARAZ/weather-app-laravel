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
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'city',
        'daily_energy_wh',
        // 'status' tidak perlu di sini karena kita atur defaultnya
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}