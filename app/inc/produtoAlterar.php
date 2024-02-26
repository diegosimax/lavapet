<?php
    session_start();
    $id_usuario = $_SESSION["id_usuario"];
    $idProdutoE = $_POST["idProdutoE"];    
    $imagemProduto = $_POST["imagemProduto"];    
    $imagemUpload = $_FILES["imagemUploadE"]["name"];   
    $nome = trim(utf8_decode($_POST["nomeE"]));
    $descricao = trim(utf8_decode($_POST["descricaoE"]));
    $qtdEstoque = trim(utf8_decode($_POST["qtd_estoqueE"])); 
    $fornecedor = trim(utf8_decode($_POST["fornecedorE"])); 
    // converte para decimal
    $valor1 = trim(utf8_decode($_POST["valor_und_pagoE"])); 
    $valor2= str_replace(".","",$valor1);//Retirou todos os pontos
    $valorPago = str_replace(",",".",$valor2);//Substitui vírgulas por pontos
    // converte para decimal
    $valor1 = trim(utf8_decode($_POST["valor_und_vendaE"])); 
    $valor2= str_replace(".","",$valor1);//Retirou todos os pontos
    $valorVenda = str_replace(",",".",$valor2);//Substitui vírgulas por pontos
    $erro = 0;

    if(empty($nome) || empty($descricao) || 
       empty($qtdEstoque) || empty($fornecedor) || 
       empty($valorPago) || empty($valorVenda)){
        $erro = 1;    
        echo "<div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
              Os campos com (*) são obrigatórios!
              </div>";
    }   

    if($erro == 0 && !empty($imagemUpload)){        
        if(!validarFoto()){
            $erro = 1;
        }        
    }

    if($erro == 0){
        if(editarProduto($idProdutoE, $imagemUpload, $nome,  
                         $descricao, $qtdEstoque, $fornecedor,
                         $valorPago, $valorVenda, $imagemProduto)){
            salvaHistorico(utf8_decode("Alterado produto ").$nome, utf8_decode("Produtos | Editar"), $id_usuario);
            $_SESSION["MSG_SUCESSO"] = "Produto ".utf8_encode($nome)." alterado com sucesso :-)";
            echo "<script type='text/javascript'> window.location = '../produtos/' </script>";
        }else{
             echo "<div class='alert alert-danger alert-dismissible'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                 <h4><i class='icon fa fa-ban'></i> Alerta!</h4>
                 Desculpe mas houve um erro ao alterar o produto.
                 </div>";
        }       
    }

    function editarProduto($idProdutoE, $imagemUpload, $nome,  
                         $descricao, $qtdEstoque, $fornecedor,
                         $valorPago, $valorVenda, $imagemProduto){        
        require_once('../../inc/global.php');
        $erro = 0;
        try{
            $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            
            //altera produto    
            $stmt = $conn->prepare('UPDATE produtos 
                                       SET nome = :nome, descricao = :descricao, 
                                           qtd_estoque = :qtd_estoque, id_fornecedor = :id_fornecedor,
                                           valor_pago_unidade = :valor_pago_unidade, valor_venda_unidade = :valor_venda_unidade                                           
                                     WHERE id_produto = :id_produto');            
            $stmt->execute(array('nome' => $nome, 'descricao' => $descricao, 
                                 'qtd_estoque' => $qtdEstoque, 'id_fornecedor' => $fornecedor,                  
                                 'valor_pago_unidade' => $valorPago, 'valor_venda_unidade' => $valorVenda,                                                   
                                 'id_produto' => $idProdutoE));                  
                        
            if(!empty($imagemUpload)){                
                $imagemUpload = $idProdutoE . "_" . basename($_FILES["imagemUploadE"]["name"]);
                $stmt = $conn->prepare('UPDATE produtos SET imagem = :imagem WHERE id_produto = :id_produto');
                $stmt->execute(array('imagem' => $imagemUpload, 'id_produto' => $idProdutoE));

                $target_dir = "../dist/img/produtos/";
                $target_file = $target_dir . $idProdutoE . "_" . basename($_FILES["imagemUploadE"]["name"]);

                // deleta foto atual
                if(file_exists($target_dir.$imagemProduto)){
                    unlink($target_dir.$imagemProduto);    
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