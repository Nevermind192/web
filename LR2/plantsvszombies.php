<?php
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "Plants";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    $filterActive = isset($_GET['submit']) & !isset($_GET['reset']);

    if (mysqli_connect_errno())
    {
        echo "Ошибка подключения к Базе Данных";
        exit;
    }

    if ($filterActive)
    {
        $searchText = $_GET['search-text'];
        $searchType = $_GET['KindsOfFields'];
        $searchCostBefore = $_GET['PlantCost-before'];
        $searchCostAfter = $_GET['PlantCost-after'];

        $query = "SELECT plants.img_path,plants.name,fields.field_name,plants.description,plants.cost
                  FROM plants
                  inner join fields ON plants.id_field = fields.id 
                  WHERE
                  (
                  (plants.name LIKE '%" . $searchText . "%' OR plants.description LIKE '%" . $searchText . "%') AND
                  (fields.field_name LIKE '%" . $searchType . "%') AND
                  (plants.cost >= ". $searchCostBefore ." AND plants.cost <= ". $searchCostAfter .")
                  )";
    }
    else
    {
        $query = "SELECT plants.img_path,plants.name,fields.field_name,plants.description,plants.cost
                    FROM plants 
                    inner join fields ON plants.id_field = fields.id 
                    ORDER BY plants.id ASC";
        if (isset($_GET['reset']))
        {
            $_GET['reset'] = false;
        }
    }

    $InfoPlants = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>2 laba</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="1.css">
    <link rel="stylesheet" href="catalog.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    <div class="header">
        <div class="menu p-3">
            <div class="container">
                <div class="row">
                    <div class="menu-top col-md-8">
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
                        </ul>
                    </div>
                    <div class="col-md-4">
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
                                <img src="images/logo.png" alt="logo" class="logo">
                            </li>
                            <li class="brand nav-item">
                                <a class="nav-link" href="#"><p>ВЕСНА</p> <p>ОСЕНЬ</p></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <div class="search-area">
                            <div class="wrapper">
                                <input class="search-input" type="search" name="search" placeholder="Я ищу ...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 offset-md-3">
                        <ul class="nav justify-content-between">
                            <li class="nav-item">
                                <img src="images/user.svg" alt="user">
                                <a class="nav-item" href="#">Войти</a>
                            </li>
                            <li class="nav-item">
                                <img src="images/sprites_1.png" alt="basket" style="max-width:25px">
                                <a class="nav-item" href="#">Корзина</a>
                            </li>
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
    <div class="container-plants row">
        <div class="filters col-md-3">
            <p class="filters-title">Фильтры</p>
            <form method="get">
                <div class="filter">
                    <p class="filter-name">Поиск</p>
                    <div class="search">
                        <input name="search-text" type="text" class="search-field" placeholder="Введите запрос.." value="<?php if ($filterActive) echo $searchText; ?>">
                    </div>
                </div>
                <div class="filter">
                    <p class="filter-name">Название поля</p>
                    <select class="kinds-of-fields" name="KindsOfFields" id="">
                        <option value="" <?php if ($filterActive & $searchType == " ") echo 'selected="selected"'; ?>>Все</option>
                        <option value="Akkerman field" <?php if ($filterActive & $searchType == "Akkerman field") echo 'selected="selected"'; ?>>Akkerman field</option>
                        <option value="Levi field" <?php if ($filterActive & $searchType == "Levi field") echo 'selected="selected"'; ?>>Levi field</option>
                        <option value="Mikassa field" <?php if ($filterActive & $searchType == "Mikassa field") echo 'selected="selected"'; ?>>Mikassa field</option>
                        <option value="Volgograd field" <?php if ($filterActive & $searchType == "Volgograd field") echo 'selected="selected"'; ?>>Volgograd field</option>
                    </select>
                </div>
                <div class="filter">
                    <p class="filter-name">Цена от</p>
                    <input type="number" class="PlantCost" name="PlantCost-before" value="<?php if(isset($_GET['submit'])) { echo $_GET['PlantCost-before']; } else echo 0; ?>">
                </div>
                <div class="filter">
                    <p class="filter-name">Цена до</p>
                    <input type="number" class="PlantCost" name="PlantCost-after" value="<?php if(isset($_GET['submit'])) { echo $_GET['PlantCost-after']; } else echo 9999; ?>">
                </div>
                <div class="filter-buttons">
                    <input type="submit" name="reset" value="Сбросить">
                    <input type="submit" name="submit" value="Применить">
                </div>
            </form>
        </div>
        <div class="plants col-md-9">
            <?php
                foreach ($InfoPlants as $item )
                {
            ?>
            <div class="catalog_plants row">
                <div class="plants_img col-md-2">
                    <img class="image" src="images/catalog_img/<?= $item['img_path'] ?>" alt="фото растений">
                </div>
                <div class="plant-info col-md-8">
                    <h3 class="plant-name">
                        <?= $item['name'] ?>
                    </h3>
                    <div class="plant-description">
                        <?= $item['description'] ?>
                        <p>Поле: <?= $item['field_name'] ?></p>
                    </div>
                </div>
                <div class="plant-cost col-md-1">
                    <?= $item['cost'] ?> <span>руб.</span>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>
