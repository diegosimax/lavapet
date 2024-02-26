<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $nome = trim(utf8_decode($_POST["nome"]));    
    $especie = $_POST["especie"];    
    $erro = 0;    

    if(empty($nome)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Todos os campos com (*) são obrigatórios.
              </div>";
    }    

    if($erro == 0){
        if(salvarRaca($nome, $especie)){
            salvaHistorico(utf8_decode("Incluído raça ").$nome, utf8_decode("Pets | Raças | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Raça ".utf8_encode($nome)." incluída com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../racas/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir a raça.
                 </div>";
        }       
    }

    function salvarRaca($nome, $especie){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s"); 

            //insere raça           
            $stmt = $conn->prepare('INSERT INTO racas 
                                    (nome, especie, criado_em) 
                                    VALUES 
                                    (:nome, :especie, :criado_em)');            
            $stmt->execute(array('nome' => $nome, 'especie' => $especie, 
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