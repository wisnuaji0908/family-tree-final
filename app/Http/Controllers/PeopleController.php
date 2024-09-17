<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\People;

class PeopleController extends Controller
{
    public function index()
    {
        $people = People::all(); 
        return view('people.index', compact('people'));
    }

    public function create()
    {
        $users = User::all(); 
        return view('people.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date',
        ]);

        People::create($request->all());

        return redirect()->route('people.index')->with('success', 'Person added successfully.');
    }


    public function show($id)
    {
        $person = People::findOrFail($id); 
        return view('people.show', compact('person')); 
    }


    public function edit($id)
    {
        $person = People::findOrFail($id); 
        $users = User::all(); 
        return view('people.edit', compact('person', 'users'));
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
        ]);

        $person = People::findOrFail($id);

        $person->update($validatedData);

        return redirect()->route('people.index')->with('success', 'Data updated successfully.');
    }




    public function destroy($id)
    {
        $person = People::findOrFail($id);
        $person->delete();
    
        return redirect()->route('people.index')->with('success', 'Data successfully removed.');
    }
    
}
