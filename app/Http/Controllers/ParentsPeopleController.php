<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\People;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentsPeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = Parents::with(['user', 'people', 'userParent'])->paginate(5); 
        return view('parentspeople.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $people = People::all();
        return view('parentspeople.create', compact('users', 'people'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'people_id' => 'required|exists:people,id|unique:parents,people_id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

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

    /**
     * Display the specified resource.
     */
    
    public function edit(string $id)
    {
        $parent = Parents::findOrFail($id);
        $users = User::all();
        $people = People::all();
        return view('parentspeople.edit', compact('parent', 'users', 'people'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'people_id' => 'required|exists:people,id|unique:parents,people_id,' . $id, 
            'parent_id' => 'nullable|exists:people,id|different:people_id', 
            'parent' => 'required|in:father,mother',
            
        ]);

        $person = People::find($request->people_id);
        $parent = People::find($request->parent_id);

        if ($person && $parent && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        $parent = Parents::findOrFail($id);

        $parent->update([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'parent_id' => $request->parent_id,
            'parent' => $request->parent,
        ]);
            
        return redirect()->route('parentspeople.index')->with('success', 'Parent updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $parent = Parents::find($id);
        if (!$parent) {
            return redirect()->route('parents.index')->with('error', 'Parent not found.');
        }
        $parent->delete();
        return redirect()->route('parentspeople.index')->with('success', 'Data successfully removed.');
    }
}
