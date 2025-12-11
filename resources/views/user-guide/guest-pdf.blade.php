<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Руководство пользователя - Гость</title>
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
        <p>Для гостей сайта</p>
    </div>
    <div class="section">
        <div class="section-title">1. Просмотр каталога продукции</div>
        <div class="step">
            <span class="step-number">Шаг 1</span> Перейдите в раздел "Каталог" через меню навигации.
        </div>
        <img src="{{ public_path('image/user-guide/guest/меню_навигации.png') }}" alt="Меню навигации" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> В каталоге вы можете:
            <ul>
                <li>Просматривать все доступные товары</li>
                <li>Использовать поиск по названию товара</li>
                <li>Фильтровать товары по категориям</li>
                <li>Устанавливать диапазон цен</li>
            </ul>
        </div>
        <img src="{{ public_path('image/user-guide/guest/каталог_товара.png') }}" alt="Страница каталога с товарами" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 3</span> Нажмите на товар, чтобы просмотреть подробную информацию, включая срок годности в формате даты.
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
            <span class="step-number">Шаг 1</span> Гость может добавлять товары в корзину, просматривая каталог и нажимая кнопку "В корзину" на странице товара.
        </div>
        <img src="{{ public_path('image/user-guide/guest/В_корзину.png') }}" alt="Кнопка В корзину на странице товара" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> Вы можете просматривать содержимое корзины, изменять количество товаров и удалять товары из корзины.
        </div>
        <img src="{{ public_path('image/user-guide/guest/корзина.png') }}" alt="Страница корзины" class="guide-image" />
        <div class="important-note">
            <span class="step-number">Важно</span> Для оформления и оплаты заказа необходимо авторизоваться в системе. После авторизации вы сможете:
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
            <span class="step-number">Шаг 1</span> На главной странице нажмите кнопку "Регистрация" в правом верхнем углу.
        </div>
        <img src="{{ public_path('image/user-guide/guest/Ргистрация.png') }}" alt="Кнопка регистрации на главной странице" class="guide-image" />
        <div class="step">
            <span class="step-number">Шаг 2</span> Заполните форму регистрации:
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
            <span class="step-number">Шаг 3</span> Нажмите кнопку "Зарегистрироваться" для завершения регистрации.
        </div>
        <div class="step">
            <span class="step-number">После регистрации</span> Вы сможете авторизоваться в системе и получить полный доступ ко всем функциям, включая оформление и оплату заказов.
        </div>
    </div>
    <div class="footer">
        <p>© 2025 Кондитерская. Все права защищены.</p>
    </div>
</body>
</html>
