<?php
    require '../controllers/config.controllers.php';
    require '../models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();

    $id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';
    $error = '';
    if ($id_transaccion == '') {
        $error = 'ERROR AL PROCESAR SU COMPRA';
    } else {
        $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
        $sql->execute([$id_transaccion, 'COMPLETED']);

        if ($sql->fetchColumn() > 0) {

            $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
            $sql->execute([$id_transaccion, 'COMPLETED']);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $idcompra = $row['id'];
            $total = $row['total'];
            $fecha = $row['fecha'];
            $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra=?");
            $sqlDet->execute([$idcompra]);
        } else {
            $error = 'ERROR AL COMPROBAR LA COMPRA';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPRA COMPLETADA</title>
    <link rel="icon" href="../img/usuario.png">
    <!--FONTAWESOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/scss/inicio.css">
    <!-- datatables-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <!--botones-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">
    <!--fin-->
</head>
<body style="background: #ECEAE9">
    <header>
        <div class="navbar navbar-expand-lg shadow-lg bg-white">
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

    <main class="container py-5">
        <div class="container">
            <?php if (strlen($error) > 0) { ?>
                <div class="row">
                    <div class="col">
                        <h4><?php echo $error; ?></h4>
                    </div>
                </div>
            <?php } else { ?>

                <div class="row">
                    <div class="col">
                        <!--
                        <b>DETALLE DE TU COMPRA: </b><?php echo $id_transaccion; ?> &nbsp;
                        <br>
                        <b>FECHA Y HORA REALIZADA: </b><?php echo $fecha; ?> &nbsp;
                        <br>
                        <b>MONTO TOTAL DE SU COMPRA:</b><?php echo MONEDA . number_format($total, 2, '.', ','); ?> &nbsp; <br>
                        <br>-->
                    </div>
                </div>

                <div class="row">
                    <div class="col table-responsive py-4">
                        <table class="table display responsive nowrap row-border order-column" id="mitabla" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">DATOS</th>
                                    <th class="text-center" data-priority="1">PRODUCTO</th>
                                    <th class="text-center">PRECIO UNITARIO</th>
                                    <th class="text-center">CANTIDAD</th>
                                    <th class="text-center">IMPORTE TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                    $importe = $row_det['precio'] * $row_det['cantidad']; ?>
                                    <tr>
                                        <td class="fw-semibold text-center">FECHA Y HORA: <?php echo $fecha; ?> <br><br> COMPROBANTE: <?php echo $id_transaccion; ?></td>
                                        <td class="fw-semibold"><br><?php echo $row_det['nombre']; ?></td>
                                        <td class="fw-semibold text-center"><br><?php echo $row_det['precio']; ?></td>
                                        <td class="fw-semibold text-center"><br><?php echo $row_det['cantidad']; ?></td>
                                        <td class="fw-semibold text-center"><br><?php echo $importe; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <!--footer-->
    <div class="container">
        <footer class="d-flex flex-wrap">
            <a href="#" class="d-flex">
                <img src="../img/perfil.png" alt="logo" width="60" height="60">
            </a>
            <div class="col mb-3"> </div>
            <div class="col mb-3 text-center">
                <button class="btn btn-outline-primary my-3" data-bs-toggle="modal" data-bs-target="#exampleModal">¿NECESITAS AYUDA? <i class="fa-solid fa-headset"></i></button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">¿Necesitas ayuda?</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="text-align: justify">
                                <b>Soporte de FabriDev</b> solucionará dudas como: “la página no carga”, “no aparece un producto”, “el carrito no muestra mis productos”, etc.
                            </div>
                            <div class="modal-footer">
                                <!-- <button class="btn btn-success fs-6 col-6 m-0" type="button"><i class="fa-brands fa-whatsapp fa-2x">&nbsp;</i>Contactar Vendedor</button> -->
                                <a href="https://wa.me/51933103242?text=Hola%20necesito%20ayuda%20por%20que%20tengo%20problemas%20en"  class="btn btn-outline-success">Soporte de FabriDev</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal -->
                <br>
                <a href="https://www.facebook.com/fabrizio.barriossaavedra.3/" class="me-3 text-decoration-none" style="color: blue"><i class="fa-brands fa-facebook fa-2x"></i></a>
                <a href="https://www.instagram.com/fabrizio_barrios18/" class="me-3 text-decoration-none" style="color: deeppink;"><i class="fa-brands fa-instagram fa-2x"></i></a>
                <a href="https://twitter.com/fabrizi48865577" class="me-3 text-decoration-none" style="color: skyblue"><i class="fa-brands fa-twitter fa-2x"></i></a>
                <a href="https://wa.me/51933103242?text=Hola%20" class="me-3 text-decoration-none" style="color: green"><i class="fa-brands fa-whatsapp fa-2x"></i></a>
            </div>
            <ul class="col-md-4" style="text-align: justify;">
                Al crear una cuenta, aceptas nuestros <a href="#" style="color: black;">TERMINOS Y CONDICIONES</a> y las normas de <a href="#" style="color: black;">POLITICAS DE TRATAMIENTO DE DATOS</a>
                <br>
                FabriDev no se hace responsable por los productos comercializados ni el servicio prestado.
            </ul>
        </footer>
    </div>
    <footer class="border-top">
        <p class="text-center py-2"><img src="../img/logo1.png" alt="logo-footer" width="90" height="30" class="me-4"> &copy; Tienda creada por RFBS23 <a href="#" class="btn btn-light btn-sm"><i class="fas fa-chevron-circle-up fa-2x"></i></a> </p>
    </footer>
    <!--fin footer-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- datatables-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <!--js de botones-->
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
    <!--botones de prubea-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#mitabla').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdf',
                        text: '<i class="fa-solid fa-file-pdf"></i> Exportar a PDF',
                        titleAttr: 'Exportar a PDF',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print"></i> Imprimir',
                        titleAttr: 'Desea Imprimir',
                        className: 'btn btn-info'
                    }
                ],
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                }],
                language: {
                    url: '../assets/js/Spanish.json'
                }
            });
        });
        //fin datatables
    </script>
</body>
</html>