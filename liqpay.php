<?php
define ( 'BLOCK', true );
@require_once 'core/core.php';
@require_once 'core/liqpay.php';
if ( $lp_on == 0 ) exit ( "
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
    
    $liqpay = new LiqPay($lp_pbkey, $lp_prkey);

    $request = 'SELECT * FROM `bp_temp_adm` WHERE `how` = 4 AND `status` = 0';

    $query = $db->m_query( $request );
    if ( $db->n_rows( $query ) > 0 )
		{
            while ( $date = $db->f_arr( $query ) )
			{
                $res = $liqpay->api("request", array(
                'action'        => 'status',
                'version'       => '3',
                'order_id'      => $date['id']
                ));

                if ( $res->status == 'success' ) {
                    $query_tarif = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_tarifs`, `".DBcfg::$dbopt['db_prefix']."_tarif_time` WHERE `".DBcfg::$dbopt['db_prefix']."_tarif_time`.tarif_id = '".$date['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.server_id = '".$date['server_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarifs`.id = '".$date['service_id']."' AND `".DBcfg::$dbopt['db_prefix']."_tarif_time`.time = '".$date['utime']."' LIMIT 1" );
			        $tarif_info = $db->f_arr( $query_tarif );

                    if ( $tarif_info['price'] != abs( ( int ) $res->amount ) ) 
                    {
                        echo 'Неверная сумма!';
                        exit;
                    }

                    if ( $date['utime'] == 0 ) {
                        $date_end = 0;
                    } else {
                        $date_end = time()+3600*24*$date['utime'];
                    }

                    $db->m_query( "UPDATE `".DBcfg::$dbopt['db_prefix']."_temp_adm` SET `status` = 1 WHERE `id` = '".$date['id']."'" );
                    $db->m_query( "INSERT INTO `".DBcfg::$dbopt['db_prefix']."_admins` (`id`, `auth`, `password`, `access`, `flags`, `servpass`, `name`, `skype`, `server_id`, `service_id`, `time`, `utime`, `hash`) VALUES (NULL, '".$db->m_escape( $date['auth'] )."', '".md5( sha1( $date['password'] ) )."', '".$date['access']."', '".$date['flags']."', '".$date['password']."', '', '', '".$date['server_id']."', '".$date['service_id']."', '".time()."', '".$date_end."', '".$at->GenerateKey()."')" );
                    $eng->up_cfg ( $date['server_id'], $eng->g_cfg( $date['server_id'] ) );
                }
            }
        }
?>