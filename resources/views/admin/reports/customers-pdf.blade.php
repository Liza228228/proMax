<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Отчет по клиентам</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #8b5cf6;
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
            width: 25%;
            padding: 10px;
            background: #f3e8ff;
            border: 1px solid #8b5cf6;
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
            color: #7c3aed;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #8b5cf6;
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
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .phone {
            font-size: 8px;
            color: #999;
        }
        .top-customer {
            background: #fef3c7 !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ОТЧЕТ ПО КЛИЕНТАМ</h1>
    </div>

    <div class="period">
        Период: {{ $dateFrom->format('d.m.Y') }} - {{ $dateTo->format('d.m.Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Всего клиентов</div>
            <div class="stat-value">{{ $stats['total_customers'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Всего заказов</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Общая выручка</div>
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '.', ' ') }} ₽</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Средний LTV</div>
            <div class="stat-value">{{ number_format($stats['average_customer_value'], 0, '.', ' ') }} ₽</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">ФИО клиента</th>
                <th style="width: 15%;">Телефон</th>
                <th style="width: 15%; text-align: center;">Заказов</th>
                <th style="width: 15%; text-align: right;">Потрачено</th>
                <th style="width: 15%; text-align: right;">Средний чек</th>
                <th style="width: 15%;">Последний заказ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customerStats as $index => $customer)
            <tr class="{{ $index < 3 ? 'top-customer' : '' }}">
                <td>{{ $customer->last_name }} {{ $customer->first_name }}</td>
                <td class="phone">{{ $customer->phone }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $customer->orders_count }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($customer->total_spent, 2, '.', ' ') }} ₽</td>
                <td style="text-align: right;">{{ number_format($customer->avg_order, 2, '.', ' ') }} ₽</td>
                <td>{{ \Carbon\Carbon::parse($customer->last_order_date)->format('d.m.Y') }}</td>
            </tr>
            @endforeach
            @if($customerStats->count() === 0)
            <tr>
                <td colspan="6" style="text-align: center; color: #999;">Нет данных за выбранный период</td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="background: #f3e8ff; font-weight: bold;">
                <td colspan="2">ИТОГО:</td>
                <td style="text-align: center;">{{ $stats['total_orders'] }}</td>
                <td style="text-align: right;">{{ number_format($stats['total_revenue'], 2, '.', ' ') }} ₽</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Отчет сформирован {{ now()->format('d.m.Y в H:i') }}
    </div>
</body>
</html>

