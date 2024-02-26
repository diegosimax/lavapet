<?php    
    $stmt = $conn->prepare('SELECT * FROM animais ORDER BY nome ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
            echo "<td>";            
                echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
                data-id='".$row["id_animal"]."' data-nome='".utf8_encode($row["nome"])."' 
                data-porte='".$row["porte"]."' data-imagem='".$row["imagem"]."'
                data-sexo='".utf8_encode($row["sexo"])."' data-cliente='".$row["id_cliente"]."'        
                data-raca='".$row["id_raca"]."' data-observacao='".utf8_encode($row["observacao"])."'
                >";
                echo utf8_encode($row["nome"]);
                echo "</a>";
            echo "</td>";            
            $stmt2 = $conn->prepare('SELECT * FROM racas WHERE id_raca = :id_raca');
            $stmt2->execute(array('id_raca' => $row["id_raca"])); 
            if($row2 = $stmt2->fetch()){
                switch ($row2["especie"]) {
                    case 1:
                        echo "<td>Cão</td>";
                        break;
                    case 2:
                        echo "<td>Gato</td>";
                        break;
                    case 3:
                        echo "<td>Coelho</td>";
                        break;
                    case 4:
                        echo "<td>Pássaro</td>";
                        break;
                    case 5:
                        echo "<td>Tartaruga</td>";
                        break;
                    case 6:
                        echo "<td>Furão</td>";
                        break;
                    default:
                        echo "<td>Espécie não cadastrada!</td>";
                        break;
                }                
                echo "<td>".utf8_encode($row2["nome"])."</td>";
            }else{
                echo "<td>Não cadastrado!</td>";
                echo "<td>Não cadastrado!</td>";
            }            
            echo "<td>";
                if($row["sexo"] == "M"){
                    echo "Macho";
                }else{
                    echo "Fêmea";
                }
            echo "</td>";
            echo "<td>";
                $stmt2 = $conn->prepare('SELECT nome FROM clientes WHERE id_cliente = :id_cliente');
                $stmt2->execute(array('id_cliente' => $row["id_cliente"])); 
                if($row2 = $stmt2->fetch()){
                    echo utf8_encode($row2["nome"]);
                }else{
                    echo "Não Cadastrado!";
                }
            echo "</td>";
            $dia = substr($row["criado_em"], 8, 2);
            $mes = substr($row["criado_em"], 5, 2);
            $ano = substr($row["criado_em"], 0, 4);
            $hora = substr($row["criado_em"], 11, 2);
            $minuto = substr($row["criado_em"], 14, 2);
            echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
            echo "<td>";        
            echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_animal"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Animal'></a>";
            echo "</td>";    
        echo "</tr>";               
    }    
   
?>