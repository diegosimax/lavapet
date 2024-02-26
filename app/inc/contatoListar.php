<?php    

    $stmt = $conn->prepare('SELECT * FROM contatos ORDER BY id_contato DESC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_contato"]."' data-titulo='".utf8_encode($row["titulo"])."'         
        data-mensagem='".utf8_encode($row["mensagem"])."' data-email='".utf8_encode($row["email"])."'
        data-nome='".utf8_encode($row["nome"])."'>";
        echo utf8_encode($row["nome"]);
        echo "</a>";                        
        echo "</td>";       
        echo "<td>".utf8_encode($row["email"])."</td>";
        if($row["titulo"] <> ""){
            echo "<td>".utf8_encode($row["titulo"])."</td>";    
        }else{
            echo "<td>Não Há!</td>";   
        }        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_contato"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Contato'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>