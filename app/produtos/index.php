<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "produtos";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Produtos</title>
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
            Produtos
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
                      <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Produto"><img src="../dist/img/adicionar.png"></a></li>                                            
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaProdutos" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>Nome</th>
                              <th>Descrição</th>
                              <th>Fornecedor</th> 
                              <th>Qtd. Estoque</th> 
                              <th>Valor Venda</th> 
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>Nome</th>
                              <th>Descrição</th>
                              <th>Fornecedor</th>    
                              <th>Qtd. Estoque</th>
                              <th>Valor Venda</th>
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/produtoListar.php'); ?> 
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
            <form id="formInserirProduto" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Produto</h4>
                </div>
                <div class="modal-body">
                <div class="row" align="center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!<br>Tamanhos iguais e acima de 199 px<br>Ex: Imagem de 200 x 200.</label>
                                <input type="file" id="imagemUpload" name="imagemUpload" class="form-control"><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Nome *</label>
                                <input type="text" id="nome" name="nome" class="form-control" placeholder="Areia Gato Feliz ..."><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->               
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Descrição *</label><br>
                                <textarea id="descricao" name="descricao" rows="5" class="form-control"></textarea><br>
                            </div>                              
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Qtd. em estoque *</label>
                                <input type="text" id="qtd_estoque" name="qtd_estoque" class="form-control" placeholder="99"><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Fornecedor *</label> 
                                <select class="form-control" id="fornecedor" name="fornecedor">
                                    <option value="" selected>Selecione</option>
                                    <?php 
                                        $stmt = $conn->prepare('SELECT * FROM fornecedores ORDER BY nome ASC');
                                        $stmt->execute(); 
                                        while($row = $stmt->fetch()){  
                                           echo "<option value='".$row["id_fornecedor"]."'>".utf8_encode($row["nome"])."</option>";
                                        }   
                                    ?>    
                                </select><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Valor UND Pago *</label>
                                <input type="text" id="valor_und_pago" name="valor_und_pago" class="form-control" placeholder="99,99"><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Valor UND Venda *</label> 
                                <input type="text" id="valor_und_venda" name="valor_und_venda" class="form-control" placeholder="99,99"><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="col-xs-12" align="left">
                            <div id="resInserirProduto"></div>
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
          <form id="formEditarProduto" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeProdutoEditar" name="nomeProdutoEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                                     
                    <img src="" width="200px" class="img-circular" name="imagemE" id="imagemE">
                    <input type="hidden" id="imagemProduto" name="imagemProduto" value="">
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!<br>Tamanhos iguais e acima de 199 px<br>Ex: Imagem de 200 x 200.</label>
                            <input type="file" id="imagemUploadE" name="imagemUploadE" class="form-control"><br>
                        </div>                              
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Nome *</label>
                            <input type="text" id="nomeE" name="nomeE" class="form-control" placeholder="Areia Gato Feliz ..."><br>
                        </div>                              
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->               
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Descrição *</label><br>
                            <textarea id="descricaoE" name="descricaoE" rows="5" class="form-control"></textarea><br>
                        </div>                              
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Qtd. em estoque *</label>
                            <input type="text" id="qtd_estoqueE" name="qtd_estoqueE" class="form-control" placeholder="99"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Fornecedor *</label> 
                            <select class="form-control" id="fornecedorE" name="fornecedorE">
                                <option value="" selected>Selecione</option>
                                <?php 
                                    $stmt = $conn->prepare('SELECT * FROM fornecedores ORDER BY nome ASC');
                                    $stmt->execute(); 
                                    while($row = $stmt->fetch()){  
                                       echo "<option value='".$row["id_fornecedor"]."'>".utf8_encode($row["nome"])."</option>";
                                    }   
                                ?>    
                            </select><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Valor UND Pago *</label>
                            <input type="text" id="valor_und_pagoE" name="valor_und_pagoE" class="form-control" placeholder="99,99"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Valor UND Venda *</label> 
                            <input type="text" id="valor_und_vendaE" name="valor_und_vendaE" class="form-control" placeholder="99,99"><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-12"><br>
                    <div class="col-xs-12" align="left">
                        <div id="resEditarProduto"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button> 
              <input type="hidden" value="" name="idProdutoE" id="idProdutoE">
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirProduto" method="post" action="../inc/produtoExcluir.php">
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
                    <label>Você tem certeza que deseja excluir o produto<br><label id="nomeProdutoExcluir" name="nomeProdutoExcluir"></label>?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idProdutoExcluir" id="idProdutoExcluir">
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
        var table = $('#tabelaProdutos').DataTable({
          lengthChange: false,
          "order": [
            [0, "asc"]
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Produtos <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Produtos <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaProdutos_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirProduto').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirProduto').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/produtoInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirProduto').html(data);             
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
        $('#formEditarProduto').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarProduto').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/produtoAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarProduto').html(data);             
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
             var idProduto = $(this).data('id');              
             $("#idProdutoExcluir").val( idProduto ); 
             $("#idProdutoE").val( idProduto ); 
             // labels
             var nomeProduto = $(this).data('nome');
             $("#nomeProdutoExcluir").text( nomeProduto );                      
             $("#nomeProdutoEditar").text( nomeProduto );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/produtos/'+imagem);
             $("#imagemProduto").val( imagem ); 
             var nome = $(this).data('nome');            
             $("#nomeE").val( nome );                         
             var descricao = $(this).data('descricao');
             $("#descricaoE").val( descricao );   
             var qtd_estoque = $(this).data('estoque');
             $("#qtd_estoqueE").val( qtd_estoque );         
             var fornecedor = $(this).data('fornecedor');
             $("#fornecedorE").val( fornecedor );         
             var valorPago = $(this).data('valorpago');
             $("#valor_und_pagoE").val( valorPago );
             var valorVenda = $(this).data('valorvenda');
             $("#valor_und_vendaE").val( valorVenda );
             
        }); 
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <script>
        $(document).ready(function () {             
            var qtdEstoque = $("#qtd_estoque");
            qtdEstoque.mask('9999');
            var valorPago = $("#valor_und_pago");
            valorPago.mask('0.009,99', {reverse: true});
            var valorVenda = $("#valor_und_venda");
            valorVenda.mask('0.009,99', {reverse: true});
            var qtdEstoque = $("#qtd_estoqueE");
            qtdEstoque.mask('9999');
            var valorPago = $("#valor_und_pagoE");
            valorPago.mask('0.009,99', {reverse: true});
            var valorVenda = $("#valor_und_vendaE");
            valorVenda.mask('0.009,99', {reverse: true});
        });
                
        function BuscaEndereco(elemento) {
            var cep = elemento.value.replace("-", "");
            $.ajax({
                type: 'GET',
                url: 'https://viacep.com.br/ws/'+cep+'/json'
            })
            .done(function(data){   
                $('#endereco').val(data.logradouro);             
                $('#cidade').val(data.localidade);             
                $('#bairro').val(data.bairro);             
            })
        }        
        
        function BuscaEnderecoE(elemento) {
            var cep = elemento.value.replace("-", "");
            $.ajax({
                type: 'GET',
                url: 'https://viacep.com.br/ws/'+cep+'/json'
            })
            .done(function(data){   
                $('#enderecoE').val(data.logradouro);             
                $('#cidadeE').val(data.localidade);             
                $('#bairroE').val(data.bairro);             
            })
        }     
    </script>
  </body>
  </html>
