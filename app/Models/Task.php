<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'completed',
    ];

    /**
     * Attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
        'completed' => false,
    ];
}
