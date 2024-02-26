<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $imagemUpload = $_FILES["imagemUpload"]["name"];   
    $titulo = trim(utf8_decode($_POST["titulo"]));
    $descricao = trim(utf8_decode($_POST["descricao"]));    
    $ordem = trim(utf8_decode($_POST["ordem"]));    
    $status = trim(utf8_decode($_POST["status"]));    
    $erro = 0;    

    if(empty($titulo) || empty($imagemUpload) || empty($ordem) || $ordem == 0){
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
        if(salvarBanner($imagemUpload, $titulo, $descricao, $ordem, $status)){
            salvaHistorico(utf8_decode("Incluído banner ").$titulo, utf8_decode("Site | Banners | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Banner (".utf8_encode($titulo).") incluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../banners/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir o banner.
                 </div>";
        }       
    }

    function salvarBanner($imagemUpload, $titulo, $descricao, $ordem, $status){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //insere banner           
            $stmt = $conn->prepare('INSERT INTO banners 
                                    (titulo, descricao,
                                     ordem, status) 
                                    VALUES 
                                    (:titulo, :descricao,
                                     :ordem, :status)');            
            $stmt->execute(array('titulo' => $titulo, 'descricao' => $descricao, 
                                 'ordem' => $ordem, 'status' => $status));
            
            if(!empty($imagemUpload)){
                $ultimoId = $conn->lastInsertId(); 
                $imagemUpload = $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);
                $stmt = $conn->prepare('UPDATE banners SET imagem = :imagem WHERE id_banner = :id_banner');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_banner' => $ultimoId));

                //salva imagem perfil
                $target_dir = "../dist/img/banners/";
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

    function validarEmail($email){
        //verifica se e-mail esta no formato correto de escrita
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){  
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                E-mail inválido.
                </div>";
        }else{
            //Valida o dominio
            $dominio=explode('@',$email);
            if(!checkdnsrr($dominio[1],'A')){
                echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                     <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                     E-mail inválido.
                     </div>";
            }else{
                return true;
            } // Retorno true para indicar que o e-mail é valido
        }
    }
    
?>