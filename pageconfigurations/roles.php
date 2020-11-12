<?php
include_once '../conexion.php';

session_start();

if(!isset($_SESSION['rol'])){
  header('location: cerrar.php');
}else{
  if($_SESSION['rol'] == 2 || $_SESSION['rol'] == 3){
    header('location: cerrar.php');
  }
}

if(isset($_POST['nombreRol'])){

  extract($_POST);
    /*print_r($_POST);
    exit();*/
    try{
      $db = new Database();
      $consulta = $db->connect()->prepare("insert into roles (nombreRol) 
        VALUES (:nombreR)");

      $result = $consulta->execute(
        array(':nombreR' => $nombreRol)
      );
      echo ' <script language="javascript">alert("Rol registrado con éxito");</script> ';
      echo "<script>location.href='roles.php'</script>";

      if ($result){
        $smsg = "Rol nuevo agregado.";
      }else{
        $fmsg = "Rol no pudo ser ingresado.";
      }
    }catch(Exception $e){
      $fmsg = $e;//"Empleado ya existe.";
      //echo "Error" . $e;
    }
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">

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
                      <h2 style="position: relative; left: 12px;" style="font-family: 'Rajdhani', sans-serif;">Registro de Roles</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Escriba su nuevo Rol</h4>
                  <form class="forms-sample" method="POST">
                    <div class="form-group">
                      <input type="text" class="form-control" id="nombreRol" name="nombreRol" placeholder="Nuevo Rol">
                    </div>                    
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary mr-2">Registrar</button>
                    </form>
                    <a href="configuraciones.php" class="btn btn-danger">Cancelar</a>
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
                      <table id="rolestable" class="table">
                        <thead>
                          <tr>
                            <th>Codigo Rol</th>
                            <th>Nombre de Rol</th>
                            <th>Operaciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $db = new Database();
                          $query = $db->connect()->query('SELECT * FROM roles');

                          $data1 = $query->fetchAll();

                          /*print_r($data1);
                          exit();*/
                          foreach ($data1 as $row) {
                            echo "<tr>". PHP_EOL
                                  ."<td> $row[0]</td>". PHP_EOL
                                  ."<td> $row[1]</td>". PHP_EOL
                                  ."<td> <a class='btn btn-inverse-warning btn-md' data-toggle='modal' href='#edit" . $row[0] . "'><i class='mdi mdi-pencil'></i></a><span>&nbsp;&nbsp;&nbsp;</span>
                                  <a href='#del" . $row[0] . "' data-toggle='modal' class='btn btn-inverse-danger btn-md'> <i class='mdi mdi-delete'></i></a></td>". PHP_EOL
                                ."</tr>". PHP_EOL;

                                include ('modal/rolmodal.php');
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
    $(document).ready( function () {
      $('#rolestable').DataTable({
        "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
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
  </body>

  </html>

