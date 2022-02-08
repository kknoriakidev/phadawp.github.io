<?php
/**                     
                        **
                       ****
  * * * * * * * * * * *    * * * * * * * * * * *
 *                                              *
 * /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\ *
 * |$        Buy Privileges V3 by TexyMC.ru  $| *
 * |$        Contacts - vk.me/texymcru       $| *
 * |$        Site - texymc.ru $              $| *
 * |$        TexyMC 2017-2077                $| *
 * \/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ * 
 *                                              *
  * * * * * * * * * * *    * * * * * * * * * * *
                       ****
                        **
**/
class DBcfg {
		static $dbopt = array
		(
			'db_host' => 'localhost', // Адрес БД
			'db_user' => 'admin_bp', // Пользователь БД
			'db_pass' => '', // Пароль БД
			'db_name' => 'admin_bp', // Имя базы данных
			'db_prefix' => 'bp' // не трогать
		);
	}

	$url = 'http://domain.ru/'; // url сайта в формате https://domain.ru/ или http://domain.ru/
	const KEY = "FED14-1F450-E6883-971E5-5CAD4";
	// Данные для входа в Админ панель вашсайт.ру/auth/admin/ 
	$adm_login = 'admin'; // Логин Администратора
	$adm_pass = 'admin'; // Пароль Администратора
?>