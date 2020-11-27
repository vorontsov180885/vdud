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
    $Donates[$i] = new DonateOperation();
    $Donates[$i]->id = $i;
    $prices = $donatRow->find('.history__cell.h-amount');
    /*$prices = $donatRow->count('.history__cell.h-amount');*/

    foreach ($prices as $price){
        $str= $price->text();
       $str = str_replace(' ','',$str);
        $str = str_replace('₽','',$str);
        $str=(float)preg_replace('!\s++!u', '', $str);
        $Donates[$i]->price = $str;
    }
    $names = $donatRow->find('.history__cell.h-name');
    foreach ($names as $name){
        $Donates[$i]->name =$name->text();
    }
    $dates = $donatRow->find('.history__cell.h-date');
    foreach ($dates as $date){
        $Donates[$i]->date = $date->text();
    }
    $i++;
}




$out_array = [];
foreach ($Donates as $Donate) {
    $Donate_copy = $Donate;
    unset($Donate_copy->id);
    $key = md5(serialize($Donate_copy));
    $out_array[$key] =$Donate;
}
$Donates = $out_array;

$i=0;
$countDonates = count($Donates);
$sumDonates = 0;
foreach ($Donates as $Donate){
    $Donate->id = $countDonates -$i;
    $sumDonates+=$Donate->price;
    $i++;
}

vardump($sumDonates);
vardump($Donates);

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