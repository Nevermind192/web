<?php
    session_start();
    $text = ($_GET['content']);
    $result = '<meta charset="UTF-8">';
    $text1 = " ";
    $action = false;
    $action11 = false;

    if($_GET["preset"] == "1")
    {
        $text = file_get_contents("https://ru.wikipedia.org/wiki/Киноринхи");
    }
    if($_GET["preset"] == "2")
    {
        $text = file_get_contents("https://mishka-knizhka.ru/skazki-dlay-detey/zarubezhnye-skazochniki/skazki-alana-milna/vinni-puh-i-vse-vse-vse/#glava-pervaya-v-kotoroj-my-znakomimsya-s-vinni-puhom-i-neskolkimi-pchy");
    }

    if(isset($_POST['ex4']) OR isset($_POST['ex7']) OR isset($_POST['ex11']) OR isset($_POST['ex18']))
    {
        $text = $_POST['content'];
        if(isset($_POST['ex4']))
        {
            $action11 = false;
            $action = true;
            $result .= Ex4($text);
        }
        if(isset($_POST['ex7']))
        {
            $action11 = false;
            $action = true;
            $result .= Ex7($text);
        }
        if(isset($_POST['ex11']))
        {
            $action = true;
            $action11 = true;
            $result .= Ex11($text);
            $text1 .= Ex11dop($text);
        }
        if(isset($_POST['ex18']))
        {
            $action11 = false;
            $action = true;
            $result .= Ex18($text);
        }
    }

    function Ex4($text)
    {
        /*$patterns = array('кто то', 'где то', 'то то', 'вот вот', 'из за', 'из под');
        $replacements = array('кто-то', 'где-то', 'то-то', 'вот-вот', 'из-за', 'из-под');
        $result = str_replace($patterns, $replacements, $text);*/


        $temp = preg_match_all('~<p.*?>(.+?)</p>~is', $text, $arr);
        if($temp) {
            foreach ($arr[1] as $match) {
                $patterns = array('о', 'а');
                $replacements = array('а', 'о');
                $result = str_replace($patterns, $replacements, $match);
            }
        }

        return $result;
    }

    function Ex7($text)
    {
        $result = preg_replace(array('/!+/', '~[.]{3, }~'), array('!!!', '...'), $text);

        return $result;
    }

    function Ex11($text)
    {
        $result = '<nav class="content-header">';
        $cou1 = 0;
        $cou2 = 0;
        $cou3 = 0;
        $all = preg_match_all("~<h[1-3].*?>(.+?)</h[1-3]>~", $text, $levels);

        foreach ($levels[0] as $el)
        {
            $lvl1 = preg_match("~<h1.*?>(.+?)</h1>~", $el, $level1);
            $lvl2 = preg_match("~<h2.*?>(.+?)</h2>~", $el, $level2);
            $lvl3 = preg_match("~<h3.*?>(.+?)</h3>~", $el, $level3);

            if($lvl1)
            {
                $level = 1;
                if($cou1 == 0)
                {
                    $result .= "<ul>";
                }
                if($cou2 > 0)
                {
                    $result .= "</ul>";
                    $cou2 = 0;
                }
                if($cou3 > 0)
                {
                    $result .= "</ul>";
                    $cou3 = 0;
                }
                $cou1 += 1;
            }
            if($lvl2)
            {
                $level = 2;
                if(($cou2 == 0) && ($cou3 == 0))
                {
                    $result .= "<ul>";
                }
                if(($cou2 == 0) && ($cou3 > 0))
                {
                    $result .= "</ul>";
                    $cou3 = 0;
                }
                if(($cou2 > 0) && ($cou3 > 0))
                {
                    $result .= "</ul>";
                    $cou3 = 0;
                }
                $cou2 += 1;
            }
            if($lvl3)
            {
                $level = 3;
                if($cou3 == 0)
                {
                    $result .= "<ul>";
                }
                if(($cou1 > 0) && ($cou2 == 0) && ($cou3 == 0))
                {
                    $result .= "<ul>";
                }
                $cou3 += 1;
            }

            $result = $result . '<li>' . '<a class="content-header-' . $level . '" href="#title-' . $cou_title . '">' . mb_strimwidth(strip_tags($el), 0, 50, "...") . '</a><br />' . '</li>';
            $cou_title += 1;
        }

        return $result . '</nav>';
    }

    $title_counts = 0;
    function Ex11dop($text)
    {
        /*$cou =-1;
        $text = stripslashes($text);
        preg_match_all("/<h1.*?>(.*?)<\/h1>/i", $text, $items);
        if (!empty($items[0])) {
            foreach ($items[0] as $i => $row) {
                $text = str_replace($row, '<h1 id="title-' . ++$cou . '"></h1>' . $row, $text);
            }
        }
        preg_match_all("/<h2.*?>(.*?)<\/h2>/i", $text, $items);
        if (!empty($items[0])) {
            foreach ($items[0] as $i => $row) {
                $text = str_replace($row, '<h2 id="title-' . ++$cou . '"></h2>' . $row, $text);
            }
        }
        preg_match_all("/<h3.*?>(.*?)<\/h3>/i", $text, $items);
        if (!empty($items[0])) {
            foreach ($items[0] as $i => $row) {
                $text = str_replace($row, '<h3 id="title-' . ++$cou . '"></h3>' . $row, $text);
            }
        }
        return $text;*/
        //$cou=0;
        /*preg_match_all("/<h1.*?>/i", $text, $items1);
        preg_match_all("/<h2.*?>/i", $text, $items2);
        preg_match_all("/<h3.*?>/i", $text, $items3);
        foreach ($items1[0] as $i => $row1) {
            //$text = str_replace($s, $r, $text);
            $text = str_replace($row1, '<h1 id="title-' . ++$i . '">', $text);
        }
        foreach ($items2[0] as $s => $row2) {
            //$text = str_replace($s, $r, $text);
            $text = str_replace($row2, '<h2 id="title-' . ++$s . '">', $text);
        }
        foreach ($items3[0] as $t => $row3) {
            //$text = str_replace($s, $r, $text);
            $text = str_replace($row3, '<h3 id="title-' . ++$t . '">', $text);
        }
        return $text;*/
        preg_match_all("/<h[1-3].*?>(.*?)<\/h[1-3]>/i", $text, $items);
        foreach ($items[0] as $i => $row)
        {
            $lvl1 = preg_match("/<h1.*?>(.*?)<\/h1>/i", $row, $level1);
            $lvl2 = preg_match("/<h2.*?>(.*?)<\/h2>/i", $row, $level2);
            $lvl3 = preg_match("/<h3.*?>(.*?)<\/h3>/i", $row, $level3);
            if($lvl1)
            {
                $text = str_replace($row, '<h1 id="title-' . $cou . '">' .strip_tags($row, '<span> <a>').'</h1>', $text);
            }
            if($lvl2)
            {
                $text = str_replace($row, '<h2 id="title-' . $cou . '">' .strip_tags($row, '<span> <a>').'</h2>', $text);
            }
            if($lvl3)
            {
                $text = str_replace($row, '<h3 id="title-' . $cou . '">' .strip_tags($row, '<span> <a>').'</h3>', $text);
            }
            $cou++;
        }
        return $text;
    }

    function Ex18($text)
    {
        $temp = preg_match_all('~<p.*?>(.+?)</p>~is', $text, $arr);
        if($temp)
        {
            foreach($arr[1] as $match)
            {
                $tokens = explode(' ', strip_tags($match, '<p>'));
                //print_r($tokens);
                for($i = 0; $i < count($tokens); $i++)
                {
                    //$cou = str_word_count($match, 0, $tokens[$i]);
                    $cou = mb_substr_count($match, trim($tokens[$i]));
                    if ($cou > 2)
                    {
                        $result1 = $result1 . " " . "<mark>" . $tokens[$i] . "</mark>" . " ";
                    }
                    else
                    {
                        $result1 = $result1 . " " . $tokens[$i] . " ";
                    }
                }
            }
            $patterns = array('о', 'а');
            $replacements = array('a', 'о');
            $result = str_replace($patterns, $replacements, $result1);
        }

        return $result;
    }
?>

<!DOCTYPE html>
<html lang="en">
<body>
<?php include "header.php" ?>
<div class="container">
    <form method="POST">
        <div class="row">
            <div class="col-md-10">
                    <textarea name='content' style="margin-top: 20px; height: 200px;" class="form-control"> <?php echo $text ?> </textarea>
            </div>
            <div class="btn col-md-1">
                <input name="ex4" style="transform: translate(0px, 35px)" type="submit" value=' Задание 4 '/>
                <input name="ex7" style="transform: translate(0px, 45px)" type="submit" value=' Задание 7 '/>
                <input name="ex11" style="transform: translate(0px, 55px)" type="submit" value='Задание 11'/>
                <input name="ex18" style="transform: translate(0px, 65px)" type="submit" value='Задание 18'/>
            </div>
        </div>
    </form>
    <?php if($action) {?>
    <div class="row">
        <div class="col-md-12">
            <hr/>
                <div class="res">
                    <?php echo $result; ?>
                </div>
            <hr/>
        </div>
    </div>
    <div class="main_text">
        <?php if($action11) echo $text1; else echo $text;?>
    </div>
    <?php }?>
</div>
</body>
</html>