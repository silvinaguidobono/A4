<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tareas a hacer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <?php
    if(isset($this->mensaje) && $this->mensaje != ""){ ?>
    <div class="alert alert-<?= $this->tipo_mensaje; ?>">
    <?php 
        echo $this->mensaje;
    ?>
    </div>
        
    <?php        
        //Session::del('tipo_mensaje');
        //Session::del('mensaje');
        //echo "borra mensaje en sesión";
        }
    ?>
    <div class="container">
    <h1><?= $this->titulo; ?></h1>