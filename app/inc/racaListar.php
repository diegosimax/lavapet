<?php    
    $stmt = $conn->prepare('SELECT * FROM racas ORDER BY nome ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
            echo "<td>";            
                echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
                data-id='".$row["id_raca"]."' data-nome='".utf8_encode($row["nome"])."' 
                data-especie='".$row["especie"]."'>";
                echo utf8_encode($row["nome"]);
                echo "</a>";
            echo "</td>";
            echo "<td>";
            switch ($row["especie"]) {
                case 1:
                    echo "Cão";
                    break;
                case 2:
                    echo "Gato";
                    break;
                case 3:
                    echo "Coelho";
                    break;
                case 4:
                    echo "Pássaro";
                    break;
                case 5:
                    echo "Tartaruga";
                    break;
                case 6:
                    echo "Furão";
                    break;
                default:
                    echo "Espécie não definida!";
                    break;
            }
            echo "</td>";
            $dia = substr($row["criado_em"], 8, 2);
            $mes = substr($row["criado_em"], 5, 2);
            $ano = substr($row["criado_em"], 0, 4);
            $hora = substr($row["criado_em"], 11, 2);
            $minuto = substr($row["criado_em"], 14, 2);
            echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
            echo "<td>";        
            echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_raca"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Raça'></a>";
            echo "</td>";    
        echo "</tr>";               
    }    
   
?>