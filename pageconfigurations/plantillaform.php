<?php 
          if(isset($_POST["addCuenta"])){
             $cuentamas = trim($_POST["addCuenta"]);


            include_once '../conexion.php';

              $html = "";
              $response = null;
                      for ($i=1; $i <= $cuentamas ; $i++) {
                    $html .= "<div class='row'>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='cuenta". $i ."'>Cuenta No ". $i."</label>
                          <div class='col-sm-9'>
                            <select class='form-control' name='cuenta". $i."' id='cuenta". $i."'>
                              <option selected='true'>Seleccione una cuenta...</option>";
                                $db = new Database();
                                $query = $db->connect()->query('SELECT * FROM cuentas order by nombreCuenta');

                                $data = $query->fetchAll();

                                foreach ($data as $row) {
                                    $html .= "<option value=".$row['codCuenta']."> No. ".$row['codCuenta']." - ". $row['nombreCuenta'] ."</option>";
                                }
                            $html .= "</select>
                          </div>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='monto". $i."'>Monto</label>
                          <div class='col-sm-9'>
                            <div class='input-group'>
                              <div class='input-group-prepend'>
                                <span class='input-group-text bg-primary text-white'>$</span>
                              </div>
                              <input type='number' class='form-control' aria-label='Monto en dolarés americanos'
                               name='monto". $i."' id='monto". $i."' value='' placeholder='0.00' step='0.01' required='true' onchange='unitario(". $i.")'>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='tipo". $i."'>Tipo de Transferencia</label>
                          <div class='col-sm-9'>
                            <select class='form-control' name='tipo". $i."' id='tipo". $i."' onchange='valor(". $i.")'>
                              <option selected='true' value='0'>Seleccione un tipo...</option>";

                                $db = new Database();
                                $query1 = $db->connect()->query('SELECT * FROM tipotrans');

                                $data1 = $query1->fetchAll();

                                foreach ($data1 as $row1) {
                                  $html .= "<option value=".$row1['codTipo'].">". $row1['nombreTrans'] ."</option>";
                                }

                            $html .="</select>
                          </div>
                        </div>
                      </div>
                      <div class='col-md-6' id='ordendiv". $i."' style='display:none'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='orden". $i."'>Orden de Producción</label>
                          <div class='col-sm-9'>
                            <select class='form-control' name='orden". $i."' id='orden". $i."' onchange='orden(". $i.")'>
                              <option selected='true' value='0'>Seleccione una orden...</option>";

                              $db = new Database();
                              $query1 = $db->connect()->query('SELECT o.codOrden, p.nombreProd FROM ordenproduccion o
                              inner join productos p on p.codProd = o.codProd where estado = \'FINALIZADO\'');

                              $data1 = $query1->fetchAll();

                              foreach ($data1 as $row1) {
                                $html .= "<option value=".$row1['codOrden']."> Cod. ". $row1['codOrden'] ." - ". $row1['nombreProd'] ."</option>";
                              }

                            $html .="</select>
                          </div> 
                        </div>
                      </div>
                    </div>
                    <div class='row' id='cantdiv". $i."' style='display:none'>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='cantP". $i."'>Cantidad de producto</label>
                          <div class='col-sm-9'>
                            <div class='input-group'>
                              <div class='input-group-prepend'>
                                <span class='input-group-text bg-primary text-white'>$</span>
                              </div>
                              <input type='number' class='form-control' aria-label='Monto en dolarés americanos'
                               name='cantP". $i."' id='cantP". $i."' value='' placeholder='0.00' step='0.01' required='true' onchange='unitario(". $i.")'>
                            </div>
                          </div> 
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='precioU". $i."'>Costo unitario</label>
                          <div class='col-sm-9'>
                            <div class='input-group'>
                              <div class='input-group-prepend'>
                                <span class='input-group-text bg-primary text-white'>$</span>
                              </div>
                              <input type='number' class='form-control' aria-label='Monto en dolarés americanos'
                               name='precioU". $i."' id='precioU". $i."' value='' placeholder='0.00' step='0.01' required='true' readonly='readonly'>
                            </div>
                          </div> 
                        </div>
                      </div>
                    </div>
                    <div class='row' id='ventdiv". $i."' style='display:none'>
                      <div class='col-md-6'>
                        <div class='form-group row'>
                          <label class='col-sm-3 col-form-label' for='tipoT". $i."'>Tipo de Transferencia</label>
                          <div class='col-sm-9'>
                            <select class='form-control' name='tipoT". $i."' id='tipoT". $i."'>
                              <option selected='true' value='0'>Seleccione un tipo...</option>";

                              $db = new Database();
                              $query1 = $db->connect()->query('SELECT * FROM tipotrans where codTipo in (1,2)');

                              $data1 = $query1->fetchAll();

                              foreach ($data1 as $row1) {
                                $html .= "<option value=".$row1['codTipo'].">". $row1['nombreTrans'] ."</option>";
                              }

                            $html .="</select>
                          </div>
                        </div>
                      </div>       
                    </div>
                    <hr noshade='noshade' style='margin-top: -3.4%;' />";
                      }
}

if (isset($_POST["ordene"])) {
  $orden = trim($_POST["ordene"]);


  include_once '../conexion.php';

  $html = "";
  $uni = "";
  $monto = "";
  $response = null;

  $db = new Database();
  $query = $db->connect()->query('SELECT * FROM ordenproduccion WHERE codOrden = '.$orden);

  $data = $query->fetchAll();
  foreach ($data as $row) {
    $html = $row["cantprod"];
    $uni = $row["cstunif"];
    $monto = $row["costototal"];
  }
  $response["uni"] = $uni;
  $response["monto"] = $monto;
}

$response["data"]= $html;

header('Content-Type: application/json');

    echo json_encode($response);

?>