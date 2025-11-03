<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'capital_raised',
        'goal',
        'status',
        'has_complaint',
        'score',
        'entrepreneur_id',
        'description',
    ];
}
