<?php

include 'head_common.php';

?>
            <form action="/tarea/agregar" method="POST" id="form-tareas">
            <p>
                <label for="titulo">Título</label>
                <br>
                <input type="text" placeholder="Ingrese el titulo" name="titulo"
                       size="50"
                        value="<?php 
                               if (isset($_POST['titulo'])){
                                   echo $_POST['titulo'];
                               }
                               ?>"/>
                <?php 
                if (isset($errores['titulo'])){
                    echo '<span class="text-danger">'.$errores['titulo'].'</span>';
                } ?>
            </p>
            <p>
                <label for="descripcion">Descripción</label>
                <br>
                <textarea name="descripcion" form="form-tareas"
                      placeholder="Ingrese la descripción" rows="4" cols="50"><?php 
                if (isset($_POST['descripcion'])){
                    echo $_POST['descripcion'];
                }
                ?></textarea>
                <?php 
                if (isset($errores['descripcion'])){
                    echo '<span class="text-danger">'.$errores['descripcion'].'</span>';
                } ?>
            </p>
            <input type="submit" name="agregar" value="Agregar tarea"/>
        </form>
        <br>
        <hr>
        <p><a href="/tarea" class="btn btn-info btn-md">Listado de tareas</a></p>
    <?php
        include 'footer_common.php';
        ?>