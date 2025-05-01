<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pasien;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JanjiTemu extends Model
{
    use SoftDeletes, Notifiable, HasApiTokens, HasFactory;
    public $table = 'janji_temu';

    // protected $appends = [
    //     'photo',
    // ];

    protected $dates = [
        'created_at',
        'updated_at',
        'tanggal',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'user_id',
        'id',
        'keluhan',
        'tanggal',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }
}
