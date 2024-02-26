    <li <?php if($urlAtiva == 'home'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>"><i class="fa fa-home"></i> <span>Home</span></a></li>    
    <li <?php if($urlAtiva == 'ordem-de-compra'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/ordem-de-compra/"><i class="fa fa-dollar"></i><span>Ordem de Compra</span></a></li>
    <li <?php if($urlAtiva == 'fornecedores'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/fornecedores/"><i class="fa fa-truck"></i><span>Fornecedores</span></a></li>            
    <li <?php if($urlAtiva == 'clientes'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/clientes/"><i class="fa fa-users"></i><span>Clientes</span></a></li>                                
    
    <li class="treeview <?php if($urlAtiva == 'animais' or $urlAtiva == 'racas'){ echo "active"; } ?>">
        <a href="#">
            <i class="fa fa-paw"></i> <span>Pets</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li <?php if($urlAtiva == 'animais'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/animais/"><i class="fa fa-paw"></i>Animais</a></li>            
            <li <?php if($urlAtiva == 'racas'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/racas/"><i class="fa fa-paw"></i>Raças</a></li>            
        </ul>
    </li>  

    <li <?php if($urlAtiva == 'produtos'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/produtos/"><i class="fa fa-cubes"></i><span>Produtos</span></a></li>
    <li <?php if($urlAtiva == 'servicos'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/servicos/"><i class="fa fa-wrench"></i><span>Serviços</span></a></li>
    <li <?php if($urlAtiva == 'historico'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/historico/"><i class="fa fa-clock-o"></i><span>Histórico</span></a></li>    
    
    <li class="treeview <?php if($urlAtiva == 'banners' or $urlAtiva == 'nossa-historia' or $urlAtiva == 'fotos' or $urlAtiva == 'contatos'){ echo "active"; } ?>">
        <a href="#">
            <i class="fa fa-globe"></i> <span>Site</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li <?php if($urlAtiva == 'banners'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/banners/"><i class="fa fa-object-group"></i>Banners</a></li>            
            <li <?php if($urlAtiva == 'nossa-historia'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/nossa-historia/"><i class="fa fa-text-width"></i>Nossa História</a></li>            
            <li <?php if($urlAtiva == 'fotos'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/fotos/"><i class="fa fa-image"></i>Fotos</a></li>            
            <li <?php if($urlAtiva == 'contatos'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/contatos/"><i class="fa fa-envelope-o"></i>Contatos</a></li>            
        </ul>
    </li> 

    <li <?php if($urlAtiva == 'usuarios'){ echo "class= 'active'"; } ?>><a href="<?=$GLOBALS["url"]?>app/usuarios/"><i class="fa fa-user-secret"></i> <span>Usuários</span></a></li>