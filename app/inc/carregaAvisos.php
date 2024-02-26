<?php
    $id_usuario = $_SESSION["id_usuario"];          
    
    $conn = new PDO('mysql:host='.$GLOBALS["dbHost"].';dbname='.$GLOBALS["dbName"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    $stmt = $conn->prepare('SELECT * FROM avisos WHERE mostrar = "S"');
    $stmt->execute(); 

    if($stmt->rowCount() > 0){
        $qtd_avisos = $stmt->rowCount();
?>
        <!-- Notifications: style can be found in dropdown.less -->  
        <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning"><?php echo $qtd_avisos; ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header">Confira seus avisos!</li>
                <li>
                <!-- inner menu: contains the actual data -->
                    <ul class="menu">          
<?php            
        
                        while($row = $stmt->fetch()){                       
?>
                        <li>
                            <a href="<?php echo $GLOBALS["url"]; ?>app/avisos/"><i class="fa fa-thumb-tack text-aqua"></i> <?php echo utf8_encode($row["titulo"]); ?></a>
                        </li>                        
<?php            
                        }
?>
                    </ul>
                </li>
            </ul>
        </li>                   
<?php         
    }    
?>
