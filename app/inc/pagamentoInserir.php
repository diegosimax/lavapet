<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $status = trim(utf8_decode($_POST["status"]));
    $idOrdem = $_POST["idOrdem"];
    
    $valor1 = trim(utf8_decode($_POST["valor_pago"])); 
    $valor2= str_replace(".","",$valor1);//Retirou todos os pontos
    $valor_pago = str_replace(",",".",$valor2);//Substitui vírgulas por pontos
    
    $dia = substr($_POST["dataP"],0,2);
    $mes = substr($_POST["dataP"],3,2);
    $ano = substr($_POST["dataP"],6,4);
    $data = $ano."-".$mes."-".$dia;
    
    $observacao = trim(utf8_decode($_POST["observacao"]));
    
    if(empty($valor_pago)){
        require_once('../../inc/global.php');
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $stmt = $conn->prepare('SELECT status FROM ordem_de_compra WHERE id_ordem_de_compra = :id_ordem_de_compra');            
        $stmt->execute(array('id_ordem_de_compra' => $idOrdem));  
        if($row = $stmt->fetch()){  
            if($status <> $row["status"]){
                if(atualizaOrdem($status, $idOrdem)){                    
                    $_SESSION["mostra-pagamentos"] = "S";            
                    if($status == "A"){
                        salvaHistorico(utf8_decode("Alterado status da Ordem ($idOrdem) para (ABERTO)"), utf8_decode("Ordem de Compra | STATUS"), $id_usuario);
                        $_SESSION["MSG_SUCESSO_PAG"] = "Alterado status para ABERTO :-)";    
                    }else{
                        salvaHistorico(utf8_decode("Alterado status da Ordem ($idOrdem) para (FECHADO)"), utf8_decode("Ordem de Compra | STATUS"), $id_usuario);
                        $_SESSION["MSG_SUCESSO_PAG"] = "Alterado status para FECHADO :-)";    
                    }  
                    echo "<script type='text/javascript'> window.location = '../itens-da-ordem/index.php?id=".$idOrdem."' </script>";
                }else{
                    echo "<div class='alert alert-danger alert-dismissible'>
                         <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                         <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                         Desculpe mas houve um erro ao atualizar o status.
                         </div>";
                }    
            }
        }   
    }else{
        if(empty($data) || empty($observacao)){
            echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Ao informar um valor você deve informar os campos (Data) e (Descrição).
                 </div>";
        }else{
            if(salvaPagamento($status, $idOrdem, $valor_pago, $data, $observacao)){
                $valor = number_format($valor_pago, 2, ',', '.');
                $_SESSION["mostra-pagamentos"] = "S";            
                $_SESSION["MSG_SUCESSO_PAG"] = "Pagamento no valor de (R$ $valor - ".utf8_encode($observacao).") incluído com sucesso :-)";
                salvaHistorico(utf8_decode("Incluído pagamento no valor de (R$ $valor - ").$observacao.") da Ordem ($idOrdem)", utf8_decode("Ordem de Compra | Item | Pagamentos | Incluir"), $id_usuario);
                echo "<script type='text/javascript'> window.location = '../itens-da-ordem/index.php?id=".$idOrdem."' </script>";
            }else{
                echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                     <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                     Desculpe mas houve um erro ao inserir o pagamento.
                     </div>";
            }    
        }
    }
    
    function salvaPagamento($status, $idOrdem, $valor_pago, $data, $observacao){
        require_once('../../inc/global.php');
        
        try{
            
            atualizaOrdem($status, $idOrdem);
            
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //insere pagamento no histórico    
            $stmt = $conn->prepare('INSERT INTO historico_ordem 
                                    (data, valor_pago, id_ordem_de_compra, observacao) 
                                    VALUES 
                                    (:data, :valor_pago, :id_ordem_de_compra, :observacao)');            
            $stmt->execute(array('data' => $data, 'valor_pago' => $valor_pago, 
                                 'id_ordem_de_compra' => $idOrdem, 'observacao' => $observacao));       
            return true;                
        } catch(PDOException $e) {            
            echo 'ERROR: ' . $e->getMessage();
        } 
    }

    function atualizaOrdem($status, $idOrdem){        
        require_once('../../inc/global.php');
        
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //altera produto    
            $stmt = $conn->prepare('UPDATE ordem_de_compra 
                                       SET status = :status
                                     WHERE id_ordem_de_compra = :id_ordem_de_compra');            
            $stmt->execute(array('status' => $status, 'id_ordem_de_compra' => $idOrdem));       
            return true;                
        } catch(PDOException $e) {            
            echo 'ERROR: ' . $e->getMessage();
        }      
    }    

    function salvaHistorico($acao, $tela, $id_usuario){
        require_once('../../inc/global.php');
        
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