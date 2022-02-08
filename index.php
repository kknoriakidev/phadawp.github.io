<?php 
	define ( 'BLOCK', true );
	require_once "core/core.php";
?>
<!--
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
-->
<!DOCTYPE html>
<html>
<!--- head --->
<?php require_once "templates/standart/tpl/body/head.php";?>
<!--- head --->
		<!--- header --->
		<?php require_once "templates/standart/tpl/body/header.php";?>
		<!--- header --->
		<!--- МЕНЮ --->
		<?php require_once "templates/standart/tpl/body/menu.php";?>
    <!--- МЕНЮ --->
    <div id="mess"></div>
		<div class="content-wrapper" style="min-height: 256.031px;">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark">Главная страница</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="#">Главная страница</a></li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<!-- left column -->
						<div class="col-md-6">
							<!-- general form elements -->
							<!-- Покупка -->
							<div class="box-body">
								<?php require_once "templates/standart/tpl/modules/buy/buy.php"; ?>
								<!-- /Покупка -->
								<!-- Покупка -->
								<?php require_once "templates/standart/tpl/modules/donate/donate.php"; ?>
								<!-- /Покупка -->
								<!-- Последние покупатели -->
								<?php require_once "templates/standart/tpl/modules/buy/last.php"; ?>
								<!-- /Последние покупатели -->
								<!-- Последние донатеры -->
								<?php require_once "templates/standart/tpl/modules/donate/top5.php"; ?>
								<!-- /Последние донатеры -->
								<!-- Список серверов -->
                <?php require_once "templates/standart/tpl/modules/servers/servers.php";?>
                <?php require_once "templates/standart/tpl/modules/skins/last.php";?>
								<!-- /Список серверов -->
							</div></div>
							<div class="col-md-6">
							<div id="selected_server"></div>
              <!-- general form elements disabled -->
              <div class="card card-<?php echo $colour;?>">
                <div class="card-header">
                  <h3 class="card-title"> Помощь игрокам, ответы на вопросы.</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="callout callout-<?php echo $colour;?>">
                    <ul style="list-style: square outside;margin-bottom:0;">
                      <center>
                      </center>
                      <li><b>Сейчас! Не упусти момент - действуют скидки!</b><br>На данный момент
                      действуют скидки на всех привилегии,<br> цены указаны с учетом
                      <i>СКИДОК.</i></li>
                      <li><b>Как активировать мою купленную привилегию?</b><br>Заходим в CS 1.6,
                      открываем консоль на клавишу "Ё" и вводим:<br> setinfo _pw "ваш пароль от
                      привилегии" и нажимаем кнопочку: <i>ENTER.</i></li>
                      <li><b>Когда моя привилегия заработает?</b><br>Привилегия активируется после
                      смены карты на сервере.</li>
                      <li><b>Что делать если мне пишет: "Kicked: You do not have access to this
                      server"</b><br>Значит вы не активировали свою привилегию, это защита от
                      взлома вашей привилегии. Открываем консоль на клавишу "Ё" и вводим:<br>
                      setinfo _pw "ваш пароль от привилегии" и нажимаем кнопочку: <i>ENTER.</i>
                      </li>
                      <li><b>Обновление серверов и информация в описании.</b><br>Уважаемые игроки,
                      обновление серверов происходит очень часто и всегда обновлять описание
                      привилегий попросту нет времени. Всё, что есть на сайте - есть и на сервере.
                      Иногда, даже больше. Так что, смотрите описание и плюшки на самом сервере.
                      </li>
                      <li><b>Что делать, если я не могу купить привилегию на сайте?</b><br>Для начала
                      посмотрите видео, может оно вам поможет. Если оно не помогло, то пишите <a href="<?php echo $sozdatel;?>" target="_blank" style="color:#00000;">[Владельцу проекта]</a></li>
                      <li><b>Что такое личный кабинет?</b><br>Личный кабинет служит для управления
                      вашей привилегией. В нём вы можете поменять пароль, ник и многое другое. Для
                      этого, зайдите в <a href="<?php echo $url;?>auth/" target="_blank" style="color:#00000;">[Личный кабинет]</a></li>
                      <li><b>Как пользоваться личным кабинетом?</b><br>После покупки привилегии, вашим
                      ником в личном кабинете будет ваш игровой ник, на который вы купили
                      привилегию. А пароль - ваш пароль от купленной вами привилегии. Запомните
                      эти данные и никому их не передайте.</li>
                      <li><b>Для чего нужен Ник?</b><br>Ник, нужен для привилегии, именно на ник,
                      который вы укажите при покупке, вам будет выдана привилегия. Ник надо
                      написать на английском языке или цифрами. Возможно написать ник на
                      английском языке, и использовать в нём цифры.</li>
                      <li><b>Для чего нужен Пароль?</b><br>Пароль вам нужен, чтобы защитить свой
                      аккаунт от взлома. Пароль вы можете придумать любой. (Не менее 6-ти символом
                      и на английском языке). <br> Возможно написать пароль на английском языке,
                      вместе с цифрами. Вам нужно ОБЯЗАТЕЛЬНО запомнить свой ник и пароль, имеено
                      с ними вы зайдете на сервер.</li>
                      </ul>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->

            </div>
            <!--/.col (right) -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
			<!--- bottom --->
			<?php require_once "templates/standart/tpl/body/bottom.php";?>
			<!--- bottom --->
			<!--

/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
|        $ Site developed by TexyMC.ru $   |
|        $ Contacts - vk.me/texymcru $     |
|        $ Site - texymc.ru $              |
|        © TexyMC 2017-2020                |
\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/ 

-->
</body>

</html>