<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileApproval extends Model
{
    use SoftDeletes;

    protected $table = 'file_approvals';

    protected $fillable = [
        'title',
        'overview_short',
        'overview',
    ];

    public function file () {
        return $this->belongsTo(File::class);
    }

    public static function boot () {
        parent::boot();

        static::creating(function ($approval) {
            $approval->file->approvals->each->delete();
        });
    }
}
