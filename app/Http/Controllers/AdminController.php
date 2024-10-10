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
        // Mengambil query pencarian jika ada
        $query = $request->input('query');
        
        // Mencari orang berdasarkan query
        if ($query) {
            $people = People::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('gender', 'LIKE', "%{$query}%")
                            ->orWhere('place_birth', 'LIKE', "%{$query}%")
                            ->paginate(5);
        } else {
            $people = People::paginate(5);
        }

        // Mengambil pengaturan terbaru untuk navbar
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
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date',
        ]);

        // Mendapatkan user yang sedang login
        $user = Auth::user();
        $setting = Setting::first();
        // Ambil semua data request
        $data = $request->all();

        // Jika user adalah admin, user_id diisi otomatis dengan ID admin
        if ($user->role == 'admin') {
            $data['user_id'] = $user->id;
        } else {
            // Jika user bukan admin, maka biarkan user memilih user_id (jika ada inputnya)
            $data['user_id'] = $data['user_id'] ?? $user->id;
        }

        // Buat People baru dengan data yang sudah diproses
        People::create($data);

        // Redirect ke halaman index dengan pesan sukses
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

        // Pastikan hanya admin yang dapat mengedit yang mereka buat
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
        $setting = Setting::first();
        // Pastikan hanya admin yang dapat mengupdate yang mereka buat
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
        $setting = Setting::first();
        return redirect()->route('admin.index')->with('success', 'Data successfully removed.');
    }
    
}
