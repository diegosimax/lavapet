<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "animais";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Animais</title>
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
            Animais
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
                      <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Animal"><img src="../dist/img/adicionar.png"></a></li>                                            
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab_1" align="left">
                        <table id="tabelaAnimais" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>                             
                              <th>Nome</th>                                
                              <th>Espécie</th>                                
                              <th>Raça</th> 
                              <th>Sexo</th> 
                              <th>Cliente</th> 
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </thead>
                          <tfoot>
                            <tr>                              
                              <th>Nome</th>
                              <th>Espécie</th>
                              <th>Raça</th>    
                              <th>Sexo</th>    
                              <th>Cliente</th>
                              <th>Criado em</th>
                              <th>Ações</th>                              
                            </tr>
                          </tfoot>
                          <tbody>
                            <?php require_once('../inc/animalListar.php'); ?> 
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
            <form id="formInserirAnimal" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Animal</h4>
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
                                <input type="text" name="nome" class="form-control" placeholder="Dewa ..."><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Porte *</label> 
                                <select class="form-control" name="porte">
                                    <option value="" selected>Selecione</option>
                                    <option value="P">Pequeno</option>
                                    <option value="M">Médio</option>
                                    <option value="G">Grande</option>
                                </select><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->                      
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label>Raça *</label> 
                                <select class="form-control" name="raca">
                                    <option value="0" selected>Não definida</option>
                                    <?php 
                                        $stmt = $conn->prepare('SELECT * FROM racas ORDER BY nome ASC');
                                        $stmt->execute(); 
                                        while($row = $stmt->fetch()){  
                                            switch ($row["especie"]) {
                                                case 1:
                                                    $especie = "Cão";
                                                    break;
                                                case 2:
                                                    $especie = "Gato";
                                                    break;
                                                case 3:
                                                    $especie = "Coelho";
                                                    break;
                                                case 4:
                                                    $especie = "Pássaro";
                                                    break;
                                                case 5:
                                                    $especie = "Tartatuga";
                                                    break;
                                                case 6:
                                                    $especie = "Furão";
                                                    break;
                                                default:
                                                    $especie = "Espécie não definida!";
                                                    break;
                                            }      
                                           echo "<option value='".$row["id_raca"]."'>".utf8_encode($row["nome"])." - ".$especie."</option>";
                                        }   
                                    ?>    
                                </select><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Sexo *</label> 
                                <select class="form-control" name="sexo">
                                    <option value="" selected>Selecione</option>
                                    <option value="F">Fêmea</option>
                                    <option value="M">Macho</option>
                                </select><br>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col --> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label>Cliente *</label>
                                <select class="form-control" name="cliente">
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
                            <div id="resInserirAnimal"></div>
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
          <form id="formEditarAnimal" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeAnimalEditar" name="nomeAnimalEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                                     
                    <img src="" width="200px" class="img-circular" name="imagemE" id="imagemE">
                    <input type="hidden" id="imagemAnimal" name="imagemAnimal" value="">
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
                            <input type="text" id="nomeE" name="nomeE" class="form-control" placeholder="Dewa ..."><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Porte *</label> 
                            <select class="form-control" id="porteE" name="porteE">
                                <option value="">Selecione</option>
                                <option value="P">Pequeno</option>
                                <option value="M">Médio</option>
                                <option value="G">Grande</option>
                            </select><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->                      
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                                <label>Raça *</label> 
                                <select class="form-control" id="racaE" name="racaE">
                                    <option value="0">Não definida</option>
                                    <?php 
                                        $stmt = $conn->prepare('SELECT * FROM racas ORDER BY nome ASC');
                                        $stmt->execute(); 
                                        while($row = $stmt->fetch()){  
                                           switch ($row["especie"]) {
                                                case 1:
                                                    $especie = "Cão";
                                                    break;
                                                case 2:
                                                    $especie = "Gato";
                                                    break;
                                                case 3:
                                                    $especie = "Coelho";
                                                    break;
                                                case 4:
                                                    $especie = "Pássaro";
                                                    break;
                                                case 5:
                                                    $especie = "Tartatuga";
                                                    break;
                                                case 6:
                                                    $especie = "Furão";
                                                    break;
                                                default:
                                                    $especie = "Espécie não definida!";
                                                    break;
                                            }           
                                            echo "<option value='".$row["id_raca"]."'>".utf8_encode($row["nome"])." - ".$especie."</option>";
                                        }   
                                    ?>    
                                </select><br>
                            </div>  
                            <div class="col-xs-6">
                                <label>Sexo *</label> 
                                <select class="form-control" id="sexoE" name="sexoE">
                                    <option value="">Selecione</option>
                                    <option value="F">Fêmea</option>
                                    <option value="M">Macho</option>
                                </select><br>
                            </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->                
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Cliente *</label>
                            <select class="form-control" id="clienteE" name="clienteE">
                                <option value="">Selecione</option>
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
                        <div id="resEditarAnimal"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button> 
              <input type="hidden" value="" name="idAnimalE" id="idAnimalE">
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
        
      <div class="modal fade" id="modal-excluir">
        <form id="formExcluirAnimal" method="post" action="../inc/animalExcluir.php">
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
                    <label>Você tem certeza que deseja excluir o animal<br><label id="nomeAnimalExcluir" name="nomeAnimalExcluir"></label>?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idAnimalExcluir" id="idAnimalExcluir">
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
        var table = $('#tabelaAnimais').DataTable({
          lengthChange: false,
          "order": [
            [0, "asc"]
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Animais <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Animais <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3, 4 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaAnimais_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirAnimal').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirAnimal').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/animalInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirAnimal').html(data);             
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
        $('#formEditarAnimal').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarAnimal').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/animalAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarAnimal').html(data);             
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
             var idAnimal = $(this).data('id');              
             $("#idAnimalExcluir").val( idAnimal ); 
             $("#idAnimalE").val( idAnimal ); 
             // labels
             var nomeAnimal = $(this).data('nome');
             $("#nomeAnimalExcluir").text( nomeAnimal );                      
             $("#nomeAnimalEditar").text( nomeAnimal );
             // editar             
             var imagem = $(this).data('imagem');                         
             $('#imagemE').attr('src', '<?=$GLOBALS["url"]?>app/dist/img/animais/'+imagem);
             $("#imagemAnimal").val( imagem ); 
             var nome = $(this).data('nome');            
             $("#nomeE").val( nome );            
             var porte = $(this).data('porte');
             $("#porteE").val( porte );                        
             var raca = $(this).data('raca');
             $("#racaE").val( raca ); 
             var sexo = $(this).data('sexo');
             $("#sexoE").val( sexo ); 
             var cliente = $(this).data('cliente');
             $("#clienteE").val( cliente );                
             var observacao = $(this).data('observacao');
             $("#observacaoE").val( observacao );
        }); 
    </script>    
    
  </body>
  </html>
