<?php
    require_once 'conexion.models.php';
    require_once '../controllers/personas.controllers.php';
    $datos = [];
    /*solicitud ajax*/
    if (isset($_POST['action'])){
        $action = $_POST['action'];
        $db = new Conexion();
        $con = $db->Conectar();

        if($action == 'usuarioExistente'){
            $datos['ok'] = usuarioExistente($_POST['usuario'], $con);
        } elseif ($action = 'emailExistente'){
            $datos['ok'] = emailExistente($_POST['email'], $con);
        }
    }
    echo json_encode($datos);
?>