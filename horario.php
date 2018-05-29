<?php 
	$a=1;
	include("modulos/conn.php");
	$as = mysqli_query($con,"SELECT * FROM materias WHERE estado_mat='1' ORDER BY abr_mat ASC");
	$d = mysqli_num_rows($as);
	$ab = '[';
	$fo = '[';
	$nom = '[';
	$co = '[';
	$i=0;
	while($row=mysqli_fetch_array($as)){
		$i++;
		if($i<$d){		
			$ab .= '"'.$row['abr_mat'].'",';
			$fo .= '"'.$row['fondo_mat'].'",';
			$nom .= '"'.$row['nombre_mat'].'",';
			$co .= '"'.$row['color_mat'].'",';
		}else{
			$ab .= '"'.$row['abr_mat'].'"';
			$fo .= '"'.$row['fondo_mat'].'"';
			$nom .= '"'.$row['nombre_mat'].'"';
			$co .= '"'.$row['color_mat'].'"';
		}
	}
	$ab .= ']';
	$fo .= ']';
	$nom .= ']';
	$co .= ']';
?>
<!doctype html>
<html lang="en">
<head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Generador de Horarios</title>
	<style type="text/css">
		table{
			border-collapse: collapse;
			width: 100%;
			text-align: center;
		}

		.grupo{
			width: 5% !important;
		}

		.espacios{
			width: 2%;
		}

		.asig{
			border: 1px solid #fff;
			width: 40px;
			text-align: center;
			padding: 3px;
			box-shadow: 2px 2px 3px #ccc;
			float: left;
			margin-right: 5px;
			font-weight: bold;
			font-family: 'Comic Sans MS';
			cursor: pointer;
			font-size: 12px;
		}

		.docente{
			color: #fff;
			font-size: 10px;
			border-top: 1px solid #fff;
		}

		.docente_t{
			color: #fff;
		}

		.asig_t{
			width: auto !important;
			font-size: 10px !important;
			border: 1px solid #000;
			text-align: center;
			box-shadow: 2px 2px 3px #ccc;
			font-weight: bold;
			font-family: 'Comic Sans MS';
			cursor: pointer;
		}
	</style>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  	<script src="js/bootstrap.js"></script>
  	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
  	<?php 
		echo '<script>var asign = '.$ab.'; var color = '.$co.'; var titulo = '.$nom.'; var fondo = '.$fo.';</script>';
  	?>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="alert alert-primary">
					<h4>Distribuci&oacute;n Manual de Horarios</h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label>Seleccione un Docente</label>
				<select id="docente" onchange="generar_asig()" class="form-control">
					<option disabled >Docentes</option>
					<option value="EVas?Eliecer Vasquez" selected>Eliecer Vasquez</option>
					<option value="JVas?Jose Vasquez">Jose Vasquez</option>
				</select>
			</div>
			<div class="col-lg-4 offset-lg-3" id="info_asig">
				
			</div>
		</div><br>
		<div class="row">
			<div class="col">
				<div id="asignaturas"></div><br>
			</div>
		</div>

		<table border="1">
			<thead>
				<tr>
					<th rowspan="2" class="grupo"></th>
					<th colspan="7" id="lunes">Lunes</th>
					<th colspan="7" id="martes">Martes</th>
					<th colspan="7" id="miercoles">Miercoles</th>
					<th colspan="7" id="jueves">Jueves</th>
					<th colspan="7" id="viernes">Viernes</th>
				</tr>
				<tr>
					<?php 
						for($i=1;$i<=5;$i++){
							for($j=1;$j<=7;$j++){
								echo '<th id="u_'.$a.'">'.$j.'</th>';
								$a++;
							}
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php 
					$dias = array("lunes","martes","miercoles","jueves","viernes");
					$grupos = mysqli_query($con,"SELECT * FROM grados WHERE estado_gra='1'");
					while($row=mysqli_fetch_array($grupos)){
						echo '<tr>';
						echo '<th class="grupo" id="'.$row['nombre_gra'].'">'.$row['nombre_gra'].'</th>';
						$a=1;
						for($i=1;$i<=5;$i++){
							for($j=1;$j<=7;$j++){
								echo '<td id="'.$row['nombre_gra'].'_'.$a.'_'.$dias[$i-1].'" class="espacios v_'.$a.'">';
								if($j>5){
									echo 'x';
								}
								echo '</td>';
								$a++;
							}
						}
						echo '</tr>';
					}
				?>
			</tbody>
		</table><br>
	</div>
	
	<script type="text/javascript">
		color_t = '';
		asig_t = '';
		doc_t = '';
		fondo_t = '';
		titulo_t = '';
		doc_m = '';
		$(document).ready(function(){
			$("td").mouseover(function(){
				var dato = $(this).attr("id");
				ss = dato.split("_");
				$("#u_"+ss[1]).css({"background":"#FF0"});
				$("#"+ss[0]).css({"background":"#FF0"});
				$("#"+ss[2]).css({"background":"#6FF695"});
			}).mouseout(function(){
				var dato = $(this).attr("id");
				ss = dato.split("_");
				$("#u_"+ss[1]).css({"background":"#FFF"});
				$("#"+ss[0]).css({"background":"#FFF"});
				$("#"+ss[2]).css({"background":"#FFF"});
			})

			$("td").mousedown(function(e){
				if(e.which==3){
					at = $(this).attr("alt");
					sss = at.split("*");
					$("#info_asig").html("Asignatura: <b>"+sss[4]+"</b><br>Docente: <b>"+sss[5]+"</b>");
				}
			})

			$(this).bind("contextmenu", function(e) {
                e.preventDefault();
             });
		})

		generar_asig();

		function generar_asig(){
			doc = $("#docente").val();
			doc_m = doc.split("?");
			$("#asignaturas").html("");
			for(i=0;i<asign.length;i++){
				$("#asignaturas").append("<div class='asig ui-widget-content' title='"+titulo[i]+"' alt='"+fondo[i]+"*"+asign[i]+"*"+doc+"*"+color[i]+"*"+titulo[i]+"' style='background: "+fondo[i]+";color:#"+color[i]+";'>"+asign[i]+"<br><span class='docente' style='color:#"+color[i]+";'>"+doc_m[0]+"</span></div>");
			}
			$("#asignaturas").append("<br><br>")
			$(".asig").draggable({
				drag: function(){
					dd = $(this).attr("alt");
					v = dd.split("*");
					color_t = v[3];
					asig_t = v[1];
					doc_t = v[2];
					doc_n = doc_t.split("?");
					fondo_t = v[0];
					titulo_t = v[4];
				},
				revert: true
			});
		}

		col = [];
		bus = [];
		conteo = 0;

		$("td").droppable({
			drop: function(){
				id = $(this).attr("id");
				verifica = $("#"+id).html();
				dd = id.split("_");
				if(verifica=='x'){
					$("#info_asig").html('<div class="alert alert-warning"><small>Este espacio no est&aacute; configurado para admitir modificaciones.</small>.</div>');
				}else{
					columna = dd[1];
					buscar = asig_t+'_'+doc_n[0];

					r = validar(columna,buscar);

					if(r){
						$("#info_asig").html('<div class="alert alert-danger"><small>El mismo docente no puede estar en dos cursos diferentes el mismo d&iacute;a a la misma hora</small>.</div>');
					}else{
						$(this).html("<div onclick='limpiar(&#39;"+id+"&#39;,&#39;"+conteo+"&#39;)' class='asig_t "+asig_t+"_"+doc_n[0]+"' style='background: "+fondo_t+";color:#"+color_t+";'>"+asig_t+"<br><span class='docente_t' style='color:#"+color_t+";'>"+doc_n[0]+"</span></div>");
						conteo++;
						$(this).attr("alt",fondo_t+"*"+asig_t+"*"+doc_t+"*"+color_t+"*"+titulo_t+"*"+doc_n[1]);
					}
				}
			}
		})

		function limpiar(id,indexelim){
			$("#"+id).html("");
			delete col[indexelim];
			delete bus[indexelim];
		}

		function validar(columna,buscar){
			d = false;
			for(i=0;i<col.length;i++){
				if(col[i]==columna){
					if(bus[i]==buscar){
						d = true;
					}
					s = bus[i].split("_");
					r = buscar.split("_");
					if(s[1]==r[1]){
						d = true;
					}
				}
			}
			if(d==false){
				col.push(columna);
				bus.push(buscar);
			}
			return d;
		}
	</script>
</body>
</html>