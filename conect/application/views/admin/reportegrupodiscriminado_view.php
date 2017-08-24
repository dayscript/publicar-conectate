  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
          echo $nav;
        ?>
        <!-- page content -->

        <div class="right_col" role="main">
          <div class="row">
              <div class="col-md-12">
                <div class="">
                  <div class="x_content">
                    <div class="row">
                      <?php
                        foreach ($datos['cargo1Final'] as $key) {
                          
                          ?>
                            <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="tile-stats">
                                  <div class="icon"><i class="fa fa-comments-o"></i>
                                  </div>
                                  <h3>Porcentaje Cumplimiento</h3>
                                  <div class="count"><?= $key[4]["percentage"]; ?></div>
                                  <!--<p><?= $key[$i]["datos"]->grupo_nombre; ?></p>-->
                                </div>
                            </div>
                          <?php
                          break;
                        }
                        foreach ($datos['cargo1Final'] as $key) {
                          
                          ?>
                            <div class="animated flipInY col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="tile-stats">
                                  <div class="icon"><i class="fa fa-comments-o"></i>
                                  </div>
                                  <h3>Puntos Cumplimiento</h3>
                                  <div class="count"><?= $key[4]["percentage_weighed"]; ?></div>
                                  <!--<p><?= $key[$i]["datos"]->grupo_nombre; ?></p>-->
                                </div>
                            </div>
                          <?php
                          break;
                        }

                      ?>
                    </div>
                  </div>
                  <div class="x_content">
                    <div class="row">
                      <?php
                        foreach ($datos['cargo1Final'] as $key) {
                          echo $key["datosUsuario"]->cargo_nombre;
                          echo $envioTablas['cargo1FinalTabla'];
                          break;
                        }
                        
                        foreach ($datos['cargo2Final'] as $key) {
                          echo $key["datosUsuario"]->cargo_nombre;
                          echo $envioTablas['cargo2FinalTabla'];
                          break;
                        }
                        
                        foreach ($datos['cargo3Final'] as $key) {
                          echo $key["datosUsuario"]->cargo_nombre;
                          echo $envioTablas['cargo3FinalTabla'];
                          break;
                        }
                        
                        foreach ($datos['cargo4Final'] as $key) {
                          echo $key["datosUsuario"]->cargo_nombre;
                          echo $envioTablas['cargo4FinalTabla'];
                          break;
                        }
                        
                        foreach ($datos['cargo5Final'] as $key) {
                          echo $key["datosUsuario"]->cargo_nombre;
                          echo $envioTablas['cargo5FinalTabla'];
                          break;
                        }
                        
                      ?>
                      

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- page content -->
      </div>
    </div>
        <?php 
          echo $footer;
        ?>