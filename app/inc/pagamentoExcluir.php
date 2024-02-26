<?php    
    $idPagamentoExcluir = $_POST["idPagamentoExcluir"];
    $idOrdem = $_POST["idOrdem"];
    require_once('../../inc/global.php');
    try{
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        $stmt = $conn->prepare('SELECT * FROM historico_ordem WHERE id_historico_ordem = :id_historico_ordem');
        $stmt->execute(array('id_historico_ordem' => $idPagamentoExcluir));
        
        if($row = $stmt->fetch()){                          
            $observacao = utf8_encode($row["observacao"]);
            $valor = number_format($row["valor_pago"], 2, ',', '.');
            
            //deleta registro
            $stmt = $conn->prepare('DELETE FROM historico_ordem WHERE id_historico_ordem = :id_historico_ordem');
            $stmt->execute(array('id_historico_ordem' => $idPagamentoExcluir));        

            session_start();
            $id_usuario = $_SESSION["id_usuario"];
            salvaHistorico(utf8_decode("Excluído pagamento no valor de (R$ $valor - ").utf8_decode($observacao).") da Ordem ($idOrdem)", utf8_decode("Ordem de Compra | Item | Pagamentos | Excluir"), $id_usuario);
            $_SESSION["mostra-pagamentos"] = "S";            
            $_SESSION["MSG_SUCESSO_PAG"] = "Pagamento no valor de (R$ $valor - ".$observacao.") excluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../itens-da-ordem/index.php?id=".$idOrdem."' </script>";        
        }
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