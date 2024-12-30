<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouplePeopleController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::id();
        $setting = Setting::first();
        $claimedPersonId = Auth::user()->people_id;

        // Filter pasangan hanya yang terkait dengan pengguna yang login
        $coupleperson = Couple::with(['people', 'partner'])
            ->where('user_id', $loggedInUser) // Filter berdasarkan user_id
            ->paginate(5);

        return view('couplepeople.index', compact('coupleperson', 'setting'));
    }

    public function create()
    {
        $people = People::all();
        $setting = Setting::first();
        return view('couplepeople.create', compact('people', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'people_id' => 'required|exists:people,id',
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

        $person = People::findOrFail($request->people_id);
        $partner = People::findOrFail($request->couple_id);

        // Validasi apakah gender mereka berbeda
        if ($person->gender === $partner->gender) {
            return redirect()->back()->withErrors('The couple must be of different genders.');
        }

        // Validasi pasangan aktif untuk `person` jika gender adalah female
        if ($person->gender === 'female') {
            $activeMarriagePerson = Couple::where(function ($query) use ($person) {
                $query->where('people_id', $person->id)
                    ->orWhere('couple_id', $person->id);
            })->whereNull('divorce_date')->first();

            if ($activeMarriagePerson) {
                return redirect()->back()->withErrors('This female person already has an active partner and cannot remarry until divorced.');
            }
        }

        // Validasi pasangan aktif untuk `partner` jika gender adalah female
        if ($partner->gender === 'female') {
            $activeMarriagePartner = Couple::where(function ($query) use ($partner) {
                $query->where('people_id', $partner->id)
                    ->orWhere('couple_id', $partner->id);
            })->whereNull('divorce_date')->first();

            if ($activeMarriagePartner) {
                return redirect()->back()->withErrors('This female partner already has an active partner and cannot remarry until divorced.');
            }
        }

        // Cek jika salah satu pasangan sudah meninggal
        if (!is_null($person->death_date) || !is_null($partner->death_date)) {
            return redirect()->back()->withErrors('One or both partners are deceased and cannot marry.');
        }

        // Cek apakah pasangan sudah ada di database
        $existingCouple = Couple::where(function ($query) use ($request) {
            $query->where('people_id', $request->people_id)
                ->where('couple_id', $request->couple_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('people_id', $request->couple_id)
                ->where('couple_id', $request->people_id);
        })->first();

        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }

        // Jika semua validasi lolos, simpan pasangan baru
        Couple::create([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('couple.index')->with('success', 'Couple created successfully.');
    }

    public function edit(Couple $couplesperson)
    {
        if ($couplesperson->user_id !== Auth::id()) {
            return redirect()->route('peoplecouple.index')->withErrors('You do not have permission to edit this data.');
        }

        $setting = Setting::first();
        $people = People::all();
        $couple = $couplesperson;

        return view('couplepeople.edit', compact('couple', 'people', 'setting'));
    }

    public function update(Request $request, Couple $couplesperson)
    {
        // Pastikan user memiliki izin untuk update data ini
        if ($couplesperson->user_id !== Auth::id()) {
            return redirect()->route('peoplecouple.index')->withErrors('You do not have permission to update this data.');
        }

        // Validasi input
        $request->validate([
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

        $claimedPersonId = Auth::user()->people_id;
        $person = People::findOrFail($claimedPersonId);

        // Validasi pasangan perempuan (belum cerai, dua arah)
        if ($person->gender === 'female') {
            $activeCouples = Couple::where(function ($query) use ($claimedPersonId, $couplesperson) {
                $query->where(function ($subQuery) use ($claimedPersonId) {
                    $subQuery->where('people_id', $claimedPersonId)
                        ->orWhere('couple_id', $claimedPersonId);
                })->whereNull('divorce_date')
                    ->where('id', '!=', $couplesperson->id);
            })->exists();

            if ($activeCouples) {
                return redirect()->back()->withErrors('This female person already has a partner and cannot remarry until divorced.');
            }
        }

        // Validasi pasangan baru
        $partner = People::findOrFail($request->couple_id);

        if ($person->gender === $partner->gender) {
            return redirect()->back()->withErrors('The couple must be of different genders.');
        }

        // Cek apakah pasangan ini sudah terdaftar (dua arah)
        $existingCouple = Couple::where(function ($query) use ($claimedPersonId, $request, $couplesperson) {
            $query->where(function ($subQuery) use ($claimedPersonId, $request) {
                $subQuery->where('people_id', $claimedPersonId)
                    ->where('couple_id', $request->couple_id);
            })->orWhere(function ($subQuery) use ($claimedPersonId, $request) {
                $subQuery->where('people_id', $request->couple_id)
                    ->where('couple_id', $claimedPersonId);
            })->where('id', '!=', $couplesperson->id);
        })->exists();

        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }

        // Update pasangan
        $couplesperson->update([
            'people_id' => $claimedPersonId,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('peoplecouple.index')->with('success', 'Couple updated successfully.');
    }

    public function destroy(Couple $couplesperson)
    {
        if ($couplesperson->user_id !== Auth::id()) {
            return redirect()->route('peoplecouple.index')->withErrors('You do not have permission to delete this data.');
        }
        $couplesperson->delete();
        $setting = Setting::first();
        return redirect()->route('peoplecouple.index')->with('success', 'Couple deleted successfully.');
    }

    public function getTreeData($id)
    {
        // Mengambil data orang dengan pasangan mereka
        $people = People::with([
            'couples' => function ($query) {
                $query->orderBy('married_date', 'asc');
            },
            'couples.partner'
        ])->findOrFail($id);

        // Tambahkan informasi gender ke data root (orang utama)
        $treeData = [
            'name' => $people->name,
            'gender' => $people->gender,
            'divorce_date' => null,
            'children' => []
        ];

        // Menambahkan pasangan dan status pernikahan mereka
        foreach ($people->couples as $couple) {
            // Tambahkan pasangan sebagai children
            $treeData['children'][] = [
                'name' => $couple->partner->name,
                'gender' => $couple->partner->gender,
                'married_date' => $couple->married_date,
                'divorce_date' => $couple->divorce_date,
                'children' => []
            ];
        }

        // Mengambil pasangan lain yang mungkin ada di partner
        $otherPartners = Couple::where('couple_id', $people->id)->with('people')->get();
        foreach ($otherPartners as $otherPartner) {
            $treeData['children'][] = [
                'name' => $otherPartner->people->name,
                'gender' => $otherPartner->people->gender,
                'married_date' => $otherPartner->married_date,
                'divorce_date' => $otherPartner->divorce_date,
                'children' => []
            ];
        }

        return response()->json($treeData);
    }
}
