<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "ordem-de-compra";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Ordem de Compra</title>
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
            Ordem  
            <small>de compra</small>
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
                    <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Ordem de Compra"><img src="../dist/img/adicionar.png"></a></li>                                            
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1" align="left">
                      <table id="tabelaOrdens" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                          <tr>                             
                            <th>Código</th>                              
                            <th>Itens</th>                              
                            <th>Cliente</th> 
                            <th>Valor da Ordem</th>
                            <th>Valor Pago</th>                              
                            <th>A pagar</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>                              
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>                              
                            <th>Código</th>
                            <th>Itens</th> 
                            <th>Cliente</th> 
                            <th>Valor da Ordem</th>
                            <th>Valor Pago</th>                              
                            <th>A pagar</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>                             
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php require_once('../inc/ordemListar.php'); ?> 
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
            <form id="formInserirOrdem" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Ordem de Compra</h4>
                </div>
                <div class="modal-body">
                    <div class="row" align="center">                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Cliente *</label>
                                    <select class="form-control" id="cliente" name="cliente">
                                        <option value="" selected>Selecione</option>
                                        <?php 
                                            $stmt = $conn->prepare('SELECT * FROM clientes ORDER BY nome ASC');
                                            $stmt->execute(); 
                                            while($row = $stmt->fetch()){  
                                                if($row["telefone1"] <> ""){
                                                    echo "<option value='".$row["id_cliente"]."'>".utf8_encode($row["nome"])." - ".$row["telefone1"]."</option>";    
                                                }else{
                                                    echo "<option value='".$row["id_cliente"]."'>".utf8_encode($row["nome"])." - ".$row["telefone2"]."</option>";       
                                                }                                           
                                            }   
                                        ?>  
                                    </select><br>
                                </div>
                                <div class="col-xs-12">
                                <label>Status</label> 
                                <select class="form-control" id="status" name="status">
                                    <option value="A">Aberto</option>
                                    <option value="F">Fechado</option>
                                </select><br>
                            </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->                                              
                        <div class="col-md-12">
                            <div class="col-xs-12" align="left">
                                <div id="resInserirOrdem"></div>
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
        
      <div class="modal fade" id="modal-listar">
        <div class="modal-dialog">          
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Ordem de Compra Nº <label id="idOrdemL" name="idOrdemL"></label></h4><br>
              <h4 class="modal-title">Cliente <label id="clienteL" name="clienteL"></label></h4><br>       
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                  
                    <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Todos</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Ordem de Compra"><img src="../dist/img/adicionar.png"></a></li>                                            
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1" align="left">
                      <table id="tabelaOrdens" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                          <tr>                             
                            <th>Qtd</th>                              
                            <th>Item</th>                              
                            <th>Valor UND</th> 
                            <th>Total</th>                               
                            <th>Ações</th>                              
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>                              
                            <th>Qtd</th>                              
                            <th>Item</th>                              
                            <th>Valor UND</th> 
                            <th>Total</th>                               
                            <th>Ações</th>                              
                          </tr>
                        </tfoot>
                        <tbody>
                          <?php                             
                            include('../inc/itemListar.php'); 
                          ?> 
                        </tbody>
                      </table>
                    </div>
                    <!-- /.tab-pane -->                      
                  </div>
                  <!-- /.tab-content -->
               </div>
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              
            </div>
          </div>
          <!-- /.modal-content -->          
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirOrdem" method="post" action="../inc/ordemExcluir.php">
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
                    <label>Você tem certeza que deseja excluir a Ordem de Compra<br>Código: <label id="codigoOrdemE" name="codigoOrdemE"></label><br>Cliente: <label id="nomeClienteE" name="nomeClienteE"></label> ?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idOrdemExcluir" id="idOrdemExcluir">
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
        var table = $('#tabelaOrdens').DataTable({
          lengthChange: false,
          "order": [
            [ 6, "asc"],  
            [ 0, "desc"]  
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Ordem de Compra <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6, 7 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Ordem de Compra <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6, 7 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaOrdens_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirOrdem').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirOrdem').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/ordemInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirOrdem').html(data);             
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
        $('#formEditarCliente').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarCliente').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/clienteAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarCliente').html(data);             
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
             var idOrdem = $(this).data('id');              
             $("#idOrdemExcluir").val( idOrdem ); 
             $("#idOrdem").text( idOrdem ); 
             $("#idOrdemL").text( idOrdem ); 
             $("#codigoOrdemE").text( idOrdem ); 
             // labels
             var nomeCliente = $(this).data('nome');
             $("#nomeClienteExcluir").text( nomeCliente );                      
             $("#nomeCliente").text( nomeCliente );
             $("#clienteL").text( nomeCliente );
             $("#nomeClienteE").text( nomeCliente );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/clientes/'+imagem);
             $("#imagemCliente").val( imagem ); 
             var nome = $(this).data('nome');            
             $("#nomeE").val( nome );            
             var email = $(this).data('email');             
        }); 
    </script>
    
  </body>
  </html>
