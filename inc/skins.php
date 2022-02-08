<?php 
	if ( ! defined ( 'BLOCK' ) )
	{
		exit ( "
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
	}
	        $query_servers = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_servers` WHERE `skins` = 1 ORDER BY `id` ASC" );
			if ( $db->n_rows( $query_servers ) > 0 )
			{
			    echo '<ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">';
			    $i = 0;
			    while ( $row = $db->f_arr( $query_servers ) )
				{
				    $i++;
				    if ( $i === 1 ) {
				        $ac = 'active';
				    } else {
				        $ac = '';
				    }
					echo '
					
					<li class="nav-item '.$ac.'">
                <a class="nav-link '.$ac.'" id="custom-tabs-one-home-tab" data-toggle="pill" href="#server_'.$i.'"
                    role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">'.$row['name'].'</a>
            </li>';
				}
				echo '</ul></div>';
			}
			echo '<div class="tab-content"><br>';
			
			$query_servers2 = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_servers` WHERE `skins` = 1 ORDER BY `id` ASC" );
			if ( $db->n_rows( $query_servers2 ) > 0 )
			{
			    $i2 = 0;
			    while ( $row2 = $db->f_arr( $query_servers2 ) )
				{
				    $i2++;
				    if ( $i2 === 1 ) {
				        $ac2 = ' active';
				    } else {
				        $ac2 = '';
				    }
				    echo '<div class="tab-pane'.$ac2.'" id="server_'.$i2.'"><div class="card-group">';
				    
				    $query = $db->m_query( "SELECT * FROM `".DBcfg::$dbopt['db_prefix']."_skins` WHERE `server_id` = '".$row2['id']."' ORDER BY `id` ASC" );
        			if ( $db->n_rows( $query ) > 0 )
        			{
        				while ( $date = $db->f_arr( $query ) )
        				{
        					echo '
							<div class="col-md-3">
  <div class="card">
  <img class="img-responsive thumbnail skin_info" src="'.$url.$date['image'].'" alt="'.$date['model_name'].'" data-id="'.$date['id'].'">
  <div class="card-body">
									<center>
									<h5 class="skin-name skin_info"> '.$date['model_name'].'</h5>
                                        <button type="button" class="btn btn-block btn-success btn-sm btn-skin skin_info" data-id="'.$date['id'].'">
                                            Купить - '.$date['price'].' '.$curr.'
                                        </button>
                                    </center>
									</div>
									</div>
								  </div>
                            <!-- /.col -->';
        				}
        			} else {
        				echo '<div class="col-md-12"><div class="callout callout-danger"><h4>Список моделей пуст!</h4>К сожалению, администратор ещё не добавил моделей для продажи.</div></div>';
        			}
        			
        			echo '</div> </div><!-- /.tab-pane -->';
				}
			}
			
			echo '</div>';
?>