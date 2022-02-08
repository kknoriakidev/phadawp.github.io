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
		<div class="content-wrapper" style="min-height: 256.031px;">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark">Покупка скинов</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Главная страница</a></li>
                <li class="breadcrumb-item active">Покупка скинов</li>
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
          <div class="col-md-12">
          <style>
.thumbnail {
    display: block;
    padding: 4px;
    margin-bottom: 20px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
}

.carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
    display: block;
    max-width: 100%;
    height: auto;
}
            </style>
                        <link href="templates/standart/dist/css/skins.css" rel="stylesheet" type="text/css" />
                        <script src="templates/standart/dist/css/skins3.js" type="text/javascript"></script>
                        <div class="nav-tabs-custom">
                        <div class="card card-danger card-tabs">
    <div class="card-header p-0 pt-1">
        
                            <?php require_once "inc/skins.php"; ?>
                        </div></div>
                    </div>
                </div>
            </section>
        </aside>



            <div id="modal-skins" class="modal fade" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                <div class="modal-header">
              <h5 class="modal-title">Купить <b class="skin_name"></b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
                  <div class="modal-body">
                    <center><img class="img-responsive skin_image" src="https://uradi-sam.rs/UserFiles/products/details" style="border-radius: 7px"></center>
                    <form role="form" action="/inc/skinbuy.php" method="POST" autocomplete="off">
                        <input type="hidden" class="id_skin" name="id_skin" value="0">
                        <div style="padding-top: 15px">
                            <label class="mlb"><span id="login_annotation"><i class="fa fa-user mri"></i> Игровой ник</span>: <small class="text-red sure">обязательно</small></label>
                            <input type="text" id="nickname" name="nickname" placeholder="Введите свой игровой ник" class="form-control fctr" maxlength="32" required="">
                        </div>
                        <div style="padding-top: 10px">
                            <label class="mlb"><span id="server_annotation"><i class="fa fa-server mri"></i> Выбранный сервер</span>:</label>
                            <input type="text" id="server_id" name="server_id" placeholder="Выбериете сервер" class="form-control fctr server_id" value="- - -" required="" disabled>
                        </div>
                        <div style="padding-top: 10px">
                            <input type="submit" class="btn btn-success btn-block skin_submit" value="Купить модель" name="submit">
                        </div>
                    </form>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>

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