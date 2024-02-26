<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/parametrosSistema.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "avisos";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title>Natela RH | <?php echo utf8_encode($nome_empresa); ?> | Avisos</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- DATA TABLE -->
    <link rel="stylesheet" href="../dist/css/dataTables.bootstrap.min.css">
           <link rel="stylesheet" href="../dist/css/buttons.bootstrap.min.css">
    <!-- DATA TABLE -->
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../dist/css/main.css">
    <!-- MAIN CSS -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        
      .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
      }

      .example-modal .modal {
        background: transparent !important;
      }
        
    </style>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $url; ?>" class="logo">
          <img src="<?php echo $url?>app/dist/img/logo_small.png" alt="" width="200px">
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">                
              <?php require_once('../inc/carregaAvisos.php'); ?>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $url?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo utf8_encode($nome_usuario); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $url?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php echo utf8_encode($nome_usuario); ?> - <?php echo utf8_encode($nome_empresa); ?>
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo $url?>app/configuracoes/" class="btn btn-default btn-flat">Configurações do Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo $url?>app/logout.php" class="btn btn-default btn-flat">Sair</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo $url?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>
                        <?php echo utf8_encode($nome_usuario); ?>
                    </p>
                    <a><?php echo utf8_encode($nome_empresa); ?></a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">OPÇÕES</li>                        
                <?php include_once('../inc/carregaMenu.php'); ?>
            </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">                    
        <!-- Main content -->
        <section class="content">
          <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Aviso Importante</h4>
                </div>
                <div class="modal-body">
                  <div class="row" align="center">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Status</label>
                        <select class="form-control select2" style="width: 100%;">
                          <option selected="selected">Novo</option>
                          <option>Analisado</option>
                          <option>Não Aprovado</option>
                          <option>Entrevistado</option>
                          <option>Contratado</option>                  
                        </select>
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-8">
                      <div class="form-group">
                        <label>Análise</label><br>
                        <textarea id="texto_analise" name="texto_analise" rows="5" cols="45"></textarea><br>                        
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info pull-right" >Adicionar</button>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>          
          <div class="row">
            <div class="col-md-12">             
              <!-- The time line -->
              <ul class="timeline">
                <!-- timeline time label -->
                <li class="time-label">
                  <span class="bg-yellow">
                    Avisos
                  </span>
                </li>
                <!-- /.timeline-label -->
                <?php                    
                    $stmt = $conn->prepare('SELECT * FROM avisos WHERE id_empresa = :id_empresa AND mostrar = :mostrar ORDER BY data_hora DESC');
                    $stmt->execute(array('id_empresa' => $idEmpresa, 'mostrar' => $mostrar)); 
                    while($row = $stmt->fetch()){  
                        $dia = substr($row["data_hora"], 8, 2);
                        $mes = substr($row["data_hora"], 5, 2);
                        $ano = substr($row["data_hora"], 0, 4);
                        $hora = substr($row["data_hora"], 11, 2);
                        $min = substr($row["data_hora"], 14, 2);
                        $data = $dia."/".$mes."/".$ano;
                        $horaExata = $hora.":".$min;
                ?>                  
                        <!-- timeline item -->
                        <li>
                          <i class="fa fa-comments bg-yellow"></i>
                          <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> <?php echo $data." ".$horaExata; ?></span>
                            <h3 class="timeline-header"><a><?php echo utf8_encode($row["titulo"]); ?></a></h3>
                            <div class="timeline-body"><?php echo utf8_encode($row["mensagem"]); ?></div>
                          </div>
                        </li>
                        <!-- END timeline item -->
                <?php 
                    }
                ?>
                <!-- timeline item -->               
                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>
              </ul>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row historico-->          
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Versão</b> Beta 1.0
        </div>
        <strong>Copyright &copy; 2017 <a href="http://www.natelaweb.com.br/">Natela Soluções Web</a></strong>
      </footer>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3.1.1 -->
    <script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>

    <!-- TABELA COM SEARCH E PDF/EXCEL -->
    <script src="../dist/js/jquery.dataTables.min.js"></script>
    <script src="../dist/js/dataTables.bootstrap.min.js"></script>
    <script src="../dist/js/dataTables.buttons.min.js"></script>
    <script src="../dist/js/buttons.bootstrap.min.js"></script>
    <script src="../dist/js/jszip.min.js"></script>
    <script src="../dist/js/pdfmake.min.js"></script>
    <script src="../dist/js/vfs_fonts.js"></script>
    <script src="../dist/js/buttons.html5.min.js"></script>
    <script src="../dist/js/buttons.print.min.js"></script>
    <script src="../dist/js/buttons.colVis.min.js"></script>
    <!-- TABELA COM SEARCH E PDF/EXCEL -->

    <script>
      $(document).ready(function () {
        $('.sidebar-menu').tree()
      })
    </script>
    <script>
      $(document).ready(function () {
        var table = $('#tabelaCurriculos').DataTable({
          lengthChange: false,
          "order": [
            [0, "desc"]
          ],
          buttons: ['copy', 'excel', 'pdf', 'colvis']
        });

        table.buttons().container()
          .appendTo('#tabelaCurriculos_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <script>
      function imprimir() {
        window.print();
      }
    </script>
  </body>

  </html>