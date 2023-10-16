<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'second_name',
        'third_name',
        'birthday',
        'mail',
        'phones',
        'family',
        'about_me',
        'files',
    ];
}
