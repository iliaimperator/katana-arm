<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'phone',
        'email',
        'address',
        'notes'
    ];

    protected $primaryKey = 'client_id';

    // Связь с автомобилями
    public function cars()
    {
        return $this->hasMany(Car::class, 'client_id', 'client_id');
    }

    // Полное имя клиента
    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name . ($this->middle_name ? ' ' . $this->middle_name : '');
    }

    // Scope для поиска
    public function scopeSearch($query, $search)
    {
        return $query->where('last_name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
    }
}
