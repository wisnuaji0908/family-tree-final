<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\People;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Parents; // Tambahkan ini di atas file PeopleController.php



class PeopleController extends Controller
{

    // public function getFamilyTree()
    // {
    //     // Ambil semua anggota keluarga beserta anak-anaknya
    //     $members = FamilyMember::with('children')->get();

    //     // Konversi data menjadi struktur pohon
    //     $tree = $this->buildTree($members);
    //     return response()->json($tree);
    // }

    // Fungsi untuk membuat struktur pohon
    private function buildTree($members, $parentId = null)
    {
        $branch = [];
        foreach ($members as $member) {
            if ($member->parent_id == $parentId) {
                $children = $this->buildTree($members, $member->id);
                $branch[] = [
                    'name' => $member->name,
                    'children' => $children
                ];
            }
        }
        return $branch;
    }


    public function index()
    {
        $user = request()->user();
        $setting = Setting::first();
        // Logika untuk admin dan user biasa
        if ($user->role === 'admin') {
            // Admin melihat semua data di tabel people
            $people = People::paginate(5);
        } else {
            // Untuk user biasa (bukan admin)
            $peopleQuery = People::query();

            // Tambahkan filter untuk kepala keluarga (misalnya ID 2)
            // atau user yang terkait langsung dengan `people_id`
            $peopleQuery->where('user_id', $user->id) // Data yang terkait dengan user login
                ->orWhere('id', $user->people_id); // Tambahkan data pasangan atau kepala keluarga

            // Optional: Tambahkan logika tambahan jika diperlukan
            if (!is_null($user->people_id)) {
                // Tampilkan data lainnya jika user memiliki `people_id`
                $peopleQuery->orWhere('user_id', $user->people_id);
            }

            $people = $peopleQuery->paginate(5);
        }
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
        $data['user_id'] = Auth::id(); // Pastikan data user_id sesuai dengan user login

        People::create($data);

        // dd('Redirecting to: people.index'); // Debug untuk memastikan sampai sini

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

    public function viewTree($id)
    {
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

    public function getFamilyTree($id)
    {
        try {
            \Log::info("Fetching family tree for person ID: {$id}");

            // Ambil data person dan relasinya
            $person = People::with(['parents.userParent', 'couples.partner'])->findOrFail($id);
            $allPeople = People::where('id', '!=', 1)->get();

            \Log::info("Person data fetched", ['person' => $person]);

            $nodes = [];
            $edges = [];

            // Fungsi untuk menambahkan node jika belum ada
            $addNodeIfNotExists = function ($id, $label, $type, $color) use (&$nodes) {
                if (!collect($nodes)->firstWhere('data.id', $id)) {
                    $nodes[] = [
                        "data" => [
                            "id" => $id,
                            "label" => $label,
                            "type" => $type,
                            "color" => $color,
                        ]
                    ];
                    \Log::info("Node added", ['id' => $id, 'label' => $label]);
                }
            };

            // Fungsi untuk menambahkan edge jika belum ada
            $addEdgeIfNotExists = function ($source, $target, $role = null) use (&$edges) {
                if (!collect($edges)->firstWhere('data', ['source' => $source, 'target' => $target])) {
                    $edges[] = [
                        "data" => [
                            "source" => $source,
                            "target" => $target,
                            "role" => $role,
                            "label" => $role,
                        ]
                    ];
                    \Log::info("Edge added", ['source' => $source, 'target' => $target, 'role' => $role]);
                }
            };

            // Tambahkan Person Node
            $personNodeId = "person_{$person->id}";
            // Cek apakah pasangan memiliki anak
            $hasChildren = \DB::table('parents')->where('parent_id', $person->id)->exists();
            $role = $hasChildren
                ? ($person->gender === 'male' ? 'Father' : 'Mother')
                : 'Pasangan'; // Jika tidak punya anak, role default jadi "Pasangan"

            $addNodeIfNotExists(
                $personNodeId,
                "{$person->name}\nGender: {$person->gender}\nRole: {$role}\nTempat, Tanggal lahir: {$person->place_birth}, {$person->birth_date}\nMeninggal: " . ($person->death_date ?? 'Belum Meninggal'),
                $person->death_date ? 'dead' : 'alive',
                $person->gender === 'female' ? 'pink' : 'blue'
            );

            // Tambahkan Couples Node dan Edge
            foreach ($person->couples as $couple) {
                $partner = $couple->partner;
                if ($partner) {
                    $partnerNodeId = "person_{$partner->id}";
                    // Cek apakah pasangan memiliki anak
                    $hasChildren = \DB::table('parents')->where('parent_id', $partner->id)->exists();
                    $role = $hasChildren
                        ? ($partner->gender === 'male' ? 'Father' : 'Mother')
                        : 'Pasangan'; // Jika tidak punya anak, role default jadi "Pasangan"

                    // Tambahkan node pasangan
                    $addNodeIfNotExists(
                        $partnerNodeId,
                        "{$partner->name}\nGender: {$partner->gender}\nRole: {$role}\n" . ($couple->divorce_date ? "Bercerai: {$couple->divorce_date}" : "Status: Menikah") . "\nMenikah: {$couple->married_date}\nMeninggal: " . ($partner->death_date ?? 'Belum Meninggal'),
                        'spouse',
                        $couple->divorce_date ? 'red' : ($partner->death_date && !$couple->divorce_date ? 'gray' : 'green')
                    );

                    // Tambahkan edge pasangan
                    $addEdgeIfNotExists($personNodeId, $partnerNodeId, 'Pasangan');
                }
            }


            // Tambahkan Anak dan Hubungan Parents
            foreach ($allPeople as $people) {
                $childNodeId = "person_{$people->id}";
                $color = $people->death_date ? 'black' : ($people->gender === 'female' ? 'pink' : 'blue');
                $addNodeIfNotExists(
                    $childNodeId,
                    "{$people->name}\nGender: {$people->gender}\nTempat, Tanggal lahir: {$people->place_birth}, {$people->birth_date}\nMeninggal: " . ($people->death_date ?? 'Belum Meninggal'),
                    $people->death_date ? 'dead' : 'alive',
                    $color
                );

                \Log::info("Node anak ditambahkan", ['childNodeId' => $childNodeId]);

                // Ambil relasi orang tua
                $parentRelations = \DB::table('parents')->where('people_id', $people->id)->get();
                \Log::info("Relasi orang tua ditemukan", ['child_id' => $people->id, 'parents' => $parentRelations->toArray()]);

                if ($parentRelations->count() > 1) {
                    // Kalau ada 2 orang tua (pasangan), hubungkan pasangan ke anak
                    $parentIds = $parentRelations->pluck('parent_id')->toArray();

                    $firstParent = People::find($parentIds[0]);
                    $secondParent = People::find($parentIds[1]);

                    if ($firstParent && $secondParent) {
                        $parentNodeId1 = "person_{$firstParent->id}";
                        $parentNodeId2 = "person_{$secondParent->id}";

                        // Tambahkan node untuk masing-masing parent
                        $addNodeIfNotExists(
                            $parentNodeId1,
                            "{$firstParent->name}\nGender: {$firstParent->gender}\nRole: Father\nTempat, Tanggal Lahir: {$firstParent->place_birth}, {$firstParent->birth_date}\nMeninggal: " . ($firstParent->death_date ?? 'Belum Meninggal'),
                            'parent',
                            $firstParent->death_date ? 'black' : ($firstParent->gender === 'female' ? 'pink' : 'blue')
                        );
                        $addNodeIfNotExists(
                            $parentNodeId2,
                            "{$secondParent->name}\nGender: {$secondParent->gender}\nRole: Mother\nTempat, Tanggal Lahir: {$secondParent->place_birth}, {$secondParent->birth_date}\nMeninggal: " . ($secondParent->death_date ?? 'Belum Meninggal'),
                            'parent',
                            $secondParent->death_date ? 'black' : ($secondParent->gender === 'female' ? 'pink' : 'blue')
                        );

                        \Log::info("Node pasangan ditambahkan", ['fatherNodeId' => $parentNodeId1, 'motherNodeId' => $parentNodeId2]);

                        // Tambahkan edge pasangan
                        $existingEdge = collect($edges)->firstWhere('data', ['source' => $parentNodeId1, 'target' => $parentNodeId2]);
                        if (!$existingEdge) {
                            $addEdgeIfNotExists($parentNodeId1, $parentNodeId2, 'Pasangan');
                            \Log::info("Edge pasangan ditambahkan", ['source' => $parentNodeId1, 'target' => $parentNodeId2]);
                        } else {
                            \Log::info("Edge pasangan sudah ada", ['source' => $parentNodeId1, 'target' => $parentNodeId2]);
                        }

                        // Tambahkan edge dari pasangan langsung ke anak
                        $existingChildEdge = collect($edges)->firstWhere('data', ['source' => $parentNodeId1, 'target' => $childNodeId]);
                        if (!$existingChildEdge) {
                            $addEdgeIfNotExists($parentNodeId1, $childNodeId, 'Anak');
                            \Log::info("Edge anak dari pasangan ditambahkan", ['source' => $parentNodeId1, 'target' => $childNodeId]);
                        } else {
                            \Log::info("Edge anak dari pasangan sudah ada", ['source' => $parentNodeId1, 'target' => $childNodeId]);
                        }
                    }
                } else {
                    // Kalau cuma ada satu parent, sambungkan langsung ke anak
                    foreach ($parentRelations as $relation) {
                        $parent = People::find($relation->parent_id);
                        if ($parent) {
                            $parentNodeId = "person_{$parent->id}";

                            $addNodeIfNotExists(
                                $parentNodeId,
                                "{$parent->name}\nGender: {$parent->gender}\nRole: Parent\nTempat, Tanggal Lahir: {$parent->place_birth}, {$parent->birth_date}\nMeninggal: " . ($parent->death_date ?? 'Belum Meninggal'),
                                'parent',
                                $parent->death_date ? 'black' : ($parent->gender === 'female' ? 'pink' : 'blue')
                            );

                            \Log::info("Node parent tunggal ditambahkan", ['parentNodeId' => $parentNodeId]);

                            $existingChildEdge = collect($edges)->firstWhere('data', ['source' => $parentNodeId, 'target' => $childNodeId]);
                            if (!$existingChildEdge) {
                                $addEdgeIfNotExists($parentNodeId, $childNodeId, 'Anak');
                                \Log::info("Edge anak dari parent tunggal ditambahkan", ['source' => $parentNodeId, 'target' => $childNodeId]);
                            } else {
                                \Log::info("Edge anak dari parent tunggal sudah ada", ['source' => $parentNodeId, 'target' => $childNodeId]);
                            }
                        }
                    }
                }
            }


            \Log::info("Family tree constructed successfully", ['nodes' => $nodes, 'edges' => $edges]);

            return response()->json(["nodes" => $nodes, "edges" => $edges]);
        } catch (\Exception $e) {
            \Log::error("Error fetching family tree: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function saveDiagramPositions(Request $request)
    {
        $positions = $request->input('positions'); // Format: [{node_id: 'person_1', x: 100, y: 200}, ...]
        \Log::info("Positions received: ", $positions); // Tambahkan log untuk debugging

        foreach ($positions as $position) {
            \DB::table('diagram_positions')->updateOrInsert(
                ['node_id' => $position['node_id']], // Cari berdasarkan `node_id`
                ['x_position' => $position['x'], 'y_position' => $position['y']] // Simpan posisi x dan y
            );
        }

        return response()->json(['message' => 'Diagram positions saved successfully!']);
    }

    public function getDiagramPositions()
    {
        $positions = \DB::table('diagram_positions')->get();
        \Log::info("Fetched positions: ", $positions->toArray()); // Tambahkan log
        return response()->json($positions); // Return semua posisi node
    }

}
