<?php declare(strict_types = 1);

namespace App\Models;

use App\Models\BaseModel;
use GuzzleHttp\Tests\Stream\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends BaseModel
{
    use HasFactory;

    /**
     * Table name in Postgres
     */
    protected $table = 'notes';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'uuid';

    /**
     * The data type of the ID.
     */
    protected $keyType = 'uuid';

    /**
     * Whether this table is incrementing
     */
    public $incrementing = false;

    /**
     * To auto-fill created-at and updated_at timestamps
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid', 'content', 'completion_time', 'created_at', 'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Create a Many to One relationship for Note to User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
