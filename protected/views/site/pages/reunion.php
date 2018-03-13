<?php

if(!isset(Yii::app()->user->id)){
  header('Location: index.php?r=site/login');
}
?>
<?php
//session_start();


extract($_GET);
/*
if(empty($_SESSION['id_admin'])){
		//$_SESSION['pag'] = "paginaprohibida.php";
	echo '<script>window.location ="http://riesgoempresa.cl/mininco/index.php?r=site/page&view=login&returnurl=terreno/moduloadmin/admin.php"</script>';
	}
	*/
?>

<!DOCTYPE html>
<html lang="en" class="app">
<head>
	<meta charset="utf-8" />
	<title>Monitoreo en Sedecc</title>
	<!--<link rel="shortcut icon" type="image/x-icon" href="http://riesgoempresa.cl/terreno/favicon.ico"/>-->
	<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="../css/animate.css" type="text/css" />
    <link rel="stylesheet" href="../css/mio.css" type="text/css" />
	<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="../css/icon.css" type="text/css" />
	<link rel="stylesheet" href="../css/font.css" type="text/css" />
	<link rel="stylesheet" href="../css/app.css" type="text/css" />
</head>

<body class="">
	<section class="vbox">

    <section>
      <section class="hbox stretch">

        <!-- /.aside -->
        <section id="content">
          <section class="hbox stretch">
            <section>
              <section class="vbox">
                <section class="scrollable padder">

<div class="panel-body table-responsive ">
    <h2 align="center" class="letraBacan">RESUMEN TABLA CAPACITACIÓN/INSPECCIÓN/REUNION</h2>
	<br>

	<section class="panel panel-default">

		<div class="row wrapper">

			<div class="col-sm-3 m-b-xs">
				<!-- <select id="area3" class="input-sm form-control m-b inline v-middle" required/></select> -->
			</div>
			<div class="col-sm-2 m-b-xs"></div>
			<div class="col-sm-2 m-b-xs">
				<a href="http://innoapsion.cl/sedecc/mapa_tripode.php"><input type="submit" name="btn_mapa" value="Mapa" style="background-color: #f2f4f8; color: #12131a; padding-top: 3px; padding-bottom: 3px; padding-right: 30px; padding-left: 30px;"></a>
				<!--
					<a id="exportar_xls" href="exportar.php" alt="Exportar a Excel" class="btn btn-sm btn-success" onclick="updateurl();"><i class="i i-file-excel"></i></a>
					<a id="exportar_pdf" href="Resumen_evaluaciones_SSO.php" alt="Exportar a PDF" class="btn btn-sm btn-danger" target="_blank" onclick="updateurl();"><i class="i i-file-pdf"></i></a>
					<a href="#" class="btn btn-sm btn-primary" alt="Enviar al Correo" data-toggle="modal" data-target="#myModal" onClick="FLTRAR()" ><i class="i i-mail"></i></a>
					<script>
						function updateurl(){
							a = document.getElementById('area3').value;
							e = document.getElementById('buscar3').value;
							document.getElementById('exportar_xls').href = 'exportar.php?a='+a+'&e='+e;
							document.getElementById('exportar_pdf').href = 'Resumen_evaluaciones_SSO.php?a='+a+'&e='+e;
						}
					</script>
				-->
			</div>
			<div class="col-sm-2 m-b-xs">
			</div>
			<div class="col-sm-3 m-b-xs">
				<input class="input-sm form-control input-m-sm inline v-middle" type="search" placeholder="Buscar por Actividad o Nombre EESS" maxlength="10" name="buscar3" id="buscar3">
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-striped b-t b-light">
				<thead>
					<tr align="center">
						<th style="text-align: center" class="th-sortable" data-toggle="class">Actividad</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Rut EESS</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Nombre EESS</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Rep. Legal Gerente</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Asesor (APR)</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Lugar</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Fecha</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Georeferencia</th>
						<th style="text-align: center" class="th-sortable" data-toggle="class">Seguimiento</th>
					</tr>
				</thead>
				<tbody id="datos" style="font-size:12px;">
				</tbody>
			</table>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-center">
					<ul class="pagination" id="paginador" name="paginador"></ul>
				</div>
			</div>
		</footer>
	</section>

</div>





          <!-- Button trigger modal -->



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Exportar a Correo</h4>
			</div>




			<script>
				function FLTRAR(){
				a = document.getElementById('area3').value;
				e = document.getElementById('buscar3').value;
				 document.getElementById("areaF").value = a;
				 document.getElementById("buscF").value = e;
				}
				</script>


			<form id="filtrar" action="email.php" method="post" data-form-title="Formulario de contacto">
				<div class="modal-body">
					<input type="hidden" value="Bhc8uKnbVUBWpKTvtsHaVV68Y+YN7KjO9wDbFqVv7Nu7yyBzKfrZT5yMsieX+F1f7UWGezCFUno1ehKOrk4Q4nipEPxiuJdBKP/JLgY7x7x+aVZK5wOZ4OvwF4DspvRM" data-form-email="true">

					<div class="form-group">
						<input type="hidden" class="form-control" name="email1" required="" value='<?php //echo $_SESSION['correo_admin'];?>'  data-form-field="Email">
					</div>

					<!-- INICIO DATOS FILTRO -->
					<div class="form-group">
						<input class="form-control" type="hidden" name="areaF" id="areaF">
					</div>
					<div class="form-group">
						<input class="form-control" type="hidden" name="buscF" id="buscF">
					</div>
					<!-- FIN DATOS FILTRO -->

					<div class="form-group">
						<input type="hidden" class="form-control" name="cod" required="" value='1'>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" name="email"   required="" placeholder="Ingrese su correo electrónico" data-form-field="Email">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="asunto" required="" placeholder="Ingrese el asunto" data-form-field="tex">
					</div>
					<div class="form-group">
						<textarea class="form-control" id="message"  name="message" rows="7" placeholder="Ingrese el mensaje" data-form-field="Message"></textarea>
					</div>
						<h3>Archivos Adjuntos</h3>
						<input style="width: 20px;	height: 20px;" type="checkbox" name="pdf" value="1" checked ><img src="../images/pdf.jpg" width="30" height="30">

						<input style="width: 20px;	height: 20px;" type="checkbox" name="excel" value="1" checked><img src="../images/excel.jpg" width="30" height="30">
						<br>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button  type="submit" class="btn btn-primary" ><i class="i i-mail" ></i> Enviar correo</button>

				</div>
			</form>
		</div>
	</div>
</div>


                </section>
              </section>
            </section>



            <!-- side content -->
            <!-- / side content -->
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
      </section>
    </section>
  </section>


  <script src="../js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../js/bootstrap.js"></script>
  <!-- App -->
  <script src="../js/app.js"></script>
  <script src="../js/slimscroll/jquery.slimscroll.min.js"></script>
  <script src="js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
  <script src="../js/app.plugin.js"></script>
  <!--<script src="bootstrap/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>-->
  <script src="js/main.js"></script>

  <script src="js/alertify.js"></script>
  <script src="../js/filtro.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="js/jquery.table2excel.js"></script>
  <script LANGUAGE="JavaScript">
function abreSitio(){
var URL = "http://";
var web = document.form5.sitio.options[document.form5.sitio.selectedIndex].value;
window.open(URL+web, '_blank', '');
}
</script>
  <script type="text/javascript">

    var paginador;
    var totalPaginas
    var itemsPorPagina = 10;
    var numerosPorPagina = 5;

    function creaPaginador(totalItems)
    {
      paginador = $(".pagination");

      totalPaginas = Math.ceil(totalItems/itemsPorPagina);

      $('<li><a href="#" class="first_link"><</a></li>').appendTo(paginador);
      $('<li><a href="#" class="prev_link">«</a></li>').appendTo(paginador);

      var pag = 0;
      while(totalPaginas > pag)
      {
        $('<li><a href="#" class="page_link">'+(pag+1)+'</a></li>').appendTo(paginador);
        pag++;
      }


      if(numerosPorPagina > 1)
      {
        $(".page_link").hide();
        $(".page_link").slice(0,numerosPorPagina).show();
      }

      $('<li><a href="#" class="next_link">»</a></li>').appendTo(paginador);
      $('<li><a href="#" class="last_link">></a></li>').appendTo(paginador);

      paginador.find(".page_link:first").addClass("active");
      paginador.find(".page_link:first").parents("li").addClass("active");

      paginador.find(".prev_link").hide();

      paginador.find("li .page_link").click(function()
      {
        var irpagina =$(this).html().valueOf()-1;
        cargaPagina(irpagina);
        return false;
      });

      paginador.find("li .first_link").click(function()
      {
        var irpagina =0;
        cargaPagina(irpagina);
        return false;
      });

      paginador.find("li .prev_link").click(function()
      {
        var irpagina =parseInt(paginador.data("pag")) -1;
        cargaPagina(irpagina);
        return false;
      });

      paginador.find("li .next_link").click(function()
      {
        var irpagina =parseInt(paginador.data("pag")) +1;
        cargaPagina(irpagina);
        return false;
      });

      paginador.find("li .last_link").click(function()
      {
        var irpagina =totalPaginas -1;
        cargaPagina(irpagina);
        return false;
      });

      cargaPagina(0);




    }
function abrirModal(){
	$('#myModal1').modal('show');
}

function cargaPagina(pagina){

	var desde = pagina * itemsPorPagina;
	var area = $('#area3').val();
 	var buscar = $('#buscar3').val();

	$.ajax({
		data:{"param1":"dame","limit":itemsPorPagina,"offset":desde,"area":area,"buscar":buscar},
        type:"GET",
        dataType:"json",
        cache:false,
        url:"conexionReunion.php"
	}).done(function(data,textStatus,jqXHR){

		var lista = data.lista;

        $("#datos").html("");

        $.each(lista, function(ind, elem){
			var roves1;
			var roves2;
			var roves3;
			var roves4;
			var roves5;
			var roves6;
			var roves7;
			var roves8;
			var semaforo;

			if (elem.sema == 3) {
					semaforo = "<img style='height:25px;' src='../images/semaforo_rojo.png'>";
				}else if (elem.sema == 2) {
					semaforo = "<img style='height:25px;' src='../images/semaforo_amarillo.png'>";
				}else if (elem.sema == 1) {
					semaforo = "<img style='height:25px;' src='../images/semaforo_verde.png'>";
				}else if (elem.sema == 0) {
					semaforo = "S/A";
				}

				roves1="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.reu_tipo+" </a></b>";
				roves2="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.eess_rut+"</a></b>";
				roves3="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.eess_razon_social+"</a></b>";
				roves4="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.eess_representante+"</a></b>";
				roves5="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.eess_apr+"</a></b>";
				roves6="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.reu_lugar+"</a></b>";
				roves7="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.reu_tiempo+"</a></b>";
				roves8="<b><a href=reunionD.php?id="+elem.reu_id+">"+elem.geo+"</a></b>";
				roves9="<b><a href=reunionD.php?id="+elem.reu_id+">"+semaforo+"</a></b>";

			$("<tr>"+
			"<td style='text-align: center'>"+roves1+"</td>"+
			"<td style='text-align: center'>"+roves2+"</td>"+
			"<td style='text-align: center'>"+roves3+"</td>"+
			"<td style='text-align: center'>"+roves4+"</td>"+
			"<td style='text-align: center'>"+roves5+"</td>"+
			"<td style='text-align: center'>"+roves6+"</td>"+
			"<td style='text-align: center'>"+roves7+"</td>"+
      		"<td style='text-align: center'>"+roves8+"</td>"+
      		"<td style='text-align: center'>"+roves9+"</td>"+
			"</tr>").appendTo($("#datos"));
        });
	}).fail(function(jqXHR,textStatus,textError){
		alert("Error al realizar la peticion dame".textError);

	});
/*
	if(pagina >= 1){
		paginador.find(".prev_link").show();
	}
	else{
		paginador.find(".prev_link").hide();
	}
	if(pagina <(totalPaginas- numerosPorPagina)){
		paginador.find(".next_link").show();
	}else
      {
        paginador.find(".next_link").hide();
      }

      paginador.data("pag",pagina);

      if(numerosPorPagina>1)
      {
        $(".page_link").hide();
        if(pagina < (totalPaginas- numerosPorPagina))
        {
          $(".page_link").slice(pagina,numerosPorPagina + pagina).show();
        }
        else{
          if(totalPaginas > numerosPorPagina)
            $(".page_link").slice(totalPaginas- numerosPorPagina).show();
          else
            $(".page_link").slice(0).show();

        }
      }

      paginador.children().removeClass("active");
      paginador.children().eq(pagina+2).addClass("active");*/


    }


$(function(){
	cargarTabla();
});

function cargarTabla(){
	$("#mitabla").empty();
	$("#paginador").empty();
	$.ajax({
		data:{"param1":"cuantos"},
        type:"GET",
        dataType:"json",
        cache:false,
        url:"conexionReunion.php"
	}).done(function(data,textStatus,jqXHR){
		var total = data.total;
        creaPaginador(total);

	}).fail(function(jqXHR,textStatus,textError){
        alert("Error al realizar la peticion cuantos".textError);
	});
}

function excel() {
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#tabla').html()));
	e.preventDefault();
}

$('#area3').on('change', function(){
   cargarTabla();
});

$('#buscar3').on('keydown', function(){
   cargarTabla();
});

    </script>
    <style>
    	.pagination{
    		display:none;
    	}
    </style>
</body>
</html>
