<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Loader; // класс для загрузки необходимых файлов, классов и модулей
use Bitrix\Highloadblock as HL; //класс для взаимодействие с Highloadblock

Loader::includeModule("highloadblock");
$APPLICATION->SetTitle("Телефон"); // вывод заголовка странцы?>
    <div class="container">
        <h3 style="margin: 40px 0 40px 0">Фильтр</h3>
        <div class="btn-group" role="group" aria-label="Basic example" style="margin: 0 0 40px 0">
            <a href="?filter=week" type="button" class="btn btn-secondary">Неделя</a>
            <a href="?filter=month" type="button" class="btn btn-secondary">Месяц</a>
            <a href="?filter=year" type="button" class="btn btn-secondary">Год</a>
            <a href="?filter=all" type="button" class="btn btn-secondary">За все время</a>
            <button id="saveExel" type="button" class="btn btn-primary">Скачать</button>
        </div>
        <table id="table" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Номер телефона абонента которому звонили</th>
                    <th scope="col">Город в который осуществляется телефонный звонок абонента</th>
                    <th scope="col">Время звонка</th>
                    <th scope="col">Длительность звонка</th>
                    <th scope="col">Стоимость звонка абонент</th>
                </tr>
            </thead>
            <tbody>
            <?
            $getUserId = $USER->GetID();;// запрос данных авторизованного пользователя
            $hlblock = HL\HighloadBlockTable::getById(4)->fetch(); // делаем запрос к HighloadBlock
            $entity = HL\HighloadBlockTable::compileEntity($hlblock); // инициализировать класс сущности
            $entityClass = $entity->getDataClass(); // берем данные

            $dayNow = new DateTime(); // берем время сервера
            $dayNowFormat = $dayNow->format('d.m.Y'); // форматируем на вывод дннь месяц год

            if ($_GET['filter'] === 'week') {
                $filter = new DateTime('-7 day');
            }
            if ($_GET['filter'] === 'month') {
                $filter = new DateTime('-1 month');
            }
            if ($_GET['filter'] === 'year') {
                $filter = new DateTime('-1 year');
            }
            if ($_GET['filter'] === 'all' || $_GET['filter'] === null) {
                $filter = new DateTime('10.10.1990');
            }

            $filterFormat = $filter->format('d.m.Y');

            $res = $entityClass::getList([ // запрос на данные из HighloadBlock отчет
                'select' => ['*'],
                'filter' => [
                    'UF_USER'   => $getUserId, // id пользователя
                    ">=UF_DATA" => ConvertDateTime($filterFormat, "DD.MM.YYYY") . " 00:00:00", // диапазон времени
                    "<=UF_DATA" => ConvertDateTime($dayNowFormat, "DD.MM.YYYY") . " 23:59:59",
                ]
            ]);

            $i = 1; // просто для того чтобы в таблицу вывести нумерацию строк
            foreach ($res as $key => $row) { // бежим по массиву с данными отчета
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $row['UF_PHONE_NUMBER_ABONENT'] // номер абонента кторуму звонили?></td>
                    <td><?
                        $hlblock1 = HL\HighloadBlockTable::getById(3)->fetch(); // делаем запрос к HighloadBlock
                        $entity1 = HL\HighloadBlockTable::compileEntity($hlblock1);  // инициализировать класс сущности
                        $entityClass1 = $entity1->getDataClass(); // берем данные
                        $res1 = $entityClass1::getList([
                            'select' => ['*'],
                        ]);
                        foreach ($res1 as $key => $row1) { // бежим по массиву городов
                            if ($row1['ID'] === $row['UF_CITY_CALL']) { // сравниваем ид города и его значения
                                echo $row1['UF_CITY']; // вывод города
                            }
                        }
                        ?></td>
                    <td><?= $row['UF_DATA'] // дата звонка?></td>
                    <td><?= $row['UF_TIME'] //время разговора?></td>
                    <td><?= str_replace('.',',',$row['UF_CALL_COST']) // вывод стоимости звонка?></td>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
    </div>

    <script>
        $('#saveExel').click(function () { // обработчик событи по клику по кнопке saveExel
            $("#table").table2excel({ // берем данные из таблицы table и запускаем функцию плагина
                name: "list1",
                filename: "Экспорт звонков",
                fileext: ".xls",
                preserveColors: false
            });
        });
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>