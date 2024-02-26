<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $idAnimalE = $_POST["idAnimalE"];    
    $imagemAnimal = $_POST["imagemAnimal"];    
    $imagemUpload = $_FILES["imagemUploadE"]["name"];   
    $nome = trim(utf8_decode($_POST["nomeE"]));   
    $porte = $_POST["porteE"];    
    $raca = $_POST["racaE"];    
    $sexo = $_POST["sexoE"];    
    $cliente = $_POST["clienteE"];
    $observacao = trim(utf8_decode($_POST["observacaoE"])); 
    $erro = 0;

    if(empty($nome) || empty($porte) || empty($raca) || empty($sexo) || empty($cliente)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Todos os campos com (*) são obrigatórios.
              </div>";
    }

    if($erro == 0 && !empty($imagemUpload)){        
        if(!validarFoto()){
            $erro = 1;
        }        
    }   

    if($erro == 0){
        if(editarAnimal($idAnimalE, $imagemUpload, $nome, 
                        $porte, $raca, $sexo, $cliente, 
                        $observacao, $imagemAnimal)){
            salvaHistorico(utf8_decode("Alterado animal ").$nome, utf8_decode("Pets | Animais | Editar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Animal ".utf8_encode($nome)." alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../animais/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar o animal.
                 </div>";
        }       
    }

    function editarAnimal($idAnimalE, $imagemUpload, $nome, 
                          $porte, $raca, $sexo, $cliente, 
                          $observacao, $imagemAnimal){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
            //altera animal    
            $stmt = $conn->prepare('UPDATE animais 
                                       SET nome = :nome, porte = :porte, 
                                           id_raca = :id_raca, sexo = :sexo,
                                           id_cliente = :id_cliente, observacao = :observacao
                                     WHERE id_animal = :id_animal');            
            $stmt->execute(array('nome' => $nome, 'porte' => $porte, 
                                 'id_raca' => $raca, 'sexo' => $sexo,                  
                                 'id_cliente' => $cliente, 'observacao' => $observacao,
                                 'id_animal' => $idAnimalE));                  
                        
            if(!empty($imagemUpload)){                
                $imagemUpload = $idAnimalE . "_" . basename($_FILES["imagemUploadE"]["name"]);
                $stmt = $conn->prepare('UPDATE animais SET imagem = :imagem WHERE id_animal = :id_animal');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_animal' => $idAnimalE));

                $target_dir = "../dist/img/animais/";
                $target_file = $target_dir . $idAnimalE . "_" . basename($_FILES["imagemUploadE"]["name"]);

                // deleta foto atual
                if(file_exists($target_dir.$imagemAnimal)){
                    unlink($target_dir.$imagemAnimal);    
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