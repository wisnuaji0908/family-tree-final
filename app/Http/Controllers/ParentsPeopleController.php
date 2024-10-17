<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\People;
use App\Models\Parents;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentsPeopleController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::id(); 
        $setting = Setting::first();
        $claimedPersonId = Auth::user()->people_id; 

        $parents = Parents::with(['user', 'people', 'userParent'])
            ->where('people_id', $claimedPersonId)
            ->orWhere('parent_id', $claimedPersonId)
            ->paginate(5);

        return view('parentspeople.index', compact('parents', 'loggedInUser', 'setting'));
    }

    public function create()
    {
        $users = User::all();
        $people = People::all();
        $setting = Setting::first();
        return view('parentspeople.create', compact('users', 'people', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        $existingParentsCount = Parents::where('people_id', $request->people_id)->count();
        if ($existingParentsCount >= 2) {
            return redirect()->back()->withErrors(['people_id' => 'This person already has two parents.'])->withInput();
        }

        $existingParentWithSameRole = Parents::where('people_id', $request->people_id)
            ->where('parent', $request->parent)
            ->first();

        if ($existingParentWithSameRole) {
            return redirect()->back()->withErrors(['parent' => 'This person already has a ' . $request->parent . '.'])->withInput();
        }

        $person = People::find($request->people_id);
        $parent = People::find($request->parent_id);
        if ($person && $parent && $person->name === $parent->name) {
        return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
    }

    Parents::create([
        'user_id' => Auth::id(), 
        'people_id' => $request->people_id,
        'parent_id' => $request->parent_id,
        'parent' => $request->parent,
    ]);

        return redirect()->route('parentspeople.index')->with('success', 'Parent added successfully.');
    }

     public function edit(string $id)
    {
        $parent = Parents::findOrFail($id);
     
         if (auth()->user()->id !== $parent->user_id) {
             return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data ini.');
         }
     
         $users = User::all();
         $people = People::all();
         $setting = Setting::first();
        return view('parentspeople.edit', compact('parent', 'users', 'people', 'setting'));
    }
     

    public function update(Request $request, string $id)
    {

    $parent = Parents::findOrFail($id);

    if (auth()->user()->id !== $parent->user_id) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data ini.');
    }

    $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'people_id' => 'required|exists:people,id|unique:parents,people_id,' . $id,
        'parent_id' => 'nullable|exists:people,id|different:people_id',
        'parent' => 'required|in:father,mother',
    ]);

    if (($parent->parent === 'father' && $request->parent === 'mother') ||
        ($parent->parent === 'mother' && $request->parent === 'father')) {
        return redirect()->back()->with('error', 'Tidak dapat mengubah parent role dari ' . $parent->parent . ' menjadi ' . $request->parent . '.');
    }

    $person = People::find($request->people_id);
    $parentData = People::find($request->parent_id);

    if ($person && $parentData && $person->name === $parentData->name) {
        return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
    }

    $parent->update([
        'user_id' => Auth::id(),
        'people_id' => $request->people_id,
        'parent_id' => $request->parent_id,
        'parent' => $request->parent,
    ]);

    return redirect()->route('parentspeople.index')->with('success', 'Parent updated successfully.');
    }

    public function destroy(string $id)
    {
        $parent = Parents::find($id);

        if (!$parent) {
            return redirect()->route('parents.index')->with('error', 'Parent not found.');
        }
        if (auth()->user()->id !== $parent->user_id) {
            return redirect()->route('parentspeople.index')->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }
        $parent->delete();
        $setting = Setting::first();
        return redirect()->route('parentspeople.index')->with('success', 'Data successfully removed.');
    }
    public function getParent($userId)
    {

        $person  = people::find (id);

        $parentFather = \App\Models\ParentsPeople::where('user_id', $userId)
            ->where('parent', 'father')
            ->with(['userParent', 'people'])
            ->get();

        $parentMother = \App\Models\ParentsPeople::where('user_id', $userId)
            ->where('parent', 'mother')
            ->with(['userParent', 'people'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'person' => $person,
                'father' => $parentFather,
                'mother' => $parentMother,
            ],
        ]);
    }
}