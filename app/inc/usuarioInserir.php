<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $imagemUpload = $_FILES["imagemUpload"]["name"];   
    $nome = trim(utf8_decode($_POST["nome"]));
    $email = trim(utf8_decode($_POST["email"]));    
    $status = trim(utf8_decode($_POST["status"]));
    $senha = trim(utf8_decode($_POST["senha"]));
    $senhaR = trim(utf8_decode($_POST["senhaR"]));
    $erro = 0;

    if(empty($nome) || empty($email) || empty($senha) || empty($senhaR) || empty($status)){
          $erro = 1;    
          echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Favor preencher todos os campos.
                 </div>";
    }

    if($erro == 0){
        if(!validarEmail($email)){
            $erro = 1;
        }
    }

    if($erro == 0 && !empty($imagemUpload)){        
        if(!validarFoto($id_usuario)){
            $erro = 1;
        }        
    }

    if($erro == 0){
        if(!validarSenha($senha, $senhaR)){
            $erro = 1;
        }
    }

    if($erro == 0){
        if(salvarUsuario($nome, $email, md5($senha), $status, $imagemUpload)){
            salvaHistorico(utf8_decode("Incluído usuário ").$nome, utf8_decode("Usuários | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Usuário ".utf8_encode($nome)." incluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../usuarios/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir o usuário.
                 </div>";
        }       
    }

    function salvarUsuario($nome, $email, $senha, $status, $imagemUpload){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s"); 

            //insere usuário           
            $stmt = $conn->prepare('INSERT INTO usuarios 
                    (nome, email, senha, status, primeiro_acesso, criado_em) 
                    VALUES (:nome, :email, :senha, :status, :primeiro_acesso, :criado_em)');            
            $stmt->execute(array('nome' => $nome, 'email' => $email, 
                                 'senha' => $senha, 'status' => $status,
                                 'primeiro_acesso' => 'S', 'criado_em' => $criado_em));
            
            if(!empty($imagemUpload)){
                $ultimoId = $conn->lastInsertId(); 
                $imagemUpload = $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);
                $stmt = $conn->prepare('UPDATE usuarios SET imagem = :imagem WHERE id_usuario = :id_usuario');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_usuario' => $ultimoId));

                //salva imagem perfil
                $target_dir = "../dist/img/usuarios/";
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
        
    function validarSenha($senhaNova, $senhaNovaR){
        $senhaOk = 1;
        if($senhaNova == $senhaNovaR){
            if($senhaNova == "" || $senhaNovaR == ""){
                $senhaOk = 0;
                echo "<div class='alert alert-danger alert-dismissible'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                    Preencha todos os campos de senha.
                    </div>";
            }
        }else{
            $senhaOk = 0;
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                As senhas são diferentes. Digite a mesma senha nos campos acima.
            </div>";
        } 
        if($senhaOk == 1){
            return true;
        }
    }

    function validarFoto($id_usuario){
        $target_dir = "../dist/img/usuarios/";
        $target_file = $target_dir . $id_usuario . "_" . basename($_FILES["imagemUpload"]["name"]);
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