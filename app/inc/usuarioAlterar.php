<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $idUsuarioE = $_POST["idUsuarioE"];    
    $nome = trim(utf8_decode($_POST["nomeE"]));
    $email = trim(utf8_decode($_POST["emailE"]));
    $status = trim(utf8_decode($_POST["statusE"]));
    $erro = 0;

    if(empty($nome) || empty($email) || empty($status)){
          $erro = 1;    
          echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Favor preencher todos os campos.
                 </div>";
    }

    if($erro == 0){
        if(!validarEmail($email)){
            $erro = 1;
        }
    }    

    if($erro == 0){
        if(editarUsuario($idUsuarioE, $nome, $email, $status)){
            salvaHistorico(utf8_decode("Alterado usuário ").$nome, utf8_decode("Usuários | Editar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Usuário ".utf8_encode($nome)." alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../usuarios/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar o usuário.
                 </div>";
        }       
    }

    function editarUsuario($idUsuarioE, $nome, $email, $status){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            //altera usuário    
            $stmt = $conn->prepare('UPDATE usuarios SET nome = :nome, email = :email, status = :status WHERE id_usuario = :id_usuario');            
            $stmt->execute(array('nome' => $nome, 'email' => $email, 'status' => $status, 'id_usuario' => $idUsuarioE));                  
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

    function validarEmail($email){
        //verifica se e-mail esta no formato correto de escrita
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {       
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                E-mail inválido.
                </div>";
        }
        else{
            //Valida o dominio
            $dominio=explode('@',$email);
            if(!checkdnsrr($dominio[1],'A')){
                echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                     <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                     E-mail inválido.
                     </div>";
            }
            else{return true;} // Retorno true para indicar que o e-mail é valido
        }
    }           
    
?>