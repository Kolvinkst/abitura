<?php
$redis = new Redis();
$redis->connect('localhost',6379);
if(!$redis){
    echo "Подключите Редис!";
}
$benutzer = (string) $_SERVER['REMOTE_ADDR'];
if ($redis->exists("$benutzer"))
{
    die("Вы уже отправляли форму. Нужно подождать час с момента последней отправки");
}
$ime = $_POST['name'];
$prezime = $_POST['secondName'];
$patronim = $_POST['patronymic'];
$rodjendan = $_POST['dateOfBirth'];
$mobitel = $_POST['phone'];
$grad = $_POST['city'];
$iskola = $_POST['school'];
$passport1 = $_FILES['pass1']['tmp_name'];
$passport2 = $_FILES['pass2']['tmp_name'];
$certificate = $_FILES['certificate']['tmp_name'];
$application = $_FILES['appOfCer']['tmp_name'];
$claim1 = $_FILES['claim1']['tmp_name'];
$claim2 = $_FILES['claim2']['tmp_name'];
$passport1_name = $_FILES['pass1']['name'];
$passport2_name = $_FILES['pass2']['name'];
$certificate_name = $_FILES['certificate']['name'];
$application_name = $_FILES['appOfCer']['name'];
$claim1_name = $_FILES['claim1']['name'];
$claim2_name = $_FILES['claim2']['name'];
$data = "Фамилия: " . "$prezime\r\n" . 
"Имя: " . "$ime\r\n" . 
"Отчество: " . "$patronim\r\n" . 
"Город: " . "$grad\r\n" .
"Школа: " . "$iskola\r\n" .
"Дата рождения: " . "$rodjendan\r\n" .
"Телефон: " . "$mobitel";
$pip = $prezime . " " . mb_strcut($ime, 1, 2) . "." . " " . mb_strcut($patronim, 1, 2); //Имя папки, заводящейся под абитуриента (Фамилия и инициалы)
$document_root = $_SERVER['DOCUMENT_ROOT']; //Переменная, в которую помещается путь к корню сервера (localhost обычно)
$pb = "$document_root/abitura/$pip/"; //Переменная, в которую помещается путь к папке под абитуриента, при необходимости нужно изменить
if(isset($_POST['formSubmit'])) { //По нажатию кнопки
    mkdir("$pb"); //Создаётся папка под абитуриента по указанному пути выше
    $pd = fopen("$pb/Данные.txt", 'ab'); //Создаётся и открывается текстовый файл с данными об абитуриенте
    flock($pd, LOCK_EX);
    fwrite($pd, $data, strlen($data)); //Запись данных об абитуриенте, которые он ввёл (ФИО, телефон)
    flock($pd, LOCK_UN);
    fclose($pd);
    move_uploaded_file($passport1, $pb.$passport1_name); //Загрузка фото паспорта
    move_uploaded_file($passport2, $pb.$passport2_name); //Загрузка фото прописки
    move_uploaded_file($certificate, $pb.$certificate_name); //Загрузка фото аттестата
    move_uploaded_file($application, $pb.$application_name); //Загрузка фото приложения к аттестату
    move_uploaded_file($claim1, $pb.$claim1_name); //Загрузка заявления (1 стр.)
    move_uploaded_file($claim2, $pb.$claim2_name); //Загрузка заявления (2 стр.)
    echo("Ваше заявление принято к рассмотрению!"); //Вывод на экране после выполнения программы
    $redis->set("$benutzer", "$benutzer", 3600);
}

// Оформление: ФИО, телефон, школа, город.
// Сессии по IP и блокировка формы заявок на час.
// Данные в базе данных (SQL).

?>