<?php 
	define ( 'BLOCK', true );
	@require_once "core/core.php";
	@require_once "core/liqpay.php";

	$type = 'buy';
	$query_admins = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_admins` WHERE `auth` = '".$db->m_escape( trim( $_POST['auth'] ) )."' AND `server_id` = '".abs( ( int ) $_POST['server'] )."' LIMIT 1" );
	$query_server = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_servers` WHERE `id` = '".abs( ( int ) $_POST['server'] )."' LIMIT 1" );
	$query_tarif = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_tarifs`, `".DBcfg::$dbopt['db_prefix']."_tarif_time` WHERE `".DBcfg::$dbopt['db_prefix']."_tarif_time`.tarif_id = '".abs( ( int ) $_POST['tarif'] )."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.server_id = '".abs( ( int ) $_POST['server'] )."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.id = '".abs( ( int ) $_POST['tarif'] )."' AND `".DBcfg::$dbopt['db_prefix']."_tarif_time`.time = '".abs( ( int ) $_POST['time'] )."' LIMIT 1" );
	$tarif_info = $db->f_arr( $query_tarif );
	$server_info = $db->f_arr( $query_server );

	if ( $db->n_rows( $query_admins ) > 0 )
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=1");
		exit();
	}
	if ( $db->n_rows( $query_server ) == 0 )
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=2");
		exit();
	}
	if ( $db->n_rows( $query_tarif ) == 0 )
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=3");
		exit();
	}
	if ( trim ( $_POST['type'] ) == 'a' ) 
	{
		if ( preg_match('/[а-яё]/i', trim( $_POST['auth'] ) ) || $_POST['auth'] == NULL || strlen( $_POST['auth'] ) > 32 )
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url."?error=4");
			exit();
		}
	} else if ( trim( $_POST['type'] ) == 'ca' ) {
		if ( ! preg_match("/^STEAM_0:[01]:[0-9]{5,10}$/", trim( $_POST['auth'] ) ) )
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url."?error=5");
			exit();
		}
	} else if ( trim( $_POST['type'] ) == 'de' ) {
		if ( ! preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", trim( $_POST['auth'] ) ) )
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url."?error=5");
			exit();
		}
	} else {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=6");
		exit();
	}
	$f = file( "core/nicks.txt" );
	foreach ( $f as $num => $str ) {
		if ( trim( $_POST['auth'] ) == NULL ) break;
		if ( strpos( $str, trim( $_POST['auth'] ) ) !== false ) 
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url."?error=7");
			exit();
		}
	}
	if ( ! preg_match( '/^[\w]{6,32}+$/', trim( $_POST['pass'] ) ) )
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=8");
		exit();
	}
	if ( ! in_array( trim( $_POST['how'] ), array( "1", "2", "3", "4" ) ) ) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url."?error=9");
		exit();
	}
	if ( $tarif_info['time'] == 0 ) $date_tarif = 'сроком Навсегда.';
	else $date_tarif = 'сроком на '.$tarif_info['time'].' Дн.';
	$desc = 'Приобретение услуги '.$tarif_info['name'].' на сервере '.$server_info['name'].', '.$date_tarif.'';
	$id_acc = rand(999, 999999);
	$db->m_query( "INSERT INTO `".DBcfg::$dbopt['db_prefix']."_temp_adm` (`id`, `auth`, `password`, `access`, `flags`, `server_id`, `service_id`, `utime` ) VALUES ('".$id_acc."', '".$db->m_escape( trim( $_POST['auth'] ) )."', '".$db->m_escape( trim( $_POST['pass'] ) )."', '".$tarif_info['access']."', '".$db->m_escape( trim( $_POST['type'] ) )."', '".abs( ( int ) $_POST['server'] )."', '".abs( ( int ) $_POST['tarif'] )."', '".abs( ( int ) $_POST['time'] )."')" );
	echo '
	<html> 
	<head>
		<title>Переадресация на сайт платёжной системы...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="Content-Language" content="ru">
		<meta http-equiv="Pragma" content="no-cache">
		<meta name="robots" content="noindex,nofollow">
	</head>
	<body>';
		if ( trim( $_POST['how'] ) == 1 && $wmr_on == 1 )
		{
			echo '
			<form name="oplata" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">
			<input type="hidden" name="LMI_PAYMENT_NO" value="'.$id_acc.'" />
			<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="'.base64_encode( $desc ).'" />
			<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.$tarif_info['price'].'" />
			<input type="hidden" name="LMI_PAYEE_PURSE" value="'.$purse.'" />';
		} else if ( trim( $_POST['how'] ) == 2 && $uni_on == 1 ){
			echo '
			<form name="oplata" method="GET" action="https://unitpay.ru/pay/'.$uni_purse.'">
			<input type="hidden" name="account" value="'.$id_acc.'" />
			<input type="hidden" name="desc" value="'.$desc.'" />
			<input type="hidden" name="sum" value="'.$tarif_info['price'].'" />';
		} else if ( trim( $_POST['how'] ) == 3 && $free_on == 1 ){

			if ($discount_on == 1) {
				$buycurr = $tarif_info["price"] - $tarif_info["price"]*$discount/100;
			} else {
				$buycurr = $tarif_info["price"];
			}
		$signature = md5("".$free_login.":".$buycurr.":".$id_acc.":".$free_pass1.":Shp_id=".$id_acc.":Shp_t=:Shp_type=".$type."");
			echo '
			<form name="oplata" method="GET" action="https://www.free-kassa.ru/merchant/cash.php">
			<input type="hidden" name="MrchLogin" value="'.$free_login.'" />
			<input type="hidden" name="OutSum" value="'.$buycurr.'" />
			<input type="hidden" name="InvId" value="'.$id_acc.'" />
			<input type="hidden" name="Desc" value="'.$desc.'" />
			<input type="hidden" name="SignatureValue" value="'.$signature.'" />
			<input type="hidden" name="Culture" value="ru" />
			<input type="hidden" name="Encoding" value="utf-8" />
			<input type="hidden" name="Shp_id" value="'.$id_acc.'" />
			<input type="hidden" name="Shp_t" value="" />
			<input type="hidden" name="Shp_type" value="'.$type.'" />';
		} else if ( trim( $_POST['how'] ) == 4 && $lp_on == 1 ){
			if ($discount_on == 1) {
				$buycurr = $tarif_info["price"] - $tarif_info["price"]*$discount/100;
			} else {
				$buycurr = $tarif_info["price"];
			}
			$liqpay = new LiqPay($lp_pbkey, $lp_prkey);
			echo $html = $liqpay->cnb_form(array(
			'action'         => 'pay',
			'amount'         => $buycurr,
			'currency'       => 'RUB',
			'description'    => $desc,
			'order_id'       => $id_acc,
			'version'        => '3'
			));
			} else {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url."?error=9");
			exit();
		}
		echo '
			<noscript><input type="submit" value="Нажмите, если не хотите ждать!" onclick="document.oplata.submit();"></noscript>
		</form>
		<script language="Javascript" type="text/javascript">
			document.oplata.submit();
		</script>
	</body>
	</html>';
?>