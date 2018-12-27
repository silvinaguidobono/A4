<?php
include 'head_common.php';
?>
        <form action="<?= URL.'log/log'?>" method="POST">
            <p>
                <label for="email">Correo electrónico</label>
                <br>
                <input type="email" placeholder="Ingrese su correo" name="email"
                       size="50"
                        value="<?php 
                            if (isset($_COOKIE['email'])){
                                echo $_COOKIE['email'];
                            }else{
                                if (isset($this->email)){
                                    echo $this->email;
                                }    
                            }
                               ?>"/>
                <?php 
                if (isset($this->errores['email'])){
                    echo '<span class="text-danger">'.$this->errores['email'].'</span>';
                } ?>    
            </p>
            <p>
                <label for="clave">Contraseña</label>
                <br>
                <input type="password" placeholder="Ingrese su contraseña" 
                       name="clave" size="50"
                       value="<?php 
                            if (isset($_COOKIE['clave'])){
                                echo $_COOKIE['clave'];
                            } 
                               ?>"/>
                <?php 
                if (isset($this->errores['clave'])){
                    echo '<span class="text-danger">'.$this->errores['clave'].'</span>';
                } ?>
            </p>
            <p>
                <input type="checkbox" name="recordar" 
                       value="Si" />Recuérdame en este equipo
            </p>
                
            <input type="submit" name="iniciar" value="Iniciar sesión"/>
            
        </form>
        <br>
        <hr>
        <p><a href="<?= URL.'home'?>" class="btn btn-primary btn-md">Volver</a></p>
        </div>
    </body>
</html>