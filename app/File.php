<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
       'overview',
       'overview_short',
       'title',
       'price',
       'live',
       'finished',
       'approved',
    ];

    public function getRouteKeyName()
    {
        return 'identifier';
    }

    public function scopeFinished ($builder) {

        return $builder->where('finished', true);
    }

    public static function boot () {
        parent::boot();

        static::creating(function ($file) {
            $file->identifier = uniqid(true);
        });
    }

    public function isFree () {
        return $this->price == 0;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
