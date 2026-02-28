<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'part_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'notes'
    ];

    protected $primaryKey = 'order_part_id';

    public function order()
    {
        return $this->belongsTo(WorkOrder::class, 'order_id', 'order_id');
    }

    public function part()
    {
        return $this->belongsTo(SparePart::class, 'part_id', 'part_id');
    }

    public static function getStatuses()
    {
        return [
            'ordered' => 'Заказана',
            'in_stock' => 'На складе',
            'used' => 'Установлена'
        ];
    }
}
