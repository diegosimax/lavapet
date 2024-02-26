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
    <title><?=$GLOBALS["nomeEmpresa"]?> | Itens da Ordem de Compra</title>
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
            
            $stmt = $conn->prepare('SELECT CLI.nome, CLI.id_cliente, ODC.status, ODC.criado_em
                                      FROM clientes AS CLI 
                                INNER JOIN ordem_de_compra AS ODC
                                        ON CLI.id_cliente = ODC.id_cliente
                                     WHERE ODC.id_ordem_de_compra = :id_ordem_de_compra');
            $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
            if($row = $stmt->fetch()){ 
                $nomeCliente = utf8_encode($row["nome"]);    
                if($row["status"] == "A"){
                    $status = "Aberto";                    
                }else{
                    $status = "Fechado";
                }    
                $idCliente = $row["id_cliente"];
                $dia = substr($row["criado_em"], 8, 2);
                $mes = substr($row["criado_em"], 5, 2);
                $ano = substr($row["criado_em"], 0, 4);                
                $dataOrdem = $dia."/".$mes."/".$ano;
            }
          ?>             
          <div class="row"> 
            <div class="col-xs-12" align="right">
              <a href="javascript:window.history.go(-1)" class="btn btn-default"><i class="fa fa-backward"></i> Voltar</a>                
            </div>                
          </div>
        </section>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12">
               <div class="nav-tabs-custom">
                  <div align="center">
                      <h3>     
                        <br>  
                        Ordem de Compra Nº: <?php echo $_GET["id"]; ?><br>
                        Cliente: <?php echo $nomeCliente; ?><br>
                        Status: <?php echo $status; ?>  
                      </h3>      
                  </div>
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Todos</a></li>
                    <li></li>
                    <li><a href="#" data-toggle="modal" data-target="#modal-inserir" title="Inserir Item"><img src="../dist/img/adicionar.png"></a></li>                                                                
                    <li></li>  
                    <li><a href="../inc/imprimirCupom.php?id=<?php echo $_GET["id"]; ?>" title="Imprimir Cupom de Compra" target="_blank" ><img src="../dist/img/imprime-cupom.png"></a></li>                                                                                    
                    <li></li>
                    <li><a href="../inc/imprimirHistorico.php?id=<?php echo $_GET["id"]; ?>" title="Imprimir Histórico de Pagamento" target="_blank" ><img src="../dist/img/imprime-historico.png"></a></li>                                                                                    
                    <li></li>
                    <li><a href="#" data-toggle="modal" data-target="#modal-fechar" title="Status/Lançamentos"><img src="../dist/img/fechar-ordem.png"></a></li>                                                                                    
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1" align="left">
                      <table id="tabelaItens" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                          <tr>   
                            <th>Item</th>                              
                            <th>Qtd</th>    
                            <th>Valor UND</th> 
                            <th>Total</th>                               
                            <th>Data</th>                               
                            <th>Ações</th>                              
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>                              
                            <th>Item</th>                              
                            <th>Qtd</th>    
                            <th>Valor UND</th> 
                            <th>Total</th>                                                                            
                            <th>Data</th>                                                                            
                            <th>Ações</th>                              
                          </tr>
                        </tfoot>                          
                        <tbody>
                        <?php                             
                            $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY id_item_da_ordem DESC');
                            $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
                            $totalDaOrdem = 0;
                            $descricao = "";

                            while($row = $stmt->fetch()){  
                                $dia = substr($row["data_venda"], 8, 2);
                                $mes = substr($row["data_venda"], 5, 2);
                                $ano = substr($row["data_venda"], 0, 4);
                                $dataC = $dia."/".$mes."/".$ano;
                                echo "<tr>";                                                                  
                                if($row["id_produto"] <> 0){
                                    $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                                    $stmt2->execute(array('id_produto' => $row["id_produto"])); 
                                    if($row2 = $stmt2->fetch()){                                         
                                        echo "<td>"; 
                                        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
                                             data-id='".$row["id_item_da_ordem"]."' data-nome='".utf8_encode($row2["nome"])."' 
                                             data-tipo='P' data-produto='".$row["id_produto"]."' 
                                             data-quantidade='".$row["quantidade"]."' data-dia='".$dataC."'>";
                                        echo utf8_encode($row2["nome"]);
                                        $descricao = utf8_encode($row2["nome"]);
                                        echo "</a>";      
                                        echo "</td>";
                                        echo "<td>";                    
                                        echo $row["quantidade"];        
                                        echo "</td>";  
                                        echo "<td>R$ ".number_format($row2["valor_venda_unidade"], 2, ',', '.')."</td>"; 
                                        $total = $row2["valor_venda_unidade"] * $row["quantidade"];
                                        echo "<td>R$ ".number_format($total, 2, ',', '.')."</td>"; 
                                        $totalDaOrdem = $totalDaOrdem + $total;                                        
                                    }
                                }
                                if($row["id_servico"] <> 0){
                                    $stmt2 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
                                    $stmt2->execute(array('id_servico' => $row["id_servico"])); 
                                    if($row2 = $stmt2->fetch()){ 
                                        echo "<td>";
                                        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
                                             data-id='".$row["id_item_da_ordem"]."' data-nome='".utf8_encode($row2["descricao"])."' 
                                             data-tipo='S' data-servico='".$row["id_servico"]."' 
                                             data-pet='".$row["id_animal"]."'
                                             data-quantidade='".$row["quantidade"]."' data-dia='".$dataC."'>";
                                        if($row["id_animal"] <> 0){
                                            $stmt3 = $conn->prepare('SELECT * FROM animais WHERE id_animal = :id_animal');
                                            $stmt3->execute(array('id_animal' => $row["id_animal"]));    
                                            if($row3 = $stmt3->fetch()){ 
                                                echo utf8_encode($row2["descricao"])." (".utf8_encode($row3["nome"]).")";                                                       
                                                $descricao = utf8_encode($row2["descricao"])." (".utf8_encode($row3["nome"]).")";
                                            }
                                        }else{
                                            echo utf8_encode($row2["descricao"]);   
                                            $descricao = utf8_encode($row2["descricao"]);   
                                        }      
                                        echo "</a>";  
                                        echo "</td>";
                                        echo "<td>";                    
                                        echo $row["quantidade"];        
                                        echo "</td>";  
                                        echo "<td>R$ ".number_format($row2["valor"], 2, ',', '.')."</td>"; 
                                        $total = $row2["valor"] * $row["quantidade"];
                                        echo "<td>R$ ".number_format($total, 2, ',', '.')."</td>"; 
                                        $totalDaOrdem = $totalDaOrdem + $total;
                                    }
                                }
                                
                                $dia = substr($row["data_venda"], 8, 2);
                                $mes = substr($row["data_venda"], 5, 2);
                                $ano = substr($row["data_venda"], 0, 4);
                                echo "<td>".$dia."/".$mes."/".$ano."</td>";   
                                        
                                echo "<td>";        
                                echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir-item' data-id='".$row["id_item_da_ordem"]."' data-item='".$descricao."'><img src='../dist/img/delete.png' title='Excluir Item'></a>";
                                echo "</td>";    
                                echo "</tr>";                                           
                            } 
                          ?> 
                        </tbody>                        
                      </table>
                      <div align="right"> 
                        <h3><b> TOTAL
                            <?php echo "R$ ".number_format($totalDaOrdem, 2, ',', '.'); ?>
                        </b></h3>
                      </div>
                    </div>
                    <!-- /.tab-pane -->                      
                  </div>
                  <!-- /.tab-content -->
                </div>
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
            <form id="formInserirItem" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Inserir Item</h4>
                </div>
                <div class="modal-body">
                    <div class="row" align="center">                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Cliente</label>
                                    <select class="form-control" id="cliente" name="cliente" disabled>
                                        <?php 
                                            $stmt = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente');
                                            $stmt->execute(array('id_cliente' => $idCliente)); 
                                            if($row = $stmt->fetch()){  
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
                                    <label>Tipo *</label> 
                                    <select class="form-control" id="tipo" name="tipo" onchange="tipoItem();">
                                        <option value="S">Serviço</option>
                                        <option value="P">Produto</option>
                                    </select><br>
                                </div>
                                <div id="servico">
                                    <div class="col-xs-12">                                    
                                        <label>Serviço *</label> 
                                        <select class="form-control" id="servico" name="servico">
                                            <?php 
                                                $stmt = $conn->prepare('SELECT * FROM servicos ORDER BY descricao ASC');
                                                $stmt->execute(); 
                                                while($row = $stmt->fetch()){  
                                                    echo "<option value='".$row["id_servico"]."'>".utf8_encode($row["descricao"])." - R$ ".number_format($row["valor"], 2, ',', '.')."</option>";                                                        
                                                }   
                                            ?>  
                                        </select><br>
                                    </div>
                                    <div class="col-xs-12">
                                            <label>Pet *</label> 
                                            <select class="form-control" id="pet" name="pet">
                                                <option value="">Nenhum</option>                                            
                                                <?php 
                                                    $stmt = $conn->prepare('SELECT * FROM animais WHERE id_cliente = :id_cliente ORDER BY nome ASC');
                                                    $stmt->execute(array('id_cliente' => $idCliente)); 
                                                    while($row = $stmt->fetch()){  
                                                        echo "<option value='".$row["id_animal"]."'>".utf8_encode($row["nome"])."</option>";                                                        
                                                    }   
                                                ?> 
                                            </select><br>
                                    </div>
                                </div>
                                <div id="produto" style="display: none;">
                                    <div class="col-xs-12">                                        
                                        <label>Produto *</label> 
                                        <select class="form-control" id="produto" name="produto">
                                            <?php 
                                                $stmt = $conn->prepare('SELECT * FROM produtos ORDER BY nome ASC');
                                                $stmt->execute(); 
                                                while($row = $stmt->fetch()){
                                                    echo "<option value='".$row["id_produto"]."'>".utf8_encode($row["nome"])." - ".utf8_encode($row["descricao"])." - R$ ".number_format($row["valor_venda_unidade"], 2, ',', '.')." - ".$row["qtd_estoque"]." UNIDADE(S)"."</option>";                                                                                                            
                                                }   
                                            ?>  
                                        </select><br>                                        
                                    </div>
                                </div>    
                                <script>
                                    function tipoItem() {
                                      var tipo = document.getElementById("tipo").value;
                                      if (tipo == "P") {
                                        document.getElementById("produto").style.display = "block";
                                        document.getElementById("servico").style.display = "none";
                                      } else {
                                        document.getElementById("produto").style.display = "none";
                                        document.getElementById("servico").style.display = "block";
                                      }
                                    }
                                </script>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->   
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label>Quantidade *</label>
                                    <input type="text" id="quantidade" name="quantidade" class="form-control" placeholder="1" value="1"><br>
                                </div>  
                                <div class="col-xs-6">
                                    <label>Data *</label> 
                                    <?php
                                        date_default_timezone_set('America/Sao_Paulo');
                                        $data = date("d/m/Y");                                     
                                    ?>
                                    <input type="text" id="data" name="data" class="form-control" placeholder="<?php echo $data; ?>" value="<?php echo $data; ?>"><br>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->  
                        <div class="col-md-12">
                            <div class="col-xs-12" align="left">
                                <div id="resInserirItem"></div>
                            </div>
                        </div>                  
                        <!-- /.col --> 
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info pull-right" >Salvar</button>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
                </div>
                <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="idOrdem" id="idOrdem">
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>       
        
      <div class="modal fade" id="modal-editar">
        <div class="modal-dialog">
          <form id="formEditarItem" method="post" enctype="multipart/form-data"> 
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Editar <label id="nomeItemEditar" name="nomeItemEditar"></label></h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Cliente</label>
                            <select class="form-control" id="clienteE" name="clienteE" disabled>
                                <?php 
                                    $stmt = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente');
                                    $stmt->execute(array('id_cliente' => $idCliente)); 
                                    if($row = $stmt->fetch()){  
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
                            <label>Tipo *</label> 
                            <select class="form-control" id="tipoE" name="tipoE" onchange="tipoItemE();">
                                <option value="S">Serviço</option>
                                <option value="P">Produto</option>
                            </select><br>
                        </div>
                        <div id="servicoE">
                            <div class="col-xs-12">                                    
                                <label>Serviço *</label> 
                                <select class="form-control" id="servicoED" name="servicoED">
                                    <?php 
                                        $stmt = $conn->prepare('SELECT * FROM servicos ORDER BY descricao ASC');
                                        $stmt->execute(); 
                                        while($row = $stmt->fetch()){  
                                            echo "<option value='".$row["id_servico"]."'>".utf8_encode($row["descricao"])." - R$ ".number_format($row["valor"], 2, ',', '.')."</option>";                                                        
                                        }   
                                    ?>  
                                </select><br>
                            </div>
                            <div class="col-xs-12">
                                    <label>Pet *</label> 
                                    <select class="form-control" id="petE" name="petE">
                                        <option value="">Nenhum</option>                                            
                                        <?php 
                                            $stmt = $conn->prepare('SELECT * FROM animais WHERE id_cliente = :id_cliente ORDER BY nome ASC');
                                            $stmt->execute(array('id_cliente' => $idCliente)); 
                                            while($row = $stmt->fetch()){  
                                                echo "<option value='".$row["id_animal"]."'>".utf8_encode($row["nome"])."</option>";                                                        
                                            }   
                                        ?> 
                                    </select><br>
                            </div>
                        </div>
                        <div id="produtoE">
                            <div class="col-xs-12">                                        
                                <label>Produto *</label> 
                                <select class="form-control" id="produtoED" name="produtoED">
                                    <?php 
                                        $stmt = $conn->prepare('SELECT * FROM produtos ORDER BY nome ASC');
                                        $stmt->execute(); 
                                        while($row = $stmt->fetch()){  
                                            echo "<option value='".$row["id_produto"]."'>".utf8_encode($row["nome"])." - ".utf8_encode($row["descricao"])." - R$ ".number_format($row["valor_venda_unidade"], 2, ',', '.')." - ".$row["qtd_estoque"]." UNIDADE(S)"."</option>";                                                                                                            
                                        }   
                                    ?>  
                                </select><br>                                        
                            </div>
                        </div>    
                        <script>
                            function tipoItemE() {
                              var tipo = document.getElementById("tipoE").value;
                              if (tipo == "P") {
                                document.getElementById("produtoE").style.display = "block";
                                document.getElementById("servicoE").style.display = "none";
                              } else {
                                document.getElementById("produtoE").style.display = "none";
                                document.getElementById("servicoE").style.display = "block";
                              }
                            }
                        </script>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->   
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label>Quantidade *</label>
                            <input type="text" id="quantidadeE" name="quantidadeE" class="form-control" placeholder="1" value="1"><br>
                        </div>  
                        <div class="col-xs-6">
                            <label>Data *</label> 
                            <?php
                                date_default_timezone_set('America/Sao_Paulo');
                                $data = date("d/m/Y");                                     
                            ?>
                            <input type="text" id="dataE" name="dataE" class="form-control" placeholder="<?php echo $data; ?>" value="<?php echo $data; ?>"><br>
                        </div>
                    </div>
                    <!-- /.form-group -->
                </div>
                <!-- /.col -->  
                <div class="col-md-12"><br>
                    <div class="col-xs-12" align="left">
                        <div id="resEditarItem"></div>
                    </div>
                </div>                  
                <!-- /.col --> 
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >Salvar</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>               
            </div>
            <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="idOrdem" id="idOrdem">
            <input type="hidden" value="" name="idItemEditar" id="idItemEditar">
          </div>
          <!-- /.modal-content -->
          </form>
        </div>
        <!-- /.modal-dialog -->
      </div>   
        
      <div class="modal fade" id="modal-fechar">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="formFecharOrdem" method="post" enctype="multipart/form-data" >  
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Pagamentos da Ordem de Compra</h4>
                  <?php
                    if (isset($_SESSION["MSG_SUCESSO_PAG"])){
                        echo "<br><div class='alert alert-success alert-dismissible'>
                                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                <h4><i class='icon fa fa-check'></i> Sucesso!</h4>".$_SESSION["MSG_SUCESSO_PAG"]."</div>"; 
                        unset($_SESSION["MSG_SUCESSO_PAG"]);
                    }    
                  ?>
                </div>
                <div class="modal-body">
                    <div class="row" align="center">                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Cliente</label>
                                    <select class="form-control" id="cliente" name="cliente" disabled>
                                        <?php 
                                            $stmt = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente');
                                            $stmt->execute(array('id_cliente' => $idCliente)); 
                                            if($row = $stmt->fetch()){  
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
                                        <option value="A" <?php if($status == "Aberto"){ echo "selected"; } ?>>Aberto</option>
                                        <option value="F" <?php if($status == "Fechado"){ echo "selected"; } ?>>Fechado</option>
                                    </select><br>
                                </div>
                                <div class="col-xs-6">                                    
                                    <label>Valor Pago</label> 
                                    <input type="text" id="valor_pago" name="valor_pago" class="form-control"><br>
                                </div>
                                <div class="col-xs-6">                                    
                                    <label>Data</label> 
                                    <?php
                                        date_default_timezone_set('America/Sao_Paulo');
                                        $data = date("d/m/Y");                                     
                                    ?>
                                    <input type="text" id="dataP" name="dataP" class="form-control" placeholder="1" value="<?php echo $data; ?>"><br>
                                </div>
                                <div class="col-xs-12">                                    
                                    <label>Descrição</label><br>
                                    <textarea id="observacao" name="observacao" rows="2" class="form-control"></textarea><br>
                                </div>
                                <div align="center"> 
                                  <table class="table table-condensed">
                                    <tr>
                                      <th>Criado ordem em</th>
                                      <th><?php echo $dataOrdem; ?></th>   
                                      <th>Total da ordem</th>
                                      <th><?php echo "R$ ".number_format($totalDaOrdem, 2, ',', '.'); ?></th>                                          
                                    </tr>  
                                  </table>
                                  <table class="table table-condensed">
                                    <tr>
                                      <th>Data</th>
                                      <th>Valor Pago</th>                                      
                                      <th>Descrição</th>                                      
                                      <th>Deletar</th>                                      
                                    </tr>
                                    <?php
                                      $stmt = $conn->prepare('SELECT * FROM historico_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY data DESC');
                                      $stmt->execute(array('id_ordem_de_compra' => $_GET["id"])); 
                                      $totalPago = 0;
                                      while($row = $stmt->fetch()){  
                                          echo "<tr>";
                                          $dia = substr($row["data"], 8, 2);
                                          $mes = substr($row["data"], 5, 2);
                                          $ano = substr($row["data"], 0, 4); 
                                          echo "<td>".$dia."/".$mes."/".$ano."</td>";
                                          echo "<td>"."R$ ".number_format($row["valor_pago"], 2, ',', '.')."</td>";
                                          echo "<td>".utf8_encode($row["observacao"])."</td>";                                                                                    
                                          echo "<td>";        
                                          echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir-pagamento' data-pagamento='".$row["id_historico_ordem"]."' data-obs='".utf8_encode($row['observacao'])."' 
                                            data-dia='".$dia."/".$mes."/".$ano."' data-valor='".number_format($row["valor_pago"], 2, ',', '.')."'><img src='../dist/img/deleteItem.png' title='Excluir Pagamento'></a>";
                                          echo "</td>";    
                                          echo "</tr>";                                         
                                          $totalPago = $totalPago + $row["valor_pago"];
                                      }  
                                      $saldo = $totalDaOrdem - $totalPago;  
                                      $tordem = number_format($totalDaOrdem, 2, ',', '.');
                                      $tpago = number_format($totalPago, 2, ',', '.');
                                    ?>      
                                    <tr>
                                      <th>Total Pago</th>
                                      <th>R$ <?php echo number_format($totalPago, 2, ',', '.'); ?></th>                                      
                                    </tr>
                                    <tr>
                                      <?php if($tordem == $tpago){ ?>             
                                      <th style='color:green;'>ORDEM PAGA</th>                                      
                                      <th></th>                                      
                                      <?php }else{ ?>
                                      <th style='color:red;'>A Pagar</th>                                      
                                      <th style='color:red;'>R$ <?php echo number_format($saldo, 2, ',', '.'); ?></th>                                                                              
                                      <?php } ?>    
                                    </tr>
                                  </table>
                                </div>
                            </div>
                            <!-- /.form-group -->                            
                        </div>
                        <!-- /.col -->                                              
                        <div class="col-md-12">
                            <div class="col-xs-12" align="left">
                                <div id="resFecharOrdem"></div>
                            </div>
                        </div>                  
                        <!-- /.col --> 
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info pull-right" >Salvar</button>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
                  <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="idOrdem" id="idOrdem">
                </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>     
        
      <div class="modal fade" id="modal-excluir-item">
        <form id="formExcluirItem" method="post" action="../inc/itemExcluir.php">
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
                  <label>Você tem certeza que deseja excluir o Item<br><label id="itemE" name="itemE"></label> ?</label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idItemExcluir" id="idItemExcluir">
              <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="idOrdem" id="idOrdem">
            </div>
          </div>
          <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->          
        </form>
      </div>
        
      <div class="modal fade" id="modal-excluir-pagamento">
        <form id="formExcluirPagamento" method="post" action="../inc/pagamentoExcluir.php">
          <div class="modal-dialog">            
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Exclusão de pagamento!</h4>
            </div>
            <div class="modal-body">
              <div class="row" align="center">
                <div class="col-md-12">                  
                  <label>Você tem certeza que deseja excluir o Pagamento?<br>
                      Data: <label id="dia" name="dia"></label><br>
                      Valor: <label id="valor" name="valor"></label><br>
                      Obs: <label id="obs" name="obs"></label><br>                  
                  </label>                    
                </div>
                <!-- /.col -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-right" >SIM EU TENHO</button>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>                                  
              <input type="hidden" value="" name="idPagamentoExcluir" id="idPagamentoExcluir">
              <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="idOrdem" id="idOrdem">
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
        var table = $('#tabelaItens').DataTable({
          lengthChange: false,
          "order": [            
            [ 0, "desc"]  
          ],
          buttons: [ 
            'copy', 
            {
                extend: 'excelHtml5',
                title: 'Itens da Ordem de Compra <?=$_GET["id"]?> | <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ,4 ,5 ]
                }
            }, 
            {
                extend: 'pdfHtml5',
                title: 'Itens da Ordem de Compra <?=$_GET["id"]?> | <?=$GLOBALS["nomeEmpresa"]?>',
                exportOptions: {
                        columns: [ 0, 1, 2, 3 ,4 ,5 ]
                }                            
            },
            'colvis'
          ]
        });

        table.buttons().container()
          .appendTo('#tabelaItens_wrapper .col-sm-6:eq(0)');
      });
    </script>
      
    <!-- JQUERY AJAX -->
    <script> 
    $(document).ready(function(){       
        $('#formInserirItem').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resInserirItem').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/itemInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resInserirItem').html(data);             
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
        $('#formEditarItem').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resEditarItem').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/itemAlterar.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resEditarItem').html(data);             
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
        $('#formFecharOrdem').submit(function(){    
            
            var formData = new FormData(this);
            
            $('#resFecharOrdem').html("<b>Processando...</b>");
            
            $.ajax({
                url: '../inc/pagamentoInserir.php', 
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
            .done(function(data){   
                $('#resFecharOrdem').html(data);             
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
             var idItem = $(this).data('id');              
             $("#idItemExcluir").val( idItem ); 
             $("#idItemEditar").val( idItem ); 
             var idPagamento = $(this).data('pagamento');              
             $("#idPagamentoExcluir").val( idPagamento );
             // labels
             var nome = $(this).data('nome');
             $("#nomeItemEditar").text( nome );
             
             var tipo = $(this).data('tipo');
             $("#tipoE").val( tipo );                 
             if (tipo == "P") {
                $("#produtoE").css("display","block");
                $("#servicoE").css("display","none");
                var produto = $(this).data('produto'); 
                $("#produtoED").val( produto );
                var qtd = $(this).data('quantidade');
                $("#quantidadeE").val( qtd ); 
                var data = $(this).data('dia');
                $("#dataE").val( data ); 
             } else {                 
                $("#servicoE").css("display","block");
                $("#produtoE").css("display","none");
                var servico = $(this).data('servico'); 
                $("#servicoED").val( servico );
                var pet = $(this).data('pet'); 
                $("#petE").val( pet );
                var qtd = $(this).data('quantidade');
                $("#quantidadeE").val( qtd ); 
                var data = $(this).data('dia');
                $("#dataE").val( data ); 
             }    
             
             // excluir pagamento
             var item = $(this).data('item');
             $("#itemE").text( item );                            
             var obs = $(this).data('obs');
             $("#obs").text( obs );  
             var dia = $(this).data('dia');
             $("#dia").text( dia ); 
             var valor = $(this).data('valor');
             $("#valor").text( valor );              
        }); 
    </script>
      
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <script>        
        $(document).ready(function () {             
            var quantidade = $("#quantidade");
            quantidade.mask('999');            
            var data = $("#data");
            data.mask('99/99/9999');
            var dataP = $("#dataP");
            dataP.mask('99/99/9999');
            var valor = $("#valor_pago");
            valor.mask('0.009,99', {reverse: true});
        });  
    </script>      
    
    <?php 
        if (isset($_SESSION["mostra-pagamentos"])){
            if($_SESSION["mostra-pagamentos"] == "S"){                
    ?>
                <script>
                    $(function() {
                     $('#modal-fechar').modal('show');
                    });
                </script>       
      
    <?php            
                unset($_SESSION["mostra-pagamentos"]);        
            }                
        }
    ?>  
      
   
      
  </body>
  </html>