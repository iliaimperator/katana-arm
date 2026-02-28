<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказ-наряд {{ $workOrder->order_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: 'Arial', sans-serif;
                font-size: 12px;
                color: #000;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .signature-area {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .signature-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            margin: 0 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Шапка документа -->
        <div class="header">
            <div class="company-name">Автосервис "Катана"</div>
            <div class="document-title">ЗАКАЗ-НАРЯД № {{ $workOrder->order_number }}</div>
            <div>Дата создания: {{ $workOrder->created_at->format('d.m.Y') }}</div>
        </div>

        <!-- Информация о клиенте и автомобиле -->
        <div class="section">
            <div class="section-title">1. ИНФОРМАЦИЯ О КЛИЕНТЕ И АВТОМОБИЛЕ</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <span class="info-label">Клиент:</span> {{ $workOrder->car->client->full_name }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Телефон:</span> {{ $workOrder->car->client->phone }}
                    </div>
                    @if($workOrder->car->client->email)
                    <div class="info-item">
                        <span class="info-label">Email:</span> {{ $workOrder->car->client->email }}
                    </div>
                    @endif
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Автомобиль:</span> {{ $workOrder->car->brand }} {{ $workOrder->car->model }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Гос. номер:</span> {{ $workOrder->car->license_plate }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">VIN:</span> {{ $workOrder->car->vin ?? 'не указан' }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Год выпуска:</span> {{ $workOrder->car->year }}
                    </div>
                    @if($workOrder->mileage)
                    <div class="info-item">
                        <span class="info-label">Пробег:</span> {{ number_format($workOrder->mileage, 0, '', ' ') }} км
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Даты и статус -->
        <div class="section">
            <div class="section-title">2. ИНФОРМАЦИЯ О ЗАКАЗЕ</div>
            <div class="grid-3">
                <div class="info-item">
                    <span class="info-label">Дата приема:</span> {{ $workOrder->reception_date->format('d.m.Y') }}
                </div>
                <div class="info-item">
                    <span class="info-label">Плановая дата завершения:</span>
                    {{ $workOrder->planned_completion_date ? $workOrder->planned_completion_date->format('d.m.Y') : 'не указана' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Статус:</span> {{ \App\Models\WorkOrder::getStatuses()[$workOrder->status] }}
                </div>
            </div>
        </div>

        <!-- Описание проблемы и работ -->
        <div class="section">
            <div class="section-title">3. ОПИСАНИЕ РАБОТ</div>
            <div class="info-item">
                <span class="info-label">Описание проблемы:</span><br>
                {{ $workOrder->problem_description }}
            </div>

            @if($workOrder->work_description)
            <div class="info-item" style="margin-top: 10px;">
                <span class="info-label">Выполненные работы:</span><br>
                {{ $workOrder->work_description }}
            </div>
            @endif

            @if($workOrder->recommendations)
            <div class="info-item" style="margin-top: 10px;">
                <span class="info-label">Рекомендации:</span><br>
                {{ $workOrder->recommendations }}
            </div>
            @endif
        </div>

        <!-- Услуги -->
        <div class="section">
            <div class="section-title">4. ВЫПОЛНЕННЫЕ УСЛУГИ</div>
            @if($workOrder->services->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование услуги</th>
                            <th>Кол-во</th>
                            <th>Цена за ед.</th>
                            <th>Стоимость</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workOrder->services as $index => $service)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $service->service->service_name }}</td>
                            <td class="text-center">{{ $service->quantity }}</td>
                            <td class="text-right">{{ number_format($service->unit_price, 2) }} ₽</td>
                            <td class="text-right">{{ number_format($service->total_price, 2) }} ₽</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" class="text-right">Итого по услугам:</td>
                            <td class="text-right">{{ number_format($workOrder->services->sum('total_price'), 2) }} ₽</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 20px; color: #666;">
                    Услуги не добавлены
                </div>
            @endif
        </div>

        <!-- Запчасти -->
        <div class="section">
            <div class="section-title">5. ИСПОЛЬЗОВАННЫЕ ЗАПЧАСТИ</div>
            @if($workOrder->parts->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование запчасти</th>
                            <th>Артикул</th>
                            <th>Кол-во</th>
                            <th>Цена за ед.</th>
                            <th>Стоимость</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($workOrder->parts as $index => $part)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $part->part->part_name }}</td>
                            <td>{{ $part->part->article_number }}</td>
                            <td class="text-center">{{ $part->quantity }}</td>
                            <td class="text-right">{{ number_format($part->unit_price, 2) }} ₽</td>
                            <td class="text-right">{{ number_format($part->total_price, 2) }} ₽</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="5" class="text-right">Итого по запчастям:</td>
                            <td class="text-right">{{ number_format($workOrder->parts->sum('total_price'), 2) }} ₽</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 20px; color: #666;">
                    Запчасти не использовались
                </div>
            @endif
        </div>

        <!-- Финансовая информация -->
        <div class="section">
            <div class="section-title">6. ФИНАНСОВАЯ ИНФОРМАЦИЯ</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <span class="info-label">Общая стоимость услуг:</span>
                        {{ number_format($workOrder->services->sum('total_price'), 2) }} ₽
                    </div>
                    <div class="info-item">
                        <span class="info-label">Общая стоимость запчастей:</span>
                        {{ number_format($workOrder->parts->sum('total_price'), 2) }} ₽
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <span class="info-label">Предоплата (50%):</span>
                        {{ number_format($workOrder->prepayment_amount, 2) }} ₽
                    </div>
                    <div class="info-item" style="font-weight: bold; font-size: 14px;">
                        <span class="info-label">ИТОГО К ОПЛАТЕ:</span>
                        {{ number_format($workOrder->final_cost, 2) }} ₽
                    </div>
                </div>
            </div>
        </div>

        <!-- Платежи -->
        @if($workOrder->payments->count() > 0)
        <div class="section">
            <div class="section-title">7. ИНФОРМАЦИЯ О ПЛАТЕЖАХ</div>
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Тип платежа</th>
                        <th>Способ оплаты</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d.m.Y') }}</td>
                        <td>{{ \App\Models\Payment::getTypes()[$payment->type] }}</td>
                        <td>{{ \App\Models\Payment::getMethods()[$payment->method] }}</td>
                        <td class="text-right">{{ number_format($payment->amount, 2) }} ₽</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Всего оплачено:</td>
                        <td class="text-right">{{ number_format($workOrder->payments->sum('amount'), 2) }} ₽</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <!-- Подписи -->
        <div class="section">
            <div class="signature-area">
                <div style="display: flex; justify-content: space-between;">
                    <div style="text-align: center;">
                        <div>Исполнитель</div>
                        <div class="signature-line"></div>
                        <div style="font-size: 10px; margin-top: 5px;">(подпись, ФИО)</div>
                    </div>
                    <div style="text-align: center;">
                        <div>Клиент</div>
                        <div class="signature-line"></div>
                        <div style="font-size: 10px; margin-top: 5px;">(подпись, ФИО)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Футер -->
        <div class="footer">
            Документ сгенерирован автоматически. Автосервис "Катана".
            Дата печати: {{ now()->format('d.m.Y H:i') }}
        </div>
    </div>

    <!-- Кнопка печати (видна только на экране) -->
    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()"
                style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px;">
            🖨️ Печать
        </button>
        <button onclick="window.close()"
                style="background: #6b7280; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            ✕ Закрыть
        </button>
    </div>
</body>
</html>
