<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

Loader::includeModule("highloadblock");
$APPLICATION->SetTitle("Телефон"); ?>
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
            $getUserId = $USER->GetID();
            $hlblock = HL\HighloadBlockTable::getById(4)->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entityClass = $entity->getDataClass();

            $dayNow = new DateTime();
            $dayNowFormat = $dayNow->format('d.m.Y');

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

            $res = $entityClass::getList([
                'select' => ['*'],
                'filter' => [
                    'UF_USER'   => $getUserId,
                    ">=UF_DATA" => ConvertDateTime($filterFormat, "DD.MM.YYYY") . " 00:00:00",
                    "<=UF_DATA" => ConvertDateTime($dayNowFormat, "DD.MM.YYYY") . " 23:59:59",
                ]
            ]);

            $i = 1;
            foreach ($res as $key => $row) {
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $row['UF_PHONE_NUMBER_ABONENT'] ?></td>
                    <td><?
                        $hlblock1 = HL\HighloadBlockTable::getById(3)->fetch();
                        $entity1 = HL\HighloadBlockTable::compileEntity($hlblock1);
                        $entityClass1 = $entity1->getDataClass();
                        $res1 = $entityClass1::getList([
                            'select' => ['*'],
                        ]);
                        foreach ($res1 as $key => $row1) {
                            if ($row1['ID'] === $row['UF_CITY_CALL']) {
                                echo $row1['UF_CITY'];
                            }
                        }
                        ?></td>
                    <td><?= $row['UF_DATA'] ?></td>
                    <td><?= $row['UF_TIME'] ?></td>
                    <td><?= str_replace('.',',',$row['UF_CALL_COST'])?></td>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
    </div>

    <script>
        $('#saveExel').click(function () {
            $("#table").table2excel({
                name: "list1",
                filename: "Экспорт звонков",
                fileext: ".xls",
                preserveColors: false
            });
        });
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>