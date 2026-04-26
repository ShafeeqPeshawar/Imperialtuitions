<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Subscriber.php
class Subscriber extends Model
{
   protected $fillable = [
    'email',
    'name', // ← add this
];
}