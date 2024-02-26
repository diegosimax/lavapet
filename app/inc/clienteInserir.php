<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $imagemUpload = $_FILES["imagemUpload"]["name"];   
    $nome = trim(utf8_decode($_POST["nome"]));
    $email = trim(utf8_decode($_POST["email"]));    
    $telefone1 = trim(utf8_decode($_POST["telefone1"]));    
    $telefone2 = trim(utf8_decode($_POST["telefone2"]));    
    $cep = trim(utf8_decode($_POST["cep"]));    
    $endereco = trim(utf8_decode($_POST["endereco"]));    
    $numero = trim(utf8_decode($_POST["numero"]));    
    $complemento = trim(utf8_decode($_POST["complemento"]));    
    $bairro = trim(utf8_decode($_POST["bairro"]));    
    $cidade = trim(utf8_decode($_POST["cidade"]));    
    $cpf = trim(utf8_decode($_POST["cpf"]));    
    $observacao = trim(utf8_decode($_POST["observacao"])); 
    $erro = 0;    

    if(empty($nome)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              O campo nome é obrigatório.
              </div>";
    }

    if(empty($telefone1) && empty($telefone2)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Digite ao menos um telefone.
              </div>";
    }    

    if($email <> ""){
        if($erro == 0){
            if(!validarEmail($email)){
                $erro = 1;
            }
        }
    }

    if($erro == 0 && !empty($imagemUpload)){        
        if(!validarFoto()){
            $erro = 1;
        }        
    }

    if($erro == 0){
        if(salvarCliente($imagemUpload, $nome, $email, $telefone1, 
                         $telefone2, $cep, $endereco, $numero,
                         $complemento, $bairro, $cidade, $cpf, $observacao)){
            salvaHistorico(utf8_decode("Incluído cliente ").$nome, utf8_decode("Clientes | Inserir"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Cliente ".utf8_encode($nome)." incluído com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../clientes/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao inserir o cliente.
                 </div>";
        }       
    }

    function salvarCliente($imagemUpload, $nome, $email, $telefone1, 
                           $telefone2, $cep, $endereco, $numero,
                           $complemento, $bairro, $cidade, $cpf, $observacao){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            //Criado em         
            date_default_timezone_set('America/Sao_Paulo');
            $criado_em = date("Y-m-d H:i:s"); 

            //insere cliente           
            $stmt = $conn->prepare('INSERT INTO clientes 
                                    (nome, email, telefone1, 
                                     telefone2, cep, endereco, 
                                     numero, complemento, bairro, 
                                     cidade, cpf, observacao, criado_em) 
                                    VALUES 
                                    (:nome, :email, :telefone1, 
                                     :telefone2, :cep, :endereco, 
                                     :numero, :complemento, :bairro, 
                                     :cidade, :cpf, :observacao, :criado_em)');            
            $stmt->execute(array('nome' => $nome, 'email' => $email, 
                                 'telefone1' => $telefone1, 'telefone2' => $telefone2,
                                 'cep' => $cep, 'endereco' => $endereco,
                                 'numero' => $numero, 'complemento' => $complemento,
                                 'bairro' => $bairro, 'cidade' => $cidade,
                                 'cpf' => $cpf, 'observacao' => $observacao, 'criado_em' => $criado_em));
            
            if(!empty($imagemUpload)){
                $ultimoId = $conn->lastInsertId(); 
                $imagemUpload = $ultimoId . "_" . basename($_FILES["imagemUpload"]["name"]);
                $stmt = $conn->prepare('UPDATE clientes SET imagem = :imagem WHERE id_cliente = :id_cliente');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_cliente' => $ultimoId));

                //salva imagem perfil
                $target_dir = "../dist/img/clientes/";
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