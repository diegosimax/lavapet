<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "banners";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Banners</title>
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
            Banners
            <small>cadastrados</small>
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
                      <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Banner"><img src="../dist/img/adicionar.png"></a></li>                                            
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaBanners" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>Imagem</th>
                              <th>Título</th> 
                              <th>Ordem</th> 
                              <th>Status</th>
                              <th>Ações</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>Imagem</th>
                              <th>Título</th> 
                              <th>Ordem</th> 
                              <th>Status</th>
                              <th>Ações</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/bannerListar.php'); ?> 
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
            <form id="formInserirBanner" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Banner</h4>
                </div>
                <div class="modal-body">
                <div class="row" align="center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!<br>Tamanho: 1600 x 600.</label>
                                <input type="file" id="imagemUpload" name="imagemUpload" class="form-control"><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Título *</label>
                                <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Foto banner 1 ..."><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Descrição</label><br>
                            <textarea id="descricao" name="descricao" rows="5" class="form-control"></textarea>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                     
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Ordem *</label>
                                <input type="text" id="ordem" name="ordem" class="form-control" placeholder="1" value="1"><br>
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
                            <div id="resInserirBanner"></div>
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
          <form id="formEditarBanner" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeBannerEditar" name="nomeBannerEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                                     
                    <img src="" width="200px" class="img-circular" name="imagemE" id="imagemE">
                    <input type="hidden" id="imagemBanner" name="imagemBanner" value="">
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!<br>Tamanho: 1600 x 600.</label>
                            <input type="file" id="imagemUploadE" name="imagemUploadE" class="form-control"><br>
                        </div>                              
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Título *</label>
                            <input type="text" id="tituloE" name="tituloE" class="form-control" placeholder="Foto banner 1 ..."><br>
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
                            <label>Ordem *</label>
                            <input type="text" id="ordemE" name="ordemE" class="form-control" placeholder="1" value="1"><br>
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
                        <div id="resEditarBanner"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button> 
              <input type="hidden" value="" name="idBannerE" id="idBannerE">
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirBanner" method="post" action="../inc/bannerExcluir.php">
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
                    <label>Você tem certeza que deseja excluir o banner<br><label id="nomeBannerExcluir" name="nomeBannerExcluir"></label>?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idBannerExcluir" id="idBannerExcluir">
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
        var table = $('#tabelaBanners').DataTable({
          lengthChange: false,
          "order": [
            [0, "asc"]
          ],
          buttons: [            
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaBanners_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirBanner').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirBanner').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/bannerInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirBanner').html(data);             
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
        $('#formEditarBanner').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarBanner').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/bannerAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarBanner').html(data);             
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
             var idBanner = $(this).data('id');              
             $("#idBannerExcluir").val( idBanner ); 
             $("#idBannerE").val( idBanner ); 
             // labels
             var nomeBanner = $(this).data('titulo');
             $("#nomeBannerExcluir").text( nomeBanner );                      
             $("#nomeBannerEditar").text( nomeBanner );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/banners/'+imagem);
             $("#imagemBanner").val( imagem ); 
             var titulo = $(this).data('titulo');            
             $("#tituloE").val( titulo );    
             var descricao = $(this).data('descricao');            
             $("#descricaoE").val( descricao );         
             var ordem = $(this).data('ordem');            
             $("#ordemE").val( ordem );
             var status = $(this).data('status');            
             $("#statusE").val( status );                    
        }); 
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <script>
        
        $(document).ready(function () {             
            var ordem = $("#ordem");
            ordem.mask('99');
            var ordemE = $("#ordemE");
            ordemE.mask('99');
        });               
         
    </script>
  </body>
  </html>
