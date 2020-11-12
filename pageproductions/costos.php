<?php

session_start();

if (!isset($_SESSION['rol'])) {
		header('location: ../cerrar.php');
} else {
	if ($_SESSION['rol'] == 2 || $_SESSION['rol'] == 1) {
		header('location: ../cerrar.php');
	}
}

include_once '../conexion.php';


if (isset($_POST["producto"])) {
	try {
		$db = new Database();
		extract($_POST);
		$consulta = $db->connect()->prepare("insert into ordenproduccion (codOrden, estado, codProd, costototal, canthoras, cifmonto, cantprod, cstmobra, cstunif) 
			VALUES (:codOrdenP, :estadoP, :codProdP, :costototalP, :canthorasP, :cifmontoP, :cantprodP, :cstmobraP, :cstunifP)");
	
		$result = $consulta->execute(
		  array(
			':codOrdenP' => $codOrden,
			':estadoP' => $estado,
			':codProdP' => $producto,
			':costototalP' => $costfinal,
			':canthorasP' => $cantHoras,
			':cifmontoP' => $cif,
			':cantprodP' => $cantPro,
			':cstmobraP' => $cxh,
			':cstunifP' => $costUni
		  )
		);
	/*
		if (!$result) {
		  $fmsg = "Transacción no pudo ser ingresada.";
		  return;
		} else {
		  for ($i = 1; $i <= $addCuenta; $i++) {
			$consulta = null; // obligado para cerrar la conexión
			$db = null;
			$db = new Database();
	
			$consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) 
				VALUES (:codT, :codCuenta, :codTipo, :montou)");
	
			$result = $consulta->execute(
			  array(
				':codT' => $codigoT,
				':codCuenta' => $POST["cuenta" . $i],
				':codTipo' => $POST["tipo" . $i],
				':montou' => $POST["monto" . $i]
			  )
			);
		  }
	
		  if ($cuentaiva != 0) {
			$consulta = null; // obligado para cerrar la conexión
			$db = null;
			$db = new Database();
	
			$consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) 
				VALUES (:codT, :codCuenta, :codTipo, :montou)");
	
			$result = $consulta->execute(
			  array(
				':codT' => $codigoT,
				':codCuenta' => $cuentaiva,
				':codTipo' => $tipoiva,
				':montou' => $montoiva
			  )
			);
		  }
		}
		if (!$result) {
		  $fmsg = "Transacción no pudo ser ingresada.";
		  return;
		} else {
		  for ($i = 1; $i <= $addCuenta; $i++) {
			if ($POST["tipo" . $i] == 4) {
			  $consulta = null; // obligado para cerrar la conexión
			  $db = null;
			  $db = new Database();
			  $consulta = $db->connect()->prepare("insert into inventario(cantidadInven, costoUnitario, total, fecha, codTrans, codTipo) VALUES ( :cantidadInven, :costoU, :total, :fecha, :codT, :codtipo)");
	
			  $result = $consulta->execute(
				array(
				  ':cantidadInven' => $POST["cantP" . $i],
				  ':costoU' => $POST["precioU" . $i],
				  ':total' => $POST["monto" . $i],
				  ':fecha' => date("Y-m-d"),
				  ':codT' => $codigoT,
				  ':codtipo' => $POST["tipoT" . $i]
				)
			  );
	
			  $codcuentacom = $POST["cuenta" . $i];
	
			  $db = null;
			  $db = new Database();
			  $query = $db->connect()->query("SELECT * FROM cuentas WHERE codCuenta like '1131%' and codCuenta =" . $codcuentacom . "");
			  $data = $query->fetchAll();
	
			  if (!empty($data)) {
	
				$db = null;
				$db = new Database();
				$query = $db->connect()->query("SELECT * FROM materiasprima where nombreMateria = '" . $data[0][1] . "'");
				$materia = $query->fetchAll();
	
				$nuevomonto = $materia[0]['monto'] + $POST["monto" . $i];
				$nuevacant = $materia[0]['cantidad'] + $POST["cantP" . $i];
				$nuevocu = $nuevomonto / $nuevacant;
				$nombreMat = $materia[0]['nombreMateria'];
	
				$db = null;
				$db = new Database();
				$consulta = $db->connect()->prepare("update materiasprima set costounitario=:costoU,
						 cantidad=:cant, monto=:montoM where nombreMateria =:materia");
	
				$result = $consulta->execute(
				  array(
					':costoU' => $nuevocu,
					':cant' => $nuevacant,
					':montoM' => $nuevomonto,
					':materia' => $nombreMat
				  )
				);
	
				if (!$result) {
				  $fmsg = $e;
				} else {
				  $smsg = "Se ingreso la transaccion";
				}
			  }
			}
			$smsg = "Se ingreso la transaccion";
		  }
		}*/
	  } catch (Exception $e) {
		$fmsg = $e; //"Transacción ya existe.";
		//echo "Error" . $e;
	  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Sistema de contabilidad</title>
	<!-- plugins:css -->
	<link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="../vendors/base/vendor.bundle.base.css">
	<!-- endinject -->
	<!-- plugin css for this page -->
	<link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- endinject -->
	<link rel="shortcut icon" href="../images/favicon.png" />
	<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500&display=swap" rel="stylesheet">
</head>

<body>
	<div class="container-scroller">
		<!-- partial:partials/_navbar.html -->

		<?php
		include("../components/navbar.php")
		?>
		<!-- Sidebar partial -->
		<div class="container-fluid page-body-wrapper">
			<!-- partial:partials/_sidebar.html -->
			<?php
			include("../components/sidebar.php")
			?>
			<!-- Main -->


			<div class="main-panel">
				<div class="content-wrapper">
					<div class="row">
						<div class="col-md-12 grid-margin">
							<div class="d-flex justify-content-between flex-wrap">
								<div class="d-flex align-items-end flex-wrap">
									<div class="mr-md-3 mr-xl-5">
										<br>
										<h2 style="position: relative; left: 12px;" style="font-family: 'Rajdhani', sans-serif;">Producción</h2>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<h3 style="text-align: center;">Orden de Fabricación</h3>
								<hr noshade='noshade'>
								<h2 class="card-title" style="text-align: center;">Producto</h2>
								<hr noshade='noshade'>
								<form class="forms-sample" method="POST" action="" style="text-align: center;">
									<div class="row">
										<div class="col-md-5">
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="codOrden">Codigo de Orden:</label>
												<div class="col-sm-8">
													<input type="text" id="codOrden" name="codOrden" class="form-control" required="true">
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div class="form-group row">
												<label for="producto" class="col-sm-2 col-form-label">Producto:</label>
												<div class="col-sm-10">
													<select name="producto" id="producto" class="form-control">
														<option value="0">Elija un producto</option>
														<?php
														$db = new Database();
														$query = $db->connect()->query('SELECT * FROM productos');

														$data = $query->fetchAll();

														foreach ($data as $row) {
															echo "<option value=" . $row['codProd'] . ">" . $row['nombreProd'] . "</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-sm-4 col-form-label" for="cantPro">Cantidad de producto:</label>
												<div class="col-sm-8">
													<input type="number" id="cantPro" name="cantPro" class="form-control"
													 value="" placeholder="0.0" step="0.1" required="true">
												</div>
											</div>
										</div>
										<div class="form-group col-md-6">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text bg-primary text-white">Materias prima</span>
												</div>
												<select name="addMateria" id="addMateria" class="form-control">
													<option value="0">Elija una cantidad</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
												</select>
											</div>
										</div>
									</div>
									<span class="materias" id="materias"></span>
									<hr noshade='noshade'>
									<h2 class="card-title" style="text-align: center;">Mano de obra directa</h2>
									<hr noshade='noshade'>
									<div class="row">
										<div class="col-md-8">
											<div class="form-group">
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text bg-primary text-white">Cantidad de horas:</span>
													</div>
													<?php
															$db = new Database();
															$query = $db->connect()->query('SELECT * FROM valoresbase WHERE nombreValor = \'COSTOXHORA\' or nombreValor = \'CIF\'');

															$data = $query->fetchAll();
													?>
													<input type="number" name="cantHoras" id="cantHoras" class="form-control col-md-12"
													  required="true" placeholder="1" onchange="calcularxhora(<?php echo $data[1]['valor'];?>, <?php echo $data[0]['valor'];?>)"
													  min="0">
													<div class="input-group-append">
														<span class="input-group-text bg-primary text-white">
															<?php
															echo "$".$data[1]['valor'];
															?></span>
													</div>
													<input type="text" value="" id="cxh" name="cxh" readonly='readonly'>
												</div>
											</div>
										</div>
									</div>
									<hr noshade='noshade'>
									<div class="row">
										<div class="form-group col-md-6">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text bg-primary text-white">Costo Indirecto de Fabricación</span>
												</div>
												<input type="text" name="cif" id="cif" class="form-control col-md-12" 
												aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly='readonly'>
											</div>
										</div>
										<div class="form-group col-md-6">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text bg-primary text-white">Costo Unitario Total</span>
												</div>
												<input type="text" name="costUni" id="costUni" class="form-control col-md-12"
												readonly='readonly' value="">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text bg-primary text-white">Costo Total</span>
												</div>
												<input type="text" name="costfinal" id="costfinal" class="form-control col-md-12" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly='readonly'>
											</div>
										</div>
										<div class="col-md-6">
											<div class="input-group mb-3">
												<div class="input-group-prepend">
													<span class="input-group-text bg-primary text-white">Estado</span>
												</div>
												<select name="estado" id="estado" class="form-control">
													<option value="EN PROCESO">EN PROCESO</option>
													<option value="FINALIZADO">FINALIZADO</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
										<?php if (isset($smsg)) { ?>
											<div class="alert alert-success" role="alert"> <?php echo $smsg; ?></div>
										<?php }
										if (isset($fmsg)) { ?>
											<div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div>
										<?php } ?>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-success mr-2">Registrar</button>
								</form>
							</div>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-12 stretch-card">
						<div class="card">
							<div class="card-body">
								<p class="card-title">Registros recientes</p>
								<div class="table-responsive">
									<table id="ordenestable" class="table">
										<thead>
											<tr>
												<th>Codigo Orden</th>
												<th>Producto</th>
												<th>Cantidad Producto</th>
												<th>Costo Total</th>
												<th>Cantidad de horas</th>
												<th>Total de CIF</th>
												<th>Costo Mano de Obra</th>
												<th>Estado</th>
												<th>Fecha</th>
												<th>Costo Unitario</th>
												<th>Opcion</th>
											</tr>
										</thead>
										<tbody>
										<?php
											$db = new Database();
											$query = $db->connect()->query('select o.codOrden, p.nombreProd, o.cantprod, o.costototal,
											 o.canthoras, o.cifmonto, o.cstmobra, o.estado, o.fecha, o.cstunif from ordenproduccion o 
											inner join productos p on p.codProd = o.codProd order by o.fecha desc limit 10');

											$data1 = $query->fetchAll();

											foreach ($data1 as $row) {
												echo "<tr>" . PHP_EOL
												. "<td> $row[0]</td>" . PHP_EOL
												. "<td> $row[1]</td>" . PHP_EOL
												. "<td> $row[2]</td>" . PHP_EOL
												. "<td> $row[3]</td>" . PHP_EOL
												. "<td> $row[4]</td>" . PHP_EOL
												. "<td> $row[5]</td>" . PHP_EOL
												. "<td> $row[6]</td>" . PHP_EOL
												. "<td> $row[7]</td>" . PHP_EOL
												. "<td> $row[8]</td>" . PHP_EOL
												. "<td> $row[9]</td>" . PHP_EOL;
												echo ($row[7]=="EN PROCESO") ? "<td> <a class='btn btn-inverse-warning btn-md' data-toggle='modal' href='#edit" . $row[0] . "'>
												<i class='mdi mdi-pencil'></i></a></td>". PHP_EOL : "<td></td>". PHP_EOL;
												echo "</tr>" . PHP_EOL;
										?>
											<div class="modal fade" id="edit<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<center>
															<h4 class="modal-title" id="myModalLabel">Finalizar Orden</h4>
															</center>
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														</div>
													<div class="modal-body">
														<div class="container-fluid">
															<h5>
																<center>Codigo: <strong><?php echo $row[0]; ?></strong></center>
															</h5>
															<p><center>Producto: <strong><?php echo $row[1]; ?></strong></center></p>
														</div> 
													</div>
													<div class="modal-footer">
															<button type="button" class="btn btn-info" data-dismiss="modal"> Cancelar</button>
															<a href="actionscosto.php?acc=edit&id=<?php echo $row[0] ?>" class="btn btn-danger"> Finalizar</a>
														</div>
													</div>
												</div>
											</div>
										<?php
											}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- content-wrapper ends -->
			<!-- partial:partials/_footer.html -->
			<?php
			include("../components/footer.php");
			?>
			<!-- partial -->
		</div>
		<!-- main-panel ends -->
	</div>
	<!-- page-body-wrapper ends -->
	</div>
	<!-- container-scroller -->

	<!-- plugins:js -->
	<script src="../vendors/base/vendor.bundle.base.js"></script>
	<!-- endinject -->
	<!-- Plugin js for this page-->
	<script src="../vendors/chart.js/Chart.min.js"></script>
	<script src="../vendors/datatables.net/jquery.dataTables.js"></script>
	<script src="../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
	<!-- End plugin js for this page-->
	<!-- inject:js -->
	<script src="../js/off-canvas.js"></script>
	<script src="../js/hoverable-collapse.js"></script>
	<script src="../js/template.js"></script>
	<!-- endinject -->
	<!-- Custom js for this page-->
	<script src="../js/dashboard.js"></script>
	<script src="../js/data-table.js"></script>
	<script src="../js/jquery.dataTables.js"></script>
	<script src="../js/dataTables.bootstrap4.js"></script>
	<!-- End custom js for this page-->
	<script>
		$(document).ready(function() {
			$('#ordenestable').DataTable({
				"language": {
					"sProcessing": "Procesando...",
					"sLengthMenu": "Mostrar _MENU_ registros",
					"sZeroRecords": "No se encontraron resultados",
					"sEmptyTable": "Ningún dato disponible en esta tabla",
					"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix": "",
					"sSearch": "Buscar:",
					"sUrl": "",
					"sInfoThousands": ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst": "Primero",
						"sLast": "Último",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					},
					"buttons": {
						"copy": "Copiar",
						"colvis": "Visibilidad"
					}
				}
			});
		});
	</script>
	<script>
		$(document).ready(function() {
			$('#addMateria').on('change', do_something);
			$('#cantPro').on('change', costounitarioT);
			$('#addMateria').on('change', costounitarioT);
		});

		function do_something() {
			var selected = $('#addMateria').val();
			$.ajax({
				url: '/ProyectoContador/pageproductions/plantillamateria.php',
				type: 'POST',
				dataType: 'json',
				data: {
					addMateria: selected
				},
				success: function(datos) {
					$("#materias").empty();
					$('#materias').append(datos.data);
				}
			});
		}

		function materiau(posi) {
			var selected = $('#nombreMateria' + posi).val();
			$.ajax({
				url: '/ProyectoContador/pageproductions/plantillamateria.php',
				type: 'POST',
				dataType: 'json',
				data: {
					nombreMateria: selected
				},
				success: function(datos) {
					document.getElementById("costMateria" + posi).value = datos.data;
				}
			});
		}

		function calcularxhora(hora, vcif) {
			var base1 = $('#cantHoras').val();
			base1 = hora * base1;
			var base = base1.toFixed(2);
			var cif1 = base * vcif;
			var cif = cif1.toFixed(2);
			document.getElementById("cxh").value = base;
			document.getElementById("cif").value = cif;
			costounitarioT();
		}

		function mattotal(posi) {
			var cant = $('#cantMateria' + posi).val();
			var cost = $('#costMateria' + posi).val();
			var costfinal = cant * cost;
			document.getElementById("costMT" + posi).value = costfinal;
			costounitarioT();
		}

		function costounitarioT() {
			var totalcantP = $('#cantPro').val();
			totalcantP = parseFloat(totalcantP);
			var totalcxh = $('#cxh').val();
			totalcxh = parseFloat(totalcxh).toFixed(2);
			var totalcif = $('#cif').val();
			totalcif = parseFloat(totalcif);

			var e = document.getElementById("addMateria");
			var nummaterias = e.options[e.selectedIndex].value;

			var costMT, antes;
			costMT = parseFloat(0);
			var totalcostounitarioT = 0;
			var costofinal = parseFloat(0);
			for (let i = 1; i <= nummaterias; i++) {
				antes = parseFloat($('#costMT' + i).val());
				costMT = costMT + antes;
				costMT = parseFloat(costMT);
			}
			costMT = parseFloat(costMT);
			totalcostounitarioT = ((parseFloat(costMT) + parseFloat(totalcxh) + parseFloat(totalcif))/ parseFloat(totalcantP)).toFixed(2);
			costofinal = (parseFloat(costMT) + parseFloat(totalcxh) + parseFloat(totalcif)).toFixed(2);
			document.getElementById("costUni").value = totalcostounitarioT;
			document.getElementById("costfinal").value = costofinal;
		}
	</script>
</body>

</html>