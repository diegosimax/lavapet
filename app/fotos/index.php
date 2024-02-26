<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "fotos";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Fotos</title>
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
            Fotos
            <small>cadastradas</small>
          </h1>      
        </section>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab_1" data-toggle="tab">Todos</a></li>
                      <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Foto"><img src="../dist/img/adicionar.png"></a></li>                                            
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaFotos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>Imagem</th>
                              <th>Descrição</th> 
                              <th>Categoria</th> 
                              <th>Status</th>
                              <th>Ações</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>Imagem</th>
                              <th>Descrição</th> 
                              <th>Categoria</th> 
                              <th>Status</th>
                              <th>Ações</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/fotoListar.php'); ?> 
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

      <div class="modal fade" id="modal-inserir">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="formInserirFoto" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Foto</h4>
                </div>
                <div class="modal-body">
                <div class="row" align="center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!</label>
                                <input type="file" id="imagemUpload" name="imagemUpload" class="form-control"><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Descrição *</label>
                                <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Nossos pets Deva e Click ..."><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Categoria *</label>
                                <select class="form-control" id="categoria" name="categoria">
                                    <option value="1">Banho e Tosa</option>
                                    <option value="2">Pets</option>
                                    <option value="3">Equipe</option>
                                    <option value="4">Eventos</option>                                    
                                </select>
                            </div>  
                            <div class="col-xs-6">
                                <label>Status *</label> 
                                <select class="form-control" id="status" name="status">
                                    <option value="A" selected>Ativo</option>
                                    <option value="I">Inativo</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                                                
                    <div class="col-md-12">
                        <div class="col-xs-12" align="left">
                            <div id="resInserirFoto"></div>
                        </div>
                    </div>                  
                    <!-- /.col --> 
                </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info pull-right" >Salvar</button>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>      
        
      <div class="modal fade" id="modal-editar">
        <div class="modal-dialog">
          <form id="formEditarFoto" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeFotoEditar" name="nomeFotoEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                                     
                    <img src="" width="200px" class="img-circular" name="imagemE" id="imagemE">
                    <input type="hidden" id="imagemFoto" name="imagemFoto" value="">
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!</label>
                            <input type="file" id="imagemUploadE" name="imagemUploadE" class="form-control"><br>
                        </div>                              
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->                                    
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Descrição</label><br>
                        <textarea id="descricaoE" name="descricaoE" rows="5" class="form-control"></textarea>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->                     
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Categoria *</label>
                            <select class="form-control" id="categoriaE" name="categoriaE">
                                <option value="1">Banho e Tosa</option>
                                <option value="2">Pets</option>
                                <option value="3">Equipe</option>
                                <option value="4">Eventos</option>                                    
                            </select>
                        </div>  
                        <div class="col-xs-6">
                            <label>Status *</label> 
                            <select class="form-control" id="statusE" name="statusE">
                                <option value="A" selected>Ativo</option>
                                <option value="I">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12"><br>
                    <div class="col-xs-12" align="left">
                        <div id="resEditarFoto"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button> 
              <input type="hidden" value="" name="idFotoE" id="idFotoE">
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirFoto" method="post" action="../inc/fotoExcluir.php">
          <div class="modal-dialog">            
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Exclusão de registro!</h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                  
                    <label>Você tem certeza que deseja excluir a foto<br><label id="nomeFotoExcluir" name="nomeFotoExcluir"></label>?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idFotoExcluir" id="idFotoExcluir">
            </div>
          </div>
          <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->          
        </form>
      </div>
      
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
        var table = $('#tabelaFotos').DataTable({
          lengthChange: false,
          "order": [
            [0, "asc"]
          ],
          buttons: [            
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaFotos_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirFoto').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirFoto').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/fotoInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirFoto').html(data);             
            })
            .fail(function() {         
                alert( "Posting failed." );             
            });
            return false; 
        });
    });     
    </script>
    <!-- JQUERY AJAX -->  
    <!-- JQUERY AJAX -->
   <script> 
    $(document).ready(function(){       
        $('#formEditarFoto').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarFoto').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/fotoAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarFoto').html(data);             
            })
            .fail(function() {         
                alert( "Posting failed." );             
            });
            return false; 
        });
    });     
    </script>
    <!-- JQUERY AJAX -->
    <script>
        $(document).on("click", ".pegaId", function () {
             var idFoto = $(this).data('id');              
             $("#idFotoExcluir").val( idFoto ); 
             $("#idFotoE").val( idFoto ); 
             // labels
             var nomeFoto = $(this).data('descricao');
             $("#nomeFotoExcluir").text( nomeFoto );                      
             $("#nomeFotoEditar").text( nomeFoto );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/fotos/'+imagem);
             $("#imagemFoto").val( imagem ); 
             var descricao = $(this).data('descricao');            
             $("#descricaoE").val( descricao );         
             var categoria = $(this).data('categoria');            
             $("#categoriaE").val( categoria );
             var status = $(this).data('status');            
             $("#statusE").val( status );                    
        }); 
    </script>
    
  </body>
  </html>
