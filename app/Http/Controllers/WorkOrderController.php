<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Car;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\WorkOrderService;
use App\Models\WorkOrderPart;
use App\Models\Payment;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $query = WorkOrder::with(['car.client', 'services', 'parts'])
                    ->orderBy('order_date', 'desc');

        // Фильтрация по статусу
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $orders = $query->get();

        return view('work-orders.index', compact('orders'));
    }

    public function create()
    {
        $cars = Car::with('client')->orderBy('brand')->orderBy('model')->get();
        $services = Service::orderBy('service_type')->orderBy('service_name')->get();
        $parts = SparePart::orderBy('part_name')->get();
        $statuses = WorkOrder::getStatuses();

        return view('work-orders.create', compact('cars', 'services', 'parts', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,car_id',
            'order_date' => 'required|date',
            'reception_date' => 'required|date',
            'problem_description' => 'required|string',
            'status' => 'required|in:draft,accepted,in_progress,waiting_parts,ready,completed,cancelled'
        ]);

        // Создаем заказ-наряд
        $workOrder = WorkOrder::create([
            'car_id' => $request->car_id,
            'order_number' => WorkOrder::generateOrderNumber(),
            'order_date' => $request->order_date,
            'reception_date' => $request->reception_date,
            'planned_completion_date' => $request->planned_completion_date,
            'problem_description' => $request->problem_description,
            'work_description' => $request->work_description,
            'recommendations' => $request->recommendations,
            'mileage' => $request->mileage,
            'status' => $request->status,
        ]);

        return redirect()->route('work-orders.show', $workOrder->order_id)
            ->with('success', 'Заказ-наряд успешно создан.');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['car.client', 'services.service', 'parts.part', 'payments']);

        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $workOrder->load(['car.client', 'services.service', 'parts.part']);
        $cars = Car::with('client')->orderBy('brand')->orderBy('model')->get();
        $services = Service::orderBy('service_type')->orderBy('service_name')->get();
        $parts = SparePart::orderBy('part_name')->get();
        $statuses = WorkOrder::getStatuses();

        return view('work-orders.edit', compact('workOrder', 'cars', 'services', 'parts', 'statuses'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,car_id',
            'order_date' => 'required|date',
            'reception_date' => 'required|date',
            'problem_description' => 'required|string',
            'status' => 'required|in:draft,accepted,in_progress,waiting_parts,ready,completed,cancelled'
        ]);

        $workOrder->update($request->all());

        return redirect()->route('work-orders.show', $workOrder->order_id)
            ->with('success', 'Заказ-наряд успешно обновлен.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work-orders.index')
            ->with('success', 'Заказ-наряд успешно удален.');
    }

    // Методы для управления запчастями в заказе
    public function addPart(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'part_id' => 'required|exists:spare_parts,part_id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'status' => 'required|in:ordered,in_stock,used',
            'notes' => 'nullable|string|max:500'
        ]);

        $part = SparePart::find($request->part_id);

        // Проверяем наличие на складе, если статус "in_stock" или "used"
        if (in_array($request->status, ['in_stock', 'used']) && $part->stock_quantity < $request->quantity) {
            return back()->with('error', 'Недостаточно запчастей на складе. Доступно: ' . $part->stock_quantity . ' шт.');
        }

        // Создаем запись о запчасти в заказе
        WorkOrderPart::create([
            'order_id' => $workOrder->order_id,
            'part_id' => $request->part_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $request->quantity * $request->unit_price,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Обновляем остатки на складе, если запчасть используется
        if (in_array($request->status, ['in_stock', 'used'])) {
            $part->decrement('stock_quantity', $request->quantity);
        }

        $this->updateOrderTotal($workOrder);

        return back()->with('success', 'Запчасть успешно добавлена в заказ.');
    }

    public function removePart(WorkOrder $workOrder, WorkOrderPart $part)
    {
        if ($part->order_id !== $workOrder->order_id) {
            return back()->with('error', 'Ошибка доступа.');
        }

        // Возвращаем запчасть на склад, если она была списана
        if (in_array($part->status, ['in_stock', 'used'])) {
            $part->part->increment('stock_quantity', $part->quantity);
        }

        $part->delete();
        $this->updateOrderTotal($workOrder);

        return back()->with('success', 'Запчасть удалена из заказа.');
    }

    public function updatePartStatus(Request $request, WorkOrder $workOrder, WorkOrderPart $part)
    {
        if ($part->order_id !== $workOrder->order_id) {
            return back()->with('error', 'Ошибка доступа.');
        }

        $request->validate([
            'status' => 'required|in:ordered,in_stock,used'
        ]);

        $oldStatus = $part->status;
        $newStatus = $request->status;

        // Логика управления складскими остатками
        if ($oldStatus != $newStatus) {
            // Если меняем со статуса "ordered" на "in_stock" или "used" - списываем со склада
            if ($oldStatus == 'ordered' && in_array($newStatus, ['in_stock', 'used'])) {
                if ($part->part->stock_quantity < $part->quantity) {
                    return back()->with('error', 'Недостаточно запчастей на складе. Доступно: ' . $part->part->stock_quantity . ' шт.');
                }
                $part->part->decrement('stock_quantity', $part->quantity);
            }
            // Если меняем с "in_stock" или "used" на "ordered" - возвращаем на склад
            elseif (in_array($oldStatus, ['in_stock', 'used']) && $newStatus == 'ordered') {
                $part->part->increment('stock_quantity', $part->quantity);
            }
        }

        $part->update(['status' => $newStatus]);

        return back()->with('success', 'Статус запчасти обновлен.');
    }


    /**
     * Добавление услуги в заказ-наряд
     */
    public function addService(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500'
        ]);

        $service = Service::find($request->service_id);

        // Проверяем, не добавлена ли уже эта услуга
        $existingService = WorkOrderService::where('order_id', $workOrder->order_id)
            ->where('service_id', $request->service_id)
            ->first();

        if ($existingService) {
            return back()->with('error', 'Эта услуга уже добавлена в заказ.');
        }

        WorkOrderService::create([
            'order_id' => $workOrder->order_id,
            'service_id' => $request->service_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $request->quantity * $request->unit_price,
            'notes' => $request->notes
        ]);

        $this->updateOrderTotal($workOrder);

        return back()->with('success', 'Услуга успешно добавлена в заказ.');
    }

    /**
     * Удаление услуги из заказ-наряда
     */
    public function removeService(WorkOrder $workOrder, WorkOrderService $service)
    {
        if ($service->order_id !== $workOrder->order_id) {
            return back()->with('error', 'Ошибка доступа.');
        }

        $service->delete();
        $this->updateOrderTotal($workOrder);

        return back()->with('success', 'Услуга удалена из заказа.');
    }

    /**
     * Изменение статуса заказ-наряда
     */
    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'status' => 'required|in:draft,accepted,in_progress,waiting_parts,ready,completed,cancelled'
        ]);

        $oldStatus = $workOrder->status;
        $workOrder->update([
            'status' => $request->status
        ]);

        // Если статус меняется на "completed", устанавливаем дату завершения
        if ($request->status == 'completed' && !$workOrder->actual_completion_date) {
            $workOrder->update([
                'actual_completion_date' => now()
            ]);
        }

        return back()->with('success', "Статус заказа изменен с '".WorkOrder::getStatuses()[$oldStatus]."' на '".WorkOrder::getStatuses()[$request->status]."'");
    }

    /**
     * Обновление общей суммы заказа
     */
    private function updateOrderTotal(WorkOrder $workOrder)
    {
        $servicesTotal = $workOrder->services()->sum('total_price');
        $partsTotal = $workOrder->parts()->sum('total_price');

        $totalCost = $servicesTotal + $partsTotal;
        $prepaymentAmount = $totalCost * 0.5; // 50% предоплата

        $workOrder->update([
            'total_cost' => $totalCost,
            'prepayment_amount' => $prepaymentAmount,
            'final_cost' => $totalCost
        ]);
    }

    public function addPayment(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:prepayment,final',
            'method' => 'required|in:cash,card,transfer',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500'
        ]);

        // Проверяем логику платежей
        if ($request->type == 'prepayment') {
            $existingPrepayment = $workOrder->payments()->where('type', 'prepayment')->exists();
            if ($existingPrepayment) {
                return back()->with('error', 'Предоплата уже внесена для этого заказа.');
            }

            if ($request->amount > $workOrder->prepayment_amount) {
                return back()->with('error', 'Сумма предоплаты не может превышать ' . number_format($workOrder->prepayment_amount, 2) . ' ₽');
            }
        }

        if ($request->type == 'final') {
            $prepayment = $workOrder->payments()->where('type', 'prepayment')->sum('amount');
            $remainingAmount = $workOrder->final_cost - $prepayment;

            if ($request->amount > $remainingAmount) {
                return back()->with('error', 'Сумма окончательного расчета не может превышать ' . number_format($remainingAmount, 2) . ' ₽');
            }

            // Если внесен окончательный расчет, меняем статус заказа на "Завершен"
            if (($prepayment + $request->amount) >= $workOrder->final_cost) {
                $workOrder->update([
                    'status' => 'completed',
                    'actual_completion_date' => now()
                ]);
            }
        }

        Payment::create([
            'order_id' => $workOrder->order_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'method' => $request->method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Платеж успешно добавлен.');
    }

    /**
     * Удаление платежа
     */
    public function removePayment(WorkOrder $workOrder, Payment $payment)
    {
        if ($payment->order_id !== $workOrder->order_id) {
            return back()->with('error', 'Ошибка доступа.');
        }

        // Если удаляем окончательный расчет, меняем статус обратно
        if ($payment->type == 'final') {
            $workOrder->update(['status' => 'ready']);
        }

        $payment->delete();

        return back()->with('success', 'Платеж удален.');
    }

    /**
     * Получение информации о платежах для формы
     */
    public function getPaymentInfo(WorkOrder $workOrder)
    {
        $prepayment = $workOrder->payments()->where('type', 'prepayment')->sum('amount');
        $finalPayment = $workOrder->payments()->where('type', 'final')->sum('amount');

        $remainingPrepayment = max(0, $workOrder->prepayment_amount - $prepayment);
        $remainingFinal = max(0, $workOrder->final_cost - $prepayment - $finalPayment);

        return response()->json([
            'prepayment' => [
                'paid' => $prepayment,
                'required' => $workOrder->prepayment_amount,
                'remaining' => $remainingPrepayment
            ],
            'final' => [
                'paid' => $finalPayment,
                'required' => $workOrder->final_cost,
                'remaining' => $remainingFinal
            ],
            'total_paid' => $prepayment + $finalPayment,
            'total_required' => $workOrder->final_cost
        ]);
    }

     /**
     * Печать заказ-наряда
     */
    public function print(WorkOrder $workOrder)
    {
        $workOrder->load(['car.client', 'services.service', 'parts.part', 'payments']);

        return view('work-orders.print', compact('workOrder'));
    }

    public function act(WorkOrder $workOrder)
    {
        $workOrder->load(['car.client', 'services.service', 'parts.part', 'payments']);

        return view('work-orders.act', compact('workOrder'));
    }
}
