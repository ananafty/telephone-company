<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); // подключение ядра битрикс
use Bitrix\Main\Loader; // класс для загрузки необходимых файлов, классов и модулей
use Bitrix\Highloadblock as HL; //класс для взаимодействие с Highloadblock
Loader::includeModule("highloadblock");

$date = new DateTime(); // берем время сервера
$time = $date->format('h'); // форматируем на вывод только часов
$tarif = null; // заранее объявленная переменная

$hlblock = HL\HighloadBlockTable::getById(4)->fetch(); // делаем запрос к HighloadBlock
$entity = HL\HighloadBlockTable::compileEntity($hlblock); // инициализировать класс сущности
$entity_data_class = $entity->getDataClass(); // берем данные

$user = CUser::GetByID('' . $_POST['user'] . ''); // запрос данных авторизованного пользователя
$callUser = CUser::GetByID('' . $_POST['callUser'] . ''); // запрос данных пользователя которуму звонили
$userArray = $user->Fetch(); // бежим по массиву данных авторизованного пользователя
$callUserArray = $callUser->Fetch(); // бежим по массиву данных пользователя которуму звонили

$hlblock1 = HL\HighloadBlockTable::getById(2)->fetch(); // делаем запрос к HighloadBlock
$entity1 = HL\HighloadBlockTable::compileEntity($hlblock1); // инициализировать класс сущности
$entityClass1 = $entity1->getDataClass(); // берем данные

$res = $entityClass1::getList(array( // берем все данные HighloadBlock тарифов
    'select' => array('*'),
   ));

while ($row = $res->fetch()) { // бежим по массиву тарифов
    if ($row['ID'] === $userArray['UF_TARIF_ABONENTA']) { // проверка совпадения тарифа с тарифом пользователея
        if ('18' >= $time || $time <= '06'){ // проверка ночного времени
            $tarif = $row['UF_NIGHT']; // присваевам переменной ночного тарифа
        } else {
            $tarif = $row['UF_DAY']; // присваеваем переменной дневного тарифа
        }
    }
}

$timeCall = date_parse(''.$_POST['time'].''); // превращаем время в массив
$summMoney = (($timeCall['hour'] * 60) + $timeCall['minute']) * $tarif;

// Массив полей для добавления
$data = array(
    "UF_USER"=>$userArray['ID'],
    "UF_TELEPHONE_NUMBER"=>$userArray['UF_PHONE_NUMBER'],
    "UF_CITY_FROM_CALLED"=>$userArray['UF_CITY'],
    "UF_USER_CALL"=>$callUserArray['ID'],
    "UF_PHONE_NUMBER_ABONENT"=>$callUserArray['UF_PHONE_NUMBER'],
    "UF_CITY_CALL"=>$callUserArray['UF_CITY'],
    "UF_DATA"=>$date->format('d.m.Y H:i:s'),
    "UF_TIME"=>$_POST['time'],
    "UF_CALL_COST"=>$summMoney,
);

$result = $entity_data_class::add($data); // отправляем данные в HighloadBlock

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); // закрытие ядра битрикс