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
    $ProjectDonate[$i] = new Project();
    $ProjectDonate[$i]->id = $i;
    $names = $project->find('h4.lk-title');
    foreach ($names as $name){
        $ProjectDonate[$i]->name=$name->text();
    }
    $descriptions = $project->find('.lk-info p');
    foreach ($descriptions as $description){
        $ProjectDonate[$i]->description = $description->text();
    }
    $textWithPrices = $project->find('.leyka-scale-label');
    foreach ($textWithPrices as $textWithPrice){
        $text = $textWithPrice->text();
        $str = str_replace(' Собрано ','',$text);
        $str = str_replace(' ','',$str);
        $str = str_replace('₽','',$str);
        $prices = explode('из',$str);
        $ProjectDonate[$i]->done_price = (float)$prices[0];
        $ProjectDonate[$i]->required_price = (float)$prices[1];
    }
    $links = $project->find('.lk-title a');
    foreach ($links as $link){
        $ProjectDonate[$i]->link=$link->attr('href');
    }
    $i++;
}
/*vardump($ProjectDonate);
vardump(count($ProjectDonate));*/
    $j=2;
    $link = $ProjectDonate[$j]->link;
    vardump($link);
    $document = new Document($link, true);  // создаем ДиДом документ с содержанием страницы  проекта пожертвования
    $donatRows = $document->find('.history__row');  // находим в содержании страницы строки с пожертвованием
    $i = 0;
    foreach ($donatRows as $donatRow) {   // пробегаемся по массиву со строками пожертвований
        $Donates[$i] = new DonateOperation();  // i-ый элемент массива $Donates определяем как объект класса DonateOperation 
        $Donates[$i]->id = $i;                      //свойству id объекта присваиваем значение $i  
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
        $i++;
    }
    $out_array = [];                                                    // чистим массив с объектами от дублей
    foreach ($Donates as $Donate) {
        $Donate_copy = $Donate;
        unset($Donate_copy->id);
        $key = md5(serialize($Donate_copy));
        $out_array[$key] = $Donate;
    }
    $Donates = $out_array;                                              // получаем массив с уникальными донатами
                                                                       // переходим к парсингу следующей страницы

$countDonates = count($Donates);
vardump($countDonates);



