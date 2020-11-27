<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); // подключение шапки
$APPLICATION->SetTitle("Телефон"); // вывод заголовка странцы?>

    <div class="container">
        <?
        $order = ['sort' => 'login ']; //переменная сортировка по логину
        $tmp = 'sort'; // переменная для включения сортировки
        $rsUsers = CUser::GetList($order, $tmp); // запрос на список всех пользователей с сортировкой по логину
        $getUser = CUser::GetByID($USER->GetID()); // запрос данных авторизованного пользователя
        $foreachGetUser = $getUser->Fetch(); // бежим по массиву данных авторизованного пользователя
        ?>
        <table class="table">
            <tbody>
            <?
            while ($arUser = $rsUsers->Fetch()) { // бежим по массиву всех пользователей
                $rsUser = CUser::GetByID('' . $arUser['ID'] . ''); // берем данные пользователя из массива
                ?>
                <tr>
                    <?
                    while ($arUser = $rsUser->Fetch()) { // бежим по массиву данных пользователя
                        if ($foreachGetUser['ID'] !== $arUser['ID']) { // делаем проверку на то чтобы авторизованный пользователь не выводился в списке контактов
                            ?>
                            <td><?= $arUser['LAST_NAME'] //вывод фамилии пользователя?></td>
                            <td><?= $arUser['NAME'] //вывод имени пользователя?></td>
                            <td><?= $arUser['SECOND_NAME'] //вывод отчества пользователя?></td>
                            <td><?= $arUser['UF_PHONE_NUMBER'] //вывод номер телефона пользователя?></td>
                            <td>
                                <button type="button"
                                        class="btn btn-primary btn-contacts"
                                        data-id-user="<?=$foreachGetUser['ID'] //записывем в атрибут ид авторизованного пользователя?>"
                                        data-id-call-user="<?=$arUser['ID'] //записывем в атрибут ид пользователя из списка?>"
                                        data-last-name="<?= $arUser['LAST_NAME'] //записывем в атрибут фамилию пользователя?>"
                                        data-name="<?= $arUser['NAME'] //записывем в атрибут имя пользователя?>"
                                        data-second-name="<?= $arUser['SECOND_NAME'] //записывем в атрибут фамилия пользователя?>"
                                        data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                    Позвонить
                                </button>
                            </td>
                            <?
                        }
                    }
                    ?>
                </tr>
                <?
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title contacts-name" id="exampleModalCenterTitle"></h5>
                </div>
                <div class="modal-body">
                    <h4 class="time">00:00:00</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var startTimeInterval = 0
        var cashe
        function startTime() { // функция секундамера
            $('.time').text('00:00:00') // сброс строки из поля с классом time
            var thisDate = new Date();
            startTimeInterval = setInterval(function(){
                var newDate = new Date() - thisDate;
                var sec   = Math.abs(Math.floor(newDate/1000)%60); //sek
                var min   = Math.abs(Math.floor(newDate/1000/60)%60); //min
                var hours = Math.abs(Math.floor(newDate/1000/60/60)%24); //hours
                if (sec.toString().length   == 1) sec   = '0' + sec;
                if (min.toString().length   == 1) min   = '0' + min;
                if (hours.toString().length == 1) hours = '0' + hours;
                $('.time').text(hours + ':' + min + ':' + sec);
            },1000);
        }

        function stopTime() {
            clearInterval(startTimeInterval)
            $('.time').text('00:00:00') // сброс строки из поля с классом time
        }

        function ajax(user, callUser, time) { // аякс запрос на запись данных в HighloadBlock
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    user: user, // id авторизованного пользователя
                    callUser: callUser, // id пользователя которому звонили
                    time: time // общее время звонка
                }
            })
        }

        document.querySelectorAll('.btn-contacts').forEach(function (e) { // бежим по массиву кнопок с классом btn-contacts
            e.addEventListener('click', function () { // активируем обработчик событий по клику
                document.querySelector('.contacts-name').innerHTML = '' + this.getAttribute('data-last-name') + ' ' + this.getAttribute('data-name') + ' ' + this.getAttribute('data-second-name') // записываем ФИО в поле с классом contacts-name
                startTime() // запускаем функцию таймера
                cashe = {
                    user: $(this).attr('data-id-user'), // записываем в обект id пользователя
                    callUser: $(this).attr('data-id-call-user'), // записываем в обект id пользователя которому звоним
                }
            })
        })

        $('.btn-close-modal').click(function () { // событие клика по кнопке закрытие модального окна
            ajax(cashe['user'], cashe['callUser'], $('.time').text()) // передаем данные пользователя в аякс функцию
            stopTime() // запуск функции стоп таймер
        })
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); //подключение подвала?>