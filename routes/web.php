<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Activity;
use App\Anime;
use App\Favorite;
use App\Fruta;
use App\Pokemon;
use Illuminate\Http\Request;

Route::post('tareas/{codigo}/crear', function (Request $request, $codigo) {

    $model = Activity::create([
        'date'     => $request->get('date'),
        'activity' => $request->get('description'),
        'assigned' => $codigo,
    ]);

    return $model;
});

Route::get('tareas/{assigned}', function ($assigned, Request $request) {

    $query = $request->get('query');

    return Activity::where('assigned', $assigned)
        ->where('activity', 'like', "%{$query}%")
        ->get()
        ->map(function ($item) {
            return [
                'id'          => $item->id,
                'assigned'    => $item->assigned,
                'date'        => $item->date,
                'isDone'      => $item->done,
                'description' => $item->activity,
            ];
        });
});

Route::get('tareas/{id}/mostrar', function ($id) {

    $item = Activity::find($id);

    return [
        'id'          => $item->id,
        'assigned'    => $item->assigned,
        'date'        => $item->date,
        'favorite'    => $item->done,
        'description' => $item->activity,
    ];
});

Route::post('tareas/{id}/favorito', function ($id) {

    $model = Activity::find($id);
    $model->done = !$model->done;
    $model->save();

    return "ok";
});

Route::post('tareas/{id}/done', function ($id) {

    $model = Activity::find($id);
    $model->done = true;
    $model->save();

    return $model;
});

Route::post('{code}/anime/favorite', function (Request $request, $code) {

    $id = $request->input('id');

    $anime = Favorite::where('codigo', $code)
        ->where('anime_id', $id)
        ->first();

    if ($anime == null) {
        Favorite::create([
            'codigo'   => $code,
            'anime_id' => $id
        ]);
        return "favorito creado";
    } else {
        $anime->delete();
        return "favorito eliminado";
    }
});

Route::get('{code}/animes', function ($code) {
    $ids = Favorite::where('codigo', $code)
        ->get()
        ->pluck('anime_id');

    return Anime::whereIn('id', $ids)->get();
});

Route::get('animes', function (Request $request) {
    $query = Anime::query();

    if ($request->has('query')) {
        $q = $request->get('query');
        $query->where('nombre', 'like', "%{$q}%");
    }

    return $query->get()->map(function ($item) {
        $item->imagen = URL::to(str_replace('http://animes.lumenagile.com', ''));
        return $item;
    });
});



Route::get('pokemons/{code}', function (Request $request, $code) {

    $query = Pokemon::where('codigo', $code);

    return $query->get();
});


Route::get('pokemons/{code}/atrapados', function (Request $request, $code) {

    $query = Pokemon::where('codigo', $code)->where('esta_atrapado', true);

    return $query->get();
});


Route::post('pokemons/{code}/crear', function (Request $request, $code) {

    $model = Pokemon::create([
        'codigo'     => $code,
        'nombre' => $request->get('nombre'),
        'tipo' => $request->get('tipo'),
        'url_imagen' => $request->get('url_imagen'),
        'esta_atrapado' => false
    ]);

    $model->save();

    return $model;
});


Route::post('pokemons/{code}/atrapar/{pokemon}', function (Request $request, $code, $pokemon) {

    $model = Pokemon::find($pokemon);
    $model->esta_atrapado = !$model->esta_atrapado;

    $model->save();

    return $model;
});


Route::get('{code}/frutas', function (Request $request, $code) {

    $query = Fruta::where('codigo', $code);

    return $query->get();
});

Route::post('{codigo}/frutas/crear', function (Request $request, $codigo) {

    $model = Fruta::create([
        'nombre' => $request->get('nombre'),
        'vitaminas' => $request->get('vitaminas'),
        'codigo' => $codigo,
        'me_gusta' => false
    ]);


    return $model;
});

Route::post('{code}/frutas/{fruta_id}/megusta', function (Request $request, $code, $fruta_id) {

    $model = Fruta::find($fruta_id);

    $model->me_gusta = !$model->me_gusta;

    $model->save();

    return $model;
});
