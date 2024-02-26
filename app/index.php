<?php
    session_start();  
    if (!isset($_SESSION["logado"])){        
      echo "<script type='text/javascript'> window.location = '../' </script>";
    }  
    require_once('../inc/global.php');
    require_once('./inc/carregaDadosTopo.php'); 
    $urlAtiva = "home";
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="dist/img/favicon.png" type="image/png">
    <title><?=$GLOBALS["nomeEmpresa"]?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- DATA TABLE -->
    <link rel="stylesheet" href="dist/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/buttons.bootstrap.min.css">
    <!-- DATA TABLE -->

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
    <div class="wrapper">
        
      <header class="main-header">
        <?php require_once('./inc/carregaHeader.php'); ?>        
      </header>
        
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <?php require_once('./inc/carregaSidebar.php'); ?>        
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <?php
                    try {       
                        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

                        $stmt = $conn->prepare('SELECT count(*) FROM clientes');
                        $stmt->execute();   
                        $qtdClientes = $stmt->fetchColumn();  
                        
                        $stmt = $conn->prepare('SELECT count(*) FROM fornecedores');
                        $stmt->execute();   
                        $qtdFornecedores = $stmt->fetchColumn();
                        
                        $stmt = $conn->prepare('SELECT count(*) FROM animais');
                        $stmt->execute();   
                        $qtdAnimais = $stmt->fetchColumn();
                        
                        $stmt = $conn->prepare('SELECT count(*) FROM racas');
                        $stmt->execute();   
                        $qtdRacas = $stmt->fetchColumn();
                        
                        $stmt = $conn->prepare('SELECT count(*) FROM produtos');
                        $stmt->execute();   
                        $qtdProdutos = $stmt->fetchColumn();
                        
                        $stmt = $conn->prepare('SELECT count(*) FROM servicos');
                        $stmt->execute();   
                        $qtdServicos = $stmt->fetchColumn();
                        
                    } catch(PDOException $e) {
                        echo 'ERROR: ' . $e->getMessage();
                    }
                  ?>
                  <h3><?php echo $qtdClientes; ?></h3>
                  <p>Clientes</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
                <a href="./clientes/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">                  
                  <h3><?php echo $qtdFornecedores; ?></h3>
                  <p>Fornecedores</p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="./fornecedores/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $qtdAnimais; ?></h3>
                  <p>Animais</p>
                </div>
                <div class="icon">
                  <i class="fa fa-paw"></i>
                </div>
                <a href="./animais/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                   <h3><?php echo $qtdRacas; ?></h3>
                  <p>Raças</p>
                </div>
                <div class="icon">
                  <i class="fa fa-paw"></i>
                </div>
                <a href="./racas/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->                      
          </div> <!-- row --> 
          <div class="row">
              <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                   <h3><?php echo $qtdProdutos; ?></h3>
                  <p>Produtos</p>
                </div>
                <div class="icon">
                  <i class="fa fa-cubes"></i>
                </div>
                <a href="./produtos/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col --> 
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-olive">
                <div class="inner">
                   <h3><?php echo $qtdServicos; ?></h3>
                  <p>Serviços</p>
                </div>
                <div class="icon">
                  <i class="fa fa-wrench"></i>
                </div>
                <a href="./servicos/" class="small-box-footer">Confira <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col --> 
          </div> <!-- row -->  
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <div class="modal fade" id="modal-troca-senha">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="formularioSenha">  
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Troque sua senha padrão por uma nova!</h4>
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

      <?php require_once('./inc/carregaFooter.php'); ?>
        
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3.1.1 -->
    <script src="plugins/jQuery/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script> 
    
    <!-- JQUERY AJAX -->
    <script> 
        $(document).ready(function(){
            $('#formularioSenha').submit(function(){     
                $('#resultadoSenha').html("<b>Processando...</b>");
                $.ajax({
                    type: 'POST',
                    url: './inc/alteraSenhaPrimeiroAcesso.php', 
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
      
    <?php require_once('./inc/verificaPrimeiroAcesso.php'); ?>  
      
  </body>   
</html>