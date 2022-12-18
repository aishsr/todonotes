<?php declare(strict_types = 1);

namespace App\Models;

use App\Models\BaseModel;
use GuzzleHttp\Tests\Stream\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseModel
{
    // use Authenticatable, Authorizable, HasFactory;
    // use AuthenticableTrait;
    use HasFactory;

    protected $table = 'users';

    public $incrementing = false;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'uuid';

    /**
     * The data type of the ID.
     */
    protected $keyType = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    // Create a One to many relationship for Users to Notes
    public function todo()
    {
        return $this->hasMany(Note::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
