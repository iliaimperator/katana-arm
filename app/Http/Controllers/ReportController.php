<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Client;
use App\Models\Car;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Общая статистика
     */
    public function general()
    {
        // Базовая статистика
        $stats = [
            'total_clients' => Client::count(),
            'total_cars' => Car::count(),
            'total_services' => Service::count(),
            'total_parts' => SparePart::count(),
            'total_orders' => WorkOrder::count(),
            'active_orders' => WorkOrder::whereIn('status', ['accepted', 'in_progress', 'waiting_parts'])->count(),
            'completed_orders' => WorkOrder::where('status', 'completed')->count(),
            'total_revenue' => WorkOrder::where('status', 'completed')->sum('final_cost'),
            'avg_order_value' => WorkOrder::where('status', 'completed')->avg('final_cost') ?? 0,
        ];

        // Статистика по месяцам за текущий год
        $monthlyStats = $this->getMonthlyStats();

        return view('reports.general', compact('stats', 'monthlyStats'));
    }

    /**
     * Финансовые отчеты
     */
    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Делаем конечную дату включительно
        $endDateInclusive = Carbon::parse($endDate)->endOfDay();

        $revenue = WorkOrder::where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDateInclusive])
            ->sum('final_cost');

        $payments = Payment::whereBetween('payment_date', [$startDate, $endDateInclusive])
            ->get();

        $paymentStats = [
            'total' => $payments->sum('amount'),
            'prepayment' => $payments->where('type', 'prepayment')->sum('amount'),
            'final' => $payments->where('type', 'final')->sum('amount'),
            'cash' => $payments->where('method', 'cash')->sum('amount'),
            'card' => $payments->where('method', 'card')->sum('amount'),
            'transfer' => $payments->where('method', 'transfer')->sum('amount'),
        ];

        $topServices = WorkOrder::where('status', 'completed')
            ->whereBetween('updated_at', [$startDate, $endDateInclusive])
            ->withCount('services')
            ->get()
            ->sum('services_count');

        return view('reports.financial', compact(
            'revenue',
            'paymentStats',
            'topServices',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Отчет по заказ-нарядам
     */
    public function orders(Request $request)
    {
        $status = $request->get('status', 'all');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Делаем конечную дату включительно
        $endDateInclusive = Carbon::parse($endDate)->endOfDay();

        $query = WorkOrder::with(['car.client', 'services', 'parts'])
            ->whereBetween('created_at', [$startDate, $endDateInclusive]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Обновляем статистику с учетом включительной конечной даты
        $statusStats = [
            'all' => WorkOrder::whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'draft' => WorkOrder::where('status', 'draft')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'accepted' => WorkOrder::where('status', 'accepted')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'in_progress' => WorkOrder::where('status', 'in_progress')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'waiting_parts' => WorkOrder::where('status', 'waiting_parts')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'ready' => WorkOrder::where('status', 'ready')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'completed' => WorkOrder::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
            'cancelled' => WorkOrder::where('status', 'cancelled')->whereBetween('created_at', [$startDate, $endDateInclusive])->count(),
        ];

        return view('reports.orders', compact('orders', 'statusStats', 'status', 'startDate', 'endDate'));
    }

    /**
     * Статистика по услугам
     */
    public function services(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Делаем конечную дату включительно
        $endDateInclusive = Carbon::parse($endDate)->endOfDay();

        // Популярные услуги
        $popularServices = \DB::table('work_order_services')
            ->join('services', 'work_order_services.service_id', '=', 'services.service_id')
            ->join('work_orders', 'work_order_services.order_id', '=', 'work_orders.order_id')
            ->where('work_orders.status', 'completed')
            ->whereBetween('work_orders.updated_at', [$startDate, $endDateInclusive])
            ->select(
                'services.service_name',
                'services.service_type',
                \DB::raw('COUNT(*) as usage_count'),
                \DB::raw('SUM(work_order_services.quantity) as total_quantity'),
                \DB::raw('SUM(work_order_services.total_price) as total_revenue')
            )
            ->groupBy('services.service_id', 'services.service_name', 'services.service_type')
            ->orderBy('usage_count', 'desc')
            ->get();

        // Статистика по типам услуг
        $serviceTypes = \DB::table('work_order_services')
            ->join('services', 'work_order_services.service_id', '=', 'services.service_id')
            ->join('work_orders', 'work_order_services.order_id', '=', 'work_orders.order_id')
            ->where('work_orders.status', 'completed')
            ->whereBetween('work_orders.updated_at', [$startDate, $endDateInclusive])
            ->select(
                'services.service_type',
                \DB::raw('COUNT(*) as usage_count'),
                \DB::raw('SUM(work_order_services.total_price) as total_revenue')
            )
            ->groupBy('services.service_type')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('reports.services', compact('popularServices', 'serviceTypes', 'startDate', 'endDate'));
    }

    /**
     * Статистика по месяцам (обновляем для consistency)
     */
    private function getMonthlyStats()
    {
        $currentYear = Carbon::now()->year;
        $stats = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::create($currentYear, $month, 1)->endOfMonth(); // Уже включительно

            $stats[$month] = [
                'orders' => WorkOrder::whereBetween('created_at', [$startDate, $endDate])->count(),
                'revenue' => WorkOrder::where('status', 'completed')
                    ->whereBetween('updated_at', [$startDate, $endDate])
                    ->sum('final_cost'),
                'month_name' => $startDate->translatedFormat('F'),
            ];
        }

        return $stats;
    }
}
