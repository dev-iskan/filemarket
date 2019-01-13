<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class File extends Model
{
    use SoftDeletes;

    protected $casts = [
        'live' => 'boolean'
    ];

    protected $fillable = [
       'overview',
       'overview_short',
       'title',
       'price',
       'live',
       'finished',
       'approved',
    ];

    const APPROVAL_PROPERTIES = [
        'title',
        'overview_short',
        'overview',
    ];

    public function getRouteKeyName()
    {
        return 'identifier';
    }

    public function setLiveAttribute($value)
    {
        $this->attributes['live'] = (int) $value;
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

    public function approvals () {
        return  $this->hasMany(FileApproval::class);
    }

    public function needsApproval (array $approvalProperties) {
        if ($this->currentPropertiesDifferToGiven($approvalProperties)) {
            return true;
        }

        return false;
    }

    protected function currentPropertiesDifferToGiven (array $properties) {
        return array_only($this->toArray(), self::APPROVAL_PROPERTIES) != $properties;
    }

    public function createApproval (array $approvalProperties)  {
        $this->approvals()->create($approvalProperties);
    }
}
