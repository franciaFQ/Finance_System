<!-- Delete -->
<div class="modal fade" id="del<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <center>
          <h4 class="modal-title" id="myModalLabel">Eliminar Empleado</h4>
        </center>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <h5>
            <center>Nombre: <strong><?php echo $row[1]; ?></strong></center>
          </h5>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal"> Cancelar</button>
        <a href="actions.php?acc=del&id=<?php echo $row[0] ?>" class="btn btn-danger"> Eliminar</a>
      </div>
    </div>
  </div>
</div>
<!-- /.modal -->


<!-- Edit -->
<div class="modal fade" id="edit<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <center>
          <h4 class="modal-title" id="myModalLabel">Editar Empleado</h4>
        </center>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">

        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Empleado</h4>
            <form class="form-sample" action="actions.php?acc=edit" method="POST">
              <p class="card-description">
                Información de empleado
              </p>
              <!-- fila uno -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label for="nombreCompletoE" class="col-sm-4 col-form-label">Nombre completo</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="nombreCompletoE" name="nombreCompletoE" placeholder="Nombre completo" value="<?php echo $row[1]; ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="salarioBaseE">Salario Base</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-primary text-white">$</span>
                        </div>
                        <input type="number" id="salarioBaseE" name="salarioBaseE" class="form-control" aria-label="Monto en dolarés americanos" step="0.01" onchange="actualizarsalarioE()" value="<?php echo $row[2]; ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- fila dos -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="salarioHoraE">Salario por hora</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-primary text-white">$</span>
                        </div>
                        <input type="number" class="form-control" id="salarioHoraE" name="salarioHoraE" aria-label="Monto en dolarés americanos" step="0.0001" value="<?php echo $row[3]; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="horasExtraE">Horas extra</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-primary text-white">hrs</span>
                        </div>
                        <input type="number" class="form-control" id="horasExtraE" name="horasExtraE" aria-label="Monto en horas" value='<?php echo $row[4]; ?>' step="0.1">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- fila tres -->
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label" for="horasNocturnasE">Horas nocturnas</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text bg-primary text-white">hrs</span>
                        </div>
                        <input type="number" class="form-control" id="horasNocturnasE" name="horasNocturnasE" aria-label="Monto en horas" value='<?php echo $row[5]; ?>' step="0.1">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label for="fechacontraE" class="col-sm-4 col-form-label">Fecha de contratación</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="fechacontraE" name="fechacontraE" placeholder="dd-mm-yyyy" onchange="preg_matchE()" required="true" value="<?php echo $row[7]; ?>">
                      <div id="errorfed" class="alert alert-danger" role="alert" style="display:none"> Fecha no coincide con formato dd-mm-yyyy </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Es patrono</label>
                    <div class="col-sm-3">
                      <div class="form-check form-check-danger">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="patronoE" id="membershipRadios1" value="0" <?php echo ($row[6]) ? "" : "checked"; ?>>
                          No
                        </label>
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <div class="form-check form-check-success">
                        <label class="form-check-label">
                          <input type="radio" class="form-check-input" name="patronoE" id="membershipRadios2" value="1" <?php echo ($row[6]) ? "checked" : ""; ?>>
                          Sí
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="codEmpleadoE" name="codEmpleadoE" value="<?php echo $row[0]; ?>">
              <!-- Sub titulo -->
              <p class="card-description">
                Acciones
              </p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal"> Cancelar</button>
        <button type="submit" class="btn btn-warning"> Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- /.modal -->