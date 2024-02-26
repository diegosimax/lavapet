<?php    

    $stmt = $conn->prepare('SELECT * FROM banners ORDER BY ordem ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_banner"]."' data-titulo='".utf8_encode($row["titulo"])."'         
        data-imagem='".$row["imagem"]."' data-descricao='".utf8_encode($row["descricao"])."' 
        data-ordem='".$row["ordem"]."' data-status='".$row["status"]."'>";
        echo "<img src='".$url."app/dist/img/banners/".$row["imagem"]."' width='100'>";        
        echo "</a>";                        
        echo "</td>";
        echo "<td>".utf8_encode($row["titulo"])."</td>";
        echo "<td>".utf8_encode($row["ordem"])."</td>";
        if($row["status"] == "A"){ echo "<td style='color:green;'>Ativo</td>"; }
        if($row["status"] == "I"){ echo "<td style='color:red;'>Inativo</td>"; }        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_banner"]."' data-titulo='".utf8_encode($row["titulo"])."'><img src='../dist/img/delete.png' title='Excluir Banner'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>