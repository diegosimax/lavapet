<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $imagemUpload = $_FILES["imagemUpload"]["name"];   
    $descricao = trim(utf8_decode($_POST["descricao"]));    
    $categoria = trim(utf8_decode($_POST["categoria"]));    
    $status = trim(utf8_decode($_POST["status"]));    
    $erro = 0;    

    if(empty($descricao) || empty($imagemUpload)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              O campo imagem e os demais campos com (*) são obrigatórios!
              </div>";
    }
    
    if($erro == 0 && !empty($imagemUpload)){        
        if(!validarFoto()){
            $erro = 1;
        }        
    }

    if($erro == 0){
        if(salvarFoto($imagemUpload, $descricao, $categoria, $status)){
            salvaHistorico(utf8_decode("Incluída foto ").$descricao, utf8_decode("Site | Fotos | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Foto (".utf8_encode($descricao).") incluída com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../fotos/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir a foto.
                 </div>";
        }       
    }

    function salvarFoto($imagemUpload, $descricao, $categoria, $status){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //insere foto           
            $stmt = $conn->prepare('INSERT INTO fotos 
                                    (descricao, categoria, status) 
                                    VALUES 
                                    (:descricao, :categoria, :status)');            
            $stmt->execute(array('descricao' => $descricao, 'categoria' => $categoria, 
                                 'status' => $status));
            
            if(!empty($imagemUpload)){
                $ultimoId = $conn->lastInsertId(); 
                $imagemUpload = $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);
                $stmt = $conn->prepare('UPDATE fotos SET imagem = :imagem WHERE id_foto = :id_foto');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_foto' => $ultimoId));

                //salva imagem 
                $target_dir = "../dist/img/fotos/";
                $target_file = $target_dir . $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);

                if(move_uploaded_file($_FILES["imagemUpload"]["tmp_name"], $target_file)) {           
                    return true;
                }else{
                     echo "<div class='alert alert-danger alert-dismissible'>
                          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                          <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                          Desculpe mas há um erro com esta imagem.
                          </div>";
                } 
            }             
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
        

    function validarFoto(){        
        $target_file = basename($_FILES["imagemUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $file_size = $_FILES['imagemUpload']['size'];                
        // Check file size
        if (($file_size > 3145728 || $file_size == 0) && $uploadOk == 1) {                        
            $uploadOk = 0;            
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                Desculpe mas a imagem de perfil é muito grande.
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
        if($uploadOk == 1){
            return true;
        }
    }  
    
?>