<?php 
	define ( 'BLOCK', true );
	@require_once 'core/core.php';
	if ( $free_on == 0 ) exit ( "
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> 
		<html>
			<head>
				<title>404 Not Found</title>
			</head>
			<body>
				<h1>Not Found</h1>
				<p>The requested URL was not found on this server.</p>
			</body>
		</html>" );
	
	$out_summ = $_POST['OutSum'];
	$inv_id = $_POST['InvId'];
	$crc = $_POST['SignatureValue'];
	$shp_id = $_POST["Shp_id"];
	$shp_t = $_POST["Shp_t"];
	$us_type = $_POST["Shp_type"];

	if($us_type == 'buy'){
		$my_crc = strtoupper( md5( "".$out_summ.":".$inv_id.":".$free_pass2.":Shp_id=".$shp_id.":Shp_t=".$shp_t."" ) );
		$query_acc = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_temp_adm` WHERE `id` = '".abs( ( int ) $shp_id )."' LIMIT 1" );
		$query_adm = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_admins` WHERE `id` = '".abs( ( int ) $shp_id )."' LIMIT 1" );
		if ( $db->n_rows( $query_acc ) > 0 )
		{
			$arr_acc = $db->f_arr( $query_acc );
			$query_tarif = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_tarifs`, `".DBcfg::$dbopt['db_prefix']."_tarif_time` WHERE `".DBcfg::$dbopt['db_prefix']."_tarif_time`.tarif_id = '".$arr_acc['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.server_id = '".$arr_acc['server_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.id = '".$arr_acc['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarif_time`.time = '".$arr_acc['utime']."' LIMIT 1" );
			$tarif_info = $db->f_arr( $query_tarif );

			if ($discount_on == 1) {
				$buycurr = $tarif_info["price"] - $tarif_info["price"]*$discount/100;
			} else {
				$buycurr = $tarif_info["price"];
			}

			if ( $buycurr != $out_summ) 
			{
				echo 'Неверная сумма!';
				exit;
			}
		} else if ( $db->n_rows( $query_adm ) > 0 ) {
			$arr_adm = $db->f_arr( $query_adm );
			$query_tarif = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_tarifs`, `".DBcfg::$dbopt['db_prefix']."_tarif_time` WHERE `".DBcfg::$dbopt['db_prefix']."_tarif_time`.tarif_id = '".$arr_adm['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.server_id = '".$arr_adm['server_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.id = '".$arr_adm['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarif_time`.time = '".abs( ( int ) $shp_t )."' LIMIT 1" );
			$tarif_info = $db->f_arr( $query_tarif );

		if ($discount_on == 1) {
				$buycurr = $tarif_info["price"] - $tarif_info["price"]*$discount/100;
			} else {
				$buycurr = $tarif_info["price"];
			}

			if ( $buycurr != $out_summ ) 
			{
				echo 'Неверная сумма!';
				exit;
			}
		} 
		echo "YES";
		$query_adm = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_admins` WHERE `id` = '".abs( ( int ) $_POST['LMI_PAYMENT_NO'] )."' LIMIT 1" );
		if ( $db->n_rows( $query_adm ) > 0 )
		{
			$arr_adm = $db->f_arr( $query_adm );
			$time = abs( ( int ) $shp_t );
			if ( $time == 0 ) {
				$db->m_query( "UPDATE `".DBcfg::$dbopt['db_prefix']."_admins` SET `utime` = ('0') WHERE `id` = '".$arr_adm['id']."'" );
			} else {
				$date_con_a = time()+3600*24*$time;
				$date_con_b = 3600*24*$time;
				
				if ( $arr_adm['utime'] < time() ) {
					$db->m_query( "UPDATE `".DBcfg::$dbopt['db_prefix']."_admins` SET `utime` = ('".$date_con_a."') WHERE `id` = '".$arr_adm['id']."'" );
				} else {
					$db->m_query( "UPDATE `".DBcfg::$dbopt['db_prefix']."_admins` SET `utime` = (`utime`+'".$date_con_b."') WHERE `id` = '".$arr_adm['id']."'" );
				}
			}
			$eng->up_cfg ( $arr_adm['server_id'], $eng->g_cfg( $arr_adm['server_id'] ) );
		} else {
			$query_acc = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_temp_adm` WHERE `id` = '".abs( ( int ) $shp_id )."' LIMIT 1" );
			$arr_acc = $db->f_arr( $query_acc );
			if ( $arr_acc['utime'] == 0 ) {
				$date_end = 0;
			} else {
				$date_end = time()+3600*24*$arr_acc['utime'];
			}
			$db->m_query( "INSERT INTO `".DBcfg::$dbopt['db_prefix']."_admins` (`id`, `auth`, `password`, `access`, `flags`, `servpass`, `name`, `skype`, `server_id`, `service_id`, `time`, `utime`, `hash`) VALUES (NULL, '".$db->m_escape( $arr_acc['auth'] )."', '".md5( sha1( $arr_acc['password'] ) )."', '".$arr_acc['access']."', '".$arr_acc['flags']."', '".$arr_acc['password']."', '', '', '".$arr_acc['server_id']."', '".$arr_acc['service_id']."', '".time()."', '".$date_end."', '".$at->GenerateKey()."')" );
			$eng->up_cfg ( $arr_acc['server_id'], $eng->g_cfg( $arr_acc['server_id'] ) );
		}
	} elseif ($us_type == 'donate') {

		$query_don = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_donate_history` WHERE `nick` = '".$inv_id."' LIMIT 1" );
		$query_top = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_donaters` WHERE `nick` = '".$inv_id."' LIMIT 1" );

		if ( $db->n_rows( $query_don ) > 0 )
		{
			$row = $db->f_arr( $query_don );

			if( $out_summ != $row['summ'] ) {
				echo 'Неверная сумма!';
				exit;
			} else {
				if ( $db->n_rows( $query_top ) > 0 )
				{
					$db->m_query( "UPDATE `".DBcfg::$dbopt['db_prefix']."_donaters` SET `summ`=summ+".$out_summ." WHERE `nick` = '".$inv_id."'" );
				} else {
					$db->m_query( "INSERT INTO `".DBcfg::$dbopt['db_prefix']."_donaters` ( `nick`, `summ` ) VALUES ('".$inv_id."', '".$out_summ."')" );
				}
			}
			echo "YES";
		}
	} elseif ($us_type == 'skins') {

		$query = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_temp_skins` WHERE `id` = '".$inv_id."' LIMIT 1" );

		if ( $db->n_rows( $query ) > 0 )
		{
			$row = $db->f_arr( $query );
			
			$skin = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_skins` WHERE `id` = '".$row['skin_id']."' LIMIT 1" );

            if ( $db->n_rows( $skin ) > 0 ) {
                $date = $db->f_arr( $skin );
    			if( $out_summ != $date['price'] ) {
    				echo 'Неверная сумма!';
    				exit;
    			} else {
    				if ( $inv_id == $row['id'] ) {
    				    $db->m_query( "INSERT INTO `".DBcfg::$dbopt['db_prefix']."_skin` ( `id`, `nick`, `server_id`, `skin_id`) VALUES (NULL, '".$row['nick']."', '".$row['server_id']."', '".$row['skin_id']."')" );
    				    $eng->set_server($row['server_id'], $eng->set_skin($row['server_id']));
    				}
    			}
            }
			echo "YES";
		}
	}
	
	
?>