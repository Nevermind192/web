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
        $path = $_SERVER['DOCUMENT_ROOT'] . '/LR5/export/';
        $query = mysqli_query($connect, "SELECT * FROM plants");

        while($row = mysqli_fetch_all($query))
        {
            $array[] = $row;
        }
        foreach($array as $file_array)
        {
            file_put_contents($path.'plants_export.json', json_encode($file_array, JSON_UNESCAPED_UNICODE));
        }

        $message = "Файл с данными сохранен на диск по адресу: /export/plants_exported.json";
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
            <button type="submit" name="export" class="btn btn-dark"style="border-radius: 8px;">Экспортировать</button>
        </div>
    </form>
    <?php
    echo $message;
    ?>
</body>
</html>
