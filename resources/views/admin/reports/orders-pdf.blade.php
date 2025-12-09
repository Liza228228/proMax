<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Отчет по заказам</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .period {
            text-align: center;
            color: #666;
            margin-bottom: 15px;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            text-align: center;
        }
        .stat-label {
            font-size: 8px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #4a5568;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-new { background: #bee3f8; color: #2c5282; }
        .status-processing { background: #fefcbf; color: #744210; }
        .status-ready { background: #c6f6d5; color: #22543d; }
        .status-completed { background: #9f7aea; color: #ffffff; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .order-items {
            font-size: 8px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ОТЧЕТ ПО ЗАКАЗАМ</h1>
    </div>

    <div class="period">
        Период: {{ $dateFrom->setTimezone('Asia/Irkutsk')->format('d.m.Y') }} - {{ $dateTo->setTimezone('Asia/Irkutsk')->format('d.m.Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Всего заказов</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Общая выручка</div>
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '.', ' ') }} ₽</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Выполнено</div>
            <div class="stat-value">{{ $stats['completed_orders'] }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">№ заказа</th>
                <th style="width: 15%;">Дата</th>
                <th style="width: 20%;">Клиент</th>
                <th style="width: 32%;">Товары</th>
                <th style="width: 10%;">Статус</th>
                <th style="width: 15%; text-align: right;">Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</td>
                <td>
                    {{ $order->user->last_name }} {{ $order->user->first_name }}
                    <div style="font-size: 8px; color: #999;">{{ $order->user->formatted_phone ?? $order->user->phone }}</div>
                </td>
                <td>
                    @foreach($order->items as $item)
                        <div class="order-items">
                            • {{ $item->product->name_product ?? 'Товар удален' }} ({{ $item->quantity }} шт.)
                        </div>
                    @endforeach
                </td>
                <td>
                    <span class="status status-{{ 
                        $order->status === 'Создан' ? 'new' : 
                        ($order->status === 'Принят' ? 'processing' : 
                        ($order->status === 'Готов к выдаче' ? 'ready' : 
                        ($order->status === 'Выполнен' ? 'completed' : 'new')))
                    }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td style="text-align: right; font-weight: bold;">
                    {{ number_format($order->total_amount, 2, '.', ' ') }} ₽
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Отчет сформирован {{ now()->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
    </div>
</body>
</html>

