<?php
    session_start();
    if (!isset($_SESSION["logado"])){
        echo "<script type='text/javascript'> window.location = '../' </script>";
    }
    require_once('../../inc/global.php');
    require_once('../inc/carregaDadosTopo.php'); 
    $urlAtiva = "configuracoes";  
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="../dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?> | Configurações do Perfil</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- DATA TABLE -->
    <link rel="stylesheet" href="../dist/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../dist/css/buttons.bootstrap.min.css">
    <!-- DATA TABLE -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="../dist/css/main.css">
    <!-- MAIN CSS -->

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
          ?>
          <div id="resultadoUsuario"></div>
          <h1>
            Suas 
            <small>configurações</small>
          </h1>      
        </section>
        <!-- Main content -->
        <section class="content">
          <form id="formularioUsuario">
          <div class="row">
            <div class="col-md-12" align="center">
                <img src="<?php echo $url; ?>app/dist/img/usuarios/<?php echo $img_usuario; ?>" class="img-circular" width="200px">&nbsp;                                     
            </div>     
            <?php 
                $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario');
                $stmt->execute(array('id_usuario' => $id_usuario));    
                if($row = $stmt->fetch()){ 
                    $nome = $row["nome"];
                    $email = $row["email"];                          
                }
              
            ?>  
            <div class="col-md-12">
              <div class="form-group">
                  <div class="col-xs-4">
                      <label>Nome</label>
                      <input type="text" name="nome" class="form-control" placeholder="Nome ..." value="<?=utf8_encode($nome)?>">
                  </div>
                  <div class="col-xs-4">
                    <label>Email</label><br> 
                    <input type="text" name="email" class="form-control" placeholder="E-mail ..." value="<?=utf8_encode($email)?>">
                  </div>
                  <div class="col-xs-4">
                      <label>&nbsp;</label><br>
                      <div align="left">                         
                        <button type="submit" class="btn btn-info" >Salvar</button>               
                      </div> 
                  </div>                   
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
              <div class="form-group"><br>                
                <div class="col-xs-4">
                    <label>Senha</label><br>
                    <a href="#" data-toggle="modal" data-target="#modal-senha">DIGITE UMA NOVA SENHA</a>
                </div>
                <div class="col-xs-4">
                    <label>Foto do Perfil</label><br>
                    <a href="#" data-toggle="modal" data-target="#modal-foto">TROCAR FOTO</a>
                </div>                                  
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->     
          </div>
          <!-- ./row -->
          </form>
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
        
      <div class="modal fade" id="modal-senha">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="formularioSenha">  
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Digite a nova senha!</h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="col-xs-6">
                        <label>Nova Senha</label><br>
                        <input type="password" name="senhaNova" class="form-control">
                    </div>
                    <div class="col-xs-6">
                        <label>Repita a nova Senha</label><br>
                        <input type="password" name="senhaNovaR" class="form-control">
                    </div>   
                  </div>
                  <!-- /.form-group -->
                </div>                  
                <!-- /.col -->                
                <div class="col-md-12"><br>
                    <div class="col-xs-12" align="left">
                        <div id="resultadoSenha"></div>
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

      <div class="modal fade" id="modal-foto">
        <div class="modal-dialog">
          <form id="formularioImagem" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Trocar sua imagem de perfil!</h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12" >
                  <div class="col-xs-12">                      
                      <div class="form-group">
                        <label>Arquivo PNG, JPG, JPEG ou GIF de até 3 MB!<br>Tamanhos iguais e acima de 199 px<br>Ex: Imagem de 200 x 200.</label>
                        <input type="file" id="imagemUpload" name="imagemUpload" class="form-control">                        
                      </div>                     
                      <!-- /.form-group -->
                  </div>
                </div>
                <!-- /.col --> 
                <div class="col-md-12"><br>
                  <div class="col-xs-12" align="left">
                    <div id="resultadoImagem"></div>
                  </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
            </div>
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
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
    <!-- TABELA COM SEARCH E PDF/EXCEL -->

    <script>
      $(document).ready(function () {
        $('.sidebar-menu').tree()
      })
    </script>
    <script>
      $(document).ready(function () {
        var table = $('#tabelaVagas').DataTable({
          lengthChange: false,
          "order": [
            [0, "desc"]
          ],
          buttons: ['copy', 'excel', 'pdf', 'colvis']
        });

        table.buttons().container()
          .appendTo('#tabelaVagas_wrapper .col-sm-6:eq(0)');
      });
    </script>
    <script>
      $(document).ready(function () {
        var table = $('#tabelaCandidatos').DataTable({
          lengthChange: false,
          "order": [
            [0, "desc"]
          ],
          buttons: ['copy', 'excel', 'pdf', 'colvis']
        });

        table.buttons().container()
          .appendTo('#tabelaCandidatos_wrapper .col-sm-6:eq(0)');
      });
    </script>
      
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){
        $('#formularioSenha').submit(function(){     
            $('#resultadoSenha').html("<b>Processando...</b>");
            $.ajax({
                type: 'POST',
                url: '../inc/configAlteraSenha.php', 
                data: $(this).serialize()
            })
            .done(function(data){   
                $('#resultadoSenha').html(data);             
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
        $('#formularioUsuario').submit(function(){     
            $('#resultadoUsuario').html("<b>Processando...</b>");
            $.ajax({
                type: 'POST',
                url: '../inc/configAlteraUsuario.php', 
                data: $(this).serialize()
            })
            .done(function(data){   
                $('#resultadoUsuario').html(data);             
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
        $('#formularioImagem').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resultadoImagem').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/configAlteraFoto.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resultadoImagem').html(data);             
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

