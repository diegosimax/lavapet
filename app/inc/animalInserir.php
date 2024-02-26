<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $imagemUpload = $_FILES["imagemUpload"]["name"];   
    $nome = trim(utf8_decode($_POST["nome"]));    
    $porte = $_POST["porte"];    
    $raca = $_POST["raca"];    
    $sexo = $_POST["sexo"];    
    $cliente = $_POST["cliente"];    
    $observacao = trim(utf8_decode($_POST["observacao"])); 
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
        if(salvarAnimal($imagemUpload, $nome, $porte, $raca, 
                         $sexo, $cliente, $observacao)){
            salvaHistorico(utf8_decode("Incluído animal ").$nome, utf8_decode("Pets | Animais | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Animal ".utf8_encode($nome)." incluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../animais/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir o animal.
                 </div>";
        }       
    }

    function salvarAnimal($imagemUpload, $nome, $porte, $raca, 
                         $sexo, $cliente, $observacao){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s"); 

            //insere animal           
            $stmt = $conn->prepare('INSERT INTO animais 
                                    (nome, porte, id_raca, 
                                     sexo, id_cliente, observacao, criado_em) 
                                    VALUES 
                                    (:nome, :porte, :id_raca, 
                                     :sexo, :id_cliente, :observacao, :criado_em)');            
            $stmt->execute(array('nome' => $nome, 'porte' => $porte, 
                                 'id_raca' => $raca, 'sexo' => $sexo,
                                 'id_cliente' => $cliente, 'observacao' => $observacao, 
                                 'criado_em' => $criado_em));
            
            if(!empty($imagemUpload)){
                $ultimoId = $conn->lastInsertId(); 
                $imagemUpload = $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);
                $stmt = $conn->prepare('UPDATE animais SET imagem = :imagem WHERE id_animal = :id_animal');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_animal' => $ultimoId));

                //salva imagem perfil
                $target_dir = "../dist/img/animais/";
                $target_file = $target_dir . $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);

                if (move_uploaded_file($_FILES["imagemUpload"]["tmp_name"], $target_file)) {           
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