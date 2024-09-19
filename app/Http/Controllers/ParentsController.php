<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use App\Models\People;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function index()
    {
        // Mengambil semua data parents dan memuat relasi user, people, dan parentEntity
        $parents = Parents::with(['user', 'people', 'parentEntity'])->get();
        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        // Ambil data user dan people untuk keperluan form
        $users = User::all();
        $people = People::all();
        return view('parents.create', compact('users', 'people'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'nullable|exists:id',
            'user_id' => 'nullable|exists:users,id', // User_id bisa kosong
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:parents,id', // Parent_id bisa kosong
            'parent' => 'required|in:father,mother',
        ]);

        // Buat data baru
        Parents::create($request->only(['user_id', 'people_id', 'parent_id', 'parent']));
        return redirect()->route('parents.index')->with('success', 'Parent added successfully.');
    }

    public function edit($id)
    {
        $parent = Parents::findOrFail($id);
        $users = User::all();
        $people = People::all();
        return view('parents.edit', compact('parent', 'users', 'people'));
    }

    public function update(Request $request, $id)
    {
            // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id', 
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:parents,id', 
            'parent' => 'required|in:father,mother',
         ]);

            // Update data
        $parent = Parents::findOrFail($id);
        $parent->update($request->only(['user_id', 'people_id', 'parent_id', 'parent']));
            
        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }


    public function destroy($id)
    {
        // Validasi: Pastikan parent dengan ID tersebut ada
        $parent = Parents::find($id);

        if (!$parent) {
            return redirect()->route('parents.index')->with('error', 'Parent not found.');
        }

        // Hapus data
        $parent->delete();
        
        return redirect()->route('parents.index')->with('success', 'Data successfully removed.');
    }
}
