<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "historico";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Histórico</title>
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
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <?php require_once('../inc/carregaHeader.php'); ?>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <?php require_once('../inc/carregaSidebar.php'); ?>
      </aside>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
           <?php
            if (isset($_SESSION["MSG_SUCESSO"])){
                echo "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Sucesso!</h4>".$_SESSION["MSG_SUCESSO"]."</div>"; 
                unset($_SESSION["MSG_SUCESSO"]);
            }
            if (isset($_SESSION["MSG_AVISO"])){
                echo "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> AVISO!</h4>".$_SESSION["MSG_AVISO"]."</div>"; 
                unset($_SESSION["MSG_AVISO"]);
            }    
          ?>
          <h1>
            Histórico 
            <small>de alterações</small>
          </h1>      
        </section>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12" align="right">
                <form id="formLimpaHistorico" method="post" enctype="multipart/form-data"> 
                    <button type="submit" class="btn btn-info pull-right" >Limpar Histórico</button>
                    <br><br><br>
                </form>
            </div>
            <div class="col-md-12">
                <div class="col-xs-12" align="left">
                    <div id="resLimpaHistorico"></div>
                </div>
            </div>    
          </div>
            
          <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">                    
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaHistorico" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>#</th>
                              <th>Ação</th>
                              <th>Tela</th> 
                              <th>Usuário</th>                
                              <th>Data/Hora</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>#</th>
                              <th>Ação</th>
                              <th>Tela</th>    
                              <th>Usuário</th>
                              <th>Data/Hora</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/historicoListar.php'); ?> 
                          </tbody>
                        </table>
                      </div>
                      <!-- /.tab-pane -->                      
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
            </div>
            <!-- /.col-->
          </div>
          <!-- ./row -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <?php require_once('../inc/carregaFooter.php'); ?>

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
    <script src="../dist/js/dataTables.responsive.min.js"></script>
    <script src="../dist/js/responsive.bootstrap.min.js"></script>
    <!-- TABELA COM SEARCH E PDF/EXCEL -->

    <script>
      $(document).ready(function () {
        $('.sidebar-menu').tree()
      })
    </script>
    
    <script>
      $(document).ready(function () {
        var table = $('#tabelaHistorico').DataTable({
          lengthChange: false,
          "order": [
            [0, "desc"]
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Histórico <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 1, 2, 3, 4 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Histórico <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 1, 2, 3, 4 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaHistorico_wrapper .col-sm-6:eq(0)');
      });
    </script> 
      
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formLimpaHistorico').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resLimpaHistorico').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/limpaHistorico.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resLimpaHistorico').html(data);             
            })
            .fail(function() {         
                alert( "Posting failed." );             
            });
            return false; 
        });
    });     
    </script>
    <!-- JQUERY AJAX --> 
    
  </body>
  </html>
