<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active', 'position'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
