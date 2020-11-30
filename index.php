<?php
require_once 'connection.php';
require_once 'funtions/vardump.php';
try{
    $dbh = new PDO($dsn,$user,$pass);
} catch (PDOException $e){
    print "Подключение не удалось: " . $e->getMessage() . "<br/>";
    die();
}

$sql = 'SELECT SUM(donates.price), COUNT(donates.price),ROUND(SUM(donates.price)/COUNT(donates.price),2) FROM donates WHERE (donates.`date`>=\'2020-11-16\');';

vardump($dbh->query($sql));


foreach ($dbh->query($sql) as $row) {
    print $row['SUM(donates.price)'] . "\t";
    print $row['COUNT(donates.price)'] . "\t";
    print $row['ROUND(SUM(donates.price)/COUNT(donates.price),2)'] . "\n";
}


$sql = 'SELECT projects.id, SUM(donates.price), COUNT(donates.price) FROM projects, donates WHERE ((donates.`date`>=\'2020-11-16\')AND(projects.id=donates.project_id)) GROUP BY projects.id;';
vardump($dbh->query($sql));

foreach ($dbh->query($sql) as $row) {
    print $row['projects.id'] . "\t";
    print $row['SUM(donates.price)'] . "\t";
    print $row['COUNT(donates.price)'] . "<br>";
}


$sql = 'SELECT donates.`date`, SUM(donates.price),COUNT(donates.price),ROUND(SUM(donates.price)/COUNT(donates.price),2) AS averange FROM donates WHERE donates.`date`>\'2020-10-01\' GROUP BY donates.`date` order BY donates.`date`;';
vardump($dbh->query($sql));

$sql = 'SELECT donates.`name`, sum(donates.price) FROM donates WHERE (donates.price>=50000) GROUP BY donates.`name` ORDER BY sum(donates.price) desc;';
vardump($dbh->query($sql));

