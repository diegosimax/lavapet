<?php    
    $id_usuario = $_SESSION["id_usuario"];
        
    $stmt = $conn->prepare('SELECT * FROM usuarios ORDER BY id_usuario DESC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";        
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_usuario"]."' data-nome='".utf8_encode($row["nome"])."' 
        data-imagem='".$row["imagem"]."' 
        data-email='".$row["email"]."' data-status='".$row["status"]."'>";
        echo utf8_encode($row["nome"]);
        echo "</a>";                        
        echo "</td>";        
        echo "<td>".$row["email"]."</td>";
        if($row["status"] == "A"){ echo "<td style='color:green;'>Ativo</td>"; }
        if($row["status"] == "I"){ echo "<td style='color:red;'>Inativo</td>"; }
        $dia = substr($row["criado_em"], 8, 2);
        $mes = substr($row["criado_em"], 5, 2);
        $ano = substr($row["criado_em"], 0, 4);
        $hora = substr($row["criado_em"], 11, 2);
        $minuto = substr($row["criado_em"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";
        $dia = substr($row["ultimo_acesso"], 8, 2);
        $mes = substr($row["ultimo_acesso"], 5, 2);
        $ano = substr($row["ultimo_acesso"], 0, 4);
        $hora = substr($row["ultimo_acesso"], 11, 2);
        $minuto = substr($row["ultimo_acesso"], 14, 2);
        if($row["ultimo_acesso"] > 0){
            echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";
        }else{
            echo "<td>Ainda não efetuou login</td>";
        }
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_usuario"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Usuário'></a>";
        echo "</td>";        
        echo "</tr>";               
    }
?>