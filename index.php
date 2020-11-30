<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.11.2020
 * Time: 8:06
 */
require_once 'funtions/vardump.php';
require_once 'vendor/autoload.php';
require_once 'classes/Project.php';
require_once 'classes/DonateOperation.php';
use DiDom\Document;

$document = new Document ('https://www.voskreseniye.ru/pogert/',true);
$projects = $document->find('.leyka-campaign-list-item.has-thumb');
$i=0;
foreach ($projects as $item=>$project){
    $ProjectDonates[$i] = new Project();
    $ProjectDonates[$i]->id = $i;
    $names = $project->find('h4.lk-title');
    foreach ($names as $name){
        $ProjectDonates[$i]->name=$name->text();
    }
    $descriptions = $project->find('.lk-info p');
    foreach ($descriptions as $description){
        $ProjectDonates[$i]->description = $description->text();
    }
    $textWithPrices = $project->find('.leyka-scale-label');
    foreach ($textWithPrices as $textWithPrice){
        $text = $textWithPrice->text();
        $str = str_replace(' Собрано ','',$text);
        $str = str_replace(' ','',$str);
        $str = str_replace('₽','',$str);
        $prices = explode('из',$str);
        $ProjectDonates[$i]->done_price = (float)$prices[0];
        $ProjectDonates[$i]->required_price = (float)$prices[1];
    }
    $links = $project->find('.lk-title a');
    foreach ($links as $link){
        $ProjectDonates[$i]->link=$link->attr('href');
    }
    $i++;
}

vardump($ProjectDonates);
$j=0;
while ($j<count($ProjectDonates)) {
    $link = $ProjectDonates[$j]->link;
    $document = new Document($link, true);  // создаем ДиДом документ с содержанием страницы  проекта пожертвования
    $donatRows = $document->find('.history__row');  // находим в содержании страницы строки с пожертвованием
    $i = 0;
    foreach ($donatRows as $donatRow) {   // пробегаемся по массиву со строками пожертвований
        $Donates[$i] = new DonateOperation();  // i-ый элемент массива $Donates определяем как объект класса DonateOperation
        $prices = $donatRow->find('.history__cell.h-amount');   //находим содержание с ценой
        foreach ($prices as $price) {                                     // пробегаемся по строкам с ценами, выделяем флоат и заносим его в свойства price
            $str = $price->text();
            $str = str_replace(' ', '', $str);
            $str = str_replace('₽', '', $str);
            $str = (float)preg_replace('!\s++!u', '', $str);
            $Donates[$i]->price = $str;
        }
        $names = $donatRow->find('.history__cell.h-name');  // находим содержания с именем жертвователя
        foreach ($names as $name) {                                   //  пробегаемся по массиву и заносим в свойство объекта $Donates тектовое содержание
            $Donates[$i]->name = $name->text();
        }
        $dates = $donatRow->find('.history__cell.h-date');   // находим содержания с датой пожертвования
        foreach ($dates as $date) {                                    //  заносим даты в свойство объекта $donates
            $Donates[$i]->date = $date->text();
        }
        $Donates[$i]->id = $i;
        $i++;
    }

    // получаем уникальные элементы массива
    if ($j == 0) {
        $countDonates = count($Donates);
        for ($i = 0; $i < $countDonates / 2; $i++) {
            if (($Donates[$i]->price === $Donates[$countDonates / 2 + $i]->price) and ($Donates[$i]->name === $Donates[$countDonates / 2 + $i]->name) and ($Donates[$i]->date === $Donates[$countDonates / 2 + $i]->date)) {
                unset ($Donates[$countDonates / 2 + $i]);
            }
        }
    }
    for ($i = 0; $i < count($Donates); $i++) {
        $Donates[$i]->project_id = $j;
    }
    $DonatesOperations[$j] = $Donates;
    unset($Donates);
    $j++;
}

// vardump($DonatesOperations);
// получили 2 массива: $ProjectDonates и $DonatesOperations

// научимся записывать значения в базу данных

require_once 'connection.php';
try{
    $dbh = new PDO($dsn,$user,$pass);
} catch (PDOException $e){
    print "Подключение не удалось: " . $e->getMessage() . "<br/>";
    die();
}




foreach ($ProjectDonates as $projectDonate) {
    $sql1 = "INSERT into projects (id,name,description,required_price,done_price,link) values ('$projectDonate->id','$projectDonate->name','$projectDonate->description','$projectDonate->required_price','$projectDonate->done_price','$projectDonate->link')";
    $dbh->query($sql1);
}






