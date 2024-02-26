<?php
    
    session_start();
    $id_usuario = $_SESSION["id_usuario"]; 

    $senhaNova = trim(utf8_decode($_POST["senhaNova"]));
    $senhaNovaR = trim(utf8_decode($_POST["senhaNovaR"]));

    if($senhaNova == $senhaNovaR){
        if( empty($senhaNova) || empty($senhaNovaR)){
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                Preencha todos os campos de senha.
                </div>";
        }else{
            require_once('../../inc/global.php');
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $stmt = $conn->prepare('UPDATE usuarios SET senha = :senha, primeiro_acesso = :primeiro_acesso WHERE id_usuario = :id_usuario');
            $stmt->execute(array('senha' => md5($senhaNova), 'id_usuario' => $id_usuario, 'primeiro_acesso' => 'N'));  

            echo "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Sucesso!</h4>Senha alterada com sucesso :-)</div>"; 
        }       
    }else{
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            As senhas são diferentes. Digite a mesma senha nos campos acima.
        </div>";
    }   
    
?>