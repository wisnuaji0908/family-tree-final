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
    
    $coupleperson = Couple::with(['people', 'partner'])
        ->where('people_id', $claimedPersonId)
        ->orWhere('couple_id', $claimedPersonId)
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
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

        $claimedPersonId = Auth::user()->people_id;

        $existingCouple = Couple::where(function($query) use ($claimedPersonId, $request) {
            $query->where('people_id', $claimedPersonId)
                  ->where('couple_id', $request->couple_id);
        })->orWhere(function($query) use ($claimedPersonId, $request) {
            $query->where('people_id', $request->couple_id)
                  ->where('couple_id', $claimedPersonId);
        })->first();

        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }

        Couple::create([
            'user_id' => Auth::id(), 
            'people_id' => $claimedPersonId, 
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('peoplecouple.index')->with('success', 'Couple created successfully.');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Couple $couplesperson)
    {
        if ($couplesperson->user_id !== Auth::id()) {
            return redirect()->route('peoplecouple.index')->withErrors('You do not have permission to update this data.');
        }

        $request->validate([
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

        $claimedPersonId = Auth::user()->people_id;

        $existingCouple = Couple::where(function($query) use ($claimedPersonId, $request, $couplesperson) {
            $query->where('people_id', $claimedPersonId)
                  ->where('couple_id', $request->couple_id)
                  ->where('id', '!=', $couplesperson->id);
        })->orWhere(function($query) use ($claimedPersonId, $request, $couplesperson) {
            $query->where('people_id', $request->couple_id)
                  ->where('couple_id', $claimedPersonId)
                  ->where('id', '!=', $couplesperson->id);
        })->first();

        if ($existingCouple) {
            return redirect()->back()->withErrors('This couple is already registered.');
        }

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
}
