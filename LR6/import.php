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

    $filename = "plants_export.json";
    $data = file_get_contents($filename);
    $array = json_decode($data, true);

    foreach ($array as $value)
    {
        $query = "INSERT INTO 'plants_imported' ('id', 'img_path', 'name', 'id_field', 'description', 'cost') VALUES 
        ('".$value['id']."', '".$value['img_path']."', '".$value['name']."', '".$value['id_field']."', '".$value['description']."', '".$value['cost']."')";
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
            <input type="text" required="required" name="path" placeholder="http://localhost/LR3(1)/import/import.csv" style="box-shadow:0 0 15px 4px rgba(0,0,0,0.06); border-radius:10px; margin:10px 0;  border: 2px solid #755a57;" />
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