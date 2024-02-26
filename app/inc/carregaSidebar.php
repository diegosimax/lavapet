<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <!-- Sidebar user panel -->
  <div class="user-panel">
    <div class="pull-left image">
      <img src="<?php echo $GLOBALS["url"]; ?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <br>
        <p>
            <?php echo utf8_encode($nome_usuario); ?>
        </p>                
    </div>
  </div>

  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MENU</li>  
    <?php include_once('carregaMenu.php'); ?>             
  </ul>
</section>
<!-- /.sidebar -->