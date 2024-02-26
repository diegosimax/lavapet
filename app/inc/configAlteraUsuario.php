<?php 
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $erro = 0;
    $nome = trim(utf8_decode($_POST["nome"]));
    $email = trim(utf8_decode($_POST["email"]));
   
    if(empty($nome) || empty($email)){
        $erro = 1;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Você deve preencher todos os campos (Nome) (E-mail).
        </div>";
    }

    
    if(validaemail($email) && $erro == 0){        
        //altera usuario
        require_once('../../inc/global.php');
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $stmt = $conn->prepare('UPDATE usuarios SET nome = :nome, email = :email WHERE id_usuario = :id_usuario');
        $stmt->execute(array('nome' => $nome, 'email' => $email, 'id_usuario' => $id_usuario));
        $_SESSION["MSG_SUCESSO"] = "Usuário ".utf8_encode($nome)." alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../configuracoes/' </script>";
        //altera usuario
    }    

    function validaemail($email){
        //verifica se e-mail esta no formato correto de escrita
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){              
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