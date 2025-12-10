<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Финансовый отчет</title>
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
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ФИНАНСОВЫЙ ОТЧЕТ</h1>
    </div>

    <div class="period">
        Период: {{ $dateFrom->setTimezone('Asia/Irkutsk')->format('d.m.Y') }} - {{ $dateTo->setTimezone('Asia/Irkutsk')->format('d.m.Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Общая выручка</div>
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '.', ' ') }} ₽</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Всего заказов</div>
            <div class="stat-value">{{ $stats['total_orders'] }}</div>
            <div class="stat-label" style="font-size: 7px; margin-top: 3px;">(Принят, Готов к выдаче, Выполнен)</div>
        </div>
    </div>

    <!-- Выручка по дням -->
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Дата</th>
                <th style="width: 35%; text-align: center;">Количество заказов</th>
                <th style="width: 35%; text-align: right;">Выручка</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByDay as $day)
            <tr>
                <td>{{ \Carbon\Carbon::parse($day->date)->setTimezone('Asia/Irkutsk')->format('d.m.Y') }}</td>
                <td style="text-align: center;">{{ $day->orders_count }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($day->revenue, 2, '.', ' ') }} ₽</td>
            </tr>
            @endforeach
            @if($revenueByDay->count() === 0)
            <tr>
                <td colspan="3" style="text-align: center; color: #999;">Нет данных за выбранный период</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Отчет сформирован {{ now()->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
    </div>
</body>
</html>

