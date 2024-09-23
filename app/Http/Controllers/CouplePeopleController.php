<?php

namespace App\Http\Controllers;

use App\Models\Couple;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class CouplePeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $couple = Couple::with('people')->paginate(5); 
        return view('couplepeople.index', compact('couple'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $people = People::all();
        return view('couplepeople.create', compact('people'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'people_id' => 'required|exists:people,id',
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

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

        Couple::create([
            'user_id' => Auth::id(),
            'people_id' => $request->people_id,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('peoplecouple.index')->with('success', 'Couple created successfully.');
    }

    /**
     * Display the specified resource.
     */

    public function edit(Couple $couple)
    {
        $people = People::all();
        return view('couplepeople.edit', compact('couple', 'people'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Couple $couple)
    {
        $request->validate([
            'people_id' => 'required|exists:people,id',
            'couple_id' => 'required|exists:people,id|different:people_id',
            'married_date' => 'required|date',
            'divorce_date' => 'nullable|date|after_or_equal:married_date',
        ]);

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

        $couple->update([
            'user_id' => Auth::id(), 
            'people_id' => $request->people_id,
            'couple_id' => $request->couple_id,
            'married_date' => $request->married_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('peoplecouple.index')->with('success', 'Couple updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Couple $couple)
    {
        $couple->delete();

        return redirect()->route('peoplecouple.index')->with('success', 'Couple deleted successfully.');
    }
}
