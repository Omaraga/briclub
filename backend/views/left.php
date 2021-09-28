<?php

use common\models\User;

?>
<aside class="main-sidebar">

    <section class="sidebar">



        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [

                    ['label' => 'Матрицы', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Матрица', 'icon' => 'dashboard', 'url' => ['/site/mat'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Пользователи', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Пользователи', 'icon' => 'dashboard', 'url' => ['/users-list'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Верификация', 'icon' => 'circle-o', 'url' => '/verifications', 'visible' => User::isAccess('admin')],
					['label' => 'Premium-аккаунты', 'icon' => 'circle-o', 'url' => '/premiums', 'visible' => User::isAccess('admin')],
                    /*['label' => 'Билеты на мероприятие', 'icon' => 'dashboard', 'url' => ['/users/events'], 'visible' => User::isAccess('admin')],*/
                    ['label' => 'Отчеты', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Закрытие столов', 'icon' => 'dashboard', 'url' => ['/users/closes'], 'visible' => User::isAccess('admin')],

                    /*['label' => 'Страны', 'icon' => 'dashboard', 'url' => ['/users/countries'], 'visible' => User::isAccess('admin')],*/
                    ['label' => 'Заработок компании со столов', 'icon' => 'dashboard', 'url' => ['/users/profit'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Заработок пользователей', 'icon' => 'dashboard', 'url' => ['/users/profituser'], 'visible' => User::isAccess('admin')],
                    /*['label' => 'Сверка балансов', 'icon' => 'dashboard', 'url' => ['/checks'], 'visible' => User::isAccess('admin')],*/

                    ['label' => 'Операции', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Вывод', 'icon' => 'dashboard', 'url' => ['/withdraws'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Переводы', 'icon' => 'dashboard', 'url' => ['/transfers-list'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Пополнение', 'icon' => 'dashboard', 'url' => ['/deposit'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Курсы валют', 'icon' => 'dashboard', 'url' => ['/changes'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Счета для пополнения', 'icon' => 'dashboard', 'url' => ['/deposit-accounts'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Токены', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Кошельки', 'icon' => 'file-code-o', 'url' => ['/wallets'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Пополнение токенов', 'icon' => 'file-code-o', 'url' => ['/deposit-tokens'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Список покупки токенов', 'icon' => 'file-code-o', 'url' => ['/token-gets'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Пользователи с токенами', 'icon' => 'file-code-o', 'url' => ['/tokens-list'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Список нод', 'icon' => 'file-code-o', 'url' => ['/nodes-list'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Список перевода токенов', 'icon' => 'file-code-o', 'url' => ['/tokens-transfers'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Тех поддержка', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Тех поддержка', 'icon' => 'dashboard', 'url' => ['/tickets'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Библиотека', 'options' => ['class' => 'header'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Книги', 'icon' => 'file-code-o', 'url' => ['/library'], 'visible' => User::isAccess('admin')],
					['label' => 'Курсы', 'icon' => 'file-code-o', 'url' => ['/courses'], 'visible' => User::isAccess('admin')],

                    ['label' => 'Настройки', 'options' => ['class' => 'header'], 'visible' => User::isAccess('moderator')],

                    ['label' => 'Новости', 'icon' => 'file-code-o', 'url' => ['/news'], 'visible' => User::isAccess('moderator')],
                    ['label' => 'Мероприятия', 'icon' => 'file-code-o', 'url' => ['/events'], 'visible' => User::isAccess('moderator')],
                    ['label' => 'Страны', 'icon' => 'file-code-o', 'url' => ['/countries'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Документы', 'icon' => 'file-code-o', 'url' => ['/documents'], 'visible' => User::isAccess('moderator')],
                    ['label' => 'Балансы', 'icon' => 'file-code-o', 'url' => ['/balanses'], 'visible' => User::isAccess('admin')],
                    ['label' => 'Техподдержка DEV', 'icon' => 'file-code-o', 'url' => ['/tickets/dev-desktop'], 'visible' => User::isAccess('developer')],


                ],
            ]
        ) ?>

    </section>

</aside>
