<?php
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "Plants";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);

    if (mysqli_connect_errno())
    {
        echo "Ошибка подключения к Базе Данных";
        exit;
    }

    if ($_COOKIE["name"]=="")
    {
        header('Location: login.php');
    }

    $filterActive = isset($_GET['submit']) & !isset($_GET['reset']);

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
<body>
<?php include "header.php" ?>
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
