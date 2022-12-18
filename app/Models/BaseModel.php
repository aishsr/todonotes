<?php declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    // @phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint

    /**
     * Model data format.
     * The custom data format is required when the model utilises
     * microsecond timestamp format within the migrations.
     * There is an implementational difference between PostgreSQL
     * and MySQL as PostgreSQL needs 'Y-m-d H:i:s.uO' format to properly
     * utilise timezones, while MySQL equivalent is 'Y-m-d H:i:s.u'.
     */
    protected $dateFormat = 'Y-m-d H:i:s.uO';

    // @phpcs:enable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
}
