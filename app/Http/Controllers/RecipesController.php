<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Recipe;
use App\IngredientType;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Auth;

class RecipesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $recipes = Recipe::where('user_id', Auth::user()->id)
                         ->paginate(10);

        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $recipe = new Recipe;
        $ingredientTypes = IngredientType::with('ingredients')->get();

        return view('recipes.create', compact('recipe', 'ingredientTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'      => 'required|max:255',
            'photo'     => 'image|max:10000',
        ]);

        $data = $request->all();

        // save recipe photo (with unique filename)
        $recipe->handlePhoto($request->file('photo'));
        $data['photo'] = $recipe->photo;

        $recipe = Auth::user()->recipes()->create($data);

        // sync recipe ingredients
        $ingredients = $request->input('ingredients') ?: [];
        $recipe->ingredients()->sync($ingredients);

        Session::flash('flash_message', 'Recipe added!');

        return redirect('recipes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);

        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $recipe = Recipe::with('ingredients', 'user')->findOrFail($id);

        // TODO: Move to middleware guard
        if (Auth::id() != $recipe->user->id) {
            Session::flash('flash_error', 'Unauthorized action!');
            return back();
        }

        $ingredientTypes = IngredientType::with('ingredients')->get();

        return view('recipes.edit', compact('recipe', 'ingredientTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $recipe = Recipe::with('user')->findOrFail($id);

        // TODO: Move to middleware guard
        if (Auth::id() != $recipe->user->id) {
            Session::flash('flash_error', 'Unauthorized action!');
            return back();
        }

        $this->validate($request, [
            'name'      => 'required|max:255',
            'photo'     => 'image|max:10000|max:10000',
        ]);

        // associate ingredients to recipe
        $ingredients = $request->input('ingredients') ? $request->input('ingredients') : [];
        $recipe->ingredients()->sync($ingredients);

        $data = $request->all();

        // save recipe photo (with unique filename)
        $recipe->handlePhoto($request->file('photo'));
        $data['photo'] = $recipe->photo;

        $recipe->update($data);


        Session::flash('flash_message', 'Recipe updated!');

        return redirect('recipes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $recipe = Recipe::with('user')->findOrFail($id);

        // TODO: Move to middleware guard
        if (Auth::id() != $recipe->user->id) {
            Session::flash('flash_error', 'Unauthorized action!');
            return back();
        }

        Recipe::destroy($id);

        Session::flash('flash_message', 'Recipe deleted!');

        return redirect('recipes');
    }

    public function deletePhoto($id)
    {
        $recipe = Recipe::with('user')->findOrFail($id);

        // TODO: Move to middleware guard
        if (Auth::id() != $recipe->user->id) {
            Session::flash('flash_error', 'Unauthorized action!');
            return back();
        }

        // can't do this right now
        if (file_exists($recipe->photo) && is_file($recipe->photo)) {
            unlink($recipe->photo);
        }

        $recipe->photo = null;
        $recipe->save();

        Session::flash('flash_message', 'Recipe photo deleted!');

        return back();
    }

}
