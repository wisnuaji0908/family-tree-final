<?php
use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
}); // {{ create_1 }}

Route::resource('people', PeopleController::class);
Route::get('/people', [PeopleController::class, 'index'])->name('people.index');
Route::get('/people/create', [PeopleController::class, 'create'])->name('people.create');
Route::post('/people/store', [PeopleController::class, 'store'])->name('people.store');
Route::get('/people/{id}', [PeopleController::class, 'show'])->name('people.show');
Route::get('/people/{id}/edit', [PeopleController::class, 'edit'])->name('people.edit');
Route::put('/people/{id}', [PeopleController::class, 'update'])->name('people.update');
Route::delete('/people/{id}', [PeopleController::class, 'destroy'])->name('people.destroy');
