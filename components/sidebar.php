<?php
    if(!isset($_SESSION['rol'])){
        header('location: cerrar.php');
    }
?>


<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar position-fixed">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <?php 
            if ($_SESSION['rol'] == 1) {
          ?> 
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-circle-outline menu-icon"></i>
              <span class="menu-title">Contador</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="estadoscontables.php">Balances/Estados</a></li>
                <li class="nav-item"> <a class="nav-link" href="nuevacontador.php">Nueva transacción</a></li>
              </ul>
            </div>
          </li>
          <?php 
            }else if ($_SESSION['rol'] == 2) {
          ?>
          <li class="nav-item">
            <a class="nav-link"  href="../pageplanillas/planilla.php">
              <i class="mdi mdi-circle-outline menu-icon"></i>
              <span class="menu-title">Planillas</span>
            </a>
            <li class="nav-item">
              <a class="nav-link" href="../pageplanillas/listarempleados.php">
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                <span class="menu-title">Ver listado</span>
              </a>
            </li>
          </li>
          <?php 
            }else if ($_SESSION['rol'] == 3) {
          ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-circle-outline menu-icon"></i>
              <span class="menu-title">Producción</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="#">Ver listado</a></li>
              </ul>
            </div>
          </li>
          <?php 
            }
            if ($_SESSION['rol'] == 1) {
          ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-circle-outline menu-icon"></i>
              <span class="menu-title">Configuraciones</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="configuraciones.php">Ver listado</a></li>
              </ul>
            </div>
          </li>
          <?php 
            }
          ?> 
        </ul>
         </div>
      </nav>
     