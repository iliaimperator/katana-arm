<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_number',
        'part_name',
        'description',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'min_stock',
        'supplier',
        'category',
        'is_active'
    ];

    protected $primaryKey = 'part_id';

    // Scope для активных запчастей
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope для поиска по артикулу или названию
    public function scopeSearch($query, $search)
    {
        return $query->where('article_number', 'like', "%{$search}%")
                    ->orWhere('part_name', 'like', "%{$search}%");
    }
}
