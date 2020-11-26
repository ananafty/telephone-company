<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

global $USER;

/**
 * Распечатывает массивы
 * @param $var
 * @param int $mode
 * @param string $str
 * @param int $die
 */
function gg($var, $mode = 0, $str = 'Var', $die = 0)
{
    switch ($mode) {
        case 0:
            echo "<pre>";
            echo "######### {$str} ##########<br/>";
            print_r($var);
            echo "</pre>";
            if ($die) {
                die();
            }
            break;
        case 2:
            $handle = fopen($_SERVER["DOCUMENT_ROOT"] . "/upload/debug.txt", "a+");
            fwrite($handle, "######### {$str} ##########\n");
            fwrite($handle, (string)$var);
            fwrite($handle, "\n\n\n");

            fclose($handle);
            break;
    }
}

?>
<!doctype html>
<html lang="<?= LANGUAGE_ID; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <? $APPLICATION->ShowHead() ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <?
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap.min.css");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/jquery-3.5.1.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/jquery.table2excel.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/assets/js/bootstrap.bundle.min.js");
    ?>
</head>
<body>
<div class="app">
    <? $APPLICATION->ShowPanel() ?>
    <?
    if (!$USER->IsAuthorized()) {
        if ($APPLICATION->GetCurPage() !== '/login/') {
            LocalRedirect('/login/');
        }
    }
    ?>
    <?if ($APPLICATION->GetCurPage() !== '/login/'):?>
    <header>
        <div class="container">
            <a href="/">
                <img src="<?= SITE_TEMPLATE_PATH . "/assets/image/Group 1.svg" ?>">
            </a>
            <a href="/?logout=yes"><h5>выход</h5></a>
        </div>
    </header>
    <?endif;?>
