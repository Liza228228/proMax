<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство менеджера</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 10px;
            border-left: 4px solid #e91e63;
            padding-left: 10px;
        }
        .step {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 3px solid #e91e63;
        }
        .step-number {
            font-weight: bold;
            color: #e91e63;
            margin-right: 5px;
        }
        .image-placeholder {
            width: 100%;
            height: 200px;
            background: #f0f0f0;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            text-align: center;
            color: #999;
        }
        .guide-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>РУКОВОДСТВО МЕНЕДЖЕРА</h1>
        <p style="font-size: 14px; color: #666;">Панель управления менеджера</p>
    </div>

    <div class="section">
        <div class="section-title">1. Доступ к панели менеджера</div>
        <div class="step">
            После входа в систему с правами менеджера в меню навигации появится ссылка "Панель менеджера".
        </div>
        <img src="{{ public_path('image/user-guide/manager/панель_менеджера.png') }}" alt="Меню навигации с кнопкой Панель менеджера" class="guide-image" />
        <div class="step">
            Нажмите на эту ссылку для перехода в панель управления.
        </div>
        <img src="{{ public_path('image/user-guide/manager/глав_панели.png') }}" alt="Главная страница панели менеджера" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">2. Управление заказами</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> В панели менеджера выберите "Управление заказами".
        </div>
        <img src="{{ public_path('image/user-guide/manager/управ_заказа.png') }}" alt="Карточка Управление заказами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> На странице управления заказами вы можете:
            <ul>
                <li>Просматривать все заказы</li>
                <li>Искать заказы по клиенту или дате</li>
                <li>Фильтровать заказы по статусу</li>
                <li>Изменять статус заказа</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/управ_заказа.png') }}" alt="Страница управления заказами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Для изменения статуса заказа:
            <ul>
                <li>Нажмите на кнопку "Изменить статус" рядом с заказом</li>
                <li>Выберите новый статус из выпадающего списка</li>
                <li>Подтвердите изменение</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/измен статуса.png') }}" alt="Изменение статуса заказа" class="guide-image" />
        <div class="step">
            <span class="step-number">Доступные статусы:</span>
            <ul>
                <li>Создан - заказ только что создан, ожидает оплаты</li>
                <li>Принят - заказ оплачен и принят в работу</li>
                <li>Готов к выдаче - заказ готов к получению</li>
                <li>Выполнен - заказ выдан клиенту</li>
            </ul>
        </div>
        <div class="step">
            <span class="step-number">Важно:</span> Менеджер не может изменить статус заказа, если он находится в статусе "Создан". Изменение статуса доступно только после того, как заказ будет оплачен и получит статус "Принят".
        </div>
    </div>

    <div class="section">
        <div class="section-title">3. Управление складами</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Выберите "Управление складами" в панели менеджера.
        </div>
        <img src="{{ public_path('image/user-guide/manager/управ_склад.png') }}" alt="Карточка Управление складами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> На странице управления складами вы можете:
            <ul>
                <li>Просматривать список всех складов</li>
                <li>Создавать новые склады</li>
                <li>Редактировать информацию о складах</li>
                <li>Просматривать ингредиенты на каждом складе</li>
                <li>Добавлять ингредиенты на склад</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/управ_склад.png') }}" alt="Страница управления складами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Для просмотра ингредиентов на складе нажмите на название склада или кнопку "Просмотр".
        </div>
        <img src="{{ public_path('image/user-guide/manager/просм_склад.png') }}" alt="Страница склада с ингредиентами" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">4. Управление ингредиентами</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Выберите "Управление ингредиентами" в панели менеджера.
        </div>
        <img src="{{ public_path('image/user-guide/manager/Управ_инг.png') }}" alt="Карточка Управление ингредиентами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> Вы можете создавать, редактировать и просматривать ингредиенты.
        </div>
        <div class="step">
            <span class="step-number">Шаг 3:</span> При создании или редактировании ингредиента:
            <ul>
                <li>Укажите название и описание</li>
                <li>Выберите тип единицы измерения</li>
                <li>Установите срок годности в формате даты (не может быть раньше сегодняшней даты)</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/доб_ингр.png') }}" alt="Форма создания/редактирования ингредиента" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">5. Добавление ингредиентов на склад</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Перейдите на страницу конкретного склада.
        </div>
        <div class="step">
            <span class="step-number">Шаг 2:</span> В форме добавления ингредиента укажите:
            <ul>
                <li>Название ингредиента</li>
                <li>Количество</li>
                <li>Единицу измерения</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/добаваить.png') }}" alt="Форма добавления ингредиента на склад" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Нажмите "Добавить" для сохранения ингредиента на складе.
        </div>
    </div>

    <div class="section">
        <div class="section-title">6. Операции со складом</div>
        <div class="step">
            На странице склада отображается история операций:
            <ul>
                <li>Перемещения между складами</li>
                <li>Начисления (внешние поступления)</li>
                <li>Списания (в производство или утилизацию)</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/manager/история.png') }}" alt="История операций со складом" class="guide-image" />
        <div class="step">
            Вы можете перемещать ингредиенты между складами, используя функцию "Переместить".
        </div>
    </div>

    <div class="section">
        <div class="section-title">7. Важные особенности</div>
        <div class="step">
            <ul>
                <li>Менеджер не может добавлять товары в корзину</li>
                <li>Корзина и история заказов скрыты для менеджера</li>
                <li>Менеджер не может изменять пароль (эта функция недоступна)</li>
                <li>Менеджер отвечает за обработку заказов и управление складами</li>
                <li>При изменении статуса заказа клиент может видеть обновления в своем профиле</li>
                <li>Срок годности ингредиентов указывается в формате даты, не может быть раньше сегодняшней даты</li>
            </ul>
        </div>
    </div>

</body>
</html>

