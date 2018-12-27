<?php

include 'head_common.php';
                
?>
        <form id="form-reg" class="form-horizontal" action="<?= URL.'reg/reg'?>" method="POST">
            <p>
                <label for="nombre">Nombre</label>
                <br>
                <input type="text" placeholder="Ingrese su nombre" id="nombre-reg" name="nombre" 
                       size="50"
                        value="<?php 
                               if (isset($this->nombre)){
                                   echo $this->nombre;
                               }
                               ?>"/>
                <?php 
                if (isset($this->errores['nombre'])){
                    echo '<span class="text-danger">'.$this->errores['nombre'].'</span>';
                } ?>
            </p>
            <p>
                <label for="apellidos">Apellidos</label>
                <br>
                <input type="text" placeholder="Ingrese sus apellidos" id="apellidos-reg" name="apellidos"
                       size="50"
                        value="<?php 
                               if (isset($this->apellidos)){
                                   echo $this->apellidos;
                               }
                               ?>"/>
                <?php 
                if (isset($this->errores['apellidos'])){
                    echo '<span class="text-danger">'.$this->errores['apellidos'].'</span>';
                } ?>
            </p>
            <p>
                <label for="email">Correo electrónico</label>
                <br>
                <input type="email" placeholder="Ingrese su correo" id="email-reg" name="email"
                       size="50"
                        value="<?php 
                               if (isset($this->email)){
                                   echo $this->email;
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
                <input type="password" placeholder="Ingrese su contraseña" id="clave-reg" name="clave"
                       size="50"/>
                <?php 
                if (isset($this->errores['clave'])){
                    echo '<span class="text-danger">'.$this->errores['clave'].'</span>';
                } ?>
            </p>
            
            <input type="submit" name="enviar" value="Registrar usuario"/>
            <!--<div class="form-group">
                <button type="submit" id="btn-reg" class="btn btn-default">Registrar usuario</button>
            </div>-->
        </form>
        <br>
        <div class="alert alert-danger" col-sm-offset-2 col-sm-8 id="msg"></div>
        <br>
        <hr>
        <p><a href="<?= URL.'home'?>" class="btn btn-primary btn-md">Volver</a></p>
        </div>
        <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
        <!--<script src="public/js/jquery.min.js"></script>
        <script src="public/js/bootstrap.min.js"></script>-->
        <script src="public/js/app.js"></script>
    </body>
</html>