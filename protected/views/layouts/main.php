<!DOCTYPE html>
<html lang="en" class="app">
<head>
  <meta charset="utf-8" />
  <title>SEDECC</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/icon.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <link rel="stylesheet" href="css/difusion.css" type="text/css" />
  <link rel="stylesheet" href="js/calendar/bootstrap_calendar.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
  <style>
    .marg{
          padding-left: 0px !important;
          padding-right: 0px !important;
        }
  .form-control{
   -webkit-box-shadow: inset 4px 4px 10px -2px rgba(0,0,0,0.75);
        -moz-box-shadow: inset 4px 4px 10px -2px rgba(0,0,0,0.75);
        box-shadow: inset 4px 4px 10px -2px rgba(0,0,0,0.75);
  }
  	.grid-view .summary{
  		display:none;
  	}
    .letraB{
      color:white;
    }
.aside-md li a:hover,
    .aside-md li ul li a:hover,
    .aside-md li:hover,
    .aside-md li ul li:hover
    {
      background: #219ebd !important;
    }
    body{
    	  background-image: url(img/2.jpg);
          background-size: 100% 100%;
    }
    .menuBorder{
      width: 280px !important;
      border-radius: 0px 30px 30px 0px !important;
    }
    .paddli{
      padding-top: 2px;
      padding-bottom: 2px;
    }

    .active a {
    background-color: #e6b548 !important;
}
.button-column a img{
	width:25px; height: 25px;
}

.errorMessage{
	background:#C9302C;
	color:#ffffff;
	padding:3px;
	font-size:8pt;
	line-height: 12px;
}
#imagencriterio{
  text-align: left;
  width: 1100px;
  background-color: #eee;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
  </style>



</head>
<body class="">
  <section class="vbox" >
    <header class="bg-white header header-md navbar navbar-fixed-top-xs box-shadow" style="background-color: #e6b548;">
      <div class="navbar-header aside-md dk" style="width:10%;">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
          <i class="fa fa-bars"></i>
        </a>
        <a href="index.php" class="navbar-brand">
          <!--img src="images/logo.png" style="padding:10px; max-height:60px;"-->
          <img src="img/logo-web.png">
          <!-- <span class="hidden-nav-xs"><?php echo CHtml::encode(Yii::app()->name); ?></span> -->
        </a>
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".user">
          <i class="fa fa-cog"></i>
        </a>
      </div>
      <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user user">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="thumb-sm pull-left" style="width: 80px; height: 40px; margin-top: -10px; padding-right: 5px;">
            <?php
            // Lógica logo
			$dir = 'images/eess/';
			$flag = 0;
			// Intentar obtener logo si el usuario es EESS
			$directorio=opendir($dir);
			while ($archivo = readdir($directorio))
				if($archivo == Yii::app()->user->id.'.jpg'){
					echo '<img src="'.$dir.$archivo.'" style="width:100%; height: 100%;">';
					$flag = 1;
				}
			closedir($directorio);
			// Intentar obtener logo si el usuario es trabajador
			$directorio=opendir($dir);
			if($flag != 1){
				$eess = Yii::app()->db->createCommand("SELECT eess_rut FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
				while ($archivo = readdir($directorio))
				if($archivo == $eess.'.jpg'){
					echo '<img src="'.$dir.$archivo.'" style="width:100%; height: 100%;">';
					$flag = 1;
				}
				closedir($directorio);
			}
			// Logo default
			if($flag != 1) echo '<img src="https://cdn2.iconfinder.com/data/icons/rcons-user/32/male-circle-512.png" align="right" style="width:50px; height: 100%;">';
            ?>

            </span>
            <?php
            // Lógica nombre de usuario
            $nombre = Yii::app()->db->createCommand("SELECT eess_nombre_corto FROM min_eess WHERE eess_rut = '".Yii::app()->user->id."'")->queryScalar();
            if($nombre == '') $nombre = Yii::app()->db->createCommand("SELECT tra_nombres FROM min_trabajador WHERE tra_rut = '".Yii::app()->user->id."'")->queryScalar();
			if($nombre == '') $nombre = Yii::app()->user->id;
			echo $nombre;
            ?> <b class="caret"></b>
          </a>
          <ul class="dropdown-menu animated fadeInRight">
            <li>
              <a href="index.php?r=site/logout">Salir</a>
            </li>
          </ul>
        </li>
      </ul>
    </header>
    <section>
      <section class="hbox stretch">
        <!-- .aside -->
        <aside class="bg-black aside-md hidden-print  scrollable" id="nav" style="background-color: #217fbd; color: white;">
          <section class="vbox ">

          <!--
          <section class="w-f scrollable">
          <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">
          -->

            <section class="w-f">
          <div data-height="100%" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">



                <!-- nav -->
                <nav class="nav-primary hidden-xs">
                  <!--div class="text-muted text-sm hidden-nav-xs padder m-t-sm m-b-sm">Start</div-->
                  <ul class="nav nav-main" data-ride="collapse">
                  	<?php
                  	// Menú activo
                  	function active($menu){
                  		if(!isset($_GET['r']))
                        {
                  			     if($menu == '') return 'active';
						            }else{
              							if($menu != '' && strpos($_GET['r'],$menu) !== false){
              								//echo $_GET['r'];
              							 return 'active';
              							}elseif($menu != '' && $_GET['r'] == 'site/page' && strpos($_GET['view'],$menu) !== false){
              								//echo $_GET['r'].$_GET['view'];
              								return 'active';
              							}

						            }
						        return '';
                  	}
                    if(Yii::app()->user->id == '96960670' ){
                      $amecharv = '<li class="'.active('evaEquipos').'">
                        <a href="index.php?r=evaEquipos" class="menuBorder">
                          <img src="img/007-edit.png" width="7%;" class="paddli">
                          <span class="font-bold letraB">Evaluaciones de Equipos</span>
                        </a>
                      </li>
                      <li class="'.active('evalInstalaciones').'">
                        <a href="index.php?r=evalInstalaciones" class="menuBorder">
                          <img src="img/007-edit.png" width="7%;" class="paddli">
                          <span class="font-bold letraB">Evaluaciones de Instalaciones</span>
                        </a>
                      </li>';
                    }else{
                      $amecharv = '';
                    }

					// Empresa
					if(Yii::app()->controller->usertype() == 1) echo '
                  	<li class="'.active('').'">
                      <a href="index.php" class="menuBorder active">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Inicio</span>
                      </a>
                    </li>
                    <!--li>
                      <a href="index.php?r=area" class="menuBorder '.active('area').'">
                        <i class="i i-statistics icon"></i>
                        <span class="font-bold letraB">Áreas</span>
                      </a>
                    </li-->
                    <!--li>
                      <a href="index.php?r=cargo" class="menuBorder">
                        <i class="i i-statistics icon"></i>
                        <span class="font-bold letraB">Cargos</span>
                      </a>
                    </li-->
                    <!--li class="'.active('fundo').'">
                      <a href="index.php?r=fundo" class="menuBorder">
                        <img src="img/fundo.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Fundos</span>
                      </a>
                    </li-->
                    <li class="'.active('faena').'">
                      <a href="index.php?r=faena" class="menuBorder">
                        <img src="img/faena.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Faenas</span>
                      </a>
                    </li>

                    <li class="'.active('trabajador').'">
                      <a href="index.php?r=trabajador" class="menuBorder">
                        <img src="img/trabajador.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Trabajadores</span>
                      </a>
                    </li>
                   <li class="'.active('evaluacion').'">
                      <a href="index.php?r=evaluacion" class="menuBorder">
                        <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones</span>
                      </a>
                    </li>
                    '.$amecharv.'

                   <li class="'.active('mapa').'">
                      <a href="index.php?r=site/page&view=mapa" class="menuBorder">
                        <img src="img/map.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Mapa</span>
                      </a>
                    </li>
                    <li class="'.active('reporte2').'">
                      <a href="index.php?r=site/page&view=reporte2" class="menuBorder">
                        <img src="img/009-bar-chart.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Reporte de Información</span>
                      </a>
                    </li>
                    <li class="'.active('eess').'">
                      <a href="index.php?r=eess/update&id='.Yii::app()->user->id.'" class="menuBorder">
                        <img src="img/trabajador.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Perfil</span>
                      </a>
                    </li>
                  	';
					// Admin
					if(Yii::app()->controller->usertype() == 2) echo '
                  	<li class="'.active('').'">
                      <a href="index.php" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Inicio</span>
                      </a>
                    </li>
                    <!--li class="'.active('area').'">
                      <a href="index.php?r=area" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Áreas</span>
                      </a>
                    </li-->
                    <li class="'.active('cargo').'">
                      <a href="index.php?r=cargo" class="menuBorder">
                        <img src="img/cargos.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Cargos</span>
                      </a>
                    </li>
                    <li class="'.active('eess').'">
                      <a href="index.php?r=eess" class="menuBorder">
                        <img src="img/EESS.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Empresas de servicio</span>
                      </a>
                    </li>
                   <li class="'.active('evaluacion').'">
                      <a href="index.php?r=evaluacion" class="menuBorder">
                        <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones</span>
                      </a>
                    </li>
                    <li class="'.active('evaEquipos').'">
                      <a href="index.php?r=evaEquipos" class="menuBorder">
                        <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones de Equipos</span>
                      </a>
                    </li>
                    <li class="'.active('evalInstalaciones').'">
                      <a href="index.php?r=evalInstalaciones" class="menuBorder">
                        <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones de Instalaciones</span>
                      </a>
                    </li>
                    <li class="'.active('reunion').'">
                      <a href="index.php?r=site/page&view=reunion" class="menuBorder">
                        <img src="img/grafico.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Bitacoras</span>
                      </a>
                    </li>
                    <li class="'.active('pregunta').'">
                      <a href="index.php?r=pregunta" class="menuBorder">
                        <img src="img/preguntas.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Preguntas</span>
                      </a>
                    </li>
                    <!--li class="'.active('tematica').'">
                      <a href="index.php?r=tematica" class="menuBorder">
                        <img src="img/tematica.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Temáticas</span>
                      </a>
                    </li-->
                    <li class="'.active('trabajador').'">
                      <a href="index.php?r=trabajador" class="menuBorder">
                        <img src="img/trabajador.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Trabajadores</span>
                      </a>
                    </li>
                    <!--li class="'.active('evento').'">
                      <a href="index.php?r=evento" class="menuBorder">
                        <img src="img/trabajador.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Accidentes / Incidentes</span>
                      </a>
                    </li-->
                    <li class="'.active('fundo').'">
                      <a href="index.php?r=fundo" class="menuBorder">
                        <img src="img/fundo.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Fundos</span>
                      </a>
                    </li>
                    <li class="'.active('faena').'">
                      <a href="index.php?r=faena" class="menuBorder">
                        <img src="img/faena.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Faenas</span>
                      </a>
                    </li>
                    <li class="'.active('vehiculo').'">
                      <a href="index.php?r=vehiculo" class="menuBorder">
                        <img src="img/faena.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Vehículos</span>
                      </a>
                    </li>
                    <li class="'.active('accidente').'">
                      <a href="index.php?r=accidente" class="menuBorder">
                        <img src="img/faena.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Accidente</span>
                      </a>
                    </li>
                    <li class="'.active('usuario').'">
                      <a href="index.php?r=usuario" class="menuBorder">
                        <img src="img/004-user.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Usuarios</span>
                      </a>
                    </li>
                    <li class="'.active('mapa').'">
                      <a href="index.php?r=site/page&view=mapa" class="menuBorder">
                        <img src="img/map.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Mapa</span>
                      </a>
                    </li>
                    <li class="'.active('reporte2').'">
                      <a href="index.php?r=site/page&view=reporte2" class="menuBorder">
                        <img src="img/grafico.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Reporte de Información</span>
                      </a>
                    </li>
                    <li class="'.active('configuracion').'">
                      <a href="index.php?r=site/page&view=configuracion" class="menuBorder">
                        <img src="img/config.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Configuración</span>
                      </a>
                    </li>


                     <li>
              			<a href="#" data-toggle="modal" data-target="#criterio" class="menuBorder">
              				<img src="img/config.png" width="7%;" class="paddli">
                        	<span class="font-bold letraB">Criterios de calificación</span>
                        </a>
              		</li>
                  	';
					// Evaluador
					if(Yii::app()->controller->usertype() == 3) echo '
                  	<li class="'.active('').'">
                      <a href="index.php" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Inicio</span>
                      </a>
                    </li>
                    <!--li class="'.active('area').'">
                      <a href="index.php?r=area letraB" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Áreas</span>
                      </a>
                    </li-->
                    <!--li class="'.active('cargo').'">
                      <a href="index.php?r=cargo" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Cargos</span>
                      </a>
                    </li-->
                    <!--
                    <li class="'.active('faena').'">
                      <a href="index.php?r=faena" class="menuBorder">
                        <img src="img/faena.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Faenas</span>
                      </a>
                    </li>
                    -->
                    <li class="'.active('trabajador').'">
                      <a href="index.php?r=trabajador" class="menuBorder">
                        <img src="img/trabajador.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Trabajadores</span>
                      </a>
                    </li>
                    <li class="'.active('evaluacion').'">
                      <a href="index.php?r=evaluacion" class="menuBorder">
                       <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones</span>
                      </a>
                    </li>
                    <li class="'.active('mapa').'">
                      <a href="index.php?r=site/page&view=mapa" class="menuBorder">
                        <img src="img/map.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Mapa</span>
                      </a>
                    </li>
                    <li class="'.active('reporte2').'">
                      <a href="index.php?r=site/page&view=reporte2" class="menuBorder">
                        <img src="img/grafico.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Reporte de Información</span>
                      </a>
                    </li>
                  	';
                  	if(Yii::app()->controller->usertype() == 4) echo '
                  	<li class="'.active('').'">
                      <a href="index.php" class="menuBorder">
                        <img src="img/012-construction.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Inicio</span>
                      </a>
                    </li>
                  	<li class="'.active('evaluacion').'">
                      <a href="index.php?r=evaluacion" class="menuBorder">
                       <img src="img/007-edit.png" width="7%;" class="paddli">
                        <span class="font-bold letraB">Evaluaciones</span>
                      </a>
                    </li>
                  	';
                  	?>
                    <!--?php } ?-->
                  </ul>
                </nav>
                <!-- / nav -->
              </div>
            </section>
            <!--
            <footer class="footer hidden-xs no-padder text-center-nav-xs">
              <a href="index.php?r=site/logout" class="btn btn-icon icon-muted btn-inactive pull-right m-l-xs m-r-xs hidden-nav-xs">
                <i class="i i-logout"></i>
              </a>

              <a href="#nav" data-toggle="class:nav-xs" class="btn btn-icon icon-muted btn-inactive m-l-xs m-r-xs">
                <i class="i i-circleleft text"></i>
                <i class="i i-circleright text-active"></i>
              </a>

            </footer>
              -->
              <!-- Modal -->
<div class="modal fade" id="criterio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width:95%;">
    <div class="modal-content">
     <div class="modal-header" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Metodología de Calificación</h4>



	  <!--iframe src="http://docs.google.com/viewer?url=http://innoapsion.cl/mininco/images/metodologia.pdf&embedded=true    " frameborder="0" style="width:100%; height:600px; margin-bottom:-5px;">
			No hay soporte de iframes
	  </iframe-->

	  	<img id="imagencriterio" src="http://innoapsion.cl/sedecc/img/criterio.png" width="80%" height="100%">
		    <!--p>It appears you don't have a PDF plugin for this browser.
		    No biggie... you can <a href="myfile.pdf">click here to
		    download the PDF file.</a></p-->
  	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
          </section>
        </aside>
        <!-- /.aside -->
        <section id="content" style="padding-left:100px;">
          <section class="hbox stretch">
            <section>
              <section class="vbox">
                <section class="scrollable padder" style="overflow: auto;">
                  <?php echo $content; ?>
                </section>
              </section>
            </section>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
      </section>
    </section>
  </section>
  <script src="js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="js/bootstrap.js"></script>
  <!-- App -->
  <script src="js/app.js"></script>
  <script src="js/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
  <script src="js/charts/sparkline/jquery.sparkline.min.js"></script>
  <script src="js/charts/flot/jquery.flot.min.js"></script>
  <script src="js/charts/flot/jquery.flot.tooltip.min.js"></script>
  <script src="js/charts/flot/jquery.flot.spline.js"></script>
  <script src="js/charts/flot/jquery.flot.pie.min.js"></script>
  <script src="js/charts/flot/jquery.flot.resize.js"></script>
  <script src="js/charts/flot/jquery.flot.grow.js"></script>
  <script src="js/charts/flot/demo.js"></script>

  <script src="js/calendar/bootstrap_calendar.js"></script>
  <script src="js/calendar/demo.js"></script>

  <script src="js/sortable/jquery.sortable.js"></script>
  <script src="js/app.plugin.js"></script>

  <h1 id="mensaje_espere" style="width:100%; position:fixed; top:30%; z-index:10000; text-align:center; display:none;">
  	<img src="http://loadinggif.com/images/image-selection/32.gif"><br><br><br>
  	Espere mientras se genera el stock...
  </h1>

  <script type="text/javascript">
  	function quitarmarca(){
	  	var x = document.querySelectorAll('[title="JavaScript charts"]');
	  	var c = x.length;

	  	for(i=0;i<c;i++){
	  		document.querySelectorAll('[title="JavaScript charts"]')[0].remove();
	  	}
  	}
  	setTimeout(quitarmarca,100);
  </script>

</body>
</html>


<?php return;/* @var $this Controller */ ?>




	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div>
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>



		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
