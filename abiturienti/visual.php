<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Просмотр данных абитуриента</title>
			<style>
			td {
				text-align: center;
			}
		</style>
</head>
<body>
	<?php
	define('DB_NAME', 'abiturienti');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_HOST', 'localhost');
	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($connection->connect_error) {
	die ('Connection error: '.$connection->connect_error);
}
$sql = "SELECT * FROM abitura INNER JOIN abitura_img ON abitura.id = abitura_img.id";
$result = $connection->query($sql);
echo "<table border ='1'>";
echo "<tr>";
echo "<th>"."№"."</td>"."<th>"."Фамилия"."</th>"."<th>"."Имя"."</th>"."<th>"."Отчество"."</th>"."<th>"."Город"."</th>"."<th>"."Школа"."</th>"."<th>"."Дата рождения"."</th>"."<th>"."Телефон"."</th>"."<th>"."Паспорт"."</th>"."<th>"."Прописка"."</th>"."<th>"."Аттестат"."</th>"."<th>"."Приложение к аттестату"."</th>"."<th>"."Заявление (1 стр.)"."</th>"."<th>"."Заявление (2 стр.)"."</th>";
echo "</tr>";
if ($result->num_rows > 0) {
	while ($row1 = $result->fetch_assoc()) {
		echo "<tr>";
		echo "<td>".$row1['id']."</td>";
		echo "<td>".$row1['sec_name']."</td>";
		echo "<td>".$row1['name']."</td>";
		echo "<td>".$row1['patronim']."</td>";
		echo "<td>".$row1['city']."</td>";
		echo "<td>".$row1['school']."</td>";
		echo "<td>".$row1['birthday']."</td>";
		echo "<td>".$row1['tel']."</td>";
		echo "<td>".'<a href="'.$row1['pass_1'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "<td>".'<a href="'.$row1['pass_2'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "<td>".'<a href="'.$row1['certificate'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "<td>".'<a href="'.$row1['application'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "<td>".'<a href="'.$row1['claim_1'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "<td>".'<a href="'.$row1['claim_2'].'" target=\\"_blank\\">Смотреть</a>'."</td>";
		echo "</tr>";
	}
} else {
	echo "<p>0 result. The 'abitura' must be empty.</p>";
}
echo "</table>";
	?>
</body>
</html>