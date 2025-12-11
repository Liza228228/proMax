<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство администратора</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #2d3748;
            line-height: 1.4;
            background: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            margin-top: 0;
            padding: 12px 10px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 6px;
        }
        .header h1 {
            margin: 0 0 4px 0;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
        .header p {
            font-size: 11px;
            color: #ffffff;
            margin: 0;
        }
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
            background: #ffffff;
            padding: 10px;
            border-radius: 5px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 8px;
            padding: 8px 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        .step {
            margin-bottom: 8px;
            padding: 8px 10px;
            background: linear-gradient(to right, #f7fafc 0%, #edf2f7 100%);
            border-left: 3px solid #f5576c;
            border-radius: 3px;
        }
        .step-number {
            font-weight: bold;
            color: #f5576c;
            margin-right: 5px;
            font-size: 11px;
            display: inline-block;
            padding: 2px 6px;
            background: #ffffff;
            border-radius: 3px;
        }
        .guide-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            margin: 8px auto;
            display: block;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        ul {
            margin: 5px 0;
            padding-left: 18px;
            list-style: none;
        }
        li {
            margin-bottom: 3px;
            padding-left: 18px;
            position: relative;
            line-height: 1.4;
        }
        li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
            background: #f0fff4;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 9px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
        }
        @page {
            margin: 8mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>РУКОВОДСТВО АДМИНИСТРАТОРА</h1>
        <p>Панель управления администратора</p>
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
            <span class="step-number">Шаг 1</span> В панели администратора выберите "Управление пользователями".
        </div>
        <img src="{{ public_path('image/user-guide/admin/управление_п_глав.png') }}" alt="Карточка Управление пользователями" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> На странице управления пользователями вы можете:
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
            <span class="step-number">Шаг 1</span> Выберите "Управление категориями" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/управ_кат_гл.png') }}" alt="Карточка Управление категориями" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> Вы можете создавать, редактировать и удалять категории товаров.
        </div>
        <img src="{{ public_path('image/user-guide/admin/управление_кат.png') }}" alt="Страница управления категориями" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">4. Управление продукцией</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> Выберите "Управление продукцией" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/Упрвление_прод.png') }}" alt="Карточка Управление продукцией" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> При создании или редактировании товара:
            <ul>
                <li>Заполните название, описание, цену</li>
                <li>Выберите категорию</li>
                <li>Загрузите фотографии товара</li>
                <li>Укажите рецепт (ингредиенты и их количество)</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_прод.png') }}" alt="Форма создания/редактирования товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3</span> Для добавления количества товара:
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
            <span class="step-number">Шаг 1</span> Выберите "Создание отчетов" в панели администратора.
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_отчет.png') }}" alt="Карточка Создание отчетов" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> Доступны следующие типы отчетов:
            <ul>
                <li>Отчет по заказам - список всех заказов за период. Показывает количество выполненных заказов (со статусом "Выполнен")</li>
                <li>Финансовый отчет - анализ выручки по дням. Учитываются только оплаченные заказы (со статусом "Принят")</li>
                <li>Отчет по складу - информация о складах, количестве ингредиентов на каждом складе и операциях со складом (перемещения, начисления, списания)</li>
                <li>Отчет по статистике продукции - статистика заказов и топ продукции</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/admin/созд_отчетт.png') }}" alt="Страница создания отчетов" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3</span> Выберите период (для отчетов по заказам и финансам) и нажмите "Сформировать PDF" для скачивания отчета.
        </div>
    </div>
    <div class="section">
        <div class="section-title">7. Важные особенности</div>
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
    <div class="footer">
        <p>© 2025 Кондитерская. Все права защищены.</p>
    </div>
</body>
</html>
