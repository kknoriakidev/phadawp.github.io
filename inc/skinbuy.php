<?php

define("BLOCK", true);
require_once "../core/core.php";
if ($_POST["nickname"] == NULL || 32 < strlen($_POST["nickname"])) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url . "skins?error=2");
    exit;
}
if ($_POST["id_skin"] == NULL || $_POST["id_skin"] == 0) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url . "skins?error=4");
    exit;
}
$query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_skins` WHERE `id` = '" . abs((int) $_POST["id_skin"]) . "' LIMIT 1");
$date = $db->f_arr($query);
$server = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_servers` WHERE `id` = '" . abs((int) $date["server_id"]) . "' LIMIT 1");
$sdate = $db->f_arr($server);
if ($db->n_rows($query) == 0 || $db->n_rows($server) == 0) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url . "skins?error=1");
    exit;
}
$desc = "Приобретение модели " . $date["model_name"] . " на сервере " . $sdate["name"];
$id = rand(999, 999999);
$db->m_query("INSERT INTO `" . DBcfg::$dbopt["db_prefix"] . "_temp_skins` (`id`, `nick`, `server_id`, `skin_id`) VALUES ('" . $id . "', '" . $db->m_escape(trim($_POST["nickname"])) . "', '" . abs((int) $date["server_id"]) . "', '" . abs((int) $_POST["id_skin"]) . "')");
echo "\r\n\t<html> \r\n\t<head>\r\n\t\t<title>Переадресация на сайт платёжной системы...</title>\r\n\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\r\n\t\t<meta http-equiv=\"Content-Language\" content=\"ru\">\r\n\t\t<meta http-equiv=\"Pragma\" content=\"no-cache\">\r\n\t\t<meta name=\"robots\" content=\"noindex,nofollow\">\r\n\t</head>\r\n\t<body>";
if ($free_on == 1) {
    $signature = md5("" . $free_login . ":" . $date["price"] . ":" . $id . ":" . $free_pass1 . ":Shp_id=" . $id . ":Shp_t=:Shp_type=skins");
    echo "\r\n\t\t\t<form name=\"oplata\" method=\"GET\" action=\"https://www.free-kassa.ru/merchant/cash.php\">\r\n\t\t\t<input type=\"hidden\" name=\"MrchLogin\" value=\"" . $free_login . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"OutSum\" value=\"" . $date["price"] . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"InvId\" value=\"" . $id . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"Desc\" value=\"" . $desc . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"SignatureValue\" value=\"" . $signature . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"Culture\" value=\"ru\" />\r\n\t\t\t<input type=\"hidden\" name=\"Encoding\" value=\"utf-8\" />\r\n\t\t\t<input type=\"hidden\" name=\"Shp_id\" value=\"" . $id . "\" />\r\n\t\t\t<input type=\"hidden\" name=\"Shp_t\" value=\"\" />\r\n\t\t\t<input type=\"hidden\" name=\"Shp_type\" value=\"skins\" />\r\n\t\t\t<noscript><input type=\"submit\" value=\"Нажмите, если не хотите ждать!\" onclick=\"document.oplata.submit();\"></noscript>\r\n\t\t</form>";
    echo "\r\n\t\t<script language=\"Javascript\" type=\"text/javascript\">\r\n\t\t\tdocument.oplata.submit();\r\n\t\t</script>\r\n\t</body>\r\n\t</html>";
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url . "?error=5");
    exit;
}

?>