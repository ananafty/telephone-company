<?php
use Bitrix\Main\Loader; // класс для загрузки необходимых файлов, классов и модулей
use Bitrix\Highloadblock as HL; //класс для взаимодействие с Highloadblock
Loader::includeModule("highloadblock");


function checkSending($UF_USER,$UF_USER_CALL,$UF_DATA) {
    $hlblock = HL\HighloadBlockTable::getById(4)->fetch(); // делаем запрос к HighloadBlock
    $entity = HL\HighloadBlockTable::compileEntity($hlblock); // инициализировать класс сущности
    $entity_data_class = $entity->getDataClass(); // берем данные

    $res = $entity_data_class::getList([ // берем все данные HighloadBlock тарифов
        'select' => ['*'],
        'filter' => [
            "UF_USER"=>$UF_USER,
            "UF_USER_CALL"=>$UF_USER_CALL,
            "UF_DATA"=>$UF_DATA
        ]
    ]);

    while ($row = $res->fetch()) { // бежим по массиву тарифов
        if ($row === null){
            echo 'error';
        } else {
            echo 'correctly';
        }
    }

}