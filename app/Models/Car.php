<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'vin',
        'license_plate',
        'brand',
        'model',
        'year',
        'engine_model',
        'engine_volume',
        'transmission',
        'color',
        'notes'
    ];

    protected $primaryKey = 'car_id';

    // Связь с клиентом
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    // Полное название автомобиля
    public function getFullNameAttribute()
    {
        return $this->brand . ' ' . $this->model . ' (' . $this->year . ')';
    }

    // Scope для поиска
    public function scopeSearch($query, $search)
    {
        return $query->where('license_plate', 'like', "%{$search}%")
                    ->orWhere('vin', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
    }
}
