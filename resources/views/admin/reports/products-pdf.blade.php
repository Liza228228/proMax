<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Отчет по статистике продукции</title>
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
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ОТЧЕТ ПО СТАТИСТИКЕ ПРОДУКЦИИ</h1>
    </div>

    <div class="period">
        Период: {{ $dateFrom->setTimezone('Asia/Irkutsk')->format('d.m.Y') }} - {{ $dateTo->setTimezone('Asia/Irkutsk')->format('d.m.Y') }}
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Всего заказано</div>
            <div class="stat-value">{{ number_format($stats['total_ordered'], 0, '.', ' ') }} шт.</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Уникальных товаров</div>
            <div class="stat-value">{{ $stats['total_products'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Общая выручка</div>
            <div class="stat-value">{{ number_format($stats['total_revenue'], 0, '.', ' ') }} ₽</div>
        </div>
    </div>

    <div class="section-title">ВСЯ ПРОДУКЦИЯ</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">№</th>
                <th style="width: 40%;">Название товара</th>
                <th style="width: 20%;">Категория</th>
                <th style="width: 15%; text-align: center;">Заказано (шт)</th>
                <th style="width: 10%; text-align: center;">Заказов</th>
                <th style="width: 10%; text-align: right;">Выручка</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productStats as $index => $product)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $product->name_product }}</td>
                <td>{{ $product->name_category ?? 'Без категории' }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $product->total_ordered }}</td>
                <td style="text-align: center;">{{ $product->orders_count }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($product->total_revenue, 2, '.', ' ') }} ₽</td>
            </tr>
            @endforeach
            @if($productStats->count() === 0)
            <tr>
                <td colspan="6" style="text-align: center; color: #999;">Нет данных за выбранный период</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">ТОП ПРОДУКЦИИ</div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;">№</th>
                <th style="width: 50%;">Название товара</th>
                <th style="width: 20%; text-align: center;">Заказано (шт)</th>
                <th style="width: 20%; text-align: right;">Выручка</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $index => $product)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $product->name_product }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $product->total_ordered }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($product->total_revenue, 2, '.', ' ') }} ₽</td>
            </tr>
            @endforeach
            @if($topProducts->count() === 0)
            <tr>
                <td colspan="4" style="text-align: center; color: #999;">Нет данных за выбранный период</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Отчет сформирован {{ now()->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}
    </div>
</body>
</html>

