<body class="nav-md">
	<div class="container body">
    <div class="main_container">
        <?php
          echo $nav;
        ?>
      <div class="right_col" role="main">
        <div class="clearfix"></div>

        <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><small>Usuario <?php echo $this->session->userdata('nombre'); ?></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <span class="section"><?= $datos['mensaje']; ?></span>
                    <form action="<?php echo base_url().index_page()?>/admin/Cargatablas/exportar/<?= ($datos["controlador"]); ?>" method="post">
                    <?php 
                      echo $select;
                    ?>
                    <input type="hidden" name="dominio_id" value="<?= $dominio; ?>">
                    <input type="submit" value="Exportar">
                    </form>
                    
                </div>
              </div>
            </div>
         
			</div>
		</div>
  </div>  
  <!--modal imagen final-->

    <!-- /page content -->
    <?php 
      echo $footer;
    ?>