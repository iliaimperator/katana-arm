<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'type',
        'method',
        'payment_date',
        'notes'
    ];

    protected $primaryKey = 'payment_id';

    // Добавляем преобразование дат
    protected $casts = [
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(WorkOrder::class, 'order_id', 'order_id');
    }

    public static function getTypes()
    {
        return [
            'prepayment' => 'Предоплата',
            'final' => 'Окончательный расчет'
        ];
    }

    public static function getMethods()
    {
        return [
            'cash' => 'Наличные',
            'card' => 'Карта',
            'transfer' => 'Перевод'
        ];
    }
}
