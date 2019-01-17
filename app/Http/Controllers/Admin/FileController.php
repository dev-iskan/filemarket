<?php

namespace App\Http\Controllers\Admin;

use App\File;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function show(File $file) {

        $file = $this->replaceFilePropertiesWithUnapprovedChanges($file);

        $uploads = $file->uploads;
        return view('files.show', compact('file','uploads'));
    }

    public function replaceFilePropertiesWithUnapprovedChanges(File $file) {
        if ($file->approvals->count()) {
            $file->fill($file->approvals->first()->toArray());
        }
        return $file;
    }
}
