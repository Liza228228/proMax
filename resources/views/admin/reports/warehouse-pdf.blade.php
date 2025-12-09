<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Отчет по складу</title>
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
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 20%;
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
        .warehouse-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .warehouse-title {
            background: #e2e8f0;
            padding: 8px;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Отчет по складу</h1>
        @php
            $reportTypeNames = [
                'all' => 'Вся информация',
                'stock' => 'Остаток склада',
                'operations' => 'Операции'
            ];
        @endphp
        <p style="margin-top: 5px; font-size: 10px; font-weight: bold;">
            Тип отчета: {{ $reportTypeNames[$reportType] ?? 'Вся информация' }}
        </p>
        @if(isset($selectedWarehouse) && $selectedWarehouse)
            <p style="margin-top: 5px; font-size: 11px; font-weight: bold;">
                Склад: {{ $selectedWarehouse->name }} ({{ $selectedWarehouse->city }}, {{ $selectedWarehouse->street }}, д. {{ $selectedWarehouse->house }})
            </p>
        @else
            <p style="margin-top: 5px; font-size: 11px; font-weight: bold;">
                Все склады
            </p>
        @endif
        <p>Дата формирования: {{ now()->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</p>
        @if(isset($dateFrom) && isset($dateTo))
            <p style="margin-top: 5px; font-size: 9px;">
                Период операций: {{ $dateFrom->format('d.m.Y') }} - {{ $dateTo->format('d.m.Y') }}
            </p>
        @elseif($reportType == 'operations')
            <p style="margin-top: 5px; font-size: 9px;">
                Период операций: не указан (все операции)
            </p>
        @endif
    </div>

    @if(in_array($reportType, ['all', 'stock']))
        <div class="stats">
            <div class="stat-box" style="width: 50%;">
                <div class="stat-label">Всего складов</div>
                <div class="stat-value">{{ $stats['total_warehouses'] }}</div>
            </div>
            <div class="stat-box" style="width: 50%;">
                <div class="stat-label">Всего ингредиентов</div>
                <div class="stat-value">{{ $stats['total_unique_ingredients'] }}</div>
            </div>
        </div>
    @endif

    @if($reportType == 'operations')
        {{-- Отчет только по операциям --}}
        @php
            $allMovements = collect();
            foreach ($warehouseMovements as $movements) {
                $allMovements = $allMovements->merge($movements);
            }
            $allMovements = $allMovements->unique('id')->sortByDesc('created_at');
        @endphp
        
        @if($allMovements->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Склад</th>
                        <th>Тип операции</th>
                        <th>Ингредиент</th>
                        <th>Количество</th>
                        <th>Откуда/Куда</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allMovements as $movement)
                        @php
                            $operationType = '';
                            $warehouseInfo = '';
                            $warehouseName = '';
                            
                            if ($movement->from_warehouse_id && $movement->to_warehouse_id) {
                                if ($selectedWarehouse && $movement->from_warehouse_id == $selectedWarehouse->id) {
                                    $operationType = 'Перемещение';
                                    $warehouseName = $movement->fromWarehouse->name ?? 'Неизвестно';
                                    $warehouseInfo = '→ ' . ($movement->toWarehouse->name ?? 'Неизвестно');
                                } else {
                                    $operationType = 'Поступление';
                                    $warehouseName = $movement->toWarehouse->name ?? 'Неизвестно';
                                    $warehouseInfo = '← ' . ($movement->fromWarehouse->name ?? 'Неизвестно');
                                }
                            } elseif ($movement->to_warehouse_id && !$movement->from_warehouse_id) {
                                $operationType = 'Начисление';
                                $warehouseName = $movement->toWarehouse->name ?? 'Неизвестно';
                                $warehouseInfo = 'Внешнее поступление';
                            } elseif ($movement->from_warehouse_id && !$movement->to_warehouse_id) {
                                $operationType = 'Списание';
                                $warehouseName = $movement->fromWarehouse->name ?? 'Неизвестно';
                                if ($movement->product) {
                                    $warehouseInfo = '→ Производство (' . $movement->product->name_product . ')';
                                } else {
                                    $warehouseInfo = '→ Утилизация';
                                }
                            }

                            $baseUnit = null;
                            if ($movement->ingredient && $movement->ingredient->unitType) {
                                $baseUnit = $units->where('unit_type_id', $movement->ingredient->unitType->id)
                                    ->where('is_base', true)
                                    ->first();
                                if (!$baseUnit) {
                                    $baseUnit = $units->where('unit_type_id', $movement->ingredient->unitType->id)
                                        ->where('multiplier_to_base', 1)
                                        ->first();
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($movement->created_at)->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</td>
                            <td>{{ $warehouseName }}</td>
                            <td>{{ $operationType }}</td>
                            <td>{{ $movement->ingredient->name ?? 'Неизвестно' }}</td>
                            <td>{{ number_format($movement->quantity, 3) }} {{ $baseUnit ? $baseUnit->name : '' }}</td>
                            <td>{{ $warehouseInfo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; padding: 20px;">Операции не найдены</p>
        @endif
    @else
        {{-- Отчет по складам с ингредиентами (all или stock) --}}
        @foreach($warehouses as $warehouse)
            <div class="warehouse-section">
                <div class="warehouse-title">
                    Склад: {{ $warehouse->name }} ({{ $warehouse->city }}, {{ $warehouse->street }}, д. {{ $warehouse->house }})
                    @if(in_array($reportType, ['all', 'stock']))
                        <span style="float: right;">Всего ингредиентов: {{ $warehouseIngredientCounts[$warehouse->id] ?? 0 }}</span>
                    @endif
                </div>
                
                @if(in_array($reportType, ['all', 'stock']) && $warehouse->stockIngredients->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Ингредиент</th>
                                <th>Количество</th>
                                <th>Единица</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warehouse->stockIngredients as $stock)
                                @php
                                    // Находим базовую единицу для типа единиц измерения ингредиента
                                    $baseUnit = null;
                                    if ($stock->ingredient && $stock->ingredient->unitType) {
                                        $baseUnit = $units->where('unit_type_id', $stock->ingredient->unitType->id)
                                            ->where('is_base', true)
                                            ->first();
                                        if (!$baseUnit) {
                                            $baseUnit = $units->where('unit_type_id', $stock->ingredient->unitType->id)
                                                ->where('multiplier_to_base', 1)
                                                ->first();
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $stock->ingredient->name ?? 'Неизвестно' }}</td>
                                    <td>{{ number_format($stock->quantity, 3) }}</td>
                                    <td>{{ $baseUnit ? $baseUnit->name : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif(in_array($reportType, ['all', 'stock']))
                    <p>На складе нет ингредиентов</p>
                @endif

                @if($reportType == 'all' && isset($warehouseMovements[$warehouse->id]) && $warehouseMovements[$warehouse->id]->count() > 0)
                    <div style="margin-top: 20px;">
                        <h3 style="font-size: 12px; font-weight: bold; margin-bottom: 10px;">Операции со складом</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Тип операции</th>
                                    <th>Ингредиент</th>
                                    <th>Количество</th>
                                    <th>Откуда/Куда</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouseMovements[$warehouse->id] as $movement)
                                    @php
                                        $operationType = '';
                                        $warehouseInfo = '';
                                        
                                        if ($movement->from_warehouse_id == $warehouse->id && $movement->to_warehouse_id) {
                                            $operationType = 'Перемещение';
                                            $warehouseInfo = '→ ' . ($movement->toWarehouse->name ?? 'Неизвестно');
                                        } elseif ($movement->to_warehouse_id == $warehouse->id && $movement->from_warehouse_id) {
                                            $operationType = 'Поступление';
                                            $warehouseInfo = '← ' . ($movement->fromWarehouse->name ?? 'Неизвестно');
                                        } elseif ($movement->to_warehouse_id == $warehouse->id && !$movement->from_warehouse_id) {
                                            $operationType = 'Начисление';
                                            $warehouseInfo = 'Внешнее поступление';
                                        } elseif ($movement->from_warehouse_id == $warehouse->id && !$movement->to_warehouse_id) {
                                            $operationType = 'Списание';
                                            if ($movement->product) {
                                                $warehouseInfo = '→ Производство (' . $movement->product->name_product . ')';
                                            } else {
                                                $warehouseInfo = '→ Утилизация';
                                            }
                                        }

                                        $baseUnit = null;
                                        if ($movement->ingredient && $movement->ingredient->unitType) {
                                            $baseUnit = $units->where('unit_type_id', $movement->ingredient->unitType->id)
                                                ->where('is_base', true)
                                                ->first();
                                            if (!$baseUnit) {
                                                $baseUnit = $units->where('unit_type_id', $movement->ingredient->unitType->id)
                                                    ->where('multiplier_to_base', 1)
                                                    ->first();
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($movement->created_at)->setTimezone('Asia/Irkutsk')->format('d.m.Y H:i') }}</td>
                                        <td>{{ $operationType }}</td>
                                        <td>{{ $movement->ingredient->name ?? 'Неизвестно' }}</td>
                                        <td>{{ number_format($movement->quantity, 3) }} {{ $baseUnit ? $baseUnit->name : '' }}</td>
                                        <td>{{ $warehouseInfo }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</body>
</html>

