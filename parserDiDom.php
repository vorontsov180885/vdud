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

$document = new Document('https://www.voskreseniye.ru/campaign/stroitelstvo-kapitalnogo-zdaniya-nochlezhki/',true);
$donatRows = $document->find('.history__row');
$i=0;
foreach ($donatRows as $donatRow){
    $Donate[$i] = new DonateOperation();
    $Donate[$i]->id = $i;
    $prices = $donatRow->find('.history__cell.h-amount');
    foreach ($prices as $price){
        $a =$price->text();

        $Donate[$i]->price =$price->text();
    }
    $names = $donatRow->find('.history__cell.h-name');
    foreach ($names as $name){
        $Donate[$i]->name =$name->text();
    }
    $dates = $donatRow->find('.history__cell.h-date');
    foreach ($dates as $date){
        $Donate[$i]->date =$date->text();
    }
    $i++;
}
echo $i;

vardump($Donate[0]);




die;


//vardump($Donate);



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

vardump($ProjectDonate);