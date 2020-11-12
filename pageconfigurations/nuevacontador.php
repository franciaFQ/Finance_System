<?php
include_once '../conexion.php';

session_start();

if (!isset($_SESSION['rol'])) {
  header('location: ../cerrar.php');
} else {
  if ($_SESSION['rol'] == 2 || $_SESSION['rol'] == 3) {
    header('location: ../cerrar.php');
  }
} 

function insertar($POST, $montoT)
{
  try {
    $db = new Database();
    extract($POST);
    $montoT = $montoT;

    $consulta = $db->connect()->prepare("insert into transferencias (codTrans, montoTrans, detalles, fechatrans) 
        VALUES (:codT, :montoT, :detalles, :fechatrans)");


    $result = $consulta->execute(
      array(
        ':codT' => $codigoT,
        ':montoT' => $montoT,
        ':detalles' => $detalles,
        ':fechatrans' => date("Y-m-d")
      )
    );

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
        }else if ($POST["tipo" . $i] == 3) {
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

            $nuevomonto = $materia[0]['monto'] - $POST["monto" . $i];
            $nuevacant = $materia[0]['cantidad'] - $POST["cantP" . $i];
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
    }
  } catch (Exception $e) {
    $fmsg = $e; //"Transacción ya existe.";
    //echo "Error" . $e;
  }
}

if (isset($_POST['monto1']) && isset($_POST['monto2'])) {
  $montoD = 0;
  $montoH = 0;

  for ($i = 1; $i <= $_POST["addCuenta"]; $i++) {
    switch ($_POST["tipo" . $i]) {
      case 1:
        $montoD += $_POST["monto" . $i];
        break;
      case 2:
        $montoH += $_POST["monto" . $i];
        break;
      case 3:
      case 4:
        switch ($_POST["tipoT" . $i]) {
          case 1:
            $montoD += $_POST["monto" . $i];
            break;
          case 2:
            $montoH += $_POST["monto" . $i];
            break;
          default:
            break;
        }
        break;
      default:
        break;
    }
  }

  if ($_POST["tipoiva"] == 1) {
    $montoD += $_POST["montoiva"];
  } elseif ($_POST["tipoiva"] == 2) {
    $montoH += $_POST["montoiva"];
  }

  if ($montoD == $montoH) {
    insertar($_POST, $montoH);
  } else {
    $fmsg = "Montos deben de coincidir no pudo ser ingresada.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Contador-Sistema de contabilidad</title>
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

    <?php
    include("../components/navbar.php")
    ?>

    <!-- Sidebar partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php
      include("../components/sidebar.php");

      ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Main -->

          <div class="col-12 grid-margin">
            <h1>Nuevas transacciones</h1>
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Transacción</h4>
                <form class="form-sample" action="" method="POST" id="formcontador">
                  <p class="card-description">
                    Información de cuentas
                  </p>
                  <!-- cuenta uno -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="addCuenta">Cuentas a afectar</label>
                        <div class="col-sm-9">
                          <select class="form-control" name="addCuenta" id="addCuenta">
                            <option selected="true" value="0">Elija la cantidad de cuentas</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-6'>
                      <div class='form-group row'>
                        <label class='col-sm-3 col-form-label' for='codigoT'>Codigo de Transacción</label>
                        <div class='col-sm-9'>
                          <div class='input-group'>
                            <input type='text' class='form-control' name='codigoT' id="codigoT" required='true'>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr noshade="noshade" style="margin-top: -3.4%;" />
                  <span id="formularios">

                  </span>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Incluye IVA</label>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                          <div class="form-check form-check-danger">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" onclick="cuentaIva()" name="iva" id="iva0" value="0" checked>
                              No
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-check form-check-success">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" onclick="cuentaIva()" name="iva" id="iva1" value="1">
                              Sí
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-6'>
                      <div class='form-group row'>
                        <label for='detalles' class='col-sm-3 col-form-label'>Detalles</label>
                        <div class='col-sm-9'>
                          <input type='text' class='form-control' id='detalles' name='detalles' placeholder='Detalles' required='true'>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="ivadiv1" style="display: none">
                    <div class='col-md-6'>
                      <div class='form-group row'>
                        <label class='col-sm-3 col-form-label' for='cuentaiva'>Cuenta de iva</label>
                        <div class='col-sm-9'>
                          <select class='form-control' name='cuentaiva' id='cuentaiva'>
                            <option selected='true' value='0'>Seleccione una cuenta...</option>
                            <?php
                            $db = new Database();
                            $query = $db->connect()->query('SELECT * FROM cuentas where codCuenta in ( 114, 217)');

                            $data = $query->fetchAll();

                            foreach ($data as $row) {
                              echo "<option value=" . $row['codCuenta'] . "> No. " . $row['codCuenta'] . " - " . $row['nombreCuenta'] . "</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-6'>
                      <div class='form-group row'>
                        <label class='col-sm-3 col-form-label' for='montoiva'>Monto</label>
                        <div class='col-sm-9'>
                          <div class='input-group'>
                            <div class='input-group-prepend'>
                              <span class='input-group-text bg-primary text-white'>$</span>
                            </div>
                            <input type='number' class='form-control' aria-label='Monto en dolarés americanos' name='montoiva' id='montoiva' value='0.00' step='0.01' required='true'>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='row' id="ivadiv2" style="display: none">
                    <div class='col-md-6'>
                      <div class='form-group row'>
                        <label class='col-sm-3 col-form-label' for='tipoiva'>Tipo de Transferencia</label>
                        <div class='col-sm-9'>
                          <select class='form-control' name='tipoiva' id='tipoiva'>
                            <option selected='true'>Seleccione un tipo...</option>
                            <?php
                            $db = new Database();
                            $query1 = $db->connect()->query('SELECT * FROM tipotrans where codTipo = 1 or codTipo = 2');

                            $data1 = $query1->fetchAll();

                            foreach ($data1 as $row1) {
                              echo "<option value=" . $row1['codTipo'] . ">" . $row1['nombreTrans'] . "</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Acciones-->
                  <p class="card-description">
                    Acciones
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <input type="submit" class="btn btn-inverse-primary btn-fw" name="btn-registrar" value="Registrar" />
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
                </form>
              </div>
            </div>
          </div>
          <!-- partial -->
        </div>

        <div class="row">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Últimas transferencias</p>
                <div class="table-responsive">
                  <table id="transtable" class="table">
                    <thead>
                      <tr>
                        <th>Codigo Transferencia</th>
                        <th>Monto</th>
                        <th>Detalles</th>
                        <th>Tipo de transferencias</th>
                        <th>Cuenta afectada</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $db = new Database();
                      $query = $db->connect()->query('select t.codTrans, dt.montoU, t.detalles, tt.nombreTrans, c.nombreCuenta, t.fechatrans from transferencias t
                            inner join detallestransferencias dt on dt.codTrans = t.codTrans
                            inner join tipotrans tt on tt.codTipo = dt.codTipo
                            inner join cuentas c on c.codCuenta = dt.codCuenta 
                            order by t.fechatrans desc limit 10');

                      $data1 = $query->fetchAll();

                      foreach ($data1 as $row) {
                        echo "<tr>" . PHP_EOL
                          . "<td> $row[0]</td>" . PHP_EOL
                          . "<td> $row[1]</td>" . PHP_EOL
                          . "<td> $row[2]</td>" . PHP_EOL
                          . "<td> $row[3]</td>" . PHP_EOL
                          . "<td> $row[4]</td>" . PHP_EOL
                          . "<td> $row[5]</td>" . PHP_EOL;
                          echo "<td> <a class='btn btn-inverse-warning btn-md' data-toggle='modal' href='#edit" . $row[0] . "'>
												  <i class='mdi mdi-pencil'></i></a></td>". PHP_EOL;
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
                                <a href="actionscuenta.php?acc=edit&id=<?php echo $row[0] ?>" class="btn btn-danger"> Finalizar</a>
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
        <?php
        include("../components/footer.php");
        ?>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
  </div>
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
      $('#transtable').DataTable({
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
      $('#addCuenta').on('change', do_something);
    });

    function do_something() {
      var selected = $('#addCuenta').val();
      $.ajax({
        url: '/ProyectoContador/pageconfigurations/plantillaform.php',
        type: 'POST',
        dataType: 'json',
        data: {
          addCuenta: selected
        },
        success: function(datos) {
          $("#formularios").empty();
          $('#formularios').append(datos.data);
        }
      });
    }

    function cuentaIva() {
      var radios = document.getElementsByName('iva');

      for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked && radios[i].value != 0) {
          // do whatever you want with the checked radio
          document.getElementById("ivadiv1").style.display = "";
          document.getElementById("ivadiv2").style.display = "";
          // only one radio can be logically checked, don't check the rest
          break;
        } else {
          document.getElementById("ivadiv1").style.display = "none";
          document.getElementById("ivadiv2").style.display = "none";
        }
      }
    }

    function valor(num) {
      var selected = $('#tipo' + num).val();

      if (selected != 0 && selected != 1 && selected != 2) {
        document.getElementById("cantdiv" + num).style.display = "";
        document.getElementById("ventdiv" + num).style.display = "";
        if(selected == 3){
          document.getElementById("ordendiv" + num).style.display = "";
        }else{
          document.getElementById("ordendiv" + num).style.display = "none";
        }
      } else {
        document.getElementById("cantdiv" + num).style.display = "none";
        document.getElementById("ventdiv" + num).style.display = "none";
        document.getElementById("ordendiv" + num).style.display = "none";
      }
    }

    function unitario(num) {
      var selected = $('#tipo' + num).val();
      var cant = $('#cantP' + num).val();
      var monto = $('#monto' + num).val();
      var uni = (parseFloat(monto) / parseFloat(cant)).toFixed(2);
      if (selected == 4) {
        document.getElementById("precioU" + num).value = uni;
       
      }
    }

    function orden(posi) {
			var selected = $('#orden' + posi).val();
			$.ajax({
				url: '/ProyectoContador/pageconfigurations/plantillaform.php',
				type: 'POST',
				dataType: 'json',
				data: {	ordene: selected	},
				success: function(datos) {
					document.getElementById("cantP" + posi).value = datos.data;
          document.getElementById("precioU" + posi).value = datos.uni;
          document.getElementById("monto" + posi).value = datos.monto;
				}
			});
		}
  </script>
</body>

</html>