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
            'people_id' => 'required|exists:people,id|unique:parents,people_id',
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);


            // Cek apakah pasangan sudah ada di database (dalam urutan apapun)
        $existingParent = Parents::where(function($query) use ($request) {
            $query->where('people_id', $request->people_id)
                ->where('parent_id', $request->parent_id);
        })->orWhere(function($query) use ($request) {
            $query->where('people_id', $request->parent_id)
                ->where('parent_id', $request->people_id);
        })->first();

        if ($existingParent) {
            return redirect()->back()->withErrors('This parent combination is already registered.')->withInput();
        }


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
            'people_id' => 'required|exists:people,id|unique:parents,people_id,' . $id, 
            'parent_id' => 'nullable|exists:people,id|different:people_id',
            'parent' => 'required|in:father,mother',
        ]);

        // Cek apakah pasangan sudah ada di database (kecuali record yang sedang di-update)
        $existingParent = Parents::where(function($query) use ($request) {
            $query->where('people_id', $request->people_id)
                ->where('parent_id', $request->parent_id);
        })->orWhere(function($query) use ($request) {
            $query->where('people_id', $request->parent_id)
                ->where('parent_id', $request->people_id); // Cek urutan terbalik
        })->where('id', '!=', $id)->first();

        if ($existingParent) {
            return redirect()->back()->withErrors('This parent combination is already registered.')->withInput();
        }

        // Cek apakah nama person dan parent_name sama atau ditukar
        $person = People::find($request->people_id);
        $parent = People::find($request->parent_id);

        if ($person && $parent && $person->name === $parent->name) {
            return redirect()->back()->withErrors(['parent_id' => 'Person and Parent names cannot be the same.'])->withInput();
        }

        // Update data jika validasi lolos
        $parentData = Parents::findOrFail($id);

        $parentData->update([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'parent_id' => $request->parent_id,
            'parent' => $request->parent,
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
