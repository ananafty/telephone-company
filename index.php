<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); // подключение шаблона шапки
$APPLICATION->SetTitle("Телефонная компания"); // вывод заголовка страницы в мета тэг
?>
<div class="container">
    <div class="d-flex align-items-center justify-content-center h-100">
        <a href="/report/" class="card" style="width: 18rem; margin: 0 10px;">
            <img src="<?=SITE_TEMPLATE_PATH . "/assets/image/business-report.png"?>" class="card-img-top" style="padding: 40px">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center">Составить отчет</h5>
            </div>
        </a>
        <a href="/call/" class="card" style="width: 18rem; margin: 0 10px;">
            <img src="<?=SITE_TEMPLATE_PATH . "/assets/image/phone.png"?>" class="card-img-top" style="padding: 40px">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center">Телефонная книга</h5>
            </div>
        </a>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>