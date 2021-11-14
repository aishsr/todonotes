<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Support\Str;

class User extends Model implements Authenticatable
{
    // use Authenticatable, Authorizable, HasFactory;
    use AuthenticableTrait;
    use HasFactory;

    protected $table = 'users';

    public $incrementing = false;


    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'uuid';

    // /**
    //  * The data type of the ID.
    //  */
    protected $keyType = 'uuid';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    // Create a One to many relationship for Users to ToDoNotes
    public function todo()
    {
        return $this->hasMany(ToDoNote::class);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
