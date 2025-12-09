<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство пользователя</title>
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
        <h1>РУКОВОДСТВО ПОЛЬЗОВАТЕЛЯ</h1>
        <p style="font-size: 14px; color: #666;">Для зарегистрированных пользователей</p>
    </div>

    <div class="section">
        <div class="section-title">1. Добавление товаров в корзину</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Перейдите в каталог и выберите интересующий товар.
        </div>
        <img src="{{ public_path('image/user-guide/user/каталог.png') }}" alt="Каталог товаров" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> На странице товара нажмите кнопку "В корзину".
        </div>
        <img src="{{ public_path('image/user-guide/user/добавить_в_корзину.png') }}" alt="Кнопка В корзину на странице товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Товар будет добавлен в корзину. Вы можете увидеть количество товаров в корзине в правом верхнем углу.
        </div>
        <img src="{{ public_path('image/user-guide/user/количество_товаров_в_корзине.png') }}" alt="Иконка корзины с количеством товаров" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">2. Управление корзиной</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Нажмите на иконку корзины, чтобы перейти к просмотру содержимого.
        </div>
        <img src="{{ public_path('image/user-guide/user/корзина.png') }}" alt="Страница корзины" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> В корзине вы можете:
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
            <span class="step-number">Шаг 1:</span> В корзине нажмите кнопку "Оформить заказ".
        </div>
        <img src="{{ public_path('image/user-guide/user/офрмление.png') }}" alt="Кнопка Оформить заказ" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> На странице оформления заказа проверьте выбранные товары и общую сумму.
        </div>
        <img src="{{ public_path('image/user-guide/user/проверка_оформления.png') }}" alt="Страница оформления заказа" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Нажмите кнопку "Оплатить" для перехода к оплате через платежную систему.
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
            <span class="step-number">Шаг 1:</span> Перейдите в профиль, нажав на ваше имя в правом верхнем углу и выбрав "Профиль".
        </div>
        <img src="{{ public_path('image/user-guide/user/переход_в_профиль.png') }}" alt="Меню пользователя" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> В разделе "Мои заказы" вы можете просмотреть все ваши заказы с их статусами.
        </div>
        <img src="{{ public_path('image/user-guide/user/мои_заказы.png') }}" alt="Раздел Мои заказы в профиле" class="guide-image" />
        <div class="step">
            <span class="step-number">Статусы заказов:</span>
            <ul>
                <li>Создан - заказ создан, ожидает оплаты</li>
                <li>Принят - заказ оплачен и принят в работу</li>
                <li>Готов к выдаче - заказ готов к получению</li>
                <li>Выполнен - заказ выдан клиенту</li>
            </ul>
        </div>
        <div class="step">
            <span class="step-number">Важно:</span> Для заказов со статусом "Создан" доступна кнопка "Оплатить заказ" для завершения оплаты.
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
</body>
</html>


