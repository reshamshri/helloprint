<?php

namespace Helloprint\Models;

use DateTime;
use Helloprint\Database\DbConnection;
use Helloprint\Exceptions\ModelException;

/**
 * Class Request
 * @package Helloprint\Models
 *
 * @property int        $id
 * @property string     $token
 * @property string     $uuid
 * @property string     $message
 * @property DateTime   $created_on
 * @property DateTime   $updated_on
 */
class Request extends Model
{
    /**
     * @var string
     */
    public string $table = 'requests';

    /**
     * @var array|string[]
     */
    protected array $fillable = ['message', 'token'];
}
