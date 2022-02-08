<?php

session_start();
@mb_internal_encoding("UTF-8");
@date_default_timezone_set("Europe/Moscow");
require_once dirname(__DIR__) . "/core/db.php";


if (!defined("BLOCK")) {
    exit("\r\n        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> \r\n        <html>\r\n            <head>\r\n                <title>404 Not Found</title>\r\n            </head>\r\n            <body>\r\n                <h1>Not Found</h1>\r\n                <p>The requested URL was not found on this server.</p>\r\n            </body>\r\n        </html>");
}
$database = @mysqli_connect(DBcfg::$dbopt["db_host"], DBcfg::$dbopt["db_user"], DBcfg::$dbopt["db_pass"], DBcfg::$dbopt["db_name"]);
if (mysqli_connect_errno()) {
    exit("На сайте проводятся технические работы, приносим свои извинения за ожидание.<br>Сообщите администрации если ошибка не пропала в течении 5 минут.<br><br> Информация для администратора сайта: " . mysqli_connect_error() . "");
}
@mysqli_set_charset($database, "utf8");
$db = new DataBase();
$eng = new Engine();
$at = new Auth();
$query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_settings` WHERE `id`=1 LIMIT 1");
$row = $db->f_arr($query);
$site_name = $row["site_name"];
$discount_on = $row["discount_on"];
$discount = $row["discount"];
$colour = $row["colour"];
$num_page = $row["num_page"];
$avatar = $row["avatar"];
$email = $row["email"];
$gruppa = $row["gruppa"];
$sozdatel = $row["sozdatel"];
$lp_on = $row["lp_on"];
$lp_pbkey = $row["lp_pbkey"];
$lp_prkey = $row["lp_prkey"];
$robo_on = $row["robo_on"];
$robo_login = $row["robo_login"];
$robo_pass1 = $row["robo_pass1"];
$robo_pass2 = $row["robo_pass2"];
$free_on = $row["free_on"];
$free_login = $row["free_login"];
$free_pass1 = $row["free_pass1"];
$free_pass2 = $row["free_pass2"];
$curr = $row["curr"];
$wmr_on = $row["wmr_on"];
$purse = $row["wmr_purse"];
$secret_key = $row["wmr_secret_key"];
$uni_on = $row["uni_on"];
$uni_purse = $row["uni_purse"];
$uni_secret_key = $row["uni_purse_key"];
$cron = $row["cron"];
$adm_ip = "";
$to = "";
$a = parse_url($url, PHP_URL_HOST);
class DataBase
{
    public function m_query($sql)
    {
        global $database;
        return mysqli_query($database, $sql);
    }
    public function n_rows($sql)
    {
        $rows = @mysqli_num_rows($sql);
        return $rows;
    }
    public function f_arr($sql)
    {
        $array = @mysqli_fetch_array($sql, MYSQLI_ASSOC);
        return $array;
    }
    public function m_escape($sql)
    {
        global $database;
        $sql = get_magic_quotes_gpc() ? stripslashes($sql) : $sql;
        $sql = mysqli_real_escape_string($database, $sql);
        return $sql;
    }
    public function test()
    {
        return true;
    }
}
class Engine
{
    public function dateDiff($startDay, $endDay)
    {
        if ($endDay - $startDay < 0) {
            return "end";
        }
        $difference = abs($endDay - $startDay);
        $month = floor($difference / 2592000);
        if (0 < $month) {
            $return["month"] = $this->declOfNum($month, array("месяц", "месяца", "месяцев"));
        }
        $days = floor($difference / 86400) % 30;
        if (0 < $days) {
            $return["days"] = $this->declOfNum($days, array("день", "дня", "дней"));
        }
        $hours = floor($difference / 3600) % 24;
        if (0 < $hours) {
            $return["hours"] = $this->declOfNum($hours, array("час", "часа", "часов"));
        }
        $minutes = floor($difference / 60) % 60;
        if (0 < $minutes) {
            $return["minutes"] = $this->declOfNum($minutes, array("минута", "минуты", "минут"));
        }
        if (0 < count($return)) {
            $datediff = implode(" ", $return);
            return $datediff;
        }
        return "few";
    }
    public function declOfNum($number, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $number . " " . $titles[4 < $number % 100 && $number % 100 < 20 ? 2 : $cases[min($number % 10, 5)]];
    }
    public function alert_mess($mess)
    {
        return "<script type='text/javascript'>\$('.toastsDefaultAutohide').ready(function(){ \$(document).Toasts('create', {class: 'bg-danger', title: 'Ошибка!',autohide: true,delay: 15000,position: 'bottomRight',icon: 'fa fa-warning fa-lg',body: '" . $mess . "'})});</script>";
    }
    public function tmp_bar()
    {
        $style = isset($_SESSION["tmp"]) ? $_SESSION["tmp"] : "skin-blue";
        $sidebar = isset($_SESSION["sidebar"]) ? $_SESSION["sidebar"] : "";
        return $style . " " . $sidebar;
    }
    public function pagination($array)
    {
        global $db;
        global $dbopt;
        if ($_GET["page"] == NULL || !is_numeric($_GET["page"])) {
            $page = 1;
        } else {
            $page = abs((int) $_GET["page"]);
        }
        $page_total = floor(($db->n_rows($db->m_query($array["query"])) - 1) / $array["page_num"] + 1);
        $page_query = $array["query"] . " LIMIT " . ($page * $array["page_num"] - $array["page_num"]) . "," . $array["page_num"] . "";
        $page_count = $page * $array["page_num"] - $array["page_num"];
        $pagination .= "<ul class=\"pagination pagination-sm m-0\">";
        if (1 < $page) {
            $pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $array["url"] . "?page=" . ($page - 1) . "\">Назад</a></li>";
        }
        for ($i = max(1, $page - 2); $i <= min($page + 2, $page_total); $i++) {
            if ($i == $page) {
                $pagination .= "<li class=\" page-itemactive\"><a class=\"page-link\">" . $i . "</a></li>";
            } else {
                $pagination .= "<li>" . "<a class=\"page-link\" href=\"" . $array["url"] . "?page=" . $i . "\">" . $i . "</a>" . "</li>";
            }
        }
        $pagination .= "" . ($page < $page_total ? "<li><a class=\"page-link\" href=\"" . $array["url"] . "?page=" . ($page + 1) . "\">Далее</a></li>" : "") . "";
        if (1 < $page_total) {
            if ($page == $page_total) {
                $pagination .= "<li><a class=\"page-link\" href=\"" . $array["url"] . "?page=1\">В начало</a></li>";
            } else {
                $pagination .= "<li><a class=\"page-link\" href=\"" . $array["url"] . "?page=" . $page_total . "\">В конец</a></li>";
            }
        }
        $pagination .= "</ul>";
        return array("query" => $page_query, "pages" => $pagination, "count" => $page_count);
    }
    public function log($text)
    {
        file_put_contents("../core/logs.txt", $text, FILE_APPEND);
    }
    public static function getString(&$packet)
    {
        $str = "";
        $n = strlen($packet);
        for ($i = 0; $packet[$i] != chr(0) && $i < $n; $i++) {
            $str .= $packet[$i];
        }
        $packet = substr($packet, strlen($str));
        return trim($str);
    }
    public static function getChar(&$packet)
    {
        $char = $packet[0];
        $packet = substr($packet, 1);
        return $char;
    }
    public function serverlist($type = false)
    {
        global $db;
        global $dbopt;
        $query = $db->m_query("SELECT * FROM `bp_servers` ORDER BY `id`");
        $list = "";
        if (0 < $db->n_rows($query)) {
            while ($row = $db->f_arr($query)) {
                $list .= "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>" . PHP_EOL;
            }
        }
        if (!$type) {
            return "\r\n\t\t\t<select class=\"form-control\" id=\"server\" name=\"server\" OnChange=\"tarif_list(); time_list(); sel_server();\" required>\r\n\t\t\t\t<option value=\"0\" disabled=\"disabled\" selected=\"selected\">Выбрать сервер</option>\r\n\t\t\t\t" . $list . "\r\n\t\t\t</select>";
        }
        return "\r\n\t\t\t<select class=\"form-control\" id=\"server_id\" name=\"server_id\" required>\r\n\t\t\t\t<option value=\"0\" disabled=\"disabled\">Выбрать сервер</option>\r\n\t\t\t\t" . $list . "\r\n\t\t\t</select>";
    }
    public function serverlist2($type = false)
    {
        global $db;
        global $dbopt;
        $query = $db->m_query("SELECT * FROM `bp_servers` ORDER BY `id`");
        $list = "";
        if (0 < $db->n_rows($query)) {
            while ($row = $db->f_arr($query)) {
                $list .= "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>" . PHP_EOL;
            }
        }
        if (!$type) {
            return "\r\n\t\t\t<select class=\"form-control\" id=\"server1\" name=\"server\" OnChange=\"sel_server();\" required>\r\n\t\t\t\t<option value=\"0\" disabled=\"disabled\" selected=\"selected\">Выбрать сервер</option>\r\n\t\t\t\t" . $list . "\r\n\t\t\t</select>";
        }
        return "\r\n\t\t\t<select class=\"form-control\" id=\"server_id1\" name=\"server_id\" required>\r\n\t\t\t\t<option value=\"0\" disabled=\"disabled\">Выбрать сервер</option>\r\n\t\t\t\t" . $list . "\r\n\t\t\t</select>";
    }

    public function tarifs($server_id)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $query = $db->m_query("SELECT * FROM `bp_tarifs` WHERE `server_id` = '" . $server_id . "'");
        if (0 < $query->num_rows) {
            while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
                $list .= "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>" . PHP_EOL;
            }
            return "<select class=\"form-control\" id=\"tarif\" name=\"tarif\" OnChange=\"time_list()\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Выбрать услугу\" required><option value=\"0\" disabled=\"disabled\" selected=\"selected\">Выбрать услугу</option>" . $list . "</select>";
        }
        return "<input type=\"text\" class=\"form-control\" value=\"Не выбран сервер\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Не выбран сервер\" disabled>";
    }
    public function rules($server_id)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $query = $db->m_query("SELECT * FROM `bp_servers` WHERE `id` = '" . $server_id . "'");
        if (0 < $query->num_rows) {
            while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
                $list .= "" . $row["rules"] . "" . PHP_EOL;
            }
            return "" . $list . "";
        }
        return "Не выбран сервер";
    }
    public function tarifs_time($tarif_id)
    {
        global $db;
        global $dbopt;
        global $discount_on;
        global $discount;
        global $curr;
        $tarif_id = abs((int) $tarif_id);
        $query = $db->m_query("SELECT * FROM `bp_tarif_time` WHERE `tarif_id` = '" . $tarif_id . "'");
        if (0 < $query->num_rows) {
            while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
                $time = $row["time"] == 0 ? "Навсегда" : $row["time"] . " дн.";
                if ($discount_on == 1) {
                    $obfuscated1 = $row["price"] - $row["price"] * $discount / 100;
                    $obfuscated2 = "(цена с учётом скидки - " . $discount . "%)";
                } else {
                    $obfuscated1 = $row["price"];
                }
                $list .= "<option value=\"" . $row["time"] . "\">" . $time . " - <h1>" . $obfuscated1 . "</h1> " . $curr . "" . $obfuscated2 . "</option>" . PHP_EOL;
            }
            return "<select class=\"form-control\" id=\"time\" name=\"time\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Выбрать срок\" required> <option value=\"net\" disabled=\"disabled\" selected=\"selected\">Выбрать срок</option>" . $list . "</select>";
        }
        return "<input type=\"text\" class=\"form-control\" value=\"Не выбрана привилегия\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Не выбрана привилегия\" disabled>";
    }
    public function tarifs_timee($tarif_id)
    {
        global $db;
        global $dbopt;
        global $url;
        global $discount_on;
        global $discount;
        global $curr;
        $tarif_id = abs((int) $tarif_id);
        $query = $db->m_query("SELECT * FROM `bp_tarif_time` WHERE `tarif_id` = '" . $tarif_id . "'");
        if (0 < $query->num_rows) {
            while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
                $time = $row["time"] == 0 ? "Навсегда" : $row["time"] . " Дней";
                if ($discount_on == 1) {
                    $obfuscated3 = $row["price"] - $row["price"] * $discount / 100;
                    $obfuscated2 = "(цена с учётом скидки - " . $discount . "%)";
                } else {
                    $obfuscated3 = $row["price"];
                }
                $list .= "" . $time . " - " . $obfuscated3 . " " . $curr . (string) $obfuscated2 . "<br>" . PHP_EOL;
            }
            return "Цена:<br>" . $list . " <br><a href=\"" . $url . "\"><button type=\"button\" class=\"btn btn-block btn-outline-success btn-lg\">Перейти к покупке</button></a>";
        }
        return "";
    }
    public function up_cfg($server_id, $config_text)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $query = $db->m_query("SELECT * FROM `bp_servers` WHERE `id` = '" . $server_id . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            $tmp_dir = ini_get("upload_tmp_dir") ? ini_get("upload_tmp_dir") : sys_get_temp_dir();
            $obfuscated4 = tempnam($tmp_dir, "");
            if ($obfuscated4 === false) {
                $obfuscated5 = "Внутренняя ошибка генерации конфигурации (возможно недоступен временный каталог)<br />";
                return $obfuscated5;
            }
            $handle = fopen($obfuscated4, "w");
            if (!$handle) {
                $obfuscated5 = "Внутренняя ошибка генерации конфигурации (возможно он недоступен для записи)<br />";
                return $obfuscated5;
            }
            fwrite($handle, $config_text);
            fclose($handle);
            $remote_file = "users.ini";
            if (strpos($row["hostname"], ":") === false) {
                $obfuscated6 = $row["hostname"];
                $ftp_port = 21;
            } else {
                $strings = explode(":", $row["hostname"]);
                list($obfuscated6, $ftp_port) = $strings;
            }
            $conn_id = @ftp_connect($obfuscated6, $ftp_port);
            if ($conn_id) {
                $login_result = @ftp_login($conn_id, $row["login"], $row["password"]);
                if ($login_result) {
                    $chdir = @ftp_chdir($conn_id, $row["path"]);
                    if ($chdir) {
                        if ($res = @ftp_put($conn_id, $remote_file, $obfuscated4, FTP_BINARY)) {
                            $obfuscated5 = "Файл конфигурации успешно загружен на Сервер: " . $row["name"] . "<br />";
                            return $obfuscated5;
                        }
                        $obfuscated5 = "Ошибка загрузки файла конфигурации! Сервер: " . $row["name"] . "<br />";
                        return $obfuscated5;
                    }
                    $obfuscated5 = "Не могу перейти в каталог настроек! Сервер: " . $row["name"] . " " . ftp_pwd($conn_id) . "<br />";
                    return $obfuscated5;
                }
                $obfuscated5 = "Ошибка авторизации на FTP во время обновления конфигурации! Сервер: " . $row["name"] . "<br />";
                return $obfuscated5;
            }
            $obfuscated5 = "Не могу подключиться к серверу для обновления конфигурации! Сервер: " . $row["name"] . "<br />";
            return $obfuscated5;
        }
        $obfuscated5 = "Сервер не найден!<br />";
        return $obfuscated5;
    }
    public function g_cfg($server_id)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $sql = $db->m_query("SELECT * FROM `bp_admins` WHERE `server_id` = '" . $server_id . "' ORDER BY `utime` = '0' DESC, `utime` DESC");
        $config = "";
        if (0 < $db->n_rows($sql)) {
            while ($row = $db->f_arr($sql)) {
                $datestart = date("d.m.Y [H:i]", $row["time"]);
                if (time() < $row["utime"] || $row["utime"] == 0) {
                    $accstatus = $row["utime"] == 0 ? "Бессрочно" : date("d.m.Y [H:i]", $row["utime"]);
                    $config .= "\"" . $row["auth"] . "\" \"" . $row["servpass"] . "\" \"" . $row["access"] . "\" \"" . $row["flags"] . "\" ; \"" . $row["name"] . "\" - \"" . $datestart . "\" - \"" . $accstatus . "\"" . "\r\n";
                } else {
                    $config .= ";\"" . $row["auth"] . "\" \"" . $row["servpass"] . "\" \"" . $row["access"] . "\" \"" . $row["flags"] . "\" ; \"" . $row["name"] . "\" - \"" . $datestart . "\" - \"Срок истек\"" . "\r\n";
                }
            }
        }
        return $config;
    }
    public function set_server($server_id, $config_text)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $query = $db->m_query("SELECT * FROM `bp_servers` WHERE `id` = '" . $server_id . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            $tmp_dir = ini_get("upload_tmp_dir") ? ini_get("upload_tmp_dir") : sys_get_temp_dir();
            $obfuscated4 = tempnam($tmp_dir, "");
            if ($obfuscated4 === false) {
                $obfuscated5 = "Внутренняя ошибка генерации конфигурации (возможно недоступен временный каталог)<br />";
                return $obfuscated5;
            }
            $handle = fopen($obfuscated4, "w");
            if (!$handle) {
                $obfuscated5 = "Внутренняя ошибка генерации конфигурации (возможно он недоступен для записи)<br />";
                return $obfuscated5;
            }
            $folder = dirname($row["skins_file"]) . "/";
            fwrite($handle, $config_text);
            fclose($handle);
            $remote_file = substr($row["skins_file"], strrpos($row["skins_file"], "/") + 1);
            if (strpos($row["hostname"], ":") === false) {
                $obfuscated6 = $row["hostname"];
                $ftp_port = 21;
            } else {
                $strings = explode(":", $row["hostname"]);
                list($obfuscated6, $ftp_port) = $strings;
            }
            $conn_id = @ftp_connect($obfuscated6, $ftp_port);
            if ($conn_id) {
                $login_result = @ftp_login($conn_id, $row["login"], $row["password"]);
                if ($login_result) {
                    $chdir = @ftp_chdir($conn_id, $folder);
                    if ($chdir) {
                        if ($res = @ftp_put($conn_id, $remote_file, $obfuscated4, FTP_BINARY)) {
                            $obfuscated5 = "Файл конфигурации успешно загружен на Сервер: " . $row["name"] . "<br />";
                            return $obfuscated5;
                        }
                        $obfuscated5 = "Ошибка загрузки файла конфигурации! Сервер: " . $row["name"] . "<br />";
                        return $obfuscated5;
                    }
                    $obfuscated5 = "Не могу перейти в каталог настроек! Сервер: " . $row["name"] . " " . ftp_pwd($conn_id) . "<br />";
                    return $obfuscated5;
                }
                $obfuscated5 = "Ошибка авторизации на FTP во время обновления конфигурации! Сервер: " . $row["name"] . "<br />";
                return $obfuscated5;
            }
            $obfuscated5 = "Не могу подключиться к серверу для обновления конфигурации! Сервер: " . $row["name"] . "<br />";
            return $obfuscated5;
        }
        $obfuscated5 = "Сервер не найден!<br />";
        return $obfuscated5;
    }
    public function set_skin($server_id)
    {
        global $db;
        global $dbopt;
        $server_id = abs((int) $server_id);
        $sql = $db->m_query("SELECT bp_skins.name_ct, bp_skins.name_tt, bp_servers.skins_save, bp_skin.nick FROM bp_skins, bp_servers JOIN bp_skin ON bp_servers.id = bp_skin.server_id WHERE bp_skins.id = bp_skin.skin_id AND bp_servers.id = '" . $server_id . "'");
        $config = "";
        if (0 < $db->n_rows($sql)) {
            while ($row = $db->f_arr($sql)) {
                $config .= str_replace("%y", date("Y"), str_replace("%m", date("m"), str_replace("%d", date("d"), str_replace("%tt", $row["name_tt"], str_replace("%ct", $row["name_ct"], str_replace("%n", $row["nick"], str_replace("]", "\"", str_replace("[", "\"", $row["skins_save"])))))))) . "\r\n";
            }
        }
        return $config;
    }
    public function serverlists()
    {
        global $db;
        global $dbopt;
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_servers` WHERE `skins` = '1' ORDER BY `id`");
        $list = "";
        if (0 < $db->n_rows($query)) {
            while ($row = $db->f_arr($query)) {
                $list .= "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>" . PHP_EOL;
            }
        }
        return "\r\n\t\t\t<select class=\"form-control fctr\" id=\"server_id\" name=\"server_id\" required>\r\n\t\t\t\t<option value=\"0\" disabled selected>Выбрать сервер</option>\r\n\t\t\t\t" . $list . "\r\n\t\t\t</select>";
    }
}
class Auth
{
    public function GenerateKey()
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < 10) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return md5($code);
    }
    public function check_auth()
    {
        global $db;
        global $dbopt;
        if (!empty($_SESSION["id"]) && !empty($_SESSION["hash"])) {
            $id = abs((int) $_SESSION["id"]);
            $hash = $db->m_escape($_SESSION["hash"]);
            $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins` WHERE `id` = '" . $id . "' AND `hash` = '" . $hash . "' LIMIT 1");
            if ($db->n_rows($query) == 0) {
                return array("request" => "error");
            }
            return array("request" => "ok");
        }
        return array("request" => "error");
    }
    public function on_auth($login, $password, $server)
    {
        global $db;
        global $dbopt;
        $server = abs((int) $server);
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins` WHERE `auth` = '" . $db->m_escape($login) . "' AND `password` = '" . $db->m_escape($password) . "' AND `server_id` = '" . $server . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            $hash = $this->GenerateKey();
            $_SESSION["hash"] = $hash;
            $_SESSION["id"] = $row["id"];
            $db->m_query("UPDATE `bp_admins` SET `hash` = '" . $hash . "' WHERE `id` = '" . $row["id"] . "'");
            return array("request" => "ok");
        }
        return array("request" => "error");
    }
    public function auth_exit($id)
    {
        global $db;
        $id = abs((int) $id);
        $hash = $db->m_escape($_SESSION["hash"]);
        unset($_SESSION["id"]);
        unset($_SESSION["hash"]);
        $_SESSION["hash"] = NULL;
        $_SESSION["id"] = NULL;
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins` WHERE `id` = '" . $id . "' AND `hash` = '" . $hash . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            $hash = $this->GenerateKey();
            $db->m_query("UPDATE `" . DBcfg::$dbopt["db_prefix"] . "_admins` SET `hash` = '" . $hash . "' WHERE `id` = '" . $row["id"] . "'");
        }
    }
    public function auth_info($info, $id, $hash)
    {
        global $db;
        $id = abs((int) $id);
        $hash = $db->m_escape($hash);
        $query = $db->m_query("SELECT " . $info . " FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins` WHERE `id` = '" . $id . "' AND `hash` = '" . $hash . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $a_info = $db->f_arr($query);
            return $a_info[$info];
        }
    }
    public function user_info($id)
    {
        global $db;
        $id = abs((int) $id);
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins` WHERE `id` = '" . $id . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            return $row;
        }
    }
    public function serv_info($id)
    {
        global $db;
        $id = abs((int) $id);
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_servers` WHERE `id` = '" . $id . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            return $row["name"];
        }
    }
    public function tarif_info($id)
    {
        global $db;
        $id = abs((int) $id);
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_tarifs` WHERE `id` = '" . $id . "' LIMIT 1");
        if (0 < $db->n_rows($query)) {
            $row = $db->f_arr($query);
            return $row["name"];
        }
    }
    public function ch_auth($id)
    {
        global $db;
        $id = abs((int) $id);
        $time = time() - 3600 * 24 * 30;
        $db->m_query("DELETE FROM `" . DBcfg::$dbopt["db_prefix"] . "_auth_count` WHERE `time` < '" . $time . "'");
        $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_auth_count` WHERE `user_id` = '" . $id . "' LIMIT 1");
        $row = $db->f_arr($query);
        if (0 < $db->n_rows($query)) {
            if ($row["count"] == 3) {
                $error = 3;
            } else {
                $db->m_query("UPDATE `" . DBcfg::$dbopt["db_prefix"] . "_auth_count` SET `count` = `count`+1 WHERE `user_id` = '" . $id . "' LIMIT 1");
            }
        } else {
            $db->m_query("INSERT INTO `" . DBcfg::$dbopt["db_prefix"] . "_auth_count` (`id`, `user_id`, `time`, `count`) VALUES (NULL, '" . $id . "', '" . time() . "', '1')");
            $error = 1;
        }
        if (!isset($error)) {
            $error = $row["count"];
        }
        return $error;
    }
}

?>