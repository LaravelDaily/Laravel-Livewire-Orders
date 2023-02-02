<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'country_id', 'price'];

    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders(): belongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
}
