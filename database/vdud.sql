-- общие сборы ночлежки после фильма Дудя
SELECT SUM(donates.price) AS 'sum', COUNT(donates.price) AS 'count',ROUND(SUM(donates.price)/COUNT(donates.price),2) AS 'averange'  FROM donates WHERE (donates.`date`>='2020-11-16');

--  общие сборы ночлежки после фильма Дудя в разрезе проектов
SELECT projects.`name` AS 'name', SUM(donates.price) AS 'sum', COUNT(donates.price) AS 'count' FROM donates,projects WHERE ((donates.`date`>='2020-11-16')AND(projects.id=donates.project_id)) GROUP BY projects.`name`;

-- смотрим динамику пожертвований чуть до и после фильма Дудя
SELECT DATE_FORMAT(donates.`date`, '%d.%m.%Y') AS 'date', SUM(donates.price) AS 'sum',COUNT(donates.price) AS 'count',ROUND(SUM(donates.price)/COUNT(donates.price),2) AS 'averange' FROM donates WHERE donates.`date`>'2020-10-01' GROUP BY donates.`date` order BY donates.`date`;
-- DATE_FORMAT(YOUR_DATE_FIELD, '%d.%m.%Y')
-- максимальные пожертвования
SELECT donates.`name` AS 'name', sum(donates.price) AS 'sum' FROM donates WHERE (donates.price>=50000) GROUP BY donates.`name` ORDER BY sum(donates.price) desc;