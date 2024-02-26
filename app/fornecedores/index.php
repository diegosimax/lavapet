<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "fornecedores";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Fornecedores</title>
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
            Fornecedores
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
                      <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Fornecedor"><img src="../dist/img/adicionar.png"></a></li>                                            
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaFornecedores" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>Nome</th>
                              <th>E-mail</th> 
                              <th>Telefone</th> 
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>Nome</th>
                              <th>E-mail</th>    
                              <th>Telefone</th>
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/fornecedorListar.php'); ?> 
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
            <form id="formInserirFornecedor" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Fornecedor</h4>
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
                            <div class="col-xs-6">
                                <label>Nome *</label>
                                <input type="text" name="nome" class="form-control" placeholder="Fulano da Silva ..."><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Email</label> 
                                <input type="text" name="email" class="form-control" placeholder="fulano@gmail.com ..."><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Telefone Móvel *</label>
                                <input type="text" id="telefone1" name="telefone1" class="form-control" placeholder="(99) 9 9999-9999"><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Telefone Fixo *</label> 
                                <input type="text" id="telefone2" name="telefone2" class="form-control" placeholder="(99) 9999-9999"><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Cep</label>
                                <input type="text" id="cep" name="cep" class="form-control" placeholder="99999-999" onchange="BuscaEndereco(this);"><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Endereço</label> 
                                <input type="text" id="endereco" name="endereco" class="form-control" placeholder="Endereço ..."><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Número</label>
                                <input type="text" id="numero" name="numero" class="form-control" placeholder="99"><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Complemento</label> 
                                <input type="text" id="complemento" name="complemento" class="form-control" placeholder="Complemento ..."><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Bairro</label>
                                <input type="text" id="bairro" name="bairro" class="form-control" placeholder="Bairro ..."><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Cidade</label> 
                                <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Cidade ..."><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>CNPJ</label>
                                <input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="99.999.999/9999-99"><br>
                            </div>                
                            <div class="col-xs-6">
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                     <div class="col-md-12">
                        <div class="form-group">
                            <label>Observação</label><br>
                            <textarea id="observacao" name="observacao" rows="5" class="form-control"></textarea>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                                
                    <div class="col-md-12">
                        <div class="col-xs-12" align="left">
                            <div id="resInserirFornecedor"></div>
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
          <form id="formEditarFornecedor" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeFornecedorEditar" name="nomeFornecedorEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                                     
                    <img src="" width="200px" class="img-circular" name="imagemE" id="imagemE">
                    <input type="hidden" id="imagemFornecedor" name="imagemFornecedor" value="">
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
                        <div class="col-xs-6">
                            <label>Nome *</label>
                            <input type="text" id="nomeE" name="nomeE" class="form-control" placeholder="Fulano da Silva ..."><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Email</label> 
                            <input type="text" id="emailE" name="emailE" class="form-control" placeholder="fulano@gmail.com ..."><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->                      
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Telefone Móvel *</label>
                            <input type="text" id="telefone1E" name="telefone1E" class="form-control" placeholder="(99) 9 9999-9999"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Telefone Fixo *</label> 
                            <input type="text" id="telefone2E" name="telefone2E" class="form-control" placeholder="(99) 9999-9999"><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Cep</label>
                            <input type="text" id="cepE" name="cepE" class="form-control" placeholder="99999-999" onchange="BuscaEnderecoE(this);"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Endereço</label> 
                            <input type="text" id="enderecoE" name="enderecoE" class="form-control" placeholder="Endereço ..."><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Número</label>
                            <input type="text" id="numeroE" name="numeroE" class="form-control" placeholder="99"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Complemento</label> 
                            <input type="text" id="complementoE" name="complementoE" class="form-control" placeholder="Complemento ..."><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Bairro</label>
                            <input type="text" id="bairroE" name="bairroE" class="form-control" placeholder="Bairro ..."><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Cidade</label> 
                            <input type="text" id="cidadeE" name="cidadeE" class="form-control" placeholder="Cidade ..."><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>CNPJ</label>
                            <input type="text" id="cnpjE" name="cnpjE" class="form-control" placeholder="99.999.999/9999-99"><br>
                        </div>                
                        <div class="col-xs-6">
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->
                 <div class="col-md-12">
                    <div class="form-group">
                        <label>Observação</label><br>
                        <textarea id="observacaoE" name="observacaoE" rows="5" class="form-control"></textarea>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col --> 
                <div class="col-md-12"><br>
                    <div class="col-xs-12" align="left">
                        <div id="resEditarFornecedor"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button> 
              <input type="hidden" value="" name="idFornecedorE" id="idFornecedorE">
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirFornecedor" method="post" action="../inc/fornecedorExcluir.php">
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
                    <label>Você tem certeza que deseja excluir o fornecedor<br><label id="nomeFornecedorExcluir" name="nomeFornecedorExcluir"></label>?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idFornecedorExcluir" id="idFornecedorExcluir">
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
        var table = $('#tabelaFornecedores').DataTable({
          lengthChange: false,
          "order": [
            [0, "asc"]
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Fornecedores <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Fornecedores <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaFornecedores_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirFornecedor').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirFornecedor').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/fornecedorInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirFornecedor').html(data);             
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
        $('#formEditarFornecedor').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarFornecedor').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/fornecedorAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarFornecedor').html(data);             
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
             var idFornecedor = $(this).data('id');              
             $("#idFornecedorExcluir").val( idFornecedor ); 
             $("#idFornecedorE").val( idFornecedor ); 
             // labels
             var nomeFornecedor = $(this).data('nome');
             $("#nomeFornecedorExcluir").text( nomeFornecedor );                      
             $("#nomeFornecedorEditar").text( nomeFornecedor );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/fornecedores/'+imagem);
             $("#imagemFornecedor").val( imagem ); 
             var nome = $(this).data('nome');            
             $("#nomeE").val( nome );            
             var email = $(this).data('email');
             $("#emailE").val( email );                        
             var telefone1 = $(this).data('telefone1');
             $("#telefone1E").val( telefone1 ); 
             var telefone2 = $(this).data('telefone2');
             $("#telefone2E").val( telefone2 ); 
             var cep = $(this).data('cep');
             $("#cepE").val( cep );   
             var cnpj = $(this).data('cnpj');
             $("#cnpjE").val( cnpj ); 
             var endereco = $(this).data('endereco');
             $("#enderecoE").val( endereco ); 
             var numero = $(this).data('numero');
             $("#numeroE").val( numero ); 
             var complemento = $(this).data('complemento');
             $("#complementoE").val( complemento ); 
             var bairro = $(this).data('bairro');
             $("#bairroE").val( bairro ); 
             var cidade = $(this).data('cidade');
             $("#cidadeE").val( cidade ); 
             var observacao = $(this).data('observacao');
             $("#observacaoE").val( observacao );
        }); 
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <script>
        $(document).ready(function () {             
            var telefone1 = $("#telefone1");
            telefone1.mask('(99) 9 9999-9999');
            var telefone2 = $("#telefone2");
            telefone2.mask('(99) 9999-9999');
            var $cnpj = $("#cnpj");
            $cnpj.mask('00.000.000/0000-00', {reverse: true});
            var $cep = $("#cep");
            $cep.mask('99999-999'); 
             var telefone1 = $("#telefone1E");
            telefone1.mask('(99) 9 9999-9999');
            var telefone2 = $("#telefone2E");
            telefone2.mask('(99) 9999-9999');
            var $cnpj = $("#cnpjE");
            $cnpj.mask('00.000.000/0000-00', {reverse: true});
            var $cep = $("#cepE");
            $cep.mask('99999-999'); 
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
