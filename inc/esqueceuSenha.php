<?php
    
    require_once('../inc/global.php');
    
    $email = $_POST["email"];

    try {
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        
        $stmt = $conn->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(array('email' => $email));
    
        if($row = $stmt->fetch()){
            if($row["status"] == "A"){
                $nome = $row["nome"];
                $idUsuario = $row["id_usuario"];
                $senhaPadrao = $GLOBALS["padraoSenha"];
                mandaEmail($email, $senhaPadrao, $nome);                
                //altera senha do usuário para padrão da empresa                                
                $stmt = $conn->prepare('UPDATE usuarios SET senha = :senha, primeiro_acesso = :primeiro_acesso WHERE id_usuario = :id_usuario');
                $stmt->execute(array('senha' => md5($senhaPadrao), 'id_usuario' => $idUsuario, 'primeiro_acesso' => 'S'));                                                 
            }else{
                echo "<br>Este usuário se encontra inativo!";
            }            
        }else{
            echo "<br>Ouve algum erro com seu e-mail!";
        }        
       
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }

    function mandaEmail($email, $senhaPadrao, $nome){
        require_once('../inc/global.php');
        require_once('../plugins/php-mailer/PHPMailerAutoload.php');

        $mail = new PHPMailer;
        
        $mail->isSMTP();                                      // Set mailer to use SMTP
        
        $mail->Host = $GLOBALS["smtpHost"];                   // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $GLOBALS["smtpUser"];               // SMTP username
        $mail->Password = $GLOBALS["smtpPass"];               // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $GLOBALS["smtpPort"];                   // TCP port to connect to

        $mail->setFrom($GLOBALS["smtpEmailFrom"], $GLOBALS["smtpEmailFromName"]);
        $mail->addAddress($email, $nome);                     // Add a recipient
        
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = utf8_decode('Sistema Banho e Tosa - Configurações da conta');
        $mail->Body    = 'Sua nova senha &eacute;, <br><b>'.$senhaPadrao.'</b><br><br>Acesse o sistema com essa senha padr&atilde;o e altere a senha em seu primeiro acesso!<br><a href="http://jaquemedeiros.com/sistema">Sistema Banho e Tosa</a>';
        $mail->AltBody = 'Sua nova senha &eacute;, '.$senhaPadrao;

        if(!$mail->send()) {
            echo '<br>E-mail não enviado!!!';
            echo '<br>Erro: ' . $mail->ErrorInfo;
        } else {
            echo '<br>Siga as instruções em seu e-mail :-)';
        }      
    }
?>