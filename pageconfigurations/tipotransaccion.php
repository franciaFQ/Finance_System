<?php

session_start();

if(!isset($_SESSION['rol'])){
  header('location: cerrar.php');
}else{
  if($_SESSION['rol'] == 2 || $_SESSION['rol'] == 3){
    header('location: cerrar.php');
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
    include("components/navbar.php")
    ?>


    <!-- Sidebar partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php 
      include("components/sidebar.php")
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
                    <h2 style="position: relative; left: 12px;" style="font-family: 'Rajdhani', sans-serif;">Registro de tipos de transacciones</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Escriba su nuevo tipo de transaccion</h4>
                  <form class="forms-sample">
                    <div class="form-group">
                      <input type="text" class="form-control" id="exampleInputName1" name="Rol" placeholder="Nueva transaccion">
                    </div>                    
                    <div class="form-group">
                    <button type="submit" class="btn btn-primary mr-2">Registrar</button>
                  </form>
                    <a href="../configuraciones.php" class="btn btn-danger">Cancelar</a>
                  </div>
                </div>
              </div>
            </div>

 
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Recent Purchases</p>
                  <div class="table-responsive">
                    <table id="recent-purchases-listing" class="table">
                      <thead>
                        <tr>
                          <th>Codigo Transaccion</th>
                          <th>Nombre de la Transaccion</th>
                          <th>Operaciones</th>
                        </tr>
                      </thead>
                      <tbody>
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
      include("components/footer.php");
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
</body>

</html>

