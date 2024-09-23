<?php
namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ParentsController extends Controller
{
    public function index()
    {
        $parents = Parents::with(['user', 'people', 'userParent'])->paginate(5); 
        return view('parents.index', compact('parents'));
    }
    

    public function create()
    {
        $users = User::all();
        $people = People::all();
        return view('parents.create', compact('users', 'people'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        // Cek apakah person sudah memiliki dua orang tua
        $existingParentsCount = Parents::where('people_id', $request->people_id)->count();
        if ($existingParentsCount >= 2) {
            return redirect()->back()->withErrors('This person already has two parents.')->withInput();
        }

        // Cek jika kombinasi orang tua sudah ada di database
        $existingParent = Parents::where('people_id', $request->parent_id)
            ->first();

        if ($existingParent) {
            return redirect()->back()->withErrors('This parent combination is already registered.')->withInput();
        }

        // Cek apakah nama person dan parent_name sama
        $person = People::find($request->people_id);
        $parent = People::find($request->parent_id);

        if ($person && $parent && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        // Create new parent record
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
        return view('parents.edit', compact('parent', 'users', 'people'));
    
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'people_id' => 'required|exists:people,id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        // Ambil data parent yang sedang diupdate
        $parentData = Parents::findOrFail($id);

        // Cek apakah nama person dan parent sama (larangan menggunakan nama yang sama)
        $person = People::find($request->people_id); // Nama person yang sedang diedit
        $parent = People::find($request->parent_id); // Nama parent yang sedang ingin di-update

        if ($person && $parent && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        // Cek apakah orang tua (father atau mother) sudah ada di database, kecuali record yang sedang di-update
        $existingParent = Parents::where('people_id', $request->people_id)
            ->where('parent_id', $request->parent_id)
            ->where('id', '!=', $id) // Kecualikan parent yang sedang di-update
            ->first();

        if ($existingParent) {
            return redirect()->back()->withErrors('This parent combination is already registered.')->withInput();
        }

        // Update data jika validasi lolos
        $parentData->update([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id, // Tetap gunakan people_id
            'parent_id' => $request->parent_id, // Update parent_id jika perlu
            'parent' => $request->parent, // Father or mother
        ]);

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');
    }

    public function destroy($id)
    {
        $parent = Parents::find($id);
        if (!$parent) {
            return redirect()->route('parents.index')->with('error', 'Parent not found.');
        }
        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'Data successfully removed.');
    }
}
