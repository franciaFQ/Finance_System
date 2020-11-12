<?php 
  if(isset($_POST["addMateria"])){
    $cuentamas = trim($_POST["addMateria"]);

    include_once '../conexion.php';

    $html = "";
    $response = null;
    for ($i=1; $i <= $cuentamas ; $i++) {
      $html .= "<hr>
        <h2 class='card-title' style='text-align: center;'>Materia prima ". $i."</h2>
          <hr>
            <div class='row'>
              <div class='col-md-6'>
                <div class='form-group row'>
                <label for='nombreMateria". $i."' class='col-sm-3 col-form-label'>Materia prima:</label>
                  <div class='col-sm-9'>
                    <select name='nombreMateria". $i."' id='nombreMateria". $i."' class='form-control' onchange='materiau(".$i.")'>
                      <option value='0' selected>Elija una materia</option>";
                        $db = new Database();
                        $query = $db->connect()->query('SELECT * FROM materiasprima' );

                        $data = $query->fetchAll();

                        foreach ($data as $row) {
                          $html.= "<option value=".$row['codMateria']."> ". $row['nombreMateria'] ."</option>";
                        }
                    $html.="</select>
                  </div>
                </div>
              </div>
              <div class='col-md-6'>
                <div class='form-group row'>
                <label class='col-sm-4 col-form-label' for='cantMateria". $i."'>Cantidad de materia:*</label>
                  <div class='col-sm-8'>
                    <div class='input-group'>
                      <div class='input-group-prepend'>
                        <span class='input-group-text bg-primary text-white'>$</span>
                      </div>
                      <input type='number' id='cantMateria". $i."' name='cantMateria". $i."' class='form-control'
                       aria-label='Monto en dolarés americanos' placeholder='0.00' value='' step='0.01' required='true'
                       onchange='mattotal(".$i.")'>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class='row'>
              <div class='form-group col-md-6' >
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text bg-primary text-white'>Costo Unitario</span>
                  </div>
                  <input type='text' class='form-control col-md-12' aria-label='Sizing example input' 
                  aria-describedby='inputGroup-sizing-default' name='costMateria". $i."' id='costMateria". $i."' value='0' readonly='readonly'>
                </div>
              </div>
              <div class='form-group col-md-6' >
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text bg-primary text-white'>Costo Total Materia</span>
                  </div>
                  <input type='text' class='form-control col-md-12' aria-label='Sizing example input' 
                  aria-describedby='inputGroup-sizing-default' name='costMT". $i."' id='costMT". $i."' value='0' readonly='readonly'>
                </div>
              </div>
            </div>";
    }
  }

if (isset($_POST["nombreMateria"])) {
  $nomMateria = trim($_POST["nombreMateria"]);
  
  include_once '../conexion.php';

  $db = new Database();
  $query = $db->connect()->query('SELECT * FROM materiasprima WHERE codMateria = '.$nomMateria );

  $data = $query->fetchAll();
  $html = "";
  $response = null;
  foreach ($data as $row) {
    $html= $row["costounitario"];
  }
}

$response["data"]= $html;
header('Content-Type: application/json');

  echo json_encode($response);
