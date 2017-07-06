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
                    <form class="form-horizontal form-label-left"  method="post" action="<?php echo base_url().index_page()?>/admin/Cargatablas/guardar/<?= ($datos["controlador"]); ?>" novalidate>
                    <!--
                      <p>For alternative validation library <code>parsleyJS</code> check out in the <a href="form.html">form page</a>
                      </p>
                      -->
                      <span class="section"><?= $datos['mensaje']; ?></span>
                      <?php 
                        if(!is_null($datosCarga)){
                          echo '<input type="text" id="id" name="id" value="'.$datosCarga[0]->slide_id .'" hidden="true">';
                          
                        }
                      ?>
                      
                      
                      <!-- Small modal -->
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="URL" name="URL" required="required" placeholder="" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo (!is_null($datosCarga)) ? $datosCarga[0]->slide_url : '' ;?>">
                          <button type="button" class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-sm" >Cargar Archivo</button>
                        </div>
                      </div>
                      <!-- /modals -->
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <button type="submit" class="btn btn-primary">Cancel</button>
                          <button id="send" type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $error; ?></label>
                      </div>

                    </form>

                </div>
              </div>
            </div>
         
			</div>
		</div>
  </div>
  <!--modal imagen -->
  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Cargar Archivo</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo base_url().index_page()?>/Login/callupload/1280/720/URL/FILE/<?= ($datos["controlador"]); ?>" class="dropzone" id="my-awesome-dropzone" enctype=”multipart/form-data” ></form>
        </div>
        <div id="Alerta1URL"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" id="termina">Close</button>
        </div>
      </div>
    </div>
 
  </div>
  
  <!--modal imagen final-->

    <!-- /page content -->
    <?php 
      echo $footer;
    ?>