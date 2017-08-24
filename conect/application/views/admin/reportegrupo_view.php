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
                        for ($i=0; $i < count($datosCargo); $i++) { 
                          ?>
                            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                              <a href="<?php echo base_url().'index.php/admin/Home/reportexgrupo/'.$datosCargo[$i]["datos"]->grupo_id; ?>">
                                <div class="tile-stats">
                                  <div class="icon"><i class="fa fa-caret-square-o-right"></i>
                                  </div>
                                  <h3>Usuarios</h3>
                                  <div class="count"><?= $datosCargo[$i]["total"]; ?></div>

                                  <!--<h3><?php echo base_url().'index.php/admin/Home/reportexgrupo/'.$datosCargo[$i]["datos"]->grupo_id; ?></h3>-->
                                  <p><?= $datosCargo[$i]["datos"]->grupo_nombre; ?></p>
                                </div>
                              </a>
                            </div>
                          <?php
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