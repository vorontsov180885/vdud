<!--
 <meta http-equiv="refresh" content="1" />
-->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<button type="button" onclick="foo()">Click</button>
<script>
    function foo () {
        $.ajax({
            url:"refreshDatabase.php", //the page containing php script
            type: "POST", //request type
            success:function(result){
                alert(result);
            }
        });
    }
</script>

<!--


<script>
    setTimeout(function(){
        window.location.reload(1);
    }, 5000);
</script>


-->

<?php
require_once 'connection.php';
require_once 'funtions/vardump.php';

try{
    $dbh = new PDO($dsn,$user,$pass);
} catch (PDOException $e){
    print "Подключение не удалось: " . $e->getMessage() . "<br/>";
    die();
}


//1
$sql1 = 'SELECT SUM(donates.price) AS \'sum\', COUNT(donates.price) AS \'count\',ROUND(SUM(donates.price)/COUNT(donates.price),2) AS \'averange\'  FROM donates WHERE (donates.`date`<\'2020-11-16\');';
$sql2 = 'SELECT SUM(donates.price) AS \'sum\', COUNT(donates.price) AS \'count\',ROUND(SUM(donates.price)/COUNT(donates.price),2) AS \'averange\'  FROM donates WHERE (donates.`date`>=\'2020-11-16\');';
//vardump($dbh->query($sql));
echo "<table border =1><th></th><th>sum</th><th>count</th><th>averange</th>";
echo "<caption>Общие сборы ночлежки после фильма Дудя</caption>";

foreach ($dbh->query($sql1) as $row) {
    echo "<tr>";
    echo "<td> До выхода фильма </td>";
    echo "<td>". number_format($row['sum'], 2, ',', ' ') . "</td>";
    echo "<td>". number_format($row['count'], 0, ',', ' ')  . "</td>";
    echo "<td>". number_format($row['averange'], 2, ',', ' ')  . "</td>";
    echo "</tr>";

}foreach ($dbh->query($sql2) as $row) {
    echo "<tr>";
    echo "<td> После выхода фильма </td>";
    echo "<td>". number_format($row['sum'], 2, ',', ' ') . "</td>";
    echo "<td>". number_format($row['count'], 0, ',', ' ')  . "</td>";
    echo "<td>". number_format($row['averange'], 2, ',', ' ')  . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br><br>";


//2
$sql = 'SELECT projects.`name` AS \'name\', SUM(donates.price) AS \'sum\', COUNT(donates.price) AS \'count\' FROM donates,projects WHERE ((donates.`date`>=\'2020-11-16\')AND(projects.id=donates.project_id)) GROUP BY projects.`name`;';
//vardump($dbh->query($sql));
echo "<table border =1><th>name</th><th>sum</th><th>count</th>";
echo "<caption>Общие сборы ночлежки после фильма Дудя в разрезе проектов</caption>";
foreach ($dbh->query($sql) as $row) {
    echo "<tr>";
    echo "<td>". $row['name'] . "</td>";
    echo "<td>". number_format($row['sum'], 2, ',', ' ')  . "</td>";
    echo "<td>". number_format($row['count'], 0, ',', ' ')  . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br><br>";


//3

$sql = 'SELECT DATE_FORMAT(donates.`date`, \'%d.%m.%Y\') AS \'date\', SUM(donates.price) AS \'sum\',COUNT(donates.price) AS \'count\',ROUND(SUM(donates.price)/COUNT(donates.price),2) AS \'averange\' FROM donates WHERE donates.`date`>\'2020-10-01\' GROUP BY donates.`date` order BY donates.`date`;';
//vardump($dbh->query($sql));
echo "<table border =1><th>date</th><th>sum</th><th>count</th><th>averange</th>";
echo "<caption>Смотрим динамику пожертвований чуть до и после фильма Дудя</caption>";
foreach ($dbh->query($sql) as $row){
    echo "<tr>";
    echo "<td>". $row['date']. "</td>";
    echo "<td>". number_format($row['sum'], 2, ',', ' ') . "</td>";
    echo "<td>". number_format($row['count'], 0, ',', ' ')  . "</td>";
    echo "<td>". number_format($row['averange'], 2, ',', ' ')  . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</table>";
echo "<br><br>";


//4
$sql = 'SELECT donates.`name` AS \'name\', sum(donates.price) AS \'sum\' FROM donates WHERE (donates.price>=50000) GROUP BY donates.`name` ORDER BY sum(donates.price) desc;';
//vardump($dbh->query($sql));
echo "<table border =1><th>name</th><th>sum</th>";
echo "<caption>максимальные пожертвования</caption>";
foreach ($dbh->query($sql) as $row){
    echo "<tr>";
    echo "<td>". $row['name']. "</td>";
    echo "<td>". number_format($row['sum'], 2, ',', ' ') . "</td>";
    echo "</tr>";
}
echo "</table>";


