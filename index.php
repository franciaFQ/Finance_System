<?php
    include_once 'conexion.php';
    
    session_start();

    if(isset($_GET['cerrar_sesion'])){
        session_unset(); 

        // destruir la session
        session_destroy(); 
    }
    
    if(isset($_SESSION['rol'])){ //revisar si esta una sesion de rol
        switch($_SESSION['rol']){
            case 1:
              header('location: pageconfigurations/estadoscontables.php');
            break;

            case 2:
              header('location: pageplanillas/planilla.php');
            break;

            case 3:
              header('location: pageproductions/costos.php');
            break;

            default:
        }
    }

    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        try{
        
        $db = new Database();
        $query = $db->connect()->prepare('SELECT * FROM usuarios WHERE usuario = :username AND contrasena = :password');

        $query->execute(['username' => $username, 'password' => $password]);

        $row = $query->fetch(PDO::FETCH_NUM);
      }catch(Exception $e){
        echo $e;
      }
        $fmsg='';
        if($row){
            $rol = $row[3];
            
            //validar rol
            $_SESSION['rol'] = $rol;
            switch($rol){
                case 1:
                    header('location: pageconfigurations/estadoscontables.php');
                break;

                case 2:
                    header('location: pageplanillas/planilla.php');
                break;

                case 3:
                    header('location: pageproductions/costos.php');
                break;

                default:
            }
        }else{
            // no existe el usuario
            $fmsg= "Nombre de usuario o contraseña incorrecto";
        }
        

    }

?>


<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistema contable</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body background="images/descarga2.jpg" style="background-attachment: fixed">
  <br>
  <br>
  <div class="container-scroller">
    <div class="container-fluid">
      <div class="d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img style="left: 90px;width: 220px;height: 160px;display: block;margin-left: auto;margin-right: auto;" src="images/logo.png"  alt="logo">
              </div>
              <h4 style="text-align: center;">Bienvenidos</h4>
             <form class="pt-3" action="" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Ingrese su Usuario" name="username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Ingrese su Contraseña" name="password">
                </div>
                <div class="mt-3">
                   <input type="submit" value="Iniciar sesión" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                </div>
                <div class="mb-2">
                    <?php if(isset($fmsg)){ ?>
                      <div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div>
                    <?php } ?>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <!-- endinject -->
</body>

</html>
