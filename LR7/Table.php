<?php
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "Plants";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    $fId = mysqli_query($connect,"SELECT id, field_name FROM fields");
    $fId2 = mysqli_query($connect,"SELECT id, field_name FROM fields");

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

    if(isset($_GET['del']))
    {
        $del_id = $_GET['del_id'];
        UserTable::del($del_id);
    }

    if(isset($_POST["change"]))
    {
        $plantName=htmlspecialchars(strip_tags($_POST["plName2"]));
        $plantDescription=htmlspecialchars(strip_tags($_POST["plDescription2"]));
        $plantCost=doubleval(htmlspecialchars(($_POST["plCost2"])));
        $id=htmlspecialchars(($_POST['select_id3']));

        if($plantCost<0.0)
        {
            $message = 'Цена указана неверно';
        }
        else
        {
            if(isset($_FILES) && $_FILES['fileFF2']['error'] == 0)
            {
                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/LR7/images/catalog_img/';
                $uploadfile = $uploaddir . basename($_FILES['fileFF2']['name']);
                $tmp_name = $_FILES["fileFF2"]["tmp_name"];
                $name = basename($_FILES["fileFF2"]["name"]);
                move_uploaded_file($tmp_name, "$uploaddir/$name");
                $invoiceScan=htmlspecialchars($_FILES["fileFF2"]["name"]);
                $message ='Файл успешно загружен';
                $ch_id = $_POST['ch_id'];
                $query = "SELECT img_path FROM plants WHERE id = '$ch_id'";
                $result_table = mysqli_query($connect, $query);
                $row1 = mysqli_fetch_array($result_table);
                $path = $_SERVER['DOCUMENT_ROOT'] . '/LR7/images/catalog_img/'.$row1[0];
                unlink($path);
                $fileName = htmlspecialchars($_FILES["fileFF2"]["name"]);
                $query = "UPDATE plants SET img_path = '$fileName', name = '$plantName', id_field = '$id', description = '$plantDescription', cost = '$plantCost' WHERE id = '$ch_id'";
                $result_table = mysqli_query($connect, $query);
            }
            else
            {
                $ch_id = $_GET['ch_id'];
                $query = "SELECT img_path FROM plants WHERE id = '$ch_id'";
                $result_table = mysqli_query($connect, $query);
                $row1 = mysqli_fetch_array($result_table);
                $query = " UPDATE plants SET img_path = '$row1[0]', name = '$plantName', id_field = '$id', description = '$plantDescription', cost = '$plantCost' WHERE id = '$ch_id'";
                $result_table = mysqli_query($connect, $query);
            }
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
            //$query = "INSERT INTO `plants`(id, img_path, name, id_field, description, cost) VALUES('$planid','$img_path','$plantName','$fieldId','$plantDescription','$plantCost');";
            $query = "INSERT INTO `plants`(img_path, name, id_field, description, cost) VALUES('$img_path','$plantName','$fieldId','$plantDescription','$plantCost');";

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

        public static function del(int $del_id)
        {
            $hostname = "localhost";
            $username = "root";
            $password = "root";
            $databaseName = "Plants";
            $connect = mysqli_connect($hostname, $username, $password, $databaseName);
            $query = "SELECT img_path FROM plants WHERE id = '$del_id'";
            $result_table = mysqli_query($connect, $query);
            $row1 = mysqli_fetch_array($result_table);
            $path = $_SERVER['DOCUMENT_ROOT'] . '/LR7/images/catalog_img/'.$row1[0];
            unlink($path);
            $query ="DELETE FROM plants WHERE id = '$del_id'";

            $update_table = mysqli_query($connect, $query);
            if($update_table != FALSE)
            {
                $message="Запись успешно удалена";
            }
            else
            {
                $message="Ошибка удаления записи";
            }

            return $message;
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<body>
    <?php include "header.php" ?>
    <h2 style="text-align: center; margin-top: 20px; color: rebeccapurple">ЖЕЕЕЕСТЬ ФТООООО работа с БД(добавление/удаление/изменение записей) WTF РАБОТАЕТ???</h2>
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
            <div class="alll" style="background-color: plum; border-radius: 10px">
            <div class="addPlant">
                <h3 style="text-align: center">Добавить новое растение в список</h3>
                <div class="row">
                    <form class="col-md-12">
                        <label class="col-md-3" for="fileFF">Прикрепить фото:</label>
                        <!--<label class="col-md-1" for="planid">id:</label>-->
                        <label class="col-md-2" for="plName">Название:</label>
                        <label class="col-md-2" for="fieldName">Поле:</label>
                        <label class="col-md-3" for="plDescription">Описание:</label>
                        <label class="col-md-1" for="plCost">Цена:</label>
                    </form>
                </div>
                <div class="row">
                    <form class="col-md-12" enctype="multipart/form-data" method="post" id="feedback-form">
                        <input class="col-md-3" required="required" type="file" name="fileFF" multiple id="fileFF">
                        <!--<input class="col-md-1" required="required" type="number" name="planid" id="planid" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">-->
                        <input class="col-md-2" required="required" type="text" name="plName" id="plName" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                        <select class="select" name="select_id" style="width: 180px; border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                            <?php
                            while ($row = mysqli_fetch_array($fId))
                            {
                                echo "<option value='".$row['id']."'>".$row['field_name']."</option>";
                            }
                            $selected_id = htmlspecialchars(strip_tags(stripslashes(trim($_GET['select_id']))));
                            ?>
                        </select>
                        <textarea cols="56" name="plDescription" id="messageFF" placeholder="Подробности о растении…" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747"></textarea>
                        <input class="col-md-1" required="required" type="number" name="plCost" id="plCost" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                        <input name="add" value="Добавить" type="submit" id="submitFF" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                    </form>
                </div>
            </div>
            <hr style="border: solid 4px">
            <div class="delPlant">
                <h3 style="text-align: center">Удалить растение из списка</h3>
                <form enctype="multipart/form-data" method="get" id="feedback-form" style="margin-left: 600px">
                    <input required="required" type="number" name="del_id" id="del_id" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                    <input name="del" value="Удалить" type="submit" id="submitFF" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                </form>
            </div>
            <hr style="border: solid 4px">
            <div class="changePlant">
                <h3 style="text-align: center">Изменить растение в списке</h3>
                <div class="row">
                    <form class="col-md-12">
                        <label class="col-md-3" for="fileFF2">Прикрепить фото:</label>
                        <label class="col-md-1" for="planid">id:</label>
                        <label class="col-md-1" for="plName2">Название:</label>
                        <label class="col-md-2" for="fieldName2">Поле:</label>
                        <label class="col-md-3" for="plDescription2">Описание:</label>
                        <label class="col-md-1" for="plCost2">Цена:</label>
                    </form>
                </div>
                <div class="row">
                    <form class="col-md-12" enctype="multipart/form-data" method="post" id="feedback-form">
                        <input class="col-md-3" required="required" type="file" name="fileFF2" multiple id="fileFF2">
                        <input class="col-md-1" required="required" type="number" name="ch_id" id="ch_id" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747"">
                        <input class="col-md-1" required="required" type="text" name="plName2" id="plName2" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                        <select class="select" name="select_id3" style="width: 180px; border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                            <?php
                            while ($row = mysqli_fetch_array($fId2))
                            {
                                echo "<option value='".$row['id']."'>".$row['field_name']."</option>";
                            }
                            $selected_id = htmlspecialchars(strip_tags(stripslashes(trim($_GET['select_id3']))));
                            ?>
                        </select>
                        <textarea cols="56" name="plDescription2" id="messageFF" placeholder="Подробности о растении…" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747"></textarea>
                        <input class="col-md-1" required="required" type="number" name="plCost2" id="plCost2" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                        <input name="change" value="Изменить" type="submit" id="submitFF" style="border-radius: 10px; background-color: #ffd747; border-color: #ffd747">
                    </form>
                </div>
            </div>
            <?php
                echo $message;
            ?>
            </div>
        </div>
    </div>
</body>
</html>
