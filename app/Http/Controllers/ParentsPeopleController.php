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
        $loggedInUser = Auth::id(); // Mendapatkan ID user yang login
        $claimedPersonId = Auth::user()->people_id; // Mendapatkan ID people dari user yang login

        // Mengambil semua data parents terkait dengan user yang login (baik sebagai person atau parent)
        $parents = Parents::with(['user', 'people', 'userParent'])
            ->where('people_id', $claimedPersonId)
            ->orWhere('parent_id', $claimedPersonId)
            ->paginate(5);

        // Mengirimkan juga loggedInUser agar bisa dibandingkan di view
        return view('parentspeople.index', compact('parents', 'loggedInUser'));
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
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        // Cek apakah person sudah punya dua parent
        $existingParentsCount = Parents::where('people_id', $request->people_id)->count();
        if ($existingParentsCount >= 2) {
            return redirect()->back()->withErrors(['people_id' => 'This person already has two parents.'])->withInput();
        }

        // Cek apakah person sudah punya parent dengan role yang sama (mother/father)
        $existingParentWithSameRole = Parents::where('people_id', $request->people_id)
            ->where('parent', $request->parent)
            ->first();

        if ($existingParentWithSameRole) {
            return redirect()->back()->withErrors(['parent' => 'This person already has a ' . $request->parent . '.'])->withInput();
        }

        // Cek apakah nama person dan parent sama
        $person = People::find($request->people_id);
        $parent = People::find($request->parent_id);
        if ($person && $parent && $person->name === $parent->name) {
        return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
    }

    // Simpan data parent baru
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
     
         // Validasi apakah user yang login adalah pembuat data
         if (auth()->user()->id !== $parent->user_id) {
             return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data ini.');
         }
     
         $users = User::all();
         $people = People::all();
        return view('parentspeople.edit', compact('parent', 'users', 'people'));
    }
     

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    $parent = Parents::findOrFail($id);

    // Validasi apakah user yang login adalah pemilik data
    if (auth()->user()->id !== $parent->user_id) {
        // Menambahkan pesan flash error
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data ini.');
    }

    // Validasi input
    $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'people_id' => 'required|exists:people,id|unique:parents,people_id,' . $id,
        'parent_id' => 'nullable|exists:people,id|different:people_id',
        'parent' => 'required|in:father,mother',
    ]);

    // Validasi perubahan dari 'father' ke 'mother' atau sebaliknya
    if (($parent->parent === 'father' && $request->parent === 'mother') ||
        ($parent->parent === 'mother' && $request->parent === 'father')) {
        // Menambahkan pesan flash error
        return redirect()->back()->with('error', 'Tidak dapat mengubah parent role dari ' . $parent->parent . ' menjadi ' . $request->parent . '.');
    }

    $person = People::find($request->people_id);
    $parentData = People::find($request->parent_id);

    if ($person && $parentData && $person->name === $parentData->name) {
        // Menambahkan pesan flash error
        return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
    }

    // Update data parent
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
        // Temukan data berdasarkan ID
        $parent = Parents::find($id);

        // Jika data tidak ditemukan, berikan pesan error
        if (!$parent) {
            return redirect()->route('parents.index')->with('error', 'Parent not found.');
        }

        // Validasi apakah user yang login adalah pemilik data
        if (auth()->user()->id !== $parent->user_id) {
            // Menambahkan pesan flash error ketika user tidak memiliki hak
            return redirect()->route('parentspeople.index')->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }

        // Hapus data jika user valid
        $parent->delete();

        return redirect()->route('parentspeople.index')->with('success', 'Data successfully removed.');
    }
}