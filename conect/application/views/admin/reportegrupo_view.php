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
                      <table class="table table-striped projects">
                        <thead>
                            <tr>
                              <th style="width: 1%">#</th>
                              <th style="width: 20%">Grupo</th>
                              <th>Cargas</th>
                              <th>Usuarios</th>
                              <th>Meta</th>
                              <th>Venta</th>
                              <th>Progreso</th>
                              <th>Estado</th>
                              <th style="width: 20%">#Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php
                          for ($i=0; $i < count($datosCargo); $i++) 
                          { 
                            ?>
                            <tr>
                              <td><?= $i+1; ?></td>
                              <td>
                                <a><?= $datosCargo[$i]["datos"]->grupo_nombre; ?></a>
                                <br/>
                                <small>Created 01.01.2015</small>
                              </td>
                              <td>
                                <!--
                                <ul class="list-inline">
                                  <li>
                                    <img src="<?php echo base_url('File'); ?>/images/user.png" class="avatar" alt="Avatar">
                                  </li>
                                  <li>
                                    <img src="<?php echo base_url('File'); ?>/images/user.png" class="avatar" alt="Avatar">
                                  </li>
                                  <li>
                                    <img src="<?php echo base_url('File'); ?>/images/user.png" class="avatar" alt="Avatar">
                                  </li>
                                  <li>
                                    <img src="<?php echo base_url('File'); ?>/images/user.png" class="avatar" alt="Avatar">
                                  </li>
                                </ul>
                                -->
                              </td>
                              <td><?= $datosCargo[$i]["total"]; ?></td>
                              <td><?= $i+1; ?></td>
                              <td><?= $i+1; ?></td>
                              <td class="project_progress">
                                <div class="progress progress_sm">
                                  <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>
                                </div>
                                <small>57% Complete</small>
                              </td>
                              <td>
                                <button type="button" class="btn btn-success btn-xs">Activo</button>
                              </td>
                              <td>
                                <a href="<?php echo base_url().'index.php/admin/Home/reportexgrupo/'.$datosCargo[$i]["datos"]->grupo_id; ?>" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> Ver </a>
                                <!--
                                <a href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                -->
                              </td>
                            </tr>
                            <?php
                          }
                          ?>
                        </tbody>
                      </table>
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