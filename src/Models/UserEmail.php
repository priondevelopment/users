<?php

namespace PrionUsers\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('prionusers.tables.user_emails');
    }

}