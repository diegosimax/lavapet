<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    
    $idOrdem = trim(utf8_decode($_POST["idOrdem"]));
    $idItem = trim(utf8_decode($_POST["idItemEditar"]));
    $tipo = trim(utf8_decode($_POST["tipoE"]));
    $idProduto = trim(utf8_decode($_POST["produtoED"])); 
    $idServico = trim(utf8_decode($_POST["servicoED"])); 
    $idPet = trim(utf8_decode($_POST["petE"]));    
    $quantidade = trim(utf8_decode($_POST["quantidadeE"]));  
    $dia = substr($_POST["dataE"],0,2);
    $mes = substr($_POST["dataE"],3,2);
    $ano = substr($_POST["dataE"],6,4);
    $data = $ano."-".$mes."-".$dia;
    
    $GLOBALS["item"] = "";    
    $erro = 0;    

    if(empty($quantidade) || empty($data)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Os campos com (*) são obrigatórios!
              </div>";
    }   

    if($erro == 0){
        if(alterarItem($idOrdem, $tipo, $idProduto, $idServico, $idPet, $quantidade, $data, $idItem)){
            salvaHistorico(utf8_decode("Alterado Item (".$GLOBALS["item"].") na Ordem (".$idOrdem.")"), utf8_decode("Ordem de Compra | Item | Alterar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Item (".$GLOBALS["item"].") alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../itens-da-ordem/index.php?id=".$idOrdem."' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar o item.
                 </div>";
        }       
    }

    function alterarItem($idOrdem, $tipo, $idProduto, $idServico, $idPet, $quantidade, $data, $idItem){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            if($tipo == "P"){ $idServico = null; $idPet = null; }
            if($tipo == "S"){ $idProduto = null; }
            if($idPet == 0){ $idPet = null; }
            
            if($tipo == "P"){
                $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_item_da_ordem = :id_item_da_ordem');
                $stmt->execute(array('id_item_da_ordem' => $idItem)); 
                if($row = $stmt->fetch()){  
                    $qtdDevolucao = $row["quantidade"];
                }
                $stmt = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                $stmt->execute(array('id_produto' => $idProduto)); 
                if($row = $stmt->fetch()){  
                    $qtdEstoque = $row["qtd_estoque"] + $qtdDevolucao;
                    $qtdEstoque = $qtdEstoque - $quantidade;
                    $stmt = $conn->prepare('UPDATE produtos 
                                               SET qtd_estoque = :qtd_estoque
                                             WHERE id_produto = :id_produto');              
                    $stmt->execute(array('id_produto' => $idProduto, 'qtd_estoque' => $qtdEstoque));  
                }                
            }
            
            //altera item   
            $stmt = $conn->prepare('UPDATE itens_da_ordem 
                                       SET id_produto = :id_produto, 
                                           id_servico = :id_servico,
                                           id_ordem_de_compra = :id_ordem_de_compra,
                                           quantidade = :quantidade, 
                                           id_animal = :id_animal, 
                                           data_venda = :data_venda
                                     WHERE id_item_da_ordem = :id_item_da_ordem');  
            
            $stmt->execute(array('id_produto' => $idProduto, 'id_servico' => $idServico, 
                                 'id_ordem_de_compra' => $idOrdem, 'quantidade' => $quantidade,
                                 'id_animal' => $idPet, 'data_venda' => $data, 'id_item_da_ordem' => $idItem));           
            
            //calcula total da ordem
            $totalItem = 0;
                        
            if($tipo == "P"){
                $stmt = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                $stmt->execute(array('id_produto' => $idProduto)); 
                if($row = $stmt->fetch()){  
                    $totalItem = $row["valor_venda_unidade"] * $quantidade;
                    $GLOBALS["item"] = utf8_encode($row["nome"]);
                }   
            }else{
                $stmt = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
                $stmt->execute(array('id_servico' => $idServico)); 
                if($row = $stmt->fetch()){  
                    $totalItem = $row["valor"] * $quantidade;
                    $GLOBALS["item"] = utf8_encode($row["descricao"]);
                }   
            }
            return true;                
        } catch(PDOException $e) {            
            echo 'ERROR: ' . $e->getMessage();
        }      
    }

    function salvaHistorico($acao, $tela, $id_usuario){
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $data_hora = date("Y-m-d H:i:s"); 

            //insere usuário           
            $stmt = $conn->prepare('INSERT INTO historico 
                    (acao, tela, data_hora, id_usuario) 
                    VALUES (:acao, :tela, :data_hora, :id_usuario)');            
            $stmt->execute(array('acao' => $acao, 'tela' => $tela, 
                                 'data_hora' => $data_hora, 'id_usuario' => $id_usuario)); 
            return true;              
        } catch(PDOException $e) {            
            echo 'ERROR: ' . $e->getMessage();
        }   
    }  
    
?>