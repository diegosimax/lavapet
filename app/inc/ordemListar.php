<?php    
    $stmt = $conn->prepare('SELECT * FROM ordem_de_compra ORDER BY status ASC, id_ordem_de_compra DESC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";                    
        echo utf8_encode($row["id_ordem_de_compra"]);        
        echo "</td>";
        
        $stmt2 = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente');
        $stmt2->execute(array('id_cliente' => $row["id_cliente"])); 
        if($row2 = $stmt2->fetch()){ 
            echo "<td>";                    
            echo "<a href='../itens-da-ordem/index.php?id=".$row["id_ordem_de_compra"]."'>";
            echo "Visualizar";                
            echo "</a>";        
            echo "</td>";
            echo "<td>";
            echo utf8_encode($row2["nome"]);            
            echo "</td>";
            $nome = utf8_encode($row2["nome"]);
        }       
        
        $stmt2 = $conn->prepare('SELECT SUM(valor_pago) FROM historico_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt2->execute(array('id_ordem_de_compra' => $row["id_ordem_de_compra"]));
        $valor_pago = 0;
        if($row2 = $stmt2->fetch()){  
            $valor_pago = $row2["SUM(valor_pago)"];     
        }
        
        $totalDaOrdem = 0;        
        $stmt2 = $conn->prepare('SELECT * FROM itens_da_ordem WHERE id_ordem_de_compra = :id_ordem_de_compra');
        $stmt2->execute(array('id_ordem_de_compra' => $row["id_ordem_de_compra"])); 
        while($row2 = $stmt2->fetch()){ 
            $valorApagar = 0;
            if($row2["id_produto"] <> 0){
                $stmt3 = $conn->prepare('SELECT * FROM produtos WHERE id_produto = :id_produto');
                $stmt3->execute(array('id_produto' => $row2["id_produto"])); 
                if($row3 = $stmt3->fetch()){                                         
                    //echo $row["quantidade"];        
                    //echo "</td>";  
                    //echo "<td>R$ ".number_format($row2["valor_venda_unidade"], 2, ',', '.')."</td>"; 
                    $valorApagar = $row3["valor_venda_unidade"] * $row2["quantidade"];
                    //echo "<td>R$ ".number_format($total, 2, ',', '.')."</td>"; 
                    $totalDaOrdem = $totalDaOrdem + $valorApagar;                                        
                }
            }
            if($row2["id_servico"] <> 0){
                $stmt3 = $conn->prepare('SELECT * FROM servicos WHERE id_servico = :id_servico');
                $stmt3->execute(array('id_servico' => $row2["id_servico"])); 
                if($row3 = $stmt3->fetch()){ 
                    //echo $row["quantidade"];        
                    //echo "</td>";  
                    //echo "<td>R$ ".number_format($row2["valor"], 2, ',', '.')."</td>"; 
                    $valorApagar = $row3["valor"] * $row2["quantidade"];
                    //echo "<td>R$ ".number_format($total, 2, ',', '.')."</td>"; 
                    $totalDaOrdem = $totalDaOrdem + $valorApagar;
                }
            }
        }
        
        //$row["valor_a_pagar"]        
        //$valorApagar
        $saldo = $totalDaOrdem - $valor_pago;
        if($saldo <> 0){
            echo "<td style='color:red;'><b>R$ ".number_format($totalDaOrdem, 2, ',', '.')."</b></td>";
            echo "<td style='color:red;'><b>R$ ".number_format($valor_pago, 2, ',', '.')."</b></td>";           
            echo "<td style='color:red;'><b>R$ ".number_format($saldo, 2, ',', '.')."</b></td>";
        }else{
            echo "<td style='color:green;'><b>R$ ".number_format($totalDaOrdem, 2, ',', '.')."</b></td>";
            echo "<td style='color:green;'><b>R$ ".number_format($valor_pago, 2, ',', '.')."</b></td>";            
            echo "<td style='color:green;'><b>PAGO</b></td>";
        }       
            
        if($row["status"] == "A"){ echo "<td><span class='label label-warning'>Aberto</span></td>"; }
        if($row["status"] == "F"){ echo "<td><span class='label label-success'>Fechado</span></td>"; }
        $dia = substr($row["criado_em"], 8, 2);
        $mes = substr($row["criado_em"], 5, 2);
        $ano = substr($row["criado_em"], 0, 4);
        $hora = substr($row["criado_em"], 11, 2);
        $minuto = substr($row["criado_em"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_ordem_de_compra"]."' data-nome='".$nome."'><img src='../dist/img/delete.png' title='Excluir Ordem de Compra'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>