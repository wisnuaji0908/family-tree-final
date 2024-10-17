<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $people = People::when($query, function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('gender', 'LIKE', "%{$query}%")
              ->orWhere('place_birth', 'LIKE', "%{$query}%")
              ->orWhere('birth_date', 'LIKE', "%{$query}%")
              ->orWhere('death_date', 'LIKE', "%{$query}%");
        })->paginate(5);

        $setting = Setting::first();

        return view('admin.index', compact('people', 'setting'));
    }

    public function create()
    {
        $users = User::all();
        $setting = Setting::first();
        return view('admin.create', compact('users', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
        ]);

        $user = Auth::user();
        $data = $request->all();
        $data['user_id'] = $user->role == 'admin' ? $user->id : ($data['user_id'] ?? $user->id);

        People::create($data);

        return redirect()->route('admin.index')->with('success', 'Person added successfully.');
    }

    public function show($id)
    {
        $person = People::findOrFail($id);
        return view('admin.show', compact('person'));
    }

    public function edit($id)
    {
        $person = People::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' || $person->user_id !== $user->id) {
            return redirect()->route('admin.index')->with('error', 'You do not have permission to edit this person.');
        }

        $setting = Setting::first();
        $users = User::all();

        return view('admin.edit', compact('person', 'users', 'setting'));
    }

    public function update(Request $request, $id)
    {
        $person = People::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' || $person->user_id !== $user->id) {
            return redirect()->route('admin.index')->with('error', 'You do not have permission to update this person.');
        }

        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
        ]);

        $person->update($validatedData);

        return redirect()->route('admin.index')->with('success', 'Data updated successfully.');
    }

    public function destroy($id)
    {
        $person = People::findOrFail($id);
        $person->delete();

        return redirect()->route('admin.index')->with('success', 'Data successfully removed.');
    }
}
