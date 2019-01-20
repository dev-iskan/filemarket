<?php

namespace App;

use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class File extends Model
{
    use SoftDeletes;
    use HasApproval;
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

        if ($this->uploads()->unapproved()->count()) {
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

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function approve () {
        $this->updateToBeVisible();
        $this->approveAllUploads();
    }

    public function approveAllUploads () {
        $this->uploads()->update([
            'approved' => true
        ]);
    }

    public function updateToBeVisible () {
        $this->update([
            'live' => true,
            'approved' => true
        ]);
    }

    public function mergeApprovalProperties () {
        $this->update(array_only(
            $this->approvals->first()->toArray(),
            self::APPROVAL_PROPERTIES
        ));
    }

    public function deleteAllApprovals () {
        $this->approvals()->delete();
    }

    public function deleteUnapprovedUploads () {
        $this->uploads()->unapproved()->delete();
    }

    public function visible () {
        if (auth()->user()->isAdmin()) {
            return true;
        }
        if (auth()->user()->isTheSameUser($this->user)) {
            return true;
        }
        return $this->live && $this->approved;
    }

    public function sales () {
        return $this->hasMany(Sale::class);
    }

    public function calculateCommission () {
        return (config('filemarket.sales.commission') / 100) * $this->price;
    }

    public function matchesSale (Sale $sale) {
        return $this->sales->contains($sale);
    }

    public function getUploadList () {
        return $this->uploads()->approved()->get()->pluck('path')->toArray();
    }
}
