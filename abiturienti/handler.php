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
$claim = $_FILES['claim']['tmp_name'];
$passport1_name = $_FILES['pass1']['name'];
$ext_pass1 = pathinfo($passport1_name, PATHINFO_EXTENSION);
$passport2_name = $_FILES['pass2']['name'];
$ext_pass2 = pathinfo($passport2_name, PATHINFO_EXTENSION);
$certificate_name = $_FILES['certificate']['name'];
$ext_cert = pathinfo($certificate_name, PATHINFO_EXTENSION);
$application_name = $_FILES['appOfCer']['name'];
$ext_app = pathinfo($application_name, PATHINFO_EXTENSION);
$claim_name = $_FILES['claim']['name'];
$ext_claim = pathinfo($claim_name, PATHINFO_EXTENSION);
$pip = $prezime . " " . mb_strcut($ime, 1, 2) . "." . " " . mb_strcut($patronim, 1, 2);
$document_root = $_SERVER['DOCUMENT_ROOT'];
$pb = "$document_root/abitura/$pip"; //Путь сохранения изображений, при необходимости - поменять

$pass1Path = "$pb/Паспорт".'.'.$ext_pass1;
$pass2Path = "$pb/Прописка".'.'.$ext_pass2;
$certPath = "$pb/Аттестат".'.'.$ext_cert;
$appPath = "$pb/Приложение к аттестату".'.'.$ext_app;
$claimPath = "$pb/Заявление".'.'.$ext_claim;

define('DB_NAME', 'abiturienti');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

if(isset($_POST['formSubmit'])) { //По нажатию кнопки
    mkdir("$pb"); //Создаётся папка под абитуриента по указанному пути выше
    move_uploaded_file($passport1, $pass1Path); //Загрузка фото паспорта
    move_uploaded_file($passport2, $pass2Path); //Загрузка фото прописки
    move_uploaded_file($certificate, $certPath); //Загрузка фото аттестата
    move_uploaded_file($application, $appPath); //Загрузка фото приложения к аттестату
    move_uploaded_file($claim, $claimPath); //Загрузка заявления

    $conn = new mysqli("localhost", "root", "", "abiturienti");
    if($conn->connect_error){
        die("Ошибка: " . $conn->connect_error);
    }
    $sql = "INSERT INTO abitura (sec_name, name, patronim, city, school, birthday, tel) VALUES ('$prezime', '$ime', '$patronim', '$grad', '$iskola', '$rodjendan', '$mobitel')";
    $sql1 = "INSERT INTO abitura_img (pass_1, pass_2, certificate, application, claim) VALUES ('$pass1Path', '$pass2Path', '$certPath', '$appPath', '$claimPath')";
    if($conn->query($sql) && $conn->query($sql1)){
        echo "Ваше заявление принято к рассмотрению!";
    } else{
        echo "Ошибка: " . $conn->error;
    }
    $conn->close();

    $redis->set("$benutzer", "$benutzer", 3600);
}

// Оформление: ФИО, телефон, школа, город.
// Сессии по IP и блокировка формы заявок на час.
// Данные в базе данных (SQL).

?>