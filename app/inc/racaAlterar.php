<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $nome = trim(utf8_decode($_POST["nomeE"]));    
    $especie = $_POST["especieE"];    
    $idRacaE = $_POST["idRacaE"];    
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
        if(editarRaca($idRacaE, $nome, $especie)){
            salvaHistorico(utf8_decode("Alterado raça ").$nome, utf8_decode("Pets | Raças | Editar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Raça ".utf8_encode($nome)." alterada com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../racas/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar a raça.
                 </div>";
        }       
    }

    function editarRaca($idRacaE, $nome, $especie){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
            //altera raça    
            $stmt = $conn->prepare('UPDATE racas 
                                       SET nome = :nome, especie = :especie
                                     WHERE id_raca = :id_raca');            
            $stmt->execute(array('nome' => $nome, 'especie' => $especie,
                                 'id_raca' => $idRacaE));   
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