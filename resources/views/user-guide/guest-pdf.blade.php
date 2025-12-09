<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство пользователя - Гость</title>
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
        <p style="font-size: 14px; color: #666;">Для гостей сайта</p>
    </div>
    <div class="section">
        <div class="section-title">1. Просмотр каталога продукции</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Перейдите в раздел "Каталог" через меню навигации.
        </div>
        <img src="{{ public_path('image/user-guide/guest/меню_навигации.png') }}" alt="Меню навигации" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> В каталоге вы можете:
            <ul>
                <li>Просматривать все доступные товары</li>
                <li>Использовать поиск по названию товара</li>
                <li>Фильтровать товары по категориям</li>
                <li>Устанавливать диапазон цен</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/guest/каталог_товара.png') }}" alt="Страница каталога с товарами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Нажмите на товар, чтобы просмотреть подробную информацию, включая срок годности в формате даты.
        </div>
        <img src="{{ public_path('image/user-guide/guest/товар_с_деталями.png') }}" alt="Страница товара с деталями" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">2. Просмотр новинок</div>
        <div class="step">
            На главной странице в разделе "Новинки" вы можете увидеть последние добавленные товары.
        </div>
        <img src="{{ public_path('image/user-guide/guest/новинки.png') }}" alt="Раздел новинок на главной странице" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">3. Информация о кондитерской</div>
        <div class="step">
            В подвале сайта вы можете найти ссылку "О нас", где представлена информация о кондитерской, режиме работы и контактах.
        </div>
        <img src="{{ public_path('image/user-guide/guest/о_нас.png') }}" alt="Страница О нас" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">4. Работа с корзиной</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> Гость может добавлять товары в корзину, просматривая каталог и нажимая кнопку "В корзину" на странице товара.
        </div>
        <img src="{{ public_path('image/user-guide/guest/В_корзину.png') }}" alt="Кнопка В корзину на странице товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> Вы можете просматривать содержимое корзины, изменять количество товаров и удалять товары из корзины.
        </div>
        <img src="{{ public_path('image/user-guide/guest/корзина.png') }}" alt="Страница корзины" class="guide-image" />
        <div class="step">
            <span class="step-number">Важно:</span> Для оформления и оплаты заказа необходимо авторизоваться в системе. После авторизации вы сможете:
            <ul>
                <li>Оформить заказ из корзины</li>
                <li>Оплатить заказ через платежную систему</li>
                <li>Просматривать историю своих заказов</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/guest/войти_для_оформления.png') }}" alt="Кнопка Оформить заказ для авторизованных пользователей" class="guide-image" />
    </div>

    <div class="section">
        <div class="section-title">5. Регистрация на сайте</div>
        <div class="step">
            <span class="step-number">Шаг 1:</span> На главной странице нажмите кнопку "Регистрация" в правом верхнем углу.
        </div>
        <img src="{{ public_path('image/user-guide/guest/Ргистрация.png') }}" alt="Кнопка регистрации на главной странице" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2:</span> Заполните форму регистрации:
            <ul>
                <li>Фамилия и имя</li>
                <li>Номер телефона</li>
                <li>Логин (уникальное имя пользователя)</li>
                <li>Пароль (минимум 8 символов)</li>
                <li>Подтверждение пароля</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/guest/форма_регистрации.png') }}" alt="Форма регистрации" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3:</span> Нажмите кнопку "Зарегистрироваться" для завершения регистрации.
        </div>
        <div class="step">
            <span class="step-number">После регистрации:</span> Вы сможете авторизоваться в системе и получить полный доступ ко всем функциям, включая оформление и оплату заказов.
        </div>
    </div>
</body>
</html>

