<?php    
    $stmt = $conn->prepare('SELECT * FROM servicos ORDER BY descricao ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_servico"]."' data-descricao='".utf8_encode($row["descricao"])."' 
        data-valor='".number_format($row["valor"], 2, ',', '.')."'
        >";
        echo utf8_encode($row["descricao"]);
        echo "</a>";                        
        echo "</td>";
        echo "<td>R$ ".number_format($row["valor"], 2, ',', '.')."</td>";    
        $dia = substr($row["criado_em"], 8, 2);
        $mes = substr($row["criado_em"], 5, 2);
        $ano = substr($row["criado_em"], 0, 4);
        $hora = substr($row["criado_em"], 11, 2);
        $minuto = substr($row["criado_em"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_servico"]."' data-descricao='".utf8_encode($row["descricao"])."'><img src='../dist/img/delete.png' title='Excluir Serviço'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>