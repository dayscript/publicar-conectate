  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
          echo $nav;
        ?>
        <!-- page content -->

        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Usuarios</span>
              <div class="count"><?= $datosCarga["totalusuarios"]; ?></div>
              <!--<span class="count_bottom"><i class="green">1% </i> Desde la semana pasada</span>-->
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Hombres</span>
              <div class="count"><?= $datosCarga["masculino"]; ?></div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Femenino</span>
              <div class="count"><?= $datosCarga["femenino"]; ?></div>
              <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>-->
            </div>
            <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Conexiones</span>
              <div class="count">0</div>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </div>
          </div>
          <!-- /top tiles -->
        </div>
        <!-- page content -->
      </div>
    </div>
        <?php 
          echo $footer;
        ?>