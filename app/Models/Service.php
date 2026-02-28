<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'service_name',
        'standard_cost',
        'description'
    ];

    protected $primaryKey = 'service_id';

    public function scopeActive($query)
    {
        return $query; // Пока у нас нет поля is_active, просто возвращаем все
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('service_type', 'like', "%{$search}%")
                    ->orWhere('service_name', 'like', "%{$search}%");
    }
}
