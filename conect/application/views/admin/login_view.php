<?php
$dataInputUsuario = array(
        'type'  => 'text',
        'name'  => 'usuario',
        'id'    => 'usuario',
        'value' => '',
        'class' => 'form-control',
        'required' => '',
        'placeholder' => "Usuario"
);
$dataInputClave = array(
        'type'  => 'password',
        'name'  => 'Password1',
        'id'    => 'Password1',
        'value' => '',
        'class' => 'form-control',
        'required' => '',
        'placeholder' => "Contraseña"
);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->Titulo; ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('File'); ?>/plugin/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('File'); ?>/plugin/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('File'); ?>/plugin/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url('File'); ?>/plugin/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('File'); ?>/css/admin/custom.min.css" rel="stylesheet">
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-99026962-1', 'auto');
      ga('send', 'pageview');

    </script>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <?php echo form_open('Login/logeo');?>
              <h1>Login Form</h1>
              <div>
                <?= form_input($dataInputUsuario); ?>
              </div>
              <div>
                <?= form_input($dataInputClave); ?>
              </div>
              <div>
                <div class="g-recaptcha" data-sitekey="<?= $this->Titulo; ?>"></div>
              </div>
              <div>
                <button id="login-button" type="submit" class="button-login button_general">Ingresar</button>
                <!--<a class="reset_pass" href="#">Lost your password?</a>-->
              </div>

              <div class="clearfix"></div>

              <div class="separator">
              <!--
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>
              -->
                <div class="clearfix"></div>
                <br />

                <div>
                  <?php 
                    if(isset($mensaje)){
                      echo '<h1><i class="fa fa-paw"></i> '.$mensaje.'!</h1>';
                    }
                  ?>
                  <p><?= $this->PoweredBy; ?></p>
                </div>
              </div>
              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br />
              </div>
            <?php echo form_close(); ?>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <?php echo form_open('admin/Login/registrate');?>
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <button id="login-button" type="submit" class="button-login button_general">Ingresar</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <?php 
                    if(isset($mensaje)){
                      echo '<h1><i class="fa fa-paw"></i> '.$mensaje.'!</h1>';
                    }
                  ?>
                  
                  <p><?= $this->PoweredBy; ?></p>
                </div>
              </div>
            <?php echo form_close(); ?>
          </section>
        </div>
      </div>
    </div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </body>
</html>

