<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'order_number',
        'order_date',
        'reception_date',
        'planned_completion_date',
        'actual_completion_date',
        'total_cost',
        'prepayment_amount',
        'final_cost',
        'status',
        'problem_description',
        'work_description',
        'recommendations',
        'mileage'
    ];

    protected $primaryKey = 'order_id';

    protected $casts = [
        'order_date' => 'date',
        'reception_date' => 'date',
        'planned_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Связи
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }

    public function services()
    {
        return $this->hasMany(WorkOrderService::class, 'order_id', 'order_id');
    }

    public function parts()
    {
        return $this->hasMany(WorkOrderPart::class, 'order_id', 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id');
    }

    // Scope для поиска
    public function scopeSearch($query, $search)
    {
        return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('car', function($q) use ($search) {
                        $q->where('license_plate', 'like', "%{$search}%")
                          ->orWhere('vin', 'like', "%{$search}%");
                    })
                    ->orWhereHas('car.client', function($q) use ($search) {
                        $q->where('last_name', 'like', "%{$search}%")
                          ->orWhere('first_name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                    });
    }

    // Scope по статусу
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Получить клиента через автомобиль
    public function getClientAttribute()
    {
        return $this->car->client;
    }

    // Статусы для формы
    public static function getStatuses()
    {
        return [
            'draft' => 'Черновик',
            'accepted' => 'Принят',
            'in_progress' => 'В работе',
            'waiting_parts' => 'Ожидает запчасти',
            'ready' => 'Готов',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен'
        ];
    }

    // Генерация номера заказ-наряда
    public static function generateOrderNumber()
    {
        $year = date('Y');
        $lastOrder = self::where('order_number', 'like', "ORD-{$year}-%")
                        ->orderBy('order_id', 'desc')
                        ->first();

        $sequence = $lastOrder ? (int)str_replace("ORD-{$year}-", "", $lastOrder->order_number) + 1 : 1;

        return "ORD-{$year}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
