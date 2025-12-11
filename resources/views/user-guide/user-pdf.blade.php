<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство пользователя</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 4px;
        }
        .step {
            margin-bottom: 8px;
            padding: 8px 10px;
            background: linear-gradient(to right, #f7fafc 0%, #edf2f7 100%);
            border-left: 3px solid #667eea;
            border-radius: 3px;
        }
        .step-number {
            font-weight: bold;
            color: #667eea;
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
        .important-note {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border-left: 3px solid #e17055;
            padding: 8px 10px;
            border-radius: 3px;
            margin: 8px 0;
        }
        .important-note .step-number {
            background: #e17055;
            color: #ffffff;
        }
        .status-list {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            border-left: 3px solid #0284c7;
            padding: 8px 10px;
            border-radius: 3px;
            margin: 8px 0;
        }
        .status-list .step-number {
            background: #0284c7;
            color: #ffffff;
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
        <h1>РУКОВОДСТВО ПОЛЬЗОВАТЕЛЯ</h1>
        <p>Для зарегистрированных пользователей</p>
    </div>
    <div class="section">
        <div class="section-title">1. Добавление товаров в корзину</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> Перейдите в каталог и выберите интересующий товар.
        </div>
        <img src="{{ public_path('image/user-guide/user/каталог.png') }}" alt="Каталог товаров" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> На странице товара нажмите кнопку "В корзину".
        </div>
        <img src="{{ public_path('image/user-guide/user/добавить_в_корзину.png') }}" alt="Кнопка В корзину на странице товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3</span> Товар будет добавлен в корзину. Вы можете увидеть количество товаров в корзине в правом верхнем углу.
        </div>
        <img src="{{ public_path('image/user-guide/user/количество_товаров_в_корзине.png') }}" alt="Иконка корзины с количеством товаров" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">2. Управление корзиной</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> Нажмите на иконку корзины, чтобы перейти к просмотру содержимого.
        </div>
        <img src="{{ public_path('image/user-guide/user/корзина.png') }}" alt="Страница корзины" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> В корзине вы можете:
            <ul>
                <li>Изменить количество товаров</li>
                <li>Удалить товар из корзины</li>
                <li>Просмотреть общую сумму заказа</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/user/корзина_удаление.png') }}" alt="Корзина с товарами и кнопками управления" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">3. Оформление заказа</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> В корзине нажмите кнопку "Оформить заказ".
        </div>
        <img src="{{ public_path('image/user-guide/user/офрмление.png') }}" alt="Кнопка Оформить заказ" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> На странице оформления заказа проверьте выбранные товары и общую сумму.
        </div>
        <img src="{{ public_path('image/user-guide/user/проверка_оформления.png') }}" alt="Страница оформления заказа" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3</span> Нажмите кнопку "Оплатить" для перехода к оплате через платежную систему.
        </div>
        <img src="{{ public_path('image/user-guide/user/кнопка_оплатить.png') }}" alt="Кнопка оплаты" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">4. Оплата заказа</div>
        <div class="step">
            После нажатия кнопки "Оплатить" вы будете перенаправлены на страницу платежной системы для завершения оплаты.
        </div>
        <img src="{{ public_path('image/user-guide/user/оплата_заказа.png') }}" alt="Страница платежной системы" class="guide-image" />
        <div class="step">
            После успешной оплаты вы будете перенаправлены обратно на сайт, где увидите подтверждение заказа.
        </div>
        <img src="{{ public_path('image/user-guide/user/Успешное_оформление_закза.png') }}" alt="Страница успешной оплаты" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">5. Просмотр истории заказов</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> Перейдите в профиль, нажав на ваше имя в правом верхнем углу и выбрав "Профиль".
        </div>
        <img src="{{ public_path('image/user-guide/user/переход_в_профиль.png') }}" alt="Меню пользователя" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> В разделе "Мои заказы" вы можете просмотреть все ваши заказы с их статусами.
        </div>
        <img src="{{ public_path('image/user-guide/user/мои_заказы.png') }}" alt="Раздел Мои заказы в профиле" class="guide-image" />
        <div class="status-list">
            <span class="step-number">Статусы заказов</span>
            <ul>
                <li>Создан - заказ создан, ожидает оплаты</li>
                <li>Принят - заказ оплачен и принят в работу</li>
                <li>Готов к выдаче - заказ готов к получению</li>
                <li>Выполнен - заказ выдан клиенту</li>
            </ul>
        </div>
        <div class="important-note">
            <span class="step-number">Важно</span> Для заказов со статусом "Создан" доступна кнопка "Оплатить заказ" для завершения оплаты.
        </div>
    </div>
    <div class="section">
        <div class="section-title">6. Информация о товарах</div>
        <div class="step">
            На странице товара отображается информация о сроке годности в формате даты (например, 15.12.2025).
        </div>
        <img src="{{ public_path('image/user-guide/user/срок_годности.png') }}" alt="Страница товара с информацией о сроке годности" class="guide-image" />
    </div>
    <div class="section">
        <div class="section-title">7. Редактирование профиля</div>
        <div class="step">
            В профиле вы можете изменить свои личные данные, включая имя, фамилию, телефон и пароль.
        </div>
        <img src="{{ public_path('image/user-guide/user/Обновить_п_Д.png') }}" alt="Форма редактирования профиля" class="guide-image" />
    </div>
    <div class="footer">
        <p>© 2025 Кондитерская. Все права защищены.</p>
    </div>
</body>
</html>
