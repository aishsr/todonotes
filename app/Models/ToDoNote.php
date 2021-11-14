<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class ToDoNote extends Model
{
    use HasFactory;

    protected $table = 'to_do_notes';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'uuid';

    /**
     * The data type of the ID.
     */
    protected $keyType = 'uuid';

    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid', 'content', 'completion_time'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Create a One to One relationship for ToDoNote to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
