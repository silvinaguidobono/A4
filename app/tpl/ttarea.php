<?php

include 'head_common.php';

if ($this->cantTareas==0){
    echo "<p>El usuario no tiene tareas asignadas</p>";
}    
else{
?>
        <div class="col-md-12">
            <div class="table-responsive">
                <p>
                    Total tareas: <span id="total"><?= $this->cantTareas;?></span>
                </p>

                <br>					
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Modificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($this->tareas as $tarea){ ?>
                        <tr data-id="id_usuario">
                            <td><?= $tarea['titulo']?></td>
                            <td>
                                <?php 
                                if($tarea['estado']==0){
                                    echo "<strong>Pendiente</strong>";
                                }elseif($tarea['estado']==1){
                                    echo "<strong>Finalizada</strong>";
                                } 
                                ?>    
                            </td>
                            <td><?= $tarea['fecha_creado']?></td>
                            <td><?= $tarea['fecha_act']?></td>
                            
                            <td class="actions">
                                <a href="/tarea/ver/id_tarea/<?= $tarea['id']?>" class="btn btn-sm btn-info">
                                    Ver
                                </a>

                                <a href="/tarea/editar/id_tarea/<?= $tarea['id']?>" class="btn btn-sm btn-primary">
                                    Editar
                                </a>

                                <a href="borrar_tarea.php?id_tarea=<?= $tarea['id']?>" class="btn btn-sm btn-danger btn-delete">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                    <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }  ?>

    <hr>
    <p><a href="/tarea/nueva" class="btn btn-primary btn-md">Nueva tarea</a></p>
    <?php
        include 'footer_common.php';
        ?>