<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\People;
use App\Models\Couple;
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

        if (
            ($parent->parent === 'father' && $request->parent === 'mother') ||
            ($parent->parent === 'mother' && $request->parent === 'father')
        ) {
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
    public function getParent()
    {
        // Ambil semua user, urutkan berdasarkan ID terkecil
        $users = User::orderBy('id')->get();
        $person = null;

        // Loop setiap user untuk mencari `people_id` yang valid
        foreach ($users as $user) {
            if ($user->people_id) { // Kalau user punya `people_id`
                $person = People::with(['couples.partner', 'parents.userParent'])
                    ->find($user->people_id); // Cek di tabel `people`

                if ($person) { // Kalau person ditemukan, break loop
                    break;
                }
            }
        }

        // Kalau person tetap null setelah loop
        if (!$person) {
            return response()->json([
                'error' => true,
                'message' => 'No valid person found.',
            ], 404);
        }

        // Ambil data father dan mother
        $father = $person->parents->firstWhere('parent', 'father');
        $mother = $person->parents->firstWhere('parent', 'mother');

        // Ambil pasangan (couples)
        $couples = $person->couples->map(function ($couple) {
            $partner = $couple->partner; // Ambil data pasangan
            return [
                'name' => $partner->name ?? "Unknown",
                'married_date' => $couple->married_date ?? "Unknown",
                'divorce_date' => $couple->divorce_date ?? null,
                'death_date' => $partner->death_date ?? null, // Tambahkan death_date pasangan
            ];
        });

        // Ambil data anak-anak (children)
        $children = Parents::where('parent_id', $person->id)->with('people')->get()->map(function ($child) {
            return [
                'name' => $child->people->name ?? "Unknown",
                'birth_date' => $child->people->birth_date ?? "Unknown",
                'gender' => $child->people->gender ?? "Unknown",
            ];
        });

        // Buat struktur tree
        $tree = [
            'person' => [
                'name' => $person->name,
                'birth_date' => $person->birth_date,
                'death_date' => $person->death_date,
                'gender' => $person->gender,
            ],
            'parents' => [
                'father' => $father ? [
                    'name' => $father->userParent->name ?? "Unknown",
                    'birth_date' => $father->userParent->birth_date ?? "Unknown",
                    'death_date' => $father->userParent->death_date ?? null,
                ] : null,
                'mother' => $mother ? [
                    'name' => $mother->userParent->name ?? "Unknown",
                    'birth_date' => $mother->userParent->birth_date ?? "Unknown",
                    'death_date' => $mother->userParent->death_date ?? null,
                ] : null,
            ],
            'couples' => $couples,
            'children' => $children,
        ];

        // Return data JSON
        return response()->json($tree);
    }
}
