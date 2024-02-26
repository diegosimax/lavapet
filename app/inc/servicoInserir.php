<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $descricao = trim(utf8_decode($_POST["descricao"]));
    // converte para decimal
    $valor1 = trim(utf8_decode($_POST["valor"])); 
    $valor2= str_replace(".","",$valor1);//Retirou todos os pontos
    $valor = str_replace(",",".",$valor2);//Substitui vírgulas por pontos
    $erro = 0;    

    if(empty($descricao) || empty($valor)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Preencha todos os campos acima!
              </div>";
    }

    if($erro == 0){
        if(salvarServico($descricao, $valor)){
            salvaHistorico(utf8_decode("Incluído serviço ").$descricao, utf8_decode("Serviços | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Serviço (".utf8_encode($descricao).") incluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../servicos/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir o serviço.
                 </div>";
        }       
    }

    function salvarServico($descricao, $valor){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s"); 

            //insere serviço           
            $stmt = $conn->prepare('INSERT INTO servicos 
                                    (descricao, valor, criado_em) 
                                    VALUES 
                                    (:descricao, :valor, :criado_em)');            
            $stmt->execute(array('descricao' => $descricao, 'valor' => $valor, 
                                 'criado_em' => $criado_em));      
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