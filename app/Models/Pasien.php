<?php

namespace App\Models;

use App\Models\User;
use App\Models\JanjiTemu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Passport\HasApiTokens;

class Pasien extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    public $table = 'pasien';

    protected $appends = [
        'photo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'diagnosa',
        'penanganan',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
        'janji_temu_id',
    ];
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function janji_temu()
    {
        return $this->belongsTo(JanjiTemu::class, 'janji_temu_id');
    }
    public function getPhotoAttribute()
    {
        $file = $this->getMedia('photo')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }
}
