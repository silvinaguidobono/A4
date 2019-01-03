<?php
include 'head_common.php';
?>
        <div><?php 
                if(isset($this->mensajeError)&&!empty($this->mensajeError))
                { 
                    echo($this->mensajeError);
                }
                ?>
        </div>
        <br>
        <hr>
        <p><a href="<?= URL.'home'?>" class="btn btn-primary btn-md">Volver</a></p>
        </div>
    </body>
</html>