<?php
    require '../controllers/config.controllers.php';
    require '../models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();

    //recepcion de la informacion
    $json = file_get_contents('php://input');
    $datos = json_decode($json, true);

    //validamos
    if (is_array($datos)){
        $id_transaccion = $datos['detalles']['id'];
        $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
        $status = $datos['detalles']['status'];
        $fecha = $datos['detalles']['update_time'];
        //hacemos conversion para que no aparesca la T y la Z
        $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
        $email = $datos['detalles']['payer']['email_address'];
        $id_cliente = $datos['detalles']['payer']['payer_id'];

        $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total) VALUES (?,?,?,?,?,?)");
        $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total]);
        $id = $con->lastInsertId();

        if( $id > 0){
            $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

            if ($productos != null) {
                foreach ($productos as $clave => $cantidad) {
                    $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND estado=1");
                    $sql->execute([$clave]);
                    $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                    $precio = $row_prod['precio'];
                    $descuento = $row_prod['descuento'];
                    $precio_desc = $precio - (($precio * $descuento) / 100);

                    $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_productos, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
                    $sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio_desc, $cantidad]);
                }
                include 'enviarEmail.controllers.php';
            }
            unset($_SESSION['carrito']); //eliminamos lo que estaba en el carrito
        }
    }
?>