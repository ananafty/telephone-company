<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
Loader::includeModule("highloadblock");

$date = new DateTime();
$time = $date->format('h');
$tarif = null;

$hlblock = HL\HighloadBlockTable::getById(4)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$user = CUser::GetByID('' . $_POST['user'] . '');
$callUser = CUser::GetByID('' . $_POST['callUser'] . '');
$userArray = $user->Fetch();
$callUserArray = $callUser->Fetch();

$hlblock1 = HL\HighloadBlockTable::getById(2)->fetch();
$entity1 = HL\HighloadBlockTable::compileEntity($hlblock1);
$entityClass1 = $entity1->getDataClass();

$res = $entityClass1::getList(array(
    'select' => array('*'),
   ));

while ($row = $res->fetch()) {
    if ($row['ID'] === $userArray['UF_TARIF_ABONENTA']) {
        if ('18' >= $time || $time <= '06'){
            $tarif = $row['UF_NIGHT'];
        } else {
            $tarif = $row['UF_DAY'];
        }
    }
}

$timeCall = date_parse(''.$_POST['time'].'');
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

$result = $entity_data_class::add($data);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");