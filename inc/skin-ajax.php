<?php
	define ( 'BLOCK', true );
	@require_once "../core/core.php";
	
	$id = abs( ( int ) $_GET['id'] );
	
	if ( isset( $id ) ) {
		$query = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_skins` WHERE `id` = '".$id."' LIMIT 1" );
        if ( $db->n_rows( $query ) > 0 ) {
            $date = $db->f_arr( $query );
            $query_server = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_servers` WHERE `id` = '".$date['server_id']."' LIMIT 1" );
            $sdate = $db->f_arr( $query_server );
            die( json_encode( array('server' => $sdate['name'], 'name' => $date['model_name'], 'image' => $url . $date['image'], 'input' => $date['id'], 'submit' => 'Купить модель - '.$date['price'].' '.$curr) ) );
	    } else {
	       die( json_encode( array('error' => 'Ошибка загрузки модели.') ) ); 
	    }
	} else {
		die( json_encode( array('error' => 'Ошибка загрузки модели.') ) );
	}
?>