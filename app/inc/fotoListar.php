<?php    

    $stmt = $conn->prepare('SELECT * FROM fotos ORDER BY id_foto DESC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_foto"]."' data-descricao='".utf8_encode($row["descricao"])."'         
        data-imagem='".$row["imagem"]."' data-categoria='".utf8_encode($row["categoria"])."' 
        data-status='".$row["status"]."'>";
        echo "<img src='".$url."app/dist/img/fotos/".$row["imagem"]."' width='100'>";        
        echo "</a>";                        
        echo "</td>";
        echo "<td>".utf8_encode($row["descricao"])."</td>";
        switch ($row["categoria"]){
            case 1:
                echo "<td>Banho e Tosa</td>";                
                break;
            case 2:
                echo "<td>Pets</td>";                
                break;
            case 3:
                echo "<td>Equipe</td>";                
                break;
            case 4:
                echo "<td>Eventos</td>";                
                break;
            default:
                echo "<td>Não Há!</td>";                
                break;                
        }
        if($row["status"] == "A"){ echo "<td style='color:green;'>Ativo</td>"; }
        if($row["status"] == "I"){ echo "<td style='color:red;'>Inativo</td>"; }        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_foto"]."' data-descricao='".utf8_encode($row["descricao"])."'><img src='../dist/img/delete.png' title='Excluir Foto'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>