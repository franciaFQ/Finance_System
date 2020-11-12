<?php
include_once '../conexion.php';

session_start();

if (!isset($_SESSION['rol'])) {
  header('location: cerrar.php');
} else {
  if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
    header('location: cerrar.php');
  }
}

if (isset($_POST['nombreCompleto'])) {

  extract($_POST);
  function fechaValida($date)
  {
    global $date;
    if (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
      echo "true";
    } else {
      echo "false";
    }
  }

  try {
    $db = new Database();
    $consulta = $db->connect()->prepare("insert into empleados (nombreEmpleado, salarioBase, salarioxHora, horasextra, horasnocturnas, fechaingreso) 
        VALUES (:nombreC, :salarioB, :salarioxH, :horase, :horasn, :ingreso)");

    $result = $consulta->execute(
      array(
        ':nombreC' => $nombreCompleto,
        ':salarioB' => $salarioBase,
        ':salarioxH' => $salarioHora,
        ':horase' => $horasExtra,
        ':horasn' => $horasNocturnas,
        ':ingreso' => $fechacontra
      )
    );

    if ($result) {
      $smsg = "Empleado nuevo agregado.";
    } else {
      $fmsg = "Empleado no pudo ser ingresado.";
    }
  } catch (Exception $e) {
    $fmsg = $e; //"Empleado ya existe.";
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
  <title>Planilla-Sistema de contabilidad</title>
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
  <link rel="stylesheet" href="cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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
          <div class="row">
            <div class="col-12 grid-margin">
              <h1>Control de empleados</h1>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Empleado</h4>
                  <form class="form-sample" action="" method="POST">
                    <p class="card-description">
                      Información de empleado
                    </p>
                    <!-- fila uno -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label for="nombreCompleto" class="col-sm-4 col-form-label">Nombre completo</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" placeholder="Nombre completo" required="true">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" for="salarioBase">Salario Base</label>
                          <div class="col-sm-9">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">$</span>
                              </div>
                              <input type="number" id="salarioBase" name="salarioBase" class="form-control" aria-label="Monto en dolarés americanos" value="0.00" step="0.01" onchange="actualizarsalario()" required="true">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fila dos -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" for="salarioHora">Salario por hora</label>
                          <div class="col-sm-9">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">$</span>
                              </div>
                              <input type="number" class="form-control" id="salarioHora" name="salarioHora" aria-label="Monto en dolarés americanos" value='0.0000' step="0.0001">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" for="horasExtra">Horas extra</label>
                          <div class="col-sm-9">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">hrs</span>
                              </div>
                              <input type="number" class="form-control" id="horasExtra" name="horasExtra" aria-label="Monto en horas" value='0.0' step="0.1">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fila tres -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label" for="horasNocturnas">Horas nocturnas</label>
                          <div class="col-sm-9">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">hrs</span>
                              </div>
                              <input type="number" class="form-control" id="horasNocturnas" name="horasNocturnas" aria-label="Monto en horas" value='0.0' step="0.1">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label for="fechacontra" class="col-sm-4 col-form-label">Fecha de contratación</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="fechacontra" name="fechacontra" placeholder="dd-mm-yyyy" onchange="preg_match()" required="true">
                            <div id="errorfe" class="alert alert-danger" role="alert" style="display:none"> Fecha no coincide con formato dd-mm-yyyy </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Sub titulo -->
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
        </div>
        <div class="row">
          <div class="col-md-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Lista de Empleados</p>
                <div class="table-responsive">
                  <table id="empleadostable" class="table">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Salario</th>
                        <th>Salario por Hora</th>
                        <th>Horas Extra</th>
                        <th>Horas Nocturnas</th>
                        <th>Fecha de Ingreso</th>
                        <th>Es Patrono</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $db = new Database();
                      $query = $db->connect()->query('SELECT * FROM empleados');

                      $data1 = $query->fetchAll();

                      foreach ($data1 as $row) {
                        echo "<tr>" . PHP_EOL
                          . "<td> $row[0]</td>" . PHP_EOL
                          . "<td> $row[1]</td>" . PHP_EOL
                          . "<td> $row[2]</td>" . PHP_EOL
                          . "<td> $row[3]</td>" . PHP_EOL
                          . "<td> $row[4]</td>" . PHP_EOL
                          . "<td> $row[5]</td>" . PHP_EOL
                          . "<td> $row[7]</td>" . PHP_EOL
                          . "<td>";
                        echo ($row[6]) ? "Sí" : "No";
                        echo "</td>" . PHP_EOL
                          . "<td> <a class='btn btn-inverse-warning btn-md' data-toggle='modal' href='#edit" . $row[0] . "'><i class='mdi mdi-pencil'></i></a><span>&nbsp;&nbsp;&nbsp;</span>
                                  <a href='#del" . $row[0] . "' data-toggle='modal' class='btn btn-inverse-danger btn-md'> <i class='mdi mdi-delete'></i></a></td>" . PHP_EOL
                          . "</tr>" . PHP_EOL;

                        include('buttonempleados.php');
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
      $('#empleadostable').DataTable({
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
  <script language="javascript">
    function actualizarsalario() {
      var base = document.getElementById('salarioBase').value;
      var xhora = base / 240;
      xhora = xhora.toFixed(4);
      document.getElementById('salarioHora').value = xhora;
    }

    function actualizarsalarioE() {
      var baseE = document.getElementById('salarioBaseE').value;
      var xhoraE = baseE / 240;
      xhoraE = xhoraE.toFixed(4);
      document.getElementById('salarioHoraE').value = xhoraE;
    }

    function preg_match() {

      var div = document.getElementById('errorfe');

      var paso = (new RegExp("^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-([0-9]{4})$").test(document.getElementById('fechacontra').value));

      if (paso) {
        div.style.display = "none";
      } else {
        div.style.display = "block";
      }
    }

    function preg_matchE() {

      var divE = document.getElementById('errorfed');

      var pasoE = (new RegExp("^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-([0-9]{4})$").test(document.getElementById('fechacontraE').value));

      if (pasoE) {
        divE.style.display = "none";
      } else {
        divE.style.display = "block";
      }
    }
  </script>
</body>

</html>