<?php 
/*
 * Validar acesso ao sistema 
 */

    require_once('../inc/global.php');

    $email = $_POST["email"];
    $senha = utf8_encode(md5($_POST["senha"]));    

    try {
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);       
        $stmt = $conn->prepare('SELECT * FROM usuarios WHERE email = :email AND senha = :senha');
        $stmt->execute(array('email' => $email , 'senha' => $senha));        
        if($row = $stmt->fetch()){            
            if($row["status"] == "A"){    
                session_start();
                $_SESSION["logado"] = "S";
                $_SESSION["id_usuario"] = $row["id_usuario"];
                
                date_default_timezone_set('America/Sao_Paulo');            
                $dataHoraUltimoAcesso = date('Y-m-d H:i:s');
                try {
                    $stmt2 = $conn->prepare('UPDATE usuarios 
                                               SET ultimo_acesso = :ultimo_acesso 
                                             WHERE id_usuario = :id_usuario');
                    $stmt2->execute(array('id_usuario' => $_SESSION["id_usuario"] , 'ultimo_acesso' => $dataHoraUltimoAcesso));
                } catch(PDOException $e) {
                    echo 'ERROR: ' . $e->getMessage();
                }                   
                
                echo "<script type='text/javascript'> window.location = 'app/' </script>";                              
            }else{                
                echo "<br>Este usuário se encontra inativo!";    
            }
        }else{            
            echo "<br>Usuário ou senha incorreta!";
        }
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }      
?>