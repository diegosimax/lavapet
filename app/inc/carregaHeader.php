<!-- Logo -->
<a href="<?php echo $GLOBALS["url"]; ?>app" class="logo">
  <img src="<?php echo $GLOBALS["url"]; ?>app/dist/img/logo_small.png" alt="" width="200px">
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>

  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">                
      <?php require_once('carregaAvisos.php'); ?>
      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="<?php echo $GLOBALS["url"]; ?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="user-image" alt="User Image">
          <span class="hidden-xs"><?php echo utf8_encode($nome_usuario); ?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?php echo $GLOBALS["url"]; ?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="img-circle" alt="User Image">                    
            <p>
              <?php echo utf8_encode($nome_usuario); ?>
            </p>
          </li>

          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="<?php echo $GLOBALS["url"]; ?>app/configuracoes/" class="btn btn-default btn-flat">Configurações do Perfil</a>
            </div>
            <div class="pull-right">
              <a href="<?php echo $GLOBALS["url"]; ?>app/logout.php" class="btn btn-default btn-flat">Sair</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>