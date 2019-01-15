<?php

namespace App\Http\Controllers\Account;

use App\File;
use App\Http\Requests\File\StoreFileRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\File\UpdateFileRequest;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index() {
        $files = auth()->user()->files()->latest()->finished()->get();
        return view('account.files.index', compact('files'));
    }

    public function create (File $file) {
        if (!$file->exists) {
            $file = $this->createAndReturnSkeletonFile();

            return redirect()->route('account.files.create', $file);
        }
        $this->authorize('touch', $file);

        return view('account.files.create', compact('file'));

    }

    public function store (StoreFileRequest $request, File $file) {
        $this->authorize('touch', $file);

        $file->fill($request->only([
            'title',
            'overview',
            'overview_short',
            'price'
        ]));
        $file->finished =  true;
        $file->save();

        // Update this
        // Flash message
        // Go to file index
        return redirect()->route('account.index')->withSuccess('Thanks,  submitted');
    }

    public function edit (File $file) {
        $this->authorize('touch', $file);
        $approval = $file->approvals()->first();
        return view('account.files.edit', compact('file', 'approval'));
    }

    public function update (UpdateFileRequest $request, File $file) {
        $this->authorize('touch', $file);
        $approvalProperties =$request->only(File::APPROVAL_PROPERTIES);
        if ( $file->needsApproval($approvalProperties)) {
           $file->createApproval($approvalProperties);
           return back()->withSuccess('Thanks! We will review your changes soon.');
        }

        $file->update($request->only(['live', 'price']));

        return back()->withSuccess('File Updated');
    }


    public function createAndReturnSkeletonFile () {
        return auth()->user()->files()->create([
            'title' => 'Untitled',
            'overview' => 'None',
            'overview_short' => 'None',
            'price' => 0,
            'finished' => false,
        ]);
    }
}
