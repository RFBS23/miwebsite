<?php
    function esNulo(array $parametros){
        foreach($parametros as $parametro){
            if (strlen(trim($parametro)) < 1){
                return true;
            }
        }
        return false;
    }
    function esEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    function validarPassword($claveAcceso, $reclaveAcceso) {
        if(strcmp($claveAcceso, $reclaveAcceso) === 0){
            return true;
        }
        return false;
    }
    function generarToken() {
        return md5(uniqid(mt_rand(), false));
    }
    function registrarPersonas(array $datos, $con) {
        $sql = $con->prepare("INSERT INTO personas (nombres, apellidos, email, telefono, dni, estado, fechaCreacion, horaCreacion) VALUES(?, ?, ?, ?, ?, 1, now(), now())");
        if ($sql->execute($datos)) {
            return $con->lastInsertId();
        }
        return  0;
    }
    function registrarUsuarios(array $datos, $con){
        $sql = $con->prepare("INSERT INTO usuarios (usuario, claveAcceso, token, id_personas) VALUES(?, ?, ?, ?)");
        if ($sql->execute($datos)) {
            return true;
        }
        return false;
    }
    function usuarioExistente($usuarios, $con) {
        $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE  ? LIMIT 1");
        $sql->execute([$usuarios]);
        if($sql->fetchColumn() > 0){
            return true;
        }
        return false;
    }
    function emailExistente($email, $con) {
        $sql = $con->prepare("SELECT id FROM personas WHERE email LIKE  ? LIMIT 1");
        $sql->execute([$email]);
        if($sql->fetchColumn() > 0){
            return true;
        }
        return false;
    }
    function mostrarMensaje(array $errors){
        if(count($errors) > 0){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            foreach ($errors as $error){
                echo '<i class="fa-solid fa-triangle-exclamation"></i> ' . $error . '<br>';
            }
            echo  '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else{
            echo  '<div class="alert alert-success alert-dismissible show" role="alert">';
            echo '<i class="fa-solid fa-circle-check">&nbsp;</i>Se registro correctamente';
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }
?>