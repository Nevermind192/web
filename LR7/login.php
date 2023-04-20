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

    if(isset($_POST["logIn"]))
    {
        $login = htmlspecialchars($_POST["login"]);
        $password = htmlspecialchars($_POST["password"]);
        $hash = password_hash($password , PASSWORD_DEFAULT);
        $query =mysqli_query($mysqli,"SELECT * FROM users WHERE nickname = '$login'");
        $numrows=mysqli_num_rows($query);
        $query2 =mysqli_query($mysqli,"SELECT password FROM users WHERE nickname = '$login'");
        $row = mysqli_fetch_assoc($query2);
        $hashedpassword = $row["password"];
        if($numrows==0)
        {
            $errors = "Имя пользователя введено неверно";
        }
        else
        {
            if (password_verify($password, $hashedpassword))
            {
                $errors = "Авторизация прошла успешно";
                setcookie("name", $login);
                header('Location: login.php');
            }
            else
            {
                $errors = "Пароль введен неверно";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <?php include "header.php" ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="container-log col-md-4">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-12 p-4">
                        <div class="log-body">
                            <form name="login-form" method="post">
                                <h2 class="mb-4" style="text-align:center">Вход</h2>
                                <p class="mb-4" style="text-align:center">Введите Логин и пароль</p>
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="login">Логин</label>
                                    <input type="text" name="login" required="required" id="login" class="form-control form-control-lg" />
                                </div>
                                <div class="form-outline mb-5">
                                    <label class="form-label" for="password">Пароль</label>
                                    <input type="password" name="password" required="required" id="password" class="form-control form-control-lg" />
                                </div>
                                <div class="text-center mb-5">
                                    <button type="submit" name="logIn" class="btn" style="border-color:orange; border-radius: 15px; width:170px; background-color: gold">Войти</button>
                                </div>
                            </form>
                            <?php if (!empty($errors)) {echo "<p class='error' style='text-align: center; padding-bottom: 10px; transform: translate(0, -25px); color: red;'>" . " ". $errors . "</p>";} ?>
                            <div class="text-center">
                                <p class="">Нет аккаунта? <a href='register.php' class="text-black fw-bold">Зарегистрироваться</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>