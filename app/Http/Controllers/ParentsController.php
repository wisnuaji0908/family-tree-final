<?php
namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ParentsController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $parents = Parents::with(['user', 'people', 'userParent'])->paginate(5);
        return view('parents.index', compact('parents', 'setting'));
    }


    public function create()
    {
        $users = User::all();
        $people = People::all();
        $setting = Setting::first();
        return view('parents.create', compact('users', 'people', 'setting'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'required|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        // Validasi gender-role consistency
        $parent = People::find($request->parent_id);
        if (
            ($parent->gender === 'male' && $request->parent !== 'father') ||
            ($parent->gender === 'female' && $request->parent !== 'mother')
        ) {
            return redirect()->back()->withErrors(['parent' => 'Role tidak sesuai dengan gender parent.'])->withInput();
        }

        // Cek jika sudah ada dua parent
        $existingParentsCount = Parents::where('people_id', $request->people_id)->count();
        if ($existingParentsCount >= 2) {
            return redirect()->back()->withErrors(['people_id' => 'This person already has two parents.'])->withInput();
        }

        // Cek kombinasi duplikat
        $existingParent = Parents::where('people_id', $request->people_id)
            ->where('parent_id', $request->parent_id)
            ->first();

        if ($existingParent) {
            return redirect()->back()->withErrors(['parent_id' => 'This parent combination already exists.'])->withInput();
        }

        // Cek nama person dan parent sama
        $person = People::find($request->people_id);
        if ($person && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        // Store data baru
        Parents::create([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'parent_id' => $request->parent_id,
            'parent' => $request->parent,
        ]);

        return redirect()->route('parents.index')->with('success', 'Parent created successfully.');
    }

    public function edit($id)
    {
        $parent = Parents::findOrFail($id);
        $users = User::all();
        $people = People::all();
        $setting = Setting::first();
        return view('parents.edit', compact('parent', 'users', 'people', 'setting'));

    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'required|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        $parentData = Parents::findOrFail($id);

        // Validasi gender-role consistency
        $parent = People::find($request->parent_id);
        if (
            ($parent->gender === 'male' && $request->parent !== 'father') ||
            ($parent->gender === 'female' && $request->parent !== 'mother')
        ) {
            return redirect()->back()->withErrors(['parent' => 'Role tidak sesuai dengan gender parent.'])->withInput();
        }

        // Cek duplikat kombinasi kecuali current data
        $existingParent = Parents::where('people_id', $request->people_id)
            ->where('parent_id', $request->parent_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingParent) {
            return redirect()->back()->withErrors(['parent_id' => 'This parent combination already exists.'])->withInput();
        }

        // Cek nama person dan parent sama
        $person = People::find($request->people_id);
        if ($person && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        // Update data
        $parentData->update([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'parent_id' => $request->parent_id,
            'parent' => $request->parent,
        ]);

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }

    public function show($id)
    {
        // Ambil parent berdasarkan ID
        $parent = Parents::findOrFail($id);

        // Ambil data parent berdasarkan user yang sedang login
        $parents = Parents::where('people_id', $parent->people_id)
            ->where('user_id', Auth::id())
            ->with(['userParent', 'people'])
            ->get();

        return view('parents.show', compact('parents', 'parent'));
    }


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
            return redirect()->route('parents.index')->with('error', 'Anda tidak memiliki izin untuk menghapus data ini.');
        }

        // Hapus data jika user valid
        $parent->delete();

        return redirect()->route('parents.index')->with('success', 'Data successfully removed.');
    }

    public function getParent($id)
    {
        $person = People::find($id);

        $parentFather = Parents::where('people_id', $id)
            ->where('parent', 'father')
            ->with(['userParent', 'people'])
            ->get();

        $parentMother = Parents::where('people_id', $id)
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
