<head>
    <meta charset="utf-8">
    <title>2 laba</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="1.css">
    <link rel="stylesheet" href="catalog.css">
    <link rel="stylesheet" href="reg.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<div class="header">
    <div class="menu p-3">
        <div class="container">
            <div class="row">
                <div class="menu-top col-md-10">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">О нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Контакты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Оплата и доставка</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Гарантия на растения</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Отзывы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Получить скидку</a>
                        </li>
                        <?php if (!$_COOKIE["name"]=="")
                        {
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link' href='plantsvszombies.php'>Секретная страница</a>";
                            echo "</li>";
                        } ?>
                    </ul>
                </div>
                <div class="col-md-2">
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a class="button_1 nav-link" href="#">СПЕЦАКЦИЯ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="searching p-2">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <ul class="nav">
                        <li class="nav-item">
                            <img src="images/inc_images/logo.png" alt="logo" class="logo">
                        </li>
                        <li class="brand nav-item">
                            <a class="nav-link" href="#"><p>ВЕСНА</p> <p>ОСЕНЬ</p></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <div class="search-area">
                        <div class="wrapper">
                            <input class="search-input" type="search" placeholder="Я ищу ...">
                        </div>
                    </div>
                </div>
                <div class="col-md-2 offset-md-3">
                    <ul class="nav justify-content-between">
                        <?php if ($_COOKIE["name"]=="")
                        {
                            echo "<a href='register.php'><img src='images/inc_images/reg-user2.png' alt='user' style='width: 30px; height: 30px; transform: translate(70px)'></a>";
                            echo "<p style='color: white; transform: translate(0, 40px)'>Регистрация</p>";
                            echo "<a href='login.php'><img src='images/inc_images/log-user.png' alt='user' style='width: 30px; height: 30px; transform: translate(45px)'></a>";
                            echo "<p style='color: white; transform: translate(0, 40px)'>Войти</p>";
                        }
                        else
                        {
                            echo "<p style='transform: translate(0, 5px)'>" . "<img src='images/inc_images/session.png' alt='user' style='width: 30px; height: 30px'> ". $_COOKIE["name"];
                            echo "<br>" . "<a href='index.php?out=true' style='color:black; padding-left: 40px; font-size:14px'>Выйти</a>" . "</p>";
                            echo "<p style='transform: translate(0, 20px)'>" . "<img src='images/inc_images/basket.png' alt='basket' style='width:30px; height: 30px'>";
                            echo "<a class='nav-item' href='#' style='color:black'>Корзина</a>" . "</p>";
                        }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="prompt">
        <div class="row">
            <div class="prompts col-md-3 offset-3">
                <a class="nav-link" href="#">саженцы винограда</a>
                <a class="nav-link" href="#">саженцы вишни</a>
            </div>
        </div>
    </div>
</div>