<?php
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "Plants";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    $fId = mysqli_query($connect,"SELECT id, field_name FROM fields");

    if (mysqli_connect_errno())
    {
        echo "Ошибка подключения к Базе Данных";
        exit;
    }

    if(isset($_POST["add"]))
    {
        $plantName=htmlspecialchars(strip_tags($_POST["plName"]));
        $plantDescription=htmlspecialchars(strip_tags($_POST["plDescription"]));
        $plantCost=doubleval(htmlspecialchars(($_POST["plCost"])));
        $id=htmlspecialchars(($_POST['select_id']));
        $planid=intval(htmlspecialchars(($_POST["planid"])));

        if(isset($_FILES) && $_FILES['fileFF']['error'] == 0)
        {
            $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/LR6/images/catalog_img/';
            $uploadfile = $uploaddir . basename($_FILES['fileFF']['name']);
            $tmp_name = $_FILES["fileFF"]["tmp_name"];
            $name = basename($_FILES["fileFF"]["name"]);
            move_uploaded_file($tmp_name, "$uploaddir/$name");
            $img_path=htmlspecialchars($_FILES["fileFF"]["name"]);
            $message ='Файл успешно загружен';
            UserTable::add($planid,$img_path,$plantName,$id,$plantDescription,$plantCost);
        }
        else
        {
            $message = 'Ошибка загрузки файла';
        }
    }



    class UserTable
    {
        public static function open()
        {
            $hostname = "localhost";
            $username = "root";
            $password = "root";
            $databaseName = "Plants";
            $connect = mysqli_connect($hostname, $username, $password, $databaseName);
            $query = "SELECT plants.id, plants.img_path, plants.name, fields.field_name, plants.description, plants.cost 
                    FROM plants inner join fields ON plants.id_field = fields.id
                    ORDER BY plants.id ASC";
            $result_table = mysqli_query($connect, $query);

            while($row1 = mysqli_fetch_array($result_table))
            {
                $resualt_text = $resualt_text. "<tr><td>".$row1[0]."</td>
                    <td><img src=\"images/catalog_img/".$row1[1]."\" height=100 width=100></img></td>
                    <td>".$row1[2]."</td>
                    <td>".$row1[3]."</td>
                    <td>".$row1[4]."</td>
                    <td>".$row1[5]."</td>
                </tr>";
            }
            return $resualt_text;
        }
        public static function add(int $planid, string $img_path, string $plantName, int $fieldId, string $plantDescription, int $plantCost)
        {
            $hostname = "localhost";
            $username = "root";
            $password = "root";
            $databaseName = "plants";
            $connect = mysqli_connect($hostname, $username, $password, $databaseName);
            $query = "INSERT INTO `plants`(id, img_path, name, id_field, description, cost) VALUES('$planid','$img_path','$plantName','$fieldId','$plantDescription','$plantCost');";

            $update_table = mysqli_query($connect, $query);
            if($update_table != FALSE)
            {
                $message="Запись успешно добавлена";
            }
            else
            {
                $message="Ошибка добавления записи";
            }

            return $message;
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<body>
    <?php include "header.php" ?>
    <h1 style="text-align: center; margin-top: 20px; color: rebeccapurple">Жееесть работа с БД(добавление/удаление/изменение записей) WTF</h1>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped" style="margin-top: 20px; background-color: #ffd747; border-radius: 20px; border-color: orange">
                <thead>
                <tr>
                    <th style="width:20px">Id</th>
                    <th style="width:120px">Фото</th>
                    <th style="width:120px">Название</th>
                    <th style="width:120px">Поле</th>
                    <th style="width:400px">Описание</th>
                    <th style="width:80px">Цена</th>
                </tr>
                </thead>
                <tbody>
                <?php echo $text=UserTable::open(); ?>
                </tbody>
            </table>
            <div class="addPlant" style="border-radius: 10px; background-color: plum">
                <h1 style="text-align: center">Добавление нового растения</h1>
                <div class="row">
                    <form class="col-md-12">
                        <label class="col-md-3" for="fileFF">Прикрепить фото:</label>
                        <label class="col-md-2" for="plName">Название:</label>
                        <label class="col-md-2" for="fieldName">Поле:</label>
                        <label class="col-md-3" for="plDescription">Описание:</label>
                        <label class="col-md-1" for="plCost">Цена:</label>
                    </form>
                </div>
                <div class="row">
                    <form class="col-md-12" enctype="multipart/form-data" method="post" id="feedback-form">
                        <input class="col-md-2" required="required" type="file" name="fileFF" multiple id="fileFF">
                        <input class="col-md-1" required="required" type="number" name="planid" id="planid" style="border-radius: 10px">
                        <input class="col-md-2" required="required" type="text" name="plName" id="plName" style="border-radius: 10px">
                        <select class="select" name="select_id" style="width: 180px; border-radius: 10px">
                            <?php
                            while ($row = mysqli_fetch_array($fId))
                            {
                                echo "<option value='".$row['id']."'>".$row['field_name']."</option>";
                            }
                            $selected_id = htmlspecialchars(strip_tags(stripslashes(trim($_GET['select_id']))));
                            ?>
                        </select>
                        <textarea cols="56" name="plDescription" id="messageFF" placeholder="Подробности о растении…" style="border-radius: 10px"></textarea>
                        <input class="col-md-1" required="required" type="number" name="plCost" id="plCost" style="border-radius: 10px">
                        <input name="add" value="Добавить" type="submit" id="submitFF">
                    </form>
                </div>
            </div>
            <?php
                echo $message;
            ?>
        </div>
    </div>
</body>
</html>
