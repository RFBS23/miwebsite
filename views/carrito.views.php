<?php
    require '../controllers/config.controllers.php';
    require '../models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();

    $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

    //print_r($_SESSION); //funcion del carrito

    $lista_carrito = array();

    if ($productos != null) {
        foreach ($productos as $clave => $cantidad) {
            $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND estado=1");
            $sql->execute([$clave]);
            $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPRA</title>
    <link rel="icon" href="../img/usuario.png">
    <!--FONTAWESOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/scss/inicio.css">
    <!-- datatables-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <!--fin-->
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg shadow bg-white">
            <div class="container">
                <a href="../index.php" class="navbar-brand d-flex align-items-center">
                    <img src="../img/logo1.png" alt="logo" width="100%" height="50">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link active">catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">catalogo</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">catalogo</a>
                        </li>
                    </ul>

                    <a href="carrito.views.php" class="btn btn-outline-primary position-relative"><i class="fa-solid fa-cart-shopping">&nbsp;</i>
                        carrito
                        <span id="num_cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $num_cart; ?></span>
                    </a>

                </div>
            </div>
        </div>
    </header>

    <main class="container">
            <div class="table-responsive py-lg-5">
                <table class="table border table-light display responsive nowrap row-border order-column" id="mitabla" style="width:100%;">
                <thead class="border-danger table-success">
                        <tr>
                            <th data-priority="1">PRODUCTO</th>
                            <th>PRECIO</th>
                            <th>CANTIDAD</th>
                            <th>SUBTOTAL</th>
                            <th>OPCIONES</th>
                        </tr>
                    </thead>

                    <tbody class="table-group-divider">
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td></td><td></td><td class="text-center"><b>LISTA VACIA</b><td></td><td></td></td></tr>';
                        } else {
                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                $_id = $producto['id'];
                                $nombre = $producto['nombre'];
                                $precio = $producto['precio'];
                                $descuento = $producto['descuento'];
                                $cantidad = $producto['cantidad'];
                                $precio_desc = $precio - (($precio * $descuento) / 100);
                                $subtotal = $cantidad * $precio_desc;
                                $total += $subtotal;
                        ?>
                            <tr>
                                <td>&nbsp;<?php echo $nombre; ?></td>
                                <td><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
                                <td>
                                    <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                </td>
                                <td>
                                    <a href="#" id="eliminar" class="btn btn-outline-danger" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal"><i class="fa-solid fa-trash-can">&nbsp;</i>ELIMINAR</a>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td class="fw-semibol">
                                <b>MONTO A PAGAR:</b> 
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <b><?php echo MONEDA . number_format($total, 2, '.', ',') ?></b>
                            </td>
                        </tr>
                        
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        
            <?php if ($lista_carrito != null) { ?>
                <div class="row py-4">
                    <div class="col-md-5 offset-md-7 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="pagos.views.php" class="btn btn-outline-primary"><i class="fa-solid fa-money-bill">&nbsp;</i>REALIZAR PAGO</a>
                    </div>
                </div>
            <?php } ?>
    </main>

    <!-- Modal ELIMINAR -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fa-solid fa-bell" id="eliminaModalLabel">&nbsp; ELIMINAR</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿DESEA ELIMINAR EL PRODUCTO SELECCIONADO ?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-success" id="btn-eliminar" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" onclick="eliminar()">SI, ELIMINAR</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">NO, CANCELAR</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN ELIMINAR -->

    <footer class="border-top futer">
        <p class="text-center py-2"><img src="../img/logo1.png" alt="logo-footer" width="90" height="30" class="me-4"> &copy; Tienda creada por RFBS23 <a href="#" class="btn btn-light btn-sm"><i class="fas fa-chevron-circle-up fa-2x"></i></a> </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- datatables-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <!--fin-->

    <script>
        //datatable
        $(document).ready(function() {
            $('#mitabla').DataTable({
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }
                ],
                language: {
                    url: '../assets/js/Spanish.json'
                }
            });

        });

        //idioma
        //fin datatables

        //eliminar
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-eliminar')
            buttonElimina.value = id
        })

        //cantidad carrito
        function actualizaCantidad(cantidad, id) {
            let url = '../controllers/carrito.controllers.php';
            let formData = new FormData()
            formData.append('action', 'agregar')
            formData.append('id', id)
            formData.append('cantidad', cantidad)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) //minuscula
                .then(data => {
                    if (data.ok) {
                        let divsubtotal = document.getElementById('subtotal_' + id)
                        divsubtotal.innerHTML = data.sub
                        let total = 0.00
                        let list = document.getElementsByName('subtotal[]')

                        for (let i = 0; i < list.length; i++) {
                            total += parseFloat(list[i].innerHTML.replace(/[S/,]/g, ''))
                        }
                        //si quitamos es-pe podemos tener problemas al momento de que se agrege un producto mas y no reconocer los decimales
                        total = new Intl.NumberFormat('es-PE', {
                            minimumFractionDigits: 2
                        }).format(total)
                        //document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                        location.reload()
                    }
                })
        }

        //eliminar compra
        function eliminar() {
            let botonElimina = document.getElementById('btn-eliminar')
            let id = botonElimina.value

            let url = '../controllers/carrito.controllers.php';
            let formData = new FormData()
            formData.append('action', 'eliminar')
            formData.append('id', id)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) //minuscula
                .then(data => {
                    if (data.ok) {
                        location.reload()
                    }
                })
        }
    </script>
</body>
</html>