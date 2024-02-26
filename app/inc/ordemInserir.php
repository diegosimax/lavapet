<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $cliente = trim(utf8_decode($_POST["cliente"]));
    $status = trim(utf8_decode($_POST["status"]));
    $erro = 0;    
    $GLOBALS["lastId"] = 0;

    if(empty($cliente)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              O campo Cliente é obrigatório!
              </div>";
    }

    if($erro == 0){
        if(salvarOrdem($cliente, $status)){
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $stmt = $conn->prepare('SELECT nome FROM clientes WHERE id_cliente = :id_cliente');            
            $stmt->execute(array('id_cliente' => $cliente)); 
            if($row = $stmt->fetch()){
                $nomeCliente = utf8_encode($row["nome"]);
            }else{
                $nomeCliente = utf8_encode("Cliente não existente!");
            }
            
            salvaHistorico(utf8_decode("Incluído Ordem de Compra (".$GLOBALS["lastId"]." - ".$nomeCliente.")"), utf8_decode("Ordem de Compra | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Ordem de Compra (".$GLOBALS["lastId"]." - ".$nomeCliente.") incluída com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../ordem-de-compra/' </script>";
        }else{
            echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir a ordem de compra.
                 </div>";
        }       
    }

    function salvarOrdem($cliente, $status){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s");
            $valorPago = 0;
            $valorAPagar = 0;

            //insere serviço           
            $stmt = $conn->prepare('INSERT INTO ordem_de_compra 
                                    (id_cliente, status, criado_em, valor_pago, valor_a_pagar) 
                                    VALUES 
                                    (:id_cliente, :status, :criado_em, :valor_pago, :valor_a_pagar)');            
            $stmt->execute(array('id_cliente' => $cliente, 'status' => $status, 
                                 'criado_em' => $criado_em, 'valor_pago' => $valorPago,
                                 'valor_a_pagar' => $valorAPagar));     
            $GLOBALS["lastId"] = $conn->lastInsertId();
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