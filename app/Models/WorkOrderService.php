<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderService extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'quantity',
        'unit_price',
        'total_price',
        'notes'
    ];

    protected $primaryKey = 'order_service_id';

    public function order()
    {
        return $this->belongsTo(WorkOrder::class, 'order_id', 'order_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }
}
