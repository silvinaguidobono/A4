<?php

include 'head_common.php';
?>
        <h2>Aplicación para registrar y actualizar tareas</h2>
        <p>Si aún no estas registrado como usuario debes registrarte previamente 
        para acceder a la aplicación</p>
        <p>Si ya estás registrado, accede para administrar tus tareas</p>
        <br>
        <ul class="list-inline">
            <li><a href="<?= URL.'reg'?>" class="btn btn-primary btn-lg">Registro de usuarios</a></li>
            <li><a href="<?= URL.'log'?>" class="btn btn-primary btn-lg">Acceder</a></li>
        </ul>
        <br>
    </div>
</body>
</html>