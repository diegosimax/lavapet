<?php   
    session_start();
    $id_usuario = $_SESSION["id_usuario"];   

    $target_dir = "../dist/img/usuarios/";
    $target_file = $target_dir . $id_usuario . "_" . basename($_FILES["imagemUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    $file_size = $_FILES['imagemUpload']['size'];
    
    // Check if file already exists
    if (file_exists($target_file) && $uploadOk == 1) {        
        $uploadOk = 0;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Desculpe mas essa é a foto atual.
        </div>";
    }

                    
    // Check file size
    if($file_size > 3145728 && $uploadOk == 1) {   
        $uploadOk = 0;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Desculpe mas seu arquivo é muito grande.
        </div>";
    }
        
    if($file_size == 0 && $uploadOk == 1) { 
        $uploadOk = 0;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Escolha uma imagem e clique em salvar.
        </div>";
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $uploadOk == 1) {
       $uploadOk = 0;
        echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
            Somente arquivos .jpg .png .jpeg e .gif são aceitos.
        </div>";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["imagemUpload"]["tmp_name"], $target_file)) {
            
            require_once('../../inc/global.php');
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
            // pega imagem atual
            $stmt = $conn->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario');
            $stmt->execute(array('id_usuario' => $id_usuario));
            if($row = $stmt->fetch()){                          
                $imagemAtual = $row["imagem"];
            }
            
            // deleta foto atual
            if(file_exists($target_dir.$imagemAtual)){
                unlink($target_dir.$imagemAtual);    
            }                        
            
            // altera foto
            $imagemNova = basename($_FILES["imagemUpload"]["name"]);
            $stmt = $conn->prepare('UPDATE usuarios SET imagem = :imagem WHERE id_usuario = :id_usuario');
            $stmt->execute(array('imagem' => $id_usuario."_".$imagemNova, 'id_usuario' => $id_usuario));  
            // fim altera foto             
            $_SESSION["MSG_SUCESSO"] = "Imagem de perfil alterada com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../configuracoes/' </script>";
        } else {
            echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas há um erro com esta imagem.
                 </div>";
        }
    }
    
?>