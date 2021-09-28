<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/profile">
                    <span data-feather="home"></span>
                    Профиль <span class="sr-only">(current)</span>
                </a>
            </li>
            <?
            if($activ){?>
                <li class="nav-item">
                    <a class="nav-link" href="/profile/structure">
                        <span data-feather="file"></span>
                        Моя площадка
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile/bonus">
                        <span data-feather="file"></span>
                        Бонусная программа
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile/refs">
                        <span data-feather="file"></span>
                        Промоушн
                    </a>
                </li>
            <?}
            ?>
            <li class="nav-item">
                <a class="nav-link" href="/profile/withdraws">
                    <span data-feather="shopping-cart"></span>
                    Активность
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/profile/actions">
                    <span data-feather="shopping-cart"></span>
                    Транзакции
                </a>
            </li>

        </ul>

    </div>
</nav>