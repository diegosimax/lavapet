<?php

    require_once('../../inc/global.php');
    require('../dist/fpdf/fpdf.php');
    

    $pdf = new FPDF("P","mm","A4");
    $pdf->SetTitle("Cupom de Compra");
    
    $pdf->AddPage();

    //MONTA CABEÇALHO
    $pdf->Image('../dist/img/logo_banho_e_tosa.png',160,6,30);   
    $pdf->SetFont('Arial','',16);    
    $pdf->Cell(0,0,$GLOBALS["nomeEmpresaInteiro"],0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',14);
    $pdf->Cell(0,20,"Cupom de Compra",0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,30,'Rua Ida Wellwock , 64',0,0,'L');
    $pdf->Ln(0);
    $pdf->Cell(0,40,'Itoupavazinha - Blumenau - SC',0,0,'L');
    $pdf->Ln(0);
    $pdf->Cell(0,50,'47 33383327 - 47 9 91963327 - 47 9 91903327',0,0,'L');
    $pdf->Ln(0);   
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,60,"Ordem de Compra",0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,70,utf8_decode('Código: '.$_GET["id"]),0,0,'L');    
    
    //Pega os dados da ordem
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
                $nomeCliente = utf8_encode($row["nome"])." - ".$row["telefone1"];
            }else{
                $nomeCliente = utf8_encode($row["nome"])." - ".$row["telefone2"];
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

    $pdf->SetX("110");
    $pdf->Cell(0,70,utf8_decode('Criado em: '.$dataOrdem),0,0,"R"); 
    $pdf->Ln(0);
    $pdf->Cell(0,80,utf8_decode('Cliente: '.$nomeCliente),0,0,'L'); 
    $pdf->SetX("110");
    $pdf->Cell(0,80,utf8_decode('Status: '.$status),0,0,"R"); 
    $pdf->Ln(0);    
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,90,"Itens da Ordem",0,0,"C");
    $pdf->Ln(0);
    $pdf->Cell(0,105,"QTD");
    $pdf->SetX("22");
    $pdf->Cell(0,105,"ITEM");
    $pdf->SetX("110");
    $pdf->Cell(0,105,"DATA");
    $pdf->SetX("140");
    $pdf->Cell(0,105,"VALOR UND");
    $pdf->SetX("175");
    $pdf->Cell(0,105,"TOTAL");   
    
    $linha = 105;
    //Lista os itens da ordem

    $stmt = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY id_item_da_ordem DESC');
    $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
    $totalDaOrdem = null;

    while($row = $stmt->fetch()){         
        $linha = $linha + 10;
        
        $pdf->Ln(0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,$linha,$row["quantidade"]);
        $pdf->SetX("22");
        
        $dia = substr($row["data_venda"], 8, 2);
        $mes = substr($row["data_venda"], 5, 2);
        $ano = substr($row["data_venda"], 0, 4);                
        
        if($row["id_produto"] <> 0){
            $stmt2 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
            $stmt2->execute(array('id_produto' => $row["id_produto"])); 
            if($row2 = $stmt2->fetch()){                 
                $pdf->Cell(0,$linha,$row2["nome"]." (Produto)");
                $pdf->SetX("110");
                $pdf->Cell(0,$linha,$dia."/".$mes."/".$ano);
                $pdf->SetX("140");
                $pdf->Cell(0,$linha,"R$ ".number_format($row2["valor_venda_unidade"], 2, ',', '.'));
                $pdf->SetX("175");
                $total = $row2["valor_venda_unidade"] * $row["quantidade"];
                $pdf->Cell(0,$linha,"R$ ".number_format($total, 2, ',', '.'));
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
                
                $pdf->Cell(0,$linha,$descricao.utf8_decode(" (Serviço)"));                
                $pdf->SetX("110");
                $pdf->Cell(0,$linha,$dia."/".$mes."/".$ano);
                $pdf->SetX("140");
                $pdf->Cell(0,$linha,"R$ ".number_format($row2["valor"], 2, ',', '.'));
                $pdf->SetX("175");
                $total = $row2["valor"] * $row["quantidade"];
                $pdf->Cell(0,$linha,"R$ ".number_format($total, 2, ',', '.'));
                $totalDaOrdem = $totalDaOrdem + $total;
            }
        }        
    }

    $linha = $linha + 15;    
    $pdf->Ln(0);    
    $pdf->SetX("140");
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,$linha,"TOTAL");
    $pdf->SetX("175");
    $pdf->Cell(0,$linha,"R$ ".number_format($totalDaOrdem, 2, ',', '.'));

    $pdf->AddPage();
    $linha = 0;
    
    //MONTA CABEÇALHO
    $pdf->Image('../dist/img/logo_banho_e_tosa.png',160,6,30);   
    $pdf->SetFont('Arial','',16);    
    $pdf->Cell(0,0,$GLOBALS["nomeEmpresaInteiro"],0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',14);
    $pdf->Cell(0,20,"Cupom de Compra",0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,30,'Rua Ida Wellwock , 64',0,0,'L');
    $pdf->Ln(0);
    $pdf->Cell(0,40,'Itoupavazinha - Blumenau - SC',0,0,'L');
    $pdf->Ln(0);
    $pdf->Cell(0,50,'47 33383327 - 47 9 91963327 - 47 9 91903327',0,0,'L');
    $pdf->Ln(0);   
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,60,"Ordem de Compra",0,0,"C");
    $pdf->Ln(0);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,70,utf8_decode('Código: '.$_GET["id"]),0,0,'L');    
    
    //Pega os dados da ordem
    try {         
        $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        
        $stmt = $conn->prepare('SELECT CLI.nome, CLI.id_cliente, ODC.status, ODC.criado_em
                                      FROM clientes AS CLI 
                                INNER JOIN ordem_de_compra AS ODC
                                        ON CLI.id_cliente = ODC.id_cliente
                                     WHERE ODC.id_ordem_de_compra = :id_ordem_de_compra');
        $stmt->execute(array('id_ordem_de_compra' => $_GET["id"]));
        if($row = $stmt->fetch()){ 
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

    $pdf->SetX("110");
    $pdf->Cell(0,70,utf8_decode('Criado em: '.$dataOrdem),0,0,"R"); 
    $pdf->Ln(0);
    $pdf->Cell(0,80,utf8_decode('Cliente: '.$nomeCliente),0,0,'L'); 
    $pdf->SetX("110");
    $pdf->Cell(0,80,utf8_decode('Status: '.$status),0,0,"R"); 
    $pdf->Ln(0); 

    

    $linha = $linha + 90;   
    $pdf->Ln(0);    
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,$linha,"Pagamentos",0,0,"C");
    $pdf->Ln(0);
    $linha = $linha + 15;  
    $pdf->Cell(0,$linha,"DATA");
    $pdf->SetX("42");
    $pdf->Cell(0,$linha, utf8_decode("DESCRIÇÃO"));
    $pdf->SetX("175");
    $pdf->Cell(0,$linha,"VALOR");

    //PAGAMENTOS
    $stmt = $conn->prepare('SELECT * FROM historico_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra ORDER BY data DESC');
    $stmt->execute(array('id_ordem_de_compra' => $_GET["id"])); 
    $totalPago = null;
    while($row = $stmt->fetch()){ 
        $dia = substr($row["data"], 8, 2);
        $mes = substr($row["data"], 5, 2);
        $ano = substr($row["data"], 0, 4);
        $linha = $linha + 10; 
        $pdf->Ln(0);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,$linha,$dia."/".$mes."/".$ano);
        $pdf->SetX("42");
        $pdf->Cell(0,$linha, $row["observacao"]);
        $pdf->SetX("175");
        $pdf->Cell(0,$linha,"R$ ".number_format($row["valor_pago"], 2, ',', '.'));
        $totalPago = $totalPago + $row["valor_pago"];
    }  
    
    $saldo = $totalDaOrdem - $totalPago;
    $tordem = number_format($totalDaOrdem, 2, ',', '.');
    $tpago = number_format($totalPago, 2, ',', '.');

    $linha = $linha + 15;    
    $pdf->Ln(0);    
    $pdf->SetX("140");
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,$linha,"TOTAL PAGO");
    $pdf->SetX("175");
    $pdf->Cell(0,$linha,"R$ ".number_format($totalPago, 2, ',', '.'));
    $linha = $linha + 15;    
    $pdf->Ln(0);    
    $pdf->SetX("140");
    $pdf->SetFont('Arial','',12);

   if($tordem == $tpago){            
        $pdf->SetTextColor(0,0,204);
        $pdf->Cell(0,$linha,"ORDEM PAGA");
   }else{
        $pdf->SetTextColor(255,0,0);
        $pdf->Cell(0,$linha,utf8_decode("À PAGAR"));
        $pdf->SetX("175");
        $pdf->Cell(0,$linha,"R$ ".number_format($saldo, 2, ',', '.'));    
   }

    $pdf->Output();
    
?>
