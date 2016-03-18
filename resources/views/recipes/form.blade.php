{!! BootForm::open(['model' => $recipe, 'store' => 'recipes.store', 'update' => 'recipes.update', 'files' => true]); !!}

    <div class="row">
        <div class="col-sm-8">
            {!! BootForm::text('name') !!}
            {!! BootForm::textarea('description', 'Description', null, ['class' => 'wysiwyg']) !!}
            {!! BootForm::select('ingredients[]', 'Ingredients', App\Ingredient::ingredientsMultiSelect(), $recipe->ingredients->lists('id')->toArray(), ['multiple']) !!}
        </div>
        <div class="col-sm-4">
            {!! BootForm::file('photo', 'Recipe Picture') !!}
            @if ($pic = $recipe->photo)
                <div class="thumbnail mt0">
                    <img class="img-responsive" src="{{ url($pic) }}" alt="{{ $recipe->name }}" data-action="zoom" />
                    <a class="btn btn-default btn-sm btn-absolute-top-right" href="{{ route('delete.recipe.photo', $recipe->id) }}"><i class="icon ion-trash-a"></i> delete</a>
                </div>
            @endif

            {!! BootForm::select('level', 'Level', $recipe->levels(), null) !!}
            {!! BootForm::select('course', 'Course', $recipe->courses(), null) !!}
            
            {!! BootForm::hidden('is_private', false) !!}{{-- Needed for update --}}
            {!! BootForm::checkbox('is_private', 'Private Recipe <i class="icon ion-locked" data-toggle="tooltip" data-placement="top" title="Only you will see the recipe"></i>') !!}
        </div>
    </div>

{{-- 
    <h2 class="mt40">
        Select Ingredients
    </h2>
    <hr>
    <div class="row">
        @foreach ($ingredientTypes as $item)

            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4>{{ $item->name }}</h4></div>
                    <div class="panel-body">
                        @foreach ($item->ingredients as $ingredient)
                            {!! BootForm::checkbox('ingredients[]', $ingredient->name, $ingredient->id) !!}
                        @endforeach
                    </div>
                </div>
            </div>

        @endforeach
    </div>
 --}}

    <hr>
    <div class="form-group">
        {!! Form::submit('Save Recipe', ['class' => 'btn btn-primary btn-lg']) !!}
    </div>

{!! BootForm::close() !!}
