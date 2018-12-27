<?php

include 'head_common.php';

?>
        <form action="/tarea/modificar" method="POST" id="form-tareas">
            <p>
                <label for="titulo">Título</label>
                <br>
                <input type="text" placeholder="Ingrese el titulo" name="titulo"
                       value="<?php
                       echo $this->tarea['titulo'];
                       /*    
                       if($_GET){
                           echo $row['0']['titulo'];
                       }
                       if (isset($_POST['titulo'])){
                           echo $_POST['titulo'];
                       }
                       */
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
                       echo $this->tarea['descripcion'];
                       /*     
                       if($_GET){
                           echo $row['0']['descripcion'];
                       }
                       if (isset($_POST['descripcion'])){
                           echo $_POST['descripcion'];
                       }
                       */
                       ?></textarea>
                <?php 
                if (isset($errores['descripcion'])){
                    echo '<span class="text-danger">'.$errores['descripcion'].'</span>';
                } ?>
            </p>
            <p>
                <label for="estado">Estado</label>
                <br>
                <input type="radio" name="estado" 
                        <?php
                        if ($this->tarea['estado']==0){
                                echo "checked";
                        }
                        /*
                        if($_GET){
                            if ($row['0']['estado']==0){
                                echo "checked";
                            }
                        }
                        if (isset($_POST['estado']) && $_POST['estado']=="Pendiente"){
                            echo "checked";
                        } 
                        */
                       ?>
                       value="Pendiente"/> Pendiente
                <br>
                <input type="radio" name="estado" 
                        <?php 
                        if ($this->tarea['estado']==1){
                            echo "checked";
                        }
                        /*
                        if($_GET){
                            if ($row['0']['estado']==1){
                                echo "checked";
                            }
                        }
                        if (isset($_POST['estado']) && $_POST['estado']=="Finalizada"){
                            echo "checked";
                        }
                        */
                       ?>
                       value="Finalizada"/> Finalizada
                <?php 
                if (isset($errores['estado'])){
                    echo '<span class="text-danger">'.$errores['estado'].'</span>';
                } 
                ?>
            </p>
            
            <input type="hidden" name="id_tarea" value="<?= $this->id_tarea; ?>" />
            
            <input type="submit" name="modificar" value="Modificar tarea"/>
            
        </form>
        <br>
        <hr>
        <p><a href="/tarea" class="btn btn-info btn-md">Listado de tareas</a></p>
    <?php
        include 'footer_common.php';
        ?>