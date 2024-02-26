<?php    
    $stmt = $conn->prepare('SELECT * FROM historico ORDER BY id_acao DESC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";
        echo "<td>".$row["id_acao"]."</td>";
        echo "<td>".utf8_encode($row["acao"])."</td>";
        echo "<td>".utf8_encode($row["tela"])."</td>";
        $stmt2 = $conn->prepare('SELECT nome FROM usuarios WHERE id_usuario = :id_usuario');
        $stmt2->execute(array('id_usuario' => $row["id_usuario"])); 
        if($row2 = $stmt2->fetch()){
            echo "<td>".utf8_encode($row2["nome"])."</td>";
        }else{
            echo "<td>Usuário já deletado!</td>";
        } 
        $dia = substr($row["data_hora"], 8, 2);
        $mes = substr($row["data_hora"], 5, 2);
        $ano = substr($row["data_hora"], 0, 4);
        $hora = substr($row["data_hora"], 11, 2);
        $minuto = substr($row["data_hora"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
        echo "</tr>";               
    }    
   
?>