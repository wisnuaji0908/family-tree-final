<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;


class PeopleController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $setting = Setting::first();
        $people = People::query()->where('user_id', $user->id)->paginate(5);
        // Menambahkan variabel $peopleTreeData yang belum didefinisikan
        $peopleTreeData = []; // Definisikan sesuai kebutuhan
        return view('people.index', compact('people', 'setting', 'peopleTreeData'));
    }

    public function create()
    {
        $users = User::all(); 
        $setting = Setting::first();
        return view('people.create', compact('users', 'setting'));
    
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'place_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
        ]);

        $userId = Auth::id();
        $data = $request->all();
        $setting = Setting::first();
        $data['user_id'] = $userId;

        People::create($data);

        return redirect()->route('people.index')->with('success', 'Person added successfully.');
    }


    public function edit($id)
    {
        $person = People::findOrFail($id); 
        $users = User::all(); 
        $setting = Setting::first();
        return view('people.edit', compact('person', 'users', 'setting'));
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
        $setting = Setting::first();

        return redirect()->route('people.index')->with('success', 'Data updated successfully.');
    }


    public function destroy($id)
    {
        $person = People::findOrFail($id);
        $person->delete();
        $setting = Setting::first();
    
        return redirect()->route('people.index')->with('success', 'Data successfully removed.');
    }

    public function viewTree($id) {
        $person = People::findOrFail($id);
        $parents = $person->parents; 
        $couple = $person->couples;
        $setting = Setting::first();
        return view('people.viewtree', compact('person', 'parents', 'couple', 'setting'));
    }
  
    
    public function showClaimForm()
    {   
        if (User::where('id', request()->user()->id)->whereNotNull('people_id')->first()) {
            abort(403); 
        }

        $people = People::get(); 
        $setting = Setting::first();
        return view('people.claim', compact('people', 'setting'));
    }

    public function claim(Request $request)
    {
        // Validasi input
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
                ->whereNotNull('user_id')
                ->firstOrFail();

        // Cek kecocokan tanggal lahir dan tempat lahir
        if ($person->birth_date == $request->birth_date && $person->place_birth === $request->place_birth) {
            User::where('id', request()->user()->id)->update(['people_id' => $person->id]);

            return redirect()->route('people.index')->with('success', 'Account claimed successfully.');
        } else {
            // Jika tidak cocok, kembalikan dengan pesan error
            return redirect()->back()->withErrors(['error' => 'Birth date or Place of birth does not match.'])->withInput();
        }
    }
}
