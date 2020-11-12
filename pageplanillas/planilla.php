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
              <h1>Generar planillas</h1>
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Opciones de Planillas</h4>
                  <p class="card-description">
                    Planillas
                  </p>
                  <!-- fila uno -->
                  <div class="row">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-2">
                      <a href="./planilla15dias.php" target="_blank" class="btn btn-inverse-primary btn-fw" name="btn-registrar">Planilla laboral</a>
                    </div>
                    <div class="col-md-3">
                      <a href="./planilla15vacaciones.php" target="_blank" class="btn btn-inverse-success btn-fw" name="btn-registrar">Planilla laboral vacaciones</a>
                    </div>
                    <div class="col-md-3">
                      <a href="./planilla15aguinaldo.php" target="_blank" class="btn btn-inverse-warning btn-fw" name="btn-registrar">Planilla laboral aguinaldo</a>
                    </div>
                    <div class="col-md-3">
                      <a href="./planilla15patrono.php" target="_blank" class="btn btn-inverse-secondary btn-fw" name="btn-registrar">Planilla laboral patrono</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- partial -->
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

</body>

</html>