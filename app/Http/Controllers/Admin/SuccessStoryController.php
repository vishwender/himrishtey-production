<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use App\Services\SuccessStoryPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class SuccessStoryController extends Controller
{
    public function index()
    {
        $stories = SuccessStory::orderByDesc('id')
            ->paginate(15);

        return view('admin.success-stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.success-stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'detail' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $story = new SuccessStory;

        $story->groom_name = $validated['groom_name'];
        $story->bride_name = $validated['bride_name'];
        $story->detail = $validated['detail'];
        $story->status = $request->boolean('status');

        if ($request->hasFile('photo')) {
            $story->photo = $this->uploadPhoto($request->file('photo'));
        }

        $story->save();

        return redirect()
            ->route('admin.success-stories.index')
            ->with('success', 'Success story added successfully.');
    }

    public function edit($id)
    {
        $story = SuccessStory::findOrFail($id);

        return view(
            'admin.success-stories.edit',
            compact('story')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'groom_name' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'detail' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $story = SuccessStory::findOrFail($id);

        $story->groom_name = $validated['groom_name'];
        $story->bride_name = $validated['bride_name'];
        $story->detail = $validated['detail'];
        $story->status = $request->boolean('status');

        $oldPhoto = $story->photo;
        if ($request->hasFile('photo')) {
            $story->photo = $this->uploadPhoto($request->file('photo'));
        }

        $story->save();

        if ($request->hasFile('photo')) {
            SuccessStoryPhoto::delete($oldPhoto);
        }

        return redirect()
            ->route('admin.success-stories.index')
            ->with('success', 'Success story updated successfully.');
    }

    public function destroy($id)
    {
        $story = SuccessStory::findOrFail($id);

        $photo = $story->photo;
        $story->delete();
        SuccessStoryPhoto::delete($photo);

        return redirect()
            ->route('admin.success-stories.index')
            ->with('success', 'Success story deleted successfully.');
    }

    private function uploadPhoto(UploadedFile $file): string
    {
        $filename = 'ss-photo-'.Str::uuid().'.'.$file->extension();
        $file->move(public_path('uploads/success-stories'), $filename);

        return $filename;
    }

    public function status($id)
    {
        $story = SuccessStory::findOrFail($id);

        $story->status = ! $story->status;
        $story->save();

        return back()
            ->with('success', 'Status updated successfully.');
    }
}
