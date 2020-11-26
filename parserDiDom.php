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
use DiDom\Document;


$document = new Document ('https://www.voskreseniye.ru/pogert/',true);
$projects = $document->find('.leyka-campaign-list-item.has-thumb');
$i=0;
foreach ($projects as $item=>$project){
    $ProjectDonate[$i] = new Project();
    $ProjectDonate[$i]->id = $i;

    $links = $project->find('.lk-title a');
    foreach ($links as $link){
        $ProjectDonate[$i]->link=$link->attr('href');
    }


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


    $i++;
}

vardump($ProjectDonate);