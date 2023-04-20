<?php
    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "Plants";

    $mysqli = mysqli_connect($hostname, $username, $password, $databaseName);

    if (mysqli_connect_errno())
    {
        echo "Ошибка подключения к Базе Данных";
        exit;
    }

    $nickname = htmlspecialchars($_POST["nickname"]);
    $email = htmlspecialchars($_POST["login"]);
    $surname = htmlspecialchars($_POST["surname"]);
    $name = htmlspecialchars($_POST["name"]);
    $patronymic = htmlspecialchars($_POST["patronymic"]);
    $gender = $_POST["inlineRadioOptions1"];
    //$birthDay = strtotime($_POST['birthday']);
    //$birth = date('d-m-Y', $birthDay);
    $birth = $_POST["birthday"];
    $address = htmlspecialchars($_POST["address"]);
    $interests = htmlspecialchars($_POST["interests"]);
    $vk = htmlspecialchars($_POST["vk-link"]);
    $blood = $_POST["inlineRadioOptions2"];
    $factor = $_POST["RH-factor"];
    $password = htmlspecialchars($_POST["pass"]);
    $hash = password_hash($password , PASSWORD_DEFAULT);
    $passProof = htmlspecialchars($_POST["pass-proof"]);
    $query = mysqli_query($mysqli,"SELECT * FROM users WHERE nickname = '$nickname'");
    $numrows = mysqli_num_rows($query);

    $errors = "";

    function passvalid($str, &$errors)
    {
        if (strlen($str) <= 6)
        {
            $errors = "Длина пароля должна быть больше 6 символов";
            return false;
        }
        if (!preg_match("/(\S*[a-z])/",$str))
        {
            $errors = "Пароль должен содержать маленькие латинские буквы";
            return false;
        }
        if (!preg_match("/(\S*[A-Z])/",$str))
        {
            $errors = "Пароль должен содержать большие латинские буквы";
            return false;
        }
        if (!preg_match("/(\S*\d)/",$str))
        {
            $errors = "Пароль должен содержать цифры";
            return false;
        }
        if (!preg_match("/(\S*\W)/",$str))
        {
            $errors = "Пароль должен содержать спецсимволы";
            return false;
        }
        if (!preg_match("/(\S*_)/",$str))
        {
            $errors = "Пароль должен содержать подчеркивание";
            return false;
        }
        if (!preg_match("/(\S*-)/",$str))
        {
            $errors = "Пароль должен содержать дефис";
            return false;
        }
        if (!preg_match("/(\S* )/",$str))
        {
            $errors = "Пароль должен содержать пробел";
            return false;
        }
        return true;
    }

if(isset($_POST["register"]))
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if (passvalid($password, $errors))
            {
                if ($numrows == 0)
                {
                    if ($password == $passProof)
                    {
                        $sql = ("INSERT INTO users VALUES ('$email', '$hash', '$nickname', '$surname', '$name', 
                                '$patronymic', '$birth', '$address', '$gender', '$interests', '$vk', '$blood', '$factor')");
                        $result = mysqli_query($mysqli, $sql);
                        if ($result)
                        {
                            $errors = "Аккаунт успешно создан";
                            setcookie("name", $username);
                        }
                        else
                        {
                            $errors = "мда";
                        }
                    }
                    else
                    {
                        $errors = "Пароли не совпадают, попробуйте снова";
                    }
                }
                else
                {
                    $errors = "Имя пользователя уже существует";
                }
            }
        }
        else
        {
            $errors = "Указанный email не корректен";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<body>
<?php include "header.php" ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="container-reg col-md-6">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-12 p-4">
                    <div class="reg-body">
                    <h2 class="mb-4" style="text-align:center">Регистрация</h2>
                        <form name="reg-form" method="post" action="">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="nickname">Имя пользователя</label>
                                        <input value="<?php print (isset($_POST['nickname']) ? $_POST['nickname'] : '') ?>" required="required" type="text" id="nickname" name="nickname" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="login">Email</label>
                                        <input value="<?php print (isset($_POST['login']) ? $_POST['login'] : '') ?>" required="required" type="text" id="login" name="login" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="surname">Фамилия</label>
                                        <input value="<?php print (isset($_POST['surname']) ? $_POST['surname'] : '') ?>" required="required" type="text" id="surname" name="surname" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="name">Имя</label>
                                        <input value="<?php print (isset($_POST['name']) ? $_POST['name'] : '') ?>" required="required" type="text" id="name" name="name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="patronymic">Отчество</label>
                                        <input value="<?php print (isset($_POST['patronymic']) ? $_POST['patronymic'] : '') ?>" required="required" type="text" id="patronymic" name="patronymic" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6>Пол: </h6>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions1" id="female" value="female" <?php if ($gender == "female") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="female">Женский</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions1" id="male" value="male" <?php if ($gender == "male") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="male">Мужской</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="birthday">Дата рождения</label>
                                        <input value="<?php print (isset($_POST['birthday']) ? $_POST['birthday'] : '') ?>" required="required" type="date" id="birthday" name="birthday" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="address">Адрес</label>
                                        <input value="<?php print (isset($_POST['address']) ? $_POST['address'] : '') ?>" required="required" type="text" id="address" name="address" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="interests">Интересы</label>
                                        <input value="<?php print (isset($_POST['interests']) ? $_POST['interests'] : '') ?>" required="required" type="text" id="interests" name="interests" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="vk-link">Профиль ВК</label>
                                        <input value="<?php print (isset($_POST['vk-link']) ? $_POST['vk-link'] : '') ?>" required="required" type="text" id="vk-link" name="vk-link" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h6>Группа крови</h6>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="blood1" value="1" <?php if ($blood == "1") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="blood1">I</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="blood2" value="2" <?php if ($blood == "2") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="blood2">II</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="blood3" value="3" <?php if ($blood == "3") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="blood3">III</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions2" id="blood4" value="4" <?php if ($blood == "4") echo 'checked="checked"'; ?>>
                                        <label class="form-check-label" for="blood4">IV</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6>Резус-фактов</h6>
                                    <!--<div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions3" id="positive" value="option1">
                                        <label class="form-check-label" for="positive">Положительный</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions3" id="negative" value="option2">
                                        <label class="form-check-label" for="negative">Отрицательный</label>
                                    </div>-->
                                    <select class="select" name="RH-factor">
                                        <option value="positive" <?php if ($factor == "positive") echo 'selected="selected"'; ?>>Положительный</option>
                                        <option value="negative" <?php if ($factor == "negative") echo 'selected="selected"'; ?>>Отрицательный</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="pass">Пароль</label>
                                        <input value="<?php print (isset($_POST['pass']) ? $_POST['pass'] : '') ?>" required="required" type="password" id="pass" name="pass" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label class="form-label" for="pass-proof">Подтвердите пароль</label>
                                        <input value="<?php print (isset($_POST['pass-proof']) ? $_POST['pass-proof'] : '') ?>" required="required" type="password" id="pass-proof" name="pass-proof" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" name="register" class="btn" value="Зарегистрироваться" style="border-color:orange; border-radius: 15px; background-color: gold">Зарегистрироваться</button>
                            </div>
                        </form>
                        <?php if (!empty($errors)) {echo "<p class='error' style='text-align: center; padding-bottom: 10px; color: red;'>" . "Ошибка: ". $errors . "</p>";} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>