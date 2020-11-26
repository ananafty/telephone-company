<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Телефон"); ?>

    <div class="container">
        <?
        $order = ['sort' => 'login '];
        $tmp = 'sort';
        $rsUsers = CUser::GetList($order, $tmp);
        $getUser = CUser::GetByID($USER->GetID());
        $foreachGetUser = $getUser->Fetch();
        ?>
        <table class="table">
            <tbody>
            <?
            while ($arUser = $rsUsers->Fetch()) {
                $rsUser = CUser::GetByID('' . $arUser['ID'] . '');

                ?>
                <tr>
                    <?
                    while ($arUser = $rsUser->Fetch()) {
                        if ($foreachGetUser['ID'] !== $arUser['ID']) {
                            ?>
                            <td><?= $arUser['LAST_NAME'] ?></td>
                            <td><?= $arUser['NAME'] ?></td>
                            <td><?= $arUser['SECOND_NAME'] ?></td>
                            <td><?= $arUser['UF_PHONE_NUMBER'] ?></td>
                            <td>
                                <button type="button"
                                        class="btn btn-primary btn-contacts"
                                        data-id-user="<?=$foreachGetUser['ID']?>"
                                        data-id-call-user="<?=$arUser['ID']?>"
                                        data-last-name="<?= $arUser['LAST_NAME'] ?>"
                                        data-name="<?= $arUser['NAME'] ?>"
                                        data-second-name="<?= $arUser['SECOND_NAME'] ?>"
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
        function startTime() {
            $('.time').text('00:00:00')
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
            $('.time').text('00:00:00')
        }

        function ajax(user, callUser, time) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    user: user,
                    callUser: callUser,
                    time: time
                }
            })
        }

        document.querySelectorAll('.btn-contacts').forEach(function (e) {
            e.addEventListener('click', function () {
                document.querySelector('.contacts-name').innerHTML = '' + this.getAttribute('data-last-name') + ' ' + this.getAttribute('data-name') + ' ' + this.getAttribute('data-second-name')
                startTime()
                cashe = {
                    user: $(this).attr('data-id-user'),
                    callUser: $(this).attr('data-id-call-user'),
                }
            })
        })

        $(document).ready(function () {
            $(document).mousedown(function (e){ // событие клика по странице
                if (!$(".modal-content").is(e.target) && // если клик сделан не по элементу
                    $(".modal-content").has(e.target).length === 0) { // если клик сделан не по вложенным элементам
                }
            });
        })

        $('.btn-close-modal').click(function () {
            ajax(cashe['user'], cashe['callUser'], $('.time').text())
            stopTime()
        })
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>