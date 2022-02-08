<?php
define("BLOCK", true);
require_once "core/core.php";
require_once "protocol.php";
$id = abs((int) $_POST["id"]);
$idd = abs((int) $_POST["id"]);
if ($id == 1) {
    $server = abs((int) $_POST["server"]);
    echo $eng->tarifs($server);
} else {
    if ($id == 2) {
        $tarif_time = abs((int) $_POST["tarif_time"]);
        echo $eng->tarifs_time($tarif_time);
    } else {
        if ($id == 3) {
            $query_server = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_servers` WHERE `id` = '" . abs((int) $_POST["server"]) . "' LIMIT 1");
            if (0 < $db->n_rows($query_server)) {
                $server_info = $db->f_arr($query_server);
				$data = serverInfo($server_info["address"]);
				$mapimg = 'uploads/mapsimg/'.$data["map"].'.jpg';
				if(!file_exists($mapimg)){
					$mapimg =  'uploads/mapsimg/no_map.jpg';
				} else {
					$mapimg =  'uploads/mapsimg/'.$data["map"].'.jpg';
				}
                if ($data["status"] == 1) {
                    $status = "<i class=\"fa fa-circle text-success\"></i>";
                } else {
                    $status = "<i class=\"fa fa-circle text-danger\"></i>";
                }
                echo "<div class=\"callout callout-" . $colour . "\"><h4>" . $data["hostname"] . "</h4><div class=\"row\"><div class=\"col-md-3\"><img src=\"" . $mapimg . "\" style=\"width: 150px; height: 105px;\" class=\"img-thumbnail\"></div><div class=\"col-md-6\"><b><i class=\"fa fa-power-off\" style=\"width: 15px;\"></i> Статус:</b> " . $status . " <br /><b><i class=\"fa fa-location-arrow\" style=\"width: 15px;\"></i> Адрес:</b> " . $server_info["address"] . " <br /><b><i class=\"fa fa-users\" style=\"width: 15px;\"></i> Игроки:</b> " . $data["players"] . "/" . $data["maxplayers"] . " <br /><b><i class=\"fa fa-map-marker\" style=\"width: 15px;\"></i> Карта:</b> " . $data["map"] . " <br /></div></div></div>";
            }
        } else {
            if ($id == 4) {
                $query_tarif = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_tarifs` WHERE `id` = '" . abs((int) $_POST["tarif"]) . "' LIMIT 1");
                $date_tarif = $db->f_arr($query_tarif);
                echo "<a role=\"button\" data-toggle=\"modal\" data-target=\"#modal-default\" class=\"btn btn-flat btn-success btn-block\">Описание \"" . $date_tarif["name"] . "\"</a><div class=\"modal fade\" id=\"modal-default\"><div class=\"modal-dialog\"><div class=\"modal-content\"><div class=\"modal-header\"><h4 class=\"modal-title\">Описание услуги " . $date_tarif["name"] . "</h4><button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button></div><div class=\"modal-body\">" . $date_tarif["info"] . "</div><div class=\"modal-footer justify-content-between\"><button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-primary\">Закрыть</button></div></div></div></div>";
            } else {
                if ($id == 5) {
                    $id = abs((int) $_COOKIE["id"]);
                    $hash = $db->m_escape($_COOKIE["hash"]);
                    $query = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_admins`,`" . DBcfg::$dbopt["db_prefix"] . "_tarif_time`\r\n\t\t\tWHERE `" . DBcfg::$dbopt["db_prefix"] . "_admins`.id = '" . $id . "' \r\n\t\t\tAND `" . DBcfg::$dbopt["db_prefix"] . "_admins`.hash = '" . $hash . "'\r\n\t\t\tAND `" . DBcfg::$dbopt["db_prefix"] . "_admins`.service_id = `" . DBcfg::$dbopt["db_prefix"] . "_tarif_time`.tarif_id\r\n\t\t\tAND `" . DBcfg::$dbopt["db_prefix"] . "_tarif_time`.time = 30\r\n\t\t\tLIMIT 1\r\n\t\t");
                    $date = $db->f_arr($query);
                    $per_day = floor($date["price"] / 30);
                    $days = floor(($date["utime"] - time()) / 86400);
                    $skidka = $date["utime"] == 0 ? "0" : $per_day * $days;
                    $query_tarif = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_tarif_time` \r\n\t\t\tWHERE `tarif_id` = '" . abs((int) $_POST["tarif"]) . "' \r\n\t\t\tAND `time` = '" . abs((int) $_POST["time"]) . "' \r\n\t\t\tLIMIT 1\r\n\t\t");
                    $date_tarif = $db->f_arr($query_tarif);
                    $price = $date_tarif["price"] - $skidka;
                    if ($date["service_id"] == abs((int) $_POST["tarif"])) {
                        echo "Данная привилегия уже стоит на вашем аккаунте! Продлить срок можно через кнопку \"Продлить\"!";
                    } else {
                        if ($price < 0) {
                            echo "Оплата за данную услугу не снимаеться!";
                        } else {
                            echo "К оплате:  " . $price . " руб. (Скидка: " . $skidka . " руб.)";
                        }
                    }
                } else {
                    if ($id == 6) {
                        $tmp = $db->m_query("SELECT `id`, `name` FROM `bp_tarifs` WHERE `server_id` = " . intval($_POST["server_id"]) . " AND `id` != " . intval($_POST["service_id"]) . " ORDER BY `access` DESC");
                        if (0 < $db->n_rows($tmp)) {
                            echo "<select id=\"donate\" onkeyup=\"checkParams()\" class=\"form-control\" onchange=\"changeselect()\" name=\"service_id\">";
                            $id = 0;
                            while ($row = $db->f_arr($tmp)) {
                                if ($id == 0) {
                                    $id = $row["id"];
                                }
                                echo "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                            }
                            $tmp = $db->m_query("SELECT `id`, `time`, `price` FROM `bp_tarif_time` WHERE `tarif_id` = " . $id . " ORDER BY `price` ASC");
                            $da = "<select id=\"time\" onkeyup=\"checkParams()\" class=\"form-control\" name=\"tarif_price\">";
                            while ($row = $db->f_arr($tmp)) {
                                if ($row["time"] == 0) {
                                    $row["time"] = "Навсегда";
                                } else {
                                    $row["time"] .= " дн.";
                                }
                                $da .= "<option value=\"" . $row["id"] . "\">" . $row["time"] . " - (" . $row["price"] . " руб.)</option>";
                            }
                            $da .= "</select>";
                            echo "<script type=\"text/javascript\">\$(function(){\$('#tarif_price').html('" . $da . "')})</script>";
                        }
                    } else {
                        if ($id == 7) {
                            $tmp = $db->m_query("SELECT `id`, `time`, `price` FROM `bp_tarif_time` WHERE `tarif_id` = " . intval($_POST["service_id"]) . " ORDER BY `price` ASC");
                            echo "<select id=\"pass\" onkeyup=\"checkParams()\" class=\"form-control\" name=\"tarif_price\">";
                            while ($row = $db->f_arr($tmp)) {
                                if ($row["time"] == 0) {
                                    $row["time"] = "Навсегда";
                                } else {
                                    $row["time"] .= " дн.";
                                }
                                echo "<option value=\"" . $row["id"] . "\">" . $row["time"] . " - (" . $row["price"] . " руб.)</option>";
                            }
                            echo "</select>";
                        } else {
                            if ($id == 77) {
                                $tmp = $db->m_query("SELECT `id`, `time`, `price` FROM `bp_tarif_time` WHERE `tarif_id` = " . intval($_POST["service_id"]) . " ORDER BY `price` ASC");
                                while ($row = $db->f_arr($tmp)) {
                                    if ($row["time"] == 0) {
                                        $row["time"] = "Навсегда";
                                    } else {
                                        $row["time"] .= " дн.";
                                    }
                                    echo "" . $row["id"] . "\">" . $row["time"] . " - (" . $row["price"] . " руб.)";
                                }
                            } else {
                                if ($id == 9) {
                                    $query_tarif = $db->m_query("SELECT * FROM `" . DBcfg::$dbopt["db_prefix"] . "_tarifs` WHERE `id` = '" . abs((int) $_POST["tarif"]) . "' LIMIT 1");
                                    $date_tarif = $db->f_arr($query_tarif);
                                    echo "<br><div class=\"card bg-" . $colour . "\"><div class=\"card-header\"><h3 class=\"card-title\">Описание доната: " . $date_tarif["name"] . "</h3></div><div class=\"card-body\">" . $date_tarif["info"] . "</div></div>";
                                } else {
                                    if ($id == 10) {
                                        $tarif_timee = abs((int) $_POST["tarif_timee"]);
                                        echo $eng->tarifs_timee($tarif_timee);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
if ($id == 444) {
    $server = abs((int) $_POST["server"]);
    echo $eng->rules($server);
}

?>