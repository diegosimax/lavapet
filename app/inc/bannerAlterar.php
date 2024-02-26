<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $idBannerE = $_POST["idBannerE"];    
    $imagemBanner = $_POST["imagemBanner"];    
    $imagemUpload = $_FILES["imagemUploadE"]["name"];   
    $titulo = trim(utf8_decode($_POST["tituloE"]));
    $descricao = trim(utf8_decode($_POST["descricaoE"]));    
    $ordem = trim(utf8_decode($_POST["ordemE"]));    
    $status = trim(utf8_decode($_POST["statusE"]));    
    $erro = 0;

    if(empty($titulo) || empty($ordem) || $ordem == 0){
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
        if(editarBanner($idBannerE, $imagemUpload, $titulo, $descricao, $ordem, $status, $imagemBanner)){
            salvaHistorico(utf8_decode("Alterado banner ").$titulo, utf8_decode("Site | Banners | Editar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Banner (".utf8_encode($titulo).") alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../banners/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar o banner.
                 </div>";
        }       
    }

    function editarBanner($idBannerE, $imagemUpload, $titulo, $descricao, $ordem, $status, $imagemBanner){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
            //altera banner    
            $stmt = $conn->prepare('UPDATE banners 
                                       SET titulo = :titulo, descricao = :descricao, 
                                           ordem = :ordem, status = :status
                                     WHERE id_banner = :id_banner');            
            $stmt->execute(array('titulo' => $titulo, 'descricao' => $descricao, 
                                 'ordem' => $ordem, 'status' => $status,
                                 'id_banner' => $idBannerE));                  
                        
            if(!empty($imagemUpload)){                
                $imagemUpload = $idBannerE . "_" . basename($_FILES["imagemUploadE"]["name"]);
                $stmt = $conn->prepare('UPDATE banners SET imagem = :imagem WHERE id_banner = :id_banner');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_banner' => $idBannerE));

                $target_dir = "../dist/img/banners/";
                $target_file = $target_dir . $idBannerE . "_" . basename($_FILES["imagemUploadE"]["name"]);

                // deleta foto atual
                    if(file_exists($target_dir.$imagemBanner)){
                        unlink($target_dir.$imagemBanner);    
                    }                    
                
                if (move_uploaded_file($_FILES["imagemUploadE"]["tmp_name"], $target_file)) {           
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

    function validarFoto(){        
        $target_file = basename($_FILES["imagemUploadE"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $file_size = $_FILES['imagemUploadE']['size'];                
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