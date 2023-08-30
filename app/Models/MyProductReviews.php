<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyProductReviews extends Model

{
    protected $fillable = [
        'user_name',
        'rating',
        'location',
        'review',
    ];
    }