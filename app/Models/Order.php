<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_date', 'subtotal', 'taxes', 'total'];

    protected $casts = [
        'order_date' => 'date:m/d/Y'
    ];

    public function products(): belongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('price', 'quantity');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
