<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Recipe extends Model implements SluggableInterface
{
    use SluggableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'photo',
        'description',
        'course',           // category es. Apetizers or Desserts
        'level',            // difficulty level
        'is_private',
        'user_id'
    ];

    /**
     * Sluggable configuration
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
        'on_update'  => true,
    ];

    /**
     * Define uploaded file path
     */
    protected $uploadPath = 'uploads/recipes';

    /**
     * The ingredients that belong to the recipe.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe');
    }

    /**
     * The user that owns the recipe.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getPublished($user = null)
    {
        return static::where('is_private', 0)
               ->orderBy('created_at', 'desc')
               ->paginate(10);
    }

    public static function getPublishedByCourse($course)
    {
        return static::where('is_private', 0)
               ->orderBy('created_at', 'desc')
               ->where('course', $course)
               ->paginate(10);
    }

    public static function getPublishedByUser($user_id)
    {
        return static::where('is_private', 0)
               ->orderBy('created_at', 'desc')
               ->where('user_id', $user_id)
               ->paginate(10);
    }

    public static function levels()
    {
        return [
            'easy'          => 'Easy',
            'medium'        => 'Medium',
            'hard'          => 'Hard',
            'very-hard'     => 'Very hard',
        ];
    }

    public static function courses()
    {
        return [
            'appetizers'    => 'Appetizers',
            'starters'      => 'Starters',
            'main-courses'  => 'Main courses',
            'side-dishes'   => 'Side dishes',
            'desserts'      => 'Desserts',
            'drinks'        => 'Drinks',
        ];
    }

    public function getPhotoAttribute($value)
    {
        return ($value ? $this->uploadPath .'/'. $value : $value);
    }

    public function handlePhoto(UploadedFile $file)
    {
        $unique = md5($file->getClientOriginalName().time());
        $filename = $this->id . '-recipe-' . $unique . '.' . $file->getClientOriginalExtension();

        $file->move($this->uploadPath, $filename);

        $this->photo = $filename;
    }
}
