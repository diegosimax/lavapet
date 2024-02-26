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
    $html .= utf8_decode("Histórico de Pagamento");
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

    $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY id_item_da_ordem DESC');
    $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
    $totalDaOrdem = null;

    while($row = $stmt->fetch()){  
        if($row["id_produto"] <> 0){
            $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
            $stmt2->execute(array('id_produto' => $row["id_produto"])); 
            if($row2 = $stmt2->fetch()){ 
                $total = $row2["valor_venda_unidade"] * $row["quantidade"];
                $totalDaOrdem = $totalDaOrdem + $total; 
            }
        }        
        if($row["id_servico"] <> 0){
            $stmt2 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
            $stmt2->execute(array('id_servico' => $row["id_servico"])); 
            if($row2 = $stmt2->fetch()){
                $total = $row2["valor"] * $row["quantidade"]; 
                $totalDaOrdem = $totalDaOrdem + $total;
            }
        }        
    }

    $html .= "<p style='font-family:courier; text-align:left; font-size:11;'>";
    $html .= "<b>TOTAL DA ORDEM: "."R$ ".number_format($totalDaOrdem, 2, ',', '.')."</b><br>";
    $html .= "</p>";
    $html .= "</div>";  
    
    $html .= "<div align='center'>"; 
    $html .= "<table width='100%' align='center'>"; 
    $html .= "<tr align='center'>"; 
    $html .= "<th>DATA</th>"; 
    $html .= "<th>".utf8_decode("DESCRIÇÃO")."</th>"; 
    $html .= "<th>VALOR</th>"; 
    $html .= "</tr>"; 

    //PAGAMENTOS
    $stmt = $conn->prepare('SELECT * FROM historico_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY data DESC');
    $stmt->execute(array('id_ordem_de_compra' => $_GET["id"])); 
    $totalPago = null;
    while($row = $stmt->fetch()){ 
        $html .= "<tr align='center'>";        
        $dia = substr($row["data"], 8, 2);
        $mes = substr($row["data"], 5, 2);
        $ano = substr($row["data"], 0, 4);        
        $html .= "<td style='width: 45px'>".$dia."/".$mes."/".$ano."</td>";
        $html .= "<td style='width: 390px'>".$row["observacao"]."</td>";
        $html .= "<td style='width: 95px'>"."R$ ".number_format($row["valor_pago"], 2, ',', '.')."</td>";
        $totalPago = $totalPago + $row["valor_pago"];
        $html .= "</tr>";
    }  
    
    $saldo = $totalDaOrdem - $totalPago;
    $tordem = number_format($totalDaOrdem, 2, ',', '.');
    $tpago = number_format($totalPago, 2, ',', '.');
    $html .= "<tr align='center'>";
    $html .= "<th> </th>"; 
    $html .= "<th> </th>"; 
    $html .= "<th> </th>"; 
    $html .= "</tr>";
    $html .= "<tr align='center'>";
    $html .= "<th></th>"; 
    $html .= "<th>TOTAL PAGO</th>"; 
    $html .= "<th>"."R$ ".number_format($totalPago, 2, ',', '.')."</th>"; 
    $html .= "</tr>";

    if($tordem == $tpago){            
        $html .= "<tr align='center'>";
        $html .= "<th> </th>";
        $html .= "<th> </th>";
        $html .= "<th><font color='#00008B'><b>ORDEM PAGA</b></font></th>"; 
        $html .= "</tr>";
    }else{
        $html .= "<tr align='center'>";
        $html .= "<th></th>"; 
        $html .= "<th><font color='#FF0000'><b>A PAGAR</b></font></th>"; 
        $html .= "<th><font color='#FF0000'><b>"."R$ ".number_format($saldo, 2, ',', '.')."</b></font></th>"; 
        $html .= "</tr>";        
    }

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