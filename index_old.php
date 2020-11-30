<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.11.2020
 * Time: 15:15
 */
require_once('connection.php');
require_once('classes/DonateOperation.php');
require_once ('funtions/vardump.php');

vardump('123213');

function detectPattern($value){
    if (preg_replace('#[0-9]+\s?([0-9]?)+\s.\s#','price',$value) == 'price'){
        return 'is price';
    }elseif (preg_replace('#\d{2}.\d{2}.\d{4}\s?#','date',$value) == 'date'){
        return 'is date';
    }else
        return 'is name';
}

/*$dbh = new PDO($dsn,$user,$pass);*/

/*$sql1='SELECT * 	FROM projects;';
foreach ($dbh->query($sql1) as $row) {
    print $row['id']." || ";
    print $row['name']." || ";
    print $row['required_price']." || ";
    print $row['done_price']."<br>";
}*/

/*$sql2="INSERT into projects (name,required_price,done_price) values ('четверный проект',444,418)";
$dbh->query($sql2);*/

/*
try {
    $dbh = new PDO($dsn, $user, $pass);
    foreach($dbh->query('SELECT * from projects') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}*/
$i=0;
$sum=0;

foreach (file($filename) as $line_num=>$line){
    $donates[$i] = new DonateOperation();
    if (detectPattern($line) == 'is price') {
        $price=(float)str_replace(' ','',$line);
        $sum+=$price;
    }
    if (detectPattern($line) == 'is name'){
        $name=$line;
    }
    if (detectPattern($line) == 'is date') {
        $donates[$i]->price =$price;
        $donates[$i]->name =$name;
        $donates[$i]->date=$line;
        $i++;
    }
}

vardump($donates);
echo "<br>";

/*$dbh = null;*/
echo "Всего собрано: ".number_format($sum, 0, ',', ' ')." руб.<br>";
echo "Всего пожертвований: ".number_format($i, 0, ',', ' ')." шт.<br>";


