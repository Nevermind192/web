<?php
    session_start();
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

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $count_row = 0;
        $file = "plants_import";
        $path =($_POST["path"]);
        $data = file_get_contents($path,true);
        $array = json_decode($data, true);

        $query = mysqli_query($connect, "DROP TABLE IF EXISTS $file");
        $query = mysqli_query($connect, "CREATE TABLE $file (id int, img_path varchar(45), name varchar(45), id_field int(25), 
        description text, cost int)");

        foreach ($array as $id=>$row)
        {
            $insertPairs = array();
            foreach ($row as $key=>$val)
            {
                $insertPairs[addslashes($key)] = addslashes($val);
            }
            $insertVals = '"' . implode('","', array_values($insertPairs)) . '"';
            $count_row++;
            $query = mysqli_query($connect, "INSERT INTO `$file` VALUES ({$insertVals});");
        }

        $message = "Файл с данными получен из $path и обработан. Создана таблица $file число записей в ней - $count_row";
    }
?>

<!DOCTYPE html>
<html lang="ru">
<body>
    <?php include "header.php" ?>
    <br>Формат - json
    <br>Способ экспорта - Файл на сервере
    <br>Способ импорта - Файл с локального сервера
    <form action="" method="post" enctype=”multipart/form-data”>
        <div class="form-group">
            <input type="text" required="required" name="path" placeholder="http://localhost/LR5/export/export.json" style="box-shadow:0 0 15px 4px rgba(0,0,0,0.06); border-radius:10px; margin:10px 0;  border: 2px solid #755a57;" />
            <br>
            <button type="submit" name="import" class="btn btn-dark"style="border-radius: 8px;">Импорт</button>
        </div>
    </form>
    <br>
    <?php
    echo $message;
    ?>
</body>
</html>