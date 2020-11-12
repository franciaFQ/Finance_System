<!-- Delete -->
<div class="modal fade" id="del<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <center><h4 class="modal-title" id="myModalLabel">Eliminar Empleado</h4></center>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">	
        <div class="container-fluid">
         <h5><center>Nombre de Rol: <strong><?php echo $row[1]; ?></strong></center></h5> 
       </div> 
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-info" data-dismiss="modal"> Cancelar</button>
      <a href="acciones/rolactions.php?acc=del&id=<?php echo $row[0] ?>" class="btn btn-danger"> Eliminar</a>
    </div>

  </div>
</div>
</div>
<!-- /.modal -->



<div class="modal fade" id="edit<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <center><h4 class="modal-title" id="myModalLabel">Editar Rol</h4></center>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">

       <div class="card">
        <div class="card-body">
          <h4 class="card-title">Roles</h4>
          <form class="form-sample" action="./acciones/rolactions.php?acc=edit" method="POST">
            <p class="card-description">
              Información de Rol
            </p>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label for="nombrerolE" class="col-sm-4 col-form-label">Nombre del Rol</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="nombrerolE" name="nombrerolE" placeholder="Nombre completo" value="<?php echo $row[1]; ?>">
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" id="codRolE" name="codRolE" value="<?php echo $row[0]; ?>" >                
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success"> Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Cancelar</button>                    
      </div>
    </form>
  </div>
</div>
</div>