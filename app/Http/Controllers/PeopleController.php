<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 
use App\Models\People;
use Illuminate\Http\Request;


class PeopleController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $people = People::query()->where('user_id', $user->id)->paginate(5);
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

        $userId = Auth::id();
        $data = $request->all();
        $data['user_id'] = $userId;

        People::create($data);

        return redirect()->route('people.index')->with('success', 'Person added successfully.');
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
            'user_id' => 'nullable|exists:users,id',
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

    public function showClaimForm()
    {   
        if (User::where('id', request()->user()->id)->whereHas('people')->first()) {
            abort(403); 
        }

        $people = People::whereNull('user_id')->get(); 

        return view('people.claim', compact('people'));
    }

    public function claim(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'birth_date' => 'required|date',
            'place_birth' => 'required|string|max:255', 
        ]);

        // $user = request()->user();

        // $user->people()->create([
        //     'name' => $request->name,
        //     'gender' => $request->gender,
        //     'birth_date' => $request->birth_date,
        //     'place_birth' => $request->place_birth,
        // ]);

        //     return redirect()->route('people.index')->with('success', 'Account claimed successfully.');


        // Mencari orang berdasarkan ID dan memastikan user_id belum diisi
        $person = People::where('id', $request->person_id)
                        ->whereNull('user_id') // Pastikan user belum mengklaim
                        ->firstOrFail();

        // Cek kecocokan tanggal lahir dan tempat lahir
        if ($person->birth_date == $request->birth_date && $person->place_birth === $request->place_birth) {
            // Update user_id jika klaim berhasil
            $person->update(['user_id' => auth()->user()->id]);

            return redirect()->route('people.index')->with('success', 'Account claimed successfully.');
        } else {
            // Jika tidak cocok, kembalikan dengan pesan error
            return redirect()->back()->withErrors(['error' => 'Birth date or Place of birth does not match.'])->withInput();
        }
    }
}
