<?php    
    $idItem = $_POST["idItemExcluir"];
    $idOrdem = $_POST["idOrdem"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        
        //pega descrição do campo 
        $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem)); 
        
        while($row = $stmt->fetch()){  
            if($row["id_produto"] <> 0){
                $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                $stmt2->execute(array('id_produto' => $row["id_produto"]));
                $qtdDevolucao = $row["quantidade"];
                if($row2 = $stmt2->fetch()){  
                    $item = utf8_encode($row2["nome"]);                    
                    $qtdEstoque = $row2["qtd_estoque"] + $qtdDevolucao;
                    $stmt3 = $conn->prepare('UPDATE produtos 
                                                SET qtd_estoque = :qtd_estoque
                                              WHERE id_produto = :id_produto');              
                    $stmt3->execute(array('id_produto' => $row["id_produto"], 'qtd_estoque' => $qtdEstoque));                  
                }
            }
            if($row["id_servico"] <> 0){
                $stmt2 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
                $stmt2->execute(array('id_servico' => $row["id_servico"]));
                if($row2 = $stmt2->fetch()){  
                    $item = utf8_encode($row2["descricao"]);
                }
            }
        }   
        

        //deleta item
        $stmt = $conn->prepare('DELETE FROM itens_da_ordem WHERE id_item_da_ordem = :id_item_da_ordem');
        $stmt->execute(array('id_item_da_ordem' => $idItem));  
        
        //calcula ordem
        $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem)); 
        $totalItem = 0;
        $totalOrdem = 0;
        while($row = $stmt->fetch()){  
            if($row["id_produto"] <> 0){
                $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                $stmt2->execute(array('id_produto' => $row["id_produto"]));
                if($row2 = $stmt2->fetch()){  
                    $totalItem = $row2["valor_venda_unidade"] * $row["quantidade"];                    
                }
            }
            if($row["id_servico"] <> 0){
                $stmt2 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
                $stmt2->execute(array('id_servico' => $row["id_servico"]));
                if($row2 = $stmt2->fetch()){  
                    $totalItem = $row2["valor"] * $row["quantidade"];                    
                }
            }
            $totalOrdem = $totalOrdem + $totalItem;
        }           
        
        //atualiza o valor da ordem
        $stmt = $conn->prepare('UPDATE ordem_de_compra 
                                   SET valor_a_pagar = :valor_a_pagar
                                 WHERE id_ordem_de_compra = :id_ordem_de_compra');            
        $stmt->execute(array('valor_a_pagar' => $totalOrdem, 'id_ordem_de_compra' => $idOrdem));   

        session_start();
        $id_usuario = $_SESSION["id_usuario"];
        salvaHistorico(utf8_decode("Excluído Item (".$item.") da Ordem (".$idOrdem.")"), utf8_decode("Ordem de Compra | Item | Excluir"), $id_usuario);           
            
        $_SESSION["MSG_SUCESSO"] = "Item ($item) excluído com sucesso :-)";
        echo "<script type='text/javascript'> window.location = '../itens-da-ordem/index.php?id=".$idOrdem."' </script>";                
    } catch(PDOException $e) {            
        echo 'ERROR: ' . $e->getMessage();            
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