<?php

namespace App\Http\Controllers\Admin;

use App\File;
use App\Http\Controllers\Controller;

class FileUpdatedController extends Controller
{
    public function index () {
        $files = File::whereHas('approvals')->oldest()->get();
        return view('admin.files.updated.index', compact('files'));
    }

    public function update (File $file) {
        // merge updated properties
        $file->mergeApprovalProperties();
        //  approve uploads
        $file->approveAllUploads();
        // delete all approvals
        $file->deleteAllApprovals();
        return back()->withSuccess("{$file->title} changes have been approved");
    }

    public function destroy (File $file) {
        $file->deleteAllApprovals();
        $file->deleteUnapprovedUploads();
        return back()->withSuccess("{$file->title} changes have been rejected");
    }
}
