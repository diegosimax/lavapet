<?php
    
    require_once('../../inc/global.php');
    require('../dist/pdf/dompdf_config.inc.php');
    
    $html = "<div align='center'>";    
    $html .= "<img src='../dist/img/logo_banho_e_tosa.png'>";
    $html .= "<h2 style='font-family:arial;'>";
    $html .= $GLOBALS["nomeEmpresaInteiro"];
    $html .= "</h2>";   
    $html .= "<p style='font-family:arial; text-align:center; font-size:12;'>";
    $html .= "Rua Ida Wellwock , 64"."<br>";
    $html .= "Itoupavazinha - Blumenau - SC"."<br>";
    $html .= "47 33383327 - 47 9 91963327 - 47 9 91903327"."<br>";
    $html .= "</p>";
    $html .= "<h3 style='font-family:arial;'>";
    $html .= "Cupom de Compra";
    $html .= "</h3>";
    $html .= "</div>";
    $html .= "<div align='left'>";  
    $html .= "<p style='font-family:courier; text-align:left; font-size:11;'>";
    $html .= utf8_decode("Código da Ordem..: ").$_GET["id"]."<br>";
    try {         
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
        $stmt = $conn->prepare('SELECT CLI.nome, CLI.telefone1, CLI.telefone2, CLI.id_cliente, ODC.status, ODC.criado_em
                                      FROM clientes AS CLI 
                                INNER JOIN ordem_de_compra AS ODC
                                        ON CLI.id_cliente = ODC.id_cliente
                                     WHERE ODC.id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
        if($row = $stmt->fetch()){ 
            if($row["telefone1"] <> ""){
                $nomeCliente = $row["nome"]." - ".$row["telefone1"];
            }else{
                $nomeCliente = $row["nome"]." - ".$row["telefone2"];
            } 
            if($row["status"] == "A"){
                $status = "Aberto";                    
            }else{
                $status = "Fechado";
            }    
            $idCliente = $row["id_cliente"];
            $dia = substr($row["criado_em"], 8, 2);
            $mes = substr($row["criado_em"], 5, 2);
            $ano = substr($row["criado_em"], 0, 4);                
            $dataOrdem = $dia."/".$mes."/".$ano;
        }
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }  
    $html .= "Cliente..........: ".$nomeCliente."<br>";
    $html .= "Criado em........: ".$dataOrdem."<br>";
    $html .= "Status...........: ".$status."<br>";
    $html .= "</p>";
    $html .= "</div>";
    $html .= "<div align='center'>";     
    $html .= "<h3 style='font-family:arial;'>";
    $html .= "Itens da Ordem";
    $html .= "</h3>";    
    $html .= "</div>";
    
    $html .= "<div align='center'>"; 
    $html .= "<table width='100%' align='center'>"; 
    $html .= "<tr align='center'>"; 
    $html .= "<th>QTD</th>"; 
    $html .= "<th>ITEM</th>"; 
    $html .= "<th>DATA</th>"; 
    $html .= "<th>VALOR UND</th>"; 
    $html .= "<th>TOTAL</th>"; 
    $html .= "</tr>"; 

    $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY id_item_da_ordem DESC');
    $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
    $totalDaOrdem = null;

    while($row = $stmt->fetch()){  
        $html .= "<tr align='center'>"; 
        $html .= "<td>".$row["quantidade"]."</td>";
        $dia = substr($row["data_venda"], 8, 2);
        $mes = substr($row["data_venda"], 5, 2);
        $ano = substr($row["data_venda"], 0, 4);                
        
        if($row["id_produto"] <> 0){
            $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
            $stmt2->execute(array('id_produto' => $row["id_produto"])); 
            if($row2 = $stmt2->fetch()){ 
                $html .= "<td>".$row2["nome"]."</td>";
                $html .= "<td>".$dia."/".$mes."/".$ano."</td>";
                $html .= "<td>"."R$ ".number_format($row2["valor_venda_unidade"], 2, ',', '.')."</td>";
                $total = $row2["valor_venda_unidade"] * $row["quantidade"];
                $html .= "<td>"."R$ ".number_format($total, 2, ',', '.')."</td>";
                $totalDaOrdem = $totalDaOrdem + $total; 
            }
        }
        
        if($row["id_servico"] <> 0){
            $stmt2 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
            $stmt2->execute(array('id_servico' => $row["id_servico"])); 
            if($row2 = $stmt2->fetch()){
                if($row["id_animal"] <> 0){
                    $stmt3 = $conn->prepare('SELECT * FROM animais WHERE id_animal = :id_animal');
                    $stmt3->execute(array('id_animal' => $row["id_animal"]));    
                    if($row3 = $stmt3->fetch()){ 
                        $descricao = $row2["descricao"]." (".$row3["nome"].")";
                    }
                }else{
                    $descricao = $row2["descricao"];   
                }
                $html .= "<td>".$descricao."</td>";
                $html .= "<td>".$dia."/".$mes."/".$ano."</td>";
                $html .= "<td>"."R$ ".number_format($row2["valor"], 2, ',', '.')."</td>";
                $total = $row2["valor"] * $row["quantidade"]; 
                $html .= "<td>"."R$ ".number_format($total, 2, ',', '.')."</td>";                               
                $totalDaOrdem = $totalDaOrdem + $total;
            }
        }
        $html .= "</tr>";
    }
    $html .= "<tr>";
    $html .= "<th></th>"; 
    $html .= "<th></th>"; 
    $html .= "<th></th>"; 
    $html .= "<th>TOTAL</th>"; 
    $html .= "<th>"."R$ ".number_format($totalDaOrdem, 2, ',', '.')."</th>"; 
    $html .= "</tr>";

    $html .= "</table>"; 
    $html .= "</div>";
    

    // Instanciamos a classe
    $dompdf = new DOMPDF();

    // Passamos o conteúdo que será convertido para PDF
    $dompdf->load_html($html);

    // Definimos o tamanho do papel e
    // sua orientação (retrato ou paisagem)
    $dompdf->set_paper('A4','portrait');

    // O arquivo é convertido
    $dompdf->render();

    // Salvo no diretório temporário do sistema
    // e exibido para o usuário
    $dompdf->stream(
        $_GET["id"]."_".$nomeCliente.".pdf",
        array(
            "Attachment" => false
        )
    );
?>