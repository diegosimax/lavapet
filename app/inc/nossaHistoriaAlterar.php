<?php 
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $titulo = trim(utf8_decode($_POST["titulo"]));
    $descricao = trim(utf8_decode($_POST["descricao"]));
    $erro = 0;
   
    if(empty($titulo) || empty($descricao)){
        $erro = 1;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Você deve preencher os campos (Título) e (Descrição).
        </div>";
    }
    
    if($erro == 0){        
        //altera nossa história
        require_once('../../inc/global.php');
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $stmt = $conn->prepare('UPDATE textos SET titulo = :titulo, descricao = :descricao WHERE id_texto = :id_texto');
        $stmt->execute(array('titulo' => $titulo, 'descricao' => $descricao, 'id_texto' => 1));
        salvaHistorico(utf8_decode("Alterado nossa história "), utf8_decode("Site | Nossa História | Editar"), $id_usuario);
        $_SESSION["MSG_SUCESSO"] = "Nossa História alterado com sucesso :-)";
        echo "<script type='text/javascript'> window.location = '../nossa-historia/' </script>";
        //altera nossa história
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