<?php

namespace App\Models;

use Hash;
use Carbon\Carbon;
use App\Models\JanjiTemu;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DateTimeInterface;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens, HasFactory;
    public $table = 'users';
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
        'tanggal_lahir',
    ];

    protected $fillable = [
        'name',
        'email',
        'alamat',
        'telp',
        'jenis_kelamin',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];
    // protected $appends = ['tanggal_lahir'];

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function janjitemu()
    {
        return $this->belongsToMany(JanjiTemu::class);
    }
    public function pasien()
    {
        return $this->belongsToMany(Pasien::class);
    }
    public function getAgeAttribute()
    {
        return Carbon::now()->diffInYears($this->tanggal_lahir);
    }

}
