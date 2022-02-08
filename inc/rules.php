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
	
	if ( ! empty( $_GET['error'] ) )
	{
		$id = abs( ( int ) $_GET['error'] );
		
		if ( $id > 0 && $id <= 9 ) {
			$error_mess = array(
				1 => '<center><strong>Игрок уже зарегистрирован на данном сервере!</strong></center>',
				2 => '<center><strong>Не выбран сервер!</strong></center>',
				3 => '<center><strong>Не выбрана услуга или срок!</strong></center>',
				4 => '<center><strong>"Ник" не указан или указан неверно!</strong></center>', 
				5 => '<center><strong>"Steam ID/IP" не указан или указан неверно!</strong></center>',
				6 => '<center><strong>Не выбран тип авторизации!</strong></center>',
				7 => '<center><strong>Данный ник использовать нельзя!</strong></center>',
				8 => '<center><strong>В пароле могут быть только английские буквы и цифры, а также его длина должна быть от 6 до 32 символа!</strong></center>',
				9 => '<center><strong>Платежная система не найдена!</strong></center>'
			);
			$error = "$('#mess').jGrowl('".$error_mess[$id]."', { life: 5000 });";
		}
	}
	
	$wmr = ( $wmr_on == 1 ) ? '<option value="1" selected="selected">Webmoney</option>' : '';
	$uni = ( $uni_on == 1 ) ? '<option value="2">Unitpay ( SMS, VISA, QIWI, ЯД, WebMoney )</option>' : '';
	$rob = ( $robo_on == 1 ) ? '<option value="3">Free Cassa ( SMS, VISA, QIWI, ЯД )</option>' : '';
	$liqpay = ( $lp_on == 1 ) ? '<option value="4">LiqPay ( Оплата для украинцев )</option>' : '';
	
	echo '
	<script type="text/javascript">
	$(function() {
		tarif_list();
		time_list();
		'.$error.'
		
		$("#tarif_list").click(function(){
			var tarif = $( "#tarif" ).val();
			$.ajax({
				type: "POST",
				url: "'.$url.'service.php",
				data: "id=55&tarif="+tarif,
				success: function(data){
					$("#tarif_infoo").html(data);
				}
			});
		});
	});
		
		function tarif_list()
		{
			var server = $( "#server" ).val();
			$.ajax({
				type: "POST",
				url: "'.$url.'service.php",
				data: "id=444&server="+server,
				success: function(data){
					$("#tarif_list").html(data);
				}
			});
		}
		
		function time_list()
		{
			var tarif_timee = $( "#tarif" ).val();
			$.ajax({
				type: "POST",
				url: "'.$url.'service.php",
				data: "id=22&tarif_timee="+tarif_timee,
				success: function(data){
					$("#tarif_list_timee").html(data);
				}
			});
		}
		
		function sel_server()
		{
			var server = $( "#server" ).val();
			$.ajax({
				type: "POST",
				url: "'.$url.'service.php",
				data: "id=3&server="+server,
				success: function(data){
					$("#selected_server").html(data);
				}
			});
		}
		
		function changetype(name)
		{
			if (name=="a")
			{
				$("#login_annotation").html("Ник");
				$("#auth").attr("placeholder", "Ник в игре");
			}
		}
	</script>
	<!-- general form elements -->
		<!-- form start -->
		';
	
		echo '	
		<div class="card card-outline card-danger">
      <div class="card-body">
        <div class="row">
        <div class="col-md-12">					
		<form role="form" action="payment.php" method="POST" autocomplete="off">
	<div class="form-group">
		<label><i class="fa fa-server"></i> Выберите Сервер:</label>
		'.$eng->serverlist().'
	</div>

	<div class="form-group">
		<div id="tarif_list"></div>
	</div>		
	</label>
</form>';
?>