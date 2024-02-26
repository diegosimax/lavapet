<?php    
    $stmt = $conn->prepare('SELECT * FROM clientes ORDER BY nome ASC');
    $stmt->execute(); 

    while($row = $stmt->fetch()){  
        echo "<tr>";      
        echo "<td>";            
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-editar' 
        data-id='".$row["id_cliente"]."' data-nome='".utf8_encode($row["nome"])."' 
        data-email='".$row["email"]."' data-telefone1='".$row["telefone1"]."' data-telefone2='".$row["telefone2"]."'
        data-imagem='".$row["imagem"]."' data-endereco='".utf8_encode($row["endereco"])."' data-bairro='".utf8_encode($row["bairro"])."'
        data-cidade='".utf8_encode($row["cidade"])."' data-cep='".$row["cep"]."' data-cpf='".$row["cpf"]."'
        data-observacao='".utf8_encode($row["observacao"])."' data-numero='".$row["numero"]."' data-complemento='".utf8_encode($row["complemento"])."'
        >";
        echo utf8_encode($row["nome"]);
        echo "</a>";                        
        echo "</td>";
        if($row["email"] <> ""){
            echo "<td>".$row["email"]."</td>";
        }else{
            echo "<td>Não Cadastrado!</td>";
        }        
        if($row["telefone1"] <> ""){
            echo "<td>".$row["telefone1"]."</td>";    
        }else{
            echo "<td>".$row["telefone2"]."</td>";    
        }        
        $dia = substr($row["criado_em"], 8, 2);
        $mes = substr($row["criado_em"], 5, 2);
        $ano = substr($row["criado_em"], 0, 4);
        $hora = substr($row["criado_em"], 11, 2);
        $minuto = substr($row["criado_em"], 14, 2);
        echo "<td>".$dia."/".$mes."/".$ano." ".$hora.":".$minuto."</td>";        
        echo "<td>";        
        echo "<a href='#' class='pegaId' data-toggle='modal' data-target='#modal-excluir' data-id='".$row["id_cliente"]."' data-nome='".utf8_encode($row["nome"])."'><img src='../dist/img/delete.png' title='Excluir Cliente'></a>";
        echo "</td>";    
        echo "</tr>";               
    }    
   
?>