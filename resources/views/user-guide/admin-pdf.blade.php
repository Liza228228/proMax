<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство администратора</title>
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
        <h1>РУКОВОДСТВО АДМИНИСТРАТОРА</h1>
        <p style="font-size: 14px; color: #666;">Панель управления администратора</p>
    </div>

    <div class="section">
        <div class="section-title">1. Доступ к панели администратора</div>
        <div class="step">
            После входа в систему с правами администратора в меню навигации появится ссылка "Панель администратора".
        </div>
        <img src="{{ public_path('image/user-guide/admin/кнопка_п_а.png') }}" alt="Меню навигации с кнопкой Панель администратора" class="guide-image" />
        <div class="step">
            Нажмите на эту ссылку для перехода в панель управления.
        </div>
        <img src="{{ public_path('image/user-guide/admin/главная_п_а.png') }}" alt="Главная страница панели администратора" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">2. Управление пользователями</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> В панели администратора выберите "Управление пользователями".
        </div>
        <img src="{{ public_path('image/user-guide/admin/управление_п_глав.png') }}" alt="Карточка Управление пользователями" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> На странице управления пользователями вы можете:
            <ul>
                <li>Просматривать список всех пользователей</li>
                <li>Искать пользователей по имени, телефону или логину</li>
                <li>Создавать новых пользователей</li>
                <li>Редактировать данные пользователей</li>
                <li>Блокировать/разблокировать пользователей</li>
                <li>Изменять роли пользователей</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/управление_пользователями.png') }}" alt="Страница управления пользователями" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">3. Управление категориями</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Выберите "Управление категориями" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/управ_кат_гл.png') }}" alt="Карточка Управление категориями" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> Вы можете создавать, редактировать и удалять категории товаров.
        </div>
        <img src="{{ public_path('image/user-guide/admin/управление_кат.png') }}" alt="Страница управления категориями" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">4. Управление продукцией</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Выберите "Управление продукцией" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/Упрвление_прод.png') }}" alt="Карточка Управление продукцией" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> При создании или редактировании товара:
            <ul>
                <li>Заполните название, описание, цену</li>
                <li>Выберите категорию</li>
                <li>Загрузите фотографии товара</li>
                <li>Укажите рецепт (ингредиенты и их количество)</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_прод.png') }}" alt="Форма создания/редактирования товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Для добавления количества товара:
            <ul>
                <li>Перейдите на страницу товара и нажмите "Добавить количество"</li>
                <li>Укажите количество единиц для производства</li>
                <li>Установите срок годности (дата, не может быть раньше сегодняшней даты)</li>
                <li>При добавлении количества автоматически списываются ингредиенты со склада согласно рецепту</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/добав_кол_прод.png') }}" alt="Форма добавления количества товара" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">5. Управление новинками</div>
        <div class="step">
            В разделе "Управление новинками" вы можете выбрать товары, которые будут отображаться в разделе "Новинки" на главной странице.
        </div>
        <img src="{{ public_path('image/user-guide/admin/новинки.png') }}" alt="Страница управления новинками" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">6. Генерация отчетов</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Выберите "Создание отчетов" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_отчет.png') }}" alt="Карточка Создание отчетов" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> Доступны следующие типы отчетов:
            <ul>
                <li>Отчет по заказам - список всех заказов за период. Показывает количество выполненных заказов (со статусом "Выполнен")</li>
                <li>Финансовый отчет - анализ выручки по дням. Учитываются только оплаченные заказы (со статусом "Принят")</li>
                <li>Отчет по складу - информация о складах, количестве ингредиентов на каждом складе и операциях со складом (перемещения, начисления, списания)</li>
                <li>Отчет по статистике продукции - статистика заказов и топ продукции</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_отчетт.png') }}" alt="Страница создания отчетов" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Выберите период (для отчетов по заказам и финансам) и нажмите "Сформировать PDF" для скачивания отчета.
        </div>
    </div>

    <div class="section">
        <div class="section-title">8. Важные особенности</div>
        <div class="step">
            <ul>
                <li>Администратор не может добавлять товары в корзину</li>
                <li>Корзина и история заказов скрыты для администратора</li>
                <li>Срок годности устанавливается только при добавлении количества продукции</li>
                <li>Товары с истекшим сроком годности автоматически становятся недоступными и их количество обнуляется</li>
                <li>При обнулении количества товар автоматически становится недоступным</li>
            </ul>
        </div>
    </div>

</body>
</html>

