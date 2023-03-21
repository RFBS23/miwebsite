<?php
    require '../controllers/config.controllers.php';
    require '../models/conexion.models.php';
    require '../vendor/autoload.php';

    MercadoPago\SDK::setAccessToken(TOKEN_MP);
    $preference = new MercadoPago\Preference();
    $productos_mp = array();

    $db = new Conexion();
    $con = $db->Conectar();

    $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

    $lista_carrito = array();

    if ($productos != null) {
        foreach ($productos as $clave => $cantidad) {
            $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND estado=1");
            $sql->execute([$clave]);
            $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        header("Location: ../index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAGO</title>
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
        <div class="row py-5">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="container py-lg-5">
                    <div class="table-responsive py-1">
                        <table class="table table-bordered table-light display responsive nowrap row-border order-column" id="mitabla" style="width:100%;">
                            <thead class="border-danger table-success">
                                <tr>
                                    <th>PRODUCTO</th>
                                    <th>SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php if ($lista_carrito == null) {
                                    echo '<tr><td colspan="5" class="text-center"><b>LISTA VACIA</b></td></tr>';
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

                                        //mercado pago
                                        $item = new MercadoPago\Item();
                                        $item->id = $_id;
                                        $item->title = $nombre;
                                        $item->quantity = $cantidad;
                                        $item->unit_price = $precio_desc;
                                        $item->currency_id = "PEN";

                                        array_push($productos_mp, $item);
                                        unset($item); //destruimos las variables especificadas para que no se pueda generar  otro item existente
                                ?>
                                        <tr>
                                            <td>&nbsp;<?php echo $nombre; ?></td>
                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td>MONTO A PAGAR:</td>
                                        <td><?php echo MONEDA . number_format($total, 2, '.', ',') ?></td>
                                    </tr>
                            </tbody>
                        <?php } ?>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 py-4">
                <center>
                    <h4>METODO DE PAGO</h4>
                </center>
                <div class="radio_tabs text-center mb-3">
                    <label class="radiobutton" data-radio="radio1">
                        <input type="radio" name="sports" class="input" checked>
                        <span class="radio_mark me-4">PLATAFORMAS</span>
                    </label>
                    <label class="radiobutton" data-radio="radio2">
                        <input type="radio" name="sports" class="input">
                        <span class="radio_mark">PAGO CON ENTREGA</span>
                    </label>
                </div>

                <div class="content py-3">
                    <div class="accordion radio_content radio1" id="accordionExample">
                        <div class="accordion-item shadow" style="overflow: hidden">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">PAYPAL</button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div id="paypal-button-container"></div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow">
                            <h2 class="accordion-header accordion-header-success" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">MERCADO PAGO</button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <center>
                                            <div class="checkout-btn"></div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">YAPE</button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <img src="../img/yape.png" class="rounded float-end" alt="QR">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow">
                            <h2 class="accordion-header" id="headingfour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">PLIN</button>
                            </h2>
                            <div id="collapsefour" class="accordion-collapse collapse" aria-labelledby="headingfour" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <img src="../img/plin.png" class="rounded float-end" alt="QR">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow">
                            <h2 class="accordion-header" id="headingfive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">AGORA</button>
                            </h2>
                            <div id="collapsefive" class="accordion-collapse collapse" aria-labelledby="headingfive" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <img src="../img/agora.png" class="rounded float-end" alt="QR">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion radio_content radio2">
                        <form class="row g-3">
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">Email</label>
                                <input type="email" class="form-control" id="inputEmail4">
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Password</label>
                                <input type="password" class="form-control" id="inputPassword4">
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                            </div>
                            <div class="col-12">
                                <label for="inputAddress2" class="form-label">Address 2</label>
                                <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                            </div>
                            <div class="col-md-6">
                                <label for="inputCity" class="form-label">City</label>
                                <input type="text" class="form-control" id="inputCity">
                            </div>
                            <div class="col-md-4">
                                <label for="inputState" class="form-label">elegir</label>
                                <select id="inputState" class="form-select">
                                    <option selected>Choose...</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="inputZip" class="form-label">Zip</label>
                                <input type="text" class="form-control" id="inputZip">
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck">
                                    <label class="form-check-label" for="gridCheck">
                                        Check me out
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--footer-->
    <footer class="border-top futer">
        <p class="text-center py-2"><img src="../img/logo1.png" alt="logo-footer" width="90" height="30" class="me-4"> &copy; Tienda creada por RFBS23 <a href="#" class="btn btn-light btn-sm"><i class="fas fa-chevron-circle-up fa-2x"></i></a> </p>
    </footer>
    <!--fin footer-->

    <?php
        $preference->items = $productos_mp;
        $preference->back_urls = array(
            "success" => "http://localhost/miwebsite/controllers/capmp.controllers.php",
            "failure" => "http://localhost/miwebsite/controllers/fallomp.controllers.php"
        );
        $preference->auto_return = "approved";
        $preference->binary_mode = true;
        $preference->save();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
    <!-- datatables-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <!--fin-->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        $(".content .radio_content").hide();
        $(".content .radio_content:first-child").show();

        /* when any radio element is clicked, Get the attribute value of that clicked radio element and show the radio_content div element which matches the attribute value and hide the remaining tab content div elements */
        $(".radiobutton").click(function(){
            var current_raido = $(this).attr("data-radio");
            $(".content .radio_content").hide();
            $("."+current_raido).show();
        })

        //datatable
        $(document).ready(function() {
            $('#mitabla').DataTable({
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

        //btn paypal
        paypal.Buttons({
            style: {
                label: 'pay'
            },
            createOrder: function(data, action) {
                return action.order.create({
                    purchase_units: [{
                        "amount": {
                            value: <?php echo $total; ?>
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                let URL = '../controllers/captura.controllers.php'
                let timerInterval

                Swal.fire({
                    title: 'Estamos procesando el detalle de su compra',
                    //html: 'I will close in <b></b> milliseconds.',
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                })

                actions.order.capture().then(function(detalles) {

                    console.log(detalles)
                    let url = '../controllers/captura.controllers.php'

                    return fetch(url, {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function(response) {
                        window.location.href = "completado.views.php?key=" + detalles['id'];
                    })
                });
            },
            onCancel: function(data) {
                alert("Pago Cancelado");
                console.log(data)
            }
        }).render('#paypal-button-container');

        //btn mercado pago(mp)
        const mp = new MercadoPago('TEST-ead8774d-2e93-40da-8c54-e8cb712b4ef9', {
            locale: 'es-PE'
        });
        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render: {
                container: '.checkout-btn',
                label: 'Pagar Con Mercado Pago'
            }
        })
    </script>
</body>
</html>