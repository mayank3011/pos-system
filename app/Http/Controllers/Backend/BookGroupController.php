<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookGroup;
use Illuminate\Support\Facades\Log;

class BookGroupController extends Controller
{
    /**
     * Show a list of all book groups.
     */
    public function index()
    {
        $groups = BookGroup::latest()->get();
        return view('backend.book_groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new book group.
     */
    public function create()
    {
        return view('backend.book_groups.create');
    }

    /**
     * Store a newly created book group in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'group_name' => 'required|string|max:255|unique:book_groups,group_name',
        ]);

        // Create the new group
        $group = BookGroup::create([
            'group_name' => $validated['group_name']
        ]);

        Log::info('New Book Group Created', ['group' => $group]);

        // Redirect back with a success message
        return redirect()->route('book-groups.index')->with([
            'message' => 'Book group added successfully.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * (Optional) Show the form for editing a book group.
     */
    public function edit($id)
    {
        $group = BookGroup::findOrFail($id);
        return view('backend.book_groups.edit', compact('group'));
    }

    /**
     * (Optional) Update the specified book group.
     */
    public function update(Request $request, $id)
    {
        $group = BookGroup::findOrFail($id);

        $validated = $request->validate([
            'group_name' => 'required|string|max:255|unique:book_groups,group_name,' . $group->id,
        ]);

        $group->update($validated);

        return redirect()->route('book-groups.index')->with([
            'message' => 'Book group updated successfully.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * (Optional) Remove the specified book group.
     */
    public function destroy($id)
    {
        $group = BookGroup::findOrFail($id);
        $group->delete();

        return redirect()->route('book-groups.index')->with([
            'message' => 'Book group deleted successfully.',
            'alert-type' => 'success'
        ]);
    }
}
