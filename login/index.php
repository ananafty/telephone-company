<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");?>

<?$APPLICATION->IncludeComponent(
	"bitrix:main.auth.form", 
	"authorization", 
	array(
		"COMPONENT_TEMPLATE" => "authorization",
		"AUTH_FORGOT_PASSWORD_URL" => "",
		"AUTH_REGISTER_URL" => "",
		"AUTH_SUCCESS_URL" => "/"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>