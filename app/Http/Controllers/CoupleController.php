<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class CoupleController extends Controller
{
    public function index()
    {
        $couple = Couple::with('people')->paginate(5); 
        $setting = Setting::first();
        return view('couple.index', compact('couple', 'setting'));
    }

    public function create()
    {
        $people = People::all();
        $setting = Setting::first();
        return view('couple.create', compact('people', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'people_id' => 'required|exists:people,id',
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);
    
        // Dapatkan informasi gender dari people_id dan couple_id
        $person = People::findOrFail($request->people_id);
        $partner = People::findOrFail($request->couple_id);
    
        // Validasi apakah gender mereka berbeda
        if ($person->gender === $partner->gender) {
            return redirect()->back()->withErrors('The couple must be of different genders.');
        }
    
        // Cek jika person adalah perempuan dan sudah memiliki pasangan
        if ($person->gender === 'female') {
            // Cek apakah dia sudah memiliki pasangan dan pasangan tersebut belum bercerai
            $existingCouple = $person->couples()->whereNull('divorce_date')->exists();
            if ($existingCouple) {
                return redirect()->back()->withErrors('This female person already has a partner.');
            }
        }
    
        // Cek jika partner adalah perempuan dan sudah memiliki pasangan
        if ($partner->gender === 'female') {
            // Cek apakah dia sudah memiliki pasangan dan pasangan tersebut belum bercerai
            $existingCouple = $partner->couples()->whereNull('divorce_date')->exists();
            if ($existingCouple) {
                return redirect()->back()->withErrors('This female partner already has a partner.');
            }
        }
    
        // Cek apakah pasangan sudah ada di database
        $existingCouple = Couple::where(function($query) use ($request) {
            $query->where('people_id', $request->people_id)
                  ->where('couple_id', $request->couple_id);
        })->orWhere(function($query) use ($request) {
            $query->where('people_id', $request->couple_id)
                  ->where('couple_id', $request->people_id);
        })->first();
    
        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }
    
        // Jika lolos validasi, simpan pasangan
        Couple::create([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);
    
        return redirect()->route('couple.index')->with('success', 'Couple created successfully.');
    }
    
    

    public function edit(Couple $couple)
    {
        $people = People::all();
        $setting = Setting::first();
        return view('couple.edit', compact('couple', 'people', 'setting'));
    }

    public function update(Request $request, Couple $couple)
    {
        $request->validate([
            'people_id' => 'required|exists:people,id',
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);
    
        // Dapatkan informasi gender dari people_id dan couple_id
        $person = People::findOrFail($request->people_id);
        $partner = People::findOrFail($request->couple_id);
    
        // Validasi apakah gender mereka berbeda
        if ($person->gender === $partner->gender) {
            return redirect()->back()->withErrors('The couple must be of different genders.');
        }
    
        // Cek jika person adalah perempuan dan sudah memiliki pasangan
        if ($person->gender === 'female') {
            // Cek apakah dia sudah memiliki pasangan yang belum bercerai
            $existingCouple = $person->couples()
                ->whereNull('divorce_date')
                ->where('id', '!=', $couple->id) // Pastikan untuk mengecualikan pasangan yang sedang diedit
                ->exists();
            if ($existingCouple) {
                return redirect()->back()->withErrors('This female person already has a partner.');
            }
        }
    
        // Cek jika partner adalah perempuan dan sudah memiliki pasangan
        if ($partner->gender === 'female') {
            // Cek apakah dia sudah memiliki pasangan yang belum bercerai
            $existingCouple = $partner->couples()
                ->whereNull('divorce_date')
                ->where('id', '!=', $couple->id) // Pastikan untuk mengecualikan pasangan yang sedang diedit
                ->exists();
            if ($existingCouple) {
                return redirect()->back()->withErrors('This female partner already has a partner.');
            }
        }
    
        // Cek apakah pasangan sudah ada di database
        $existingCouple = Couple::where(function($query) use ($request, $couple) {
            $query->where('people_id', $request->people_id)
                  ->where('couple_id', $request->couple_id)
                  ->where('id', '!=', $couple->id);
        })->orWhere(function($query) use ($request, $couple) {
            $query->where('people_id', $request->couple_id)
                  ->where('couple_id', $request->people_id)
                  ->where('id', '!=', $couple->id);
        })->first();
    
        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }
    
        // Jika lolos validasi, update pasangan
        $couple->update([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);
    
        return redirect()->route('couple.index')->with('success', 'Couple updated successfully.');
    }
    
    
    
    public function destroy(Couple $couple)
    {
        $couple->delete();

        return redirect()->route('couple.index')->with('success', 'Couple deleted successfully.');
    }

    
    public function getTreeData($id)
    {
    // Mengambil data orang dengan pasangan mereka, diurutkan berdasarkan tanggal pernikahan
    $people = People::with(['couples' => function($query) {
        $query->orderBy('married_date', 'asc');
    }, 'couples.partner'])->findOrFail($id);

    // Menyimpan apakah orang ini sudah memiliki pasangan baru setelah perceraian
    $hasNewPartnerAfterDivorce = false;

    // Tambahkan informasi gender ke data root (orang utama)
    $treeData = [
        'name' => $people->name,
        'gender' => $people->gender,
        'divorce_date' => null,
        'children' => [],
        'color' => 'red' // Default warna merah jika sudah bercerai
    ];

    // Menambahkan pasangan dan status pernikahan mereka
    foreach ($people->couples as $couple) {
        $isDivorced = !is_null($couple->divorce_date); // Cek apakah pasangan ini sudah bercerai
    
        // Jika pasangan sudah bercerai
        if ($isDivorced) {
            $treeData['color'] = 'red'; // Orang utama tetap merah karena bercerai
        }
    
        // Jika pasangan belum bercerai, orang ini mungkin menikah lagi
        if (!$isDivorced) {
            $hasNewPartnerAfterDivorce = true;
            $treeData['color'] = 'green'; // Orang utama berubah menjadi hijau jika menikah lagi
        }
    
        // Tambahkan pasangan sebagai children
        $treeData['children'][] = [
            'name' => $couple->partner->name,
            'gender' => $couple->partner->gender,
            'married_date' => $couple->married_date,
            'divorce_date' => $couple->divorce_date,
            'color' => $isDivorced ? 'red' : 'green', // Pasangan yang bercerai berwarna merah
            'children' => []
        ];
    }

    
    // Mengambil pasangan lain yang mungkin ada di partner (untuk pasangan lain dari partner)
    $otherPartners = Couple::where('couple_id', $people->id)->with('people')->get();
    foreach ($otherPartners as $otherPartner) {
        $treeData['children'][] = [
            'name' => $otherPartner->people->name,
            'gender' => $otherPartner->people->gender,
            'married_date' => $otherPartner->married_date,
            'divorce_date' => $otherPartner->divorce_date,
            'color' => $otherPartner->divorce_date ? 'red' : 'green',
            'children' => []
        ];
    }

    return response()->json($treeData);
}


    
}
