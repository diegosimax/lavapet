<?php    
    $stmt = $conn->prepare('SELECT * FROM produtos ORDER BY nome ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_produto"]."' data-nome='".utf8_encode($row["nome"])."' 
        data-descricao='".utf8_encode($row["descricao"])."' data-estoque='".$row["qtd_estoque"]."' 
        data-valorpago='".number_format($row["valor_pago_unidade"], 2, ',', '.')."' data-imagem='".$row["imagem"]."' 
        data-valorvenda='".number_format($row["valor_venda_unidade"], 2, ',', '.')."' data-fornecedor='".$row["id_fornecedor"]."'
        >";
        echo utf8_encode($row["nome"]);
        echo "</a>";                        
        echo "</td>";
        echo "<td>".utf8_encode($row["descricao"])."</td>";        
        echo "<td>";
            $stmt2 = $conn->prepare('SELECT nome FROM fornecedores WHERE id_fornecedor = :id_fornecedor');
            $stmt2->execute(array('id_fornecedor' => $row["id_fornecedor"])); 
            if($row2 = $stmt2->fetch()){
                echo utf8_encode($row2["nome"]);
            }else{
                echo "Não Cadastrado!";
            }
        echo "</td>";
        echo "<td>".$row["qtd_estoque"]."</td>";        
        echo "<td>R$ ".number_format($row["valor_venda_unidade"], 2, ',', '.')."</td>";        
        $dia = substr($row["criado_em"], 8, 2);
        $mes = substr($row["criado_em"], 5, 2);
        $ano = substr($row["criado_em"], 0, 4);
        $hora = substr($row["criado_em"], 11, 2);
        $minuto = substr($row["criado_em"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_produto"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Produto'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>