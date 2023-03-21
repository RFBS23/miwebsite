<?php
    require 'controllers/config.controllers.php';
    require 'models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();
    $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE estado=1");
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INICIO</title>
    <link rel="icon" href="img/usuario.png">
    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous">
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/scss/inicio.css">
</head>
<body>
    <header>
        <div class="navbar navbar-expand-lg shadow bg-white">
            <div class="container">
                <a href="index.php" class="navbar-brand d-flex align-items-center">
                    <img src="img/logo1.png" alt="logo" width="100%" height="50">
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
                            <a href="#" class="nav-link active">Contactanos</a>
                        </li>
                    </ul>
                    <a href="views/carrito.views.php" class="btn btn-outline-primary position-relative"><i class="fa-solid fa-cart-shopping">&nbsp;</i>
                        carrito
                        <span id="num_cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $num_cart; ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="position-relative overflow-hidden m-md-3 text-center py-2">
            <img src="img/banner.jpg" alt="banner" width="100%" height="400" style="border-radius: 20px">
            <div class="carousel-caption col-md-5 p-lg-5 mx-auto  text-dark">
                <h1 class="display-4 fw-semibold">BIENVENIDOS</h1>
                <p class="lead my-3 fw-semibold">FABRIDEV  esta es una pagina de venta de software donde podras adquirir tus programas a un precio increible</p>
                <button class="btn btn-outline-light border-dark bg-white"><i class="fa-solid fa-cart-shopping" style="color: #FF7F50">&nbsp;</i><a href="#comprarAhora" class="text-success fw-semibold" style="text-decoration: none;">COMPRAR AHORA</a></button>
            </div>
        </div>

        <div class="container py-lg-5" id="comprarAhora">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($resultado as $row) { ?>
                    <div class="col">
                        <div class="card shadow-lg" style="border-radius: 20px;">
                            <center class="py-3"> <!-- hasta poner otra img-->
                                <?php
                                $id = $row['id'];
                                $imagen = "img/proyectos/" . $id . "/imagenes.jpg";
                                if (!file_exists($imagen)) {
                                    $imagen = "img/sinfoto.png"; //img sin foto
                                }
                                ?>
                                <img src="<?php echo $imagen; ?>" alt="imagen1" width="80%" height="250">
                            </center>
                            <div class="card-body">
                                <h5 class="card-title py-1 d-block w-100"><?php echo $row['nombre']; ?></h5>
                                <p class="card-text py-1">$ <?php echo number_format($row['precio'], 2, '.', ','); ?> | <span class="badge bg-success rounded-pill text-end">Con <?php echo $row['descuento']; ?>% Descuento</span></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-outline-primary" type="button" id="liveAlertBtn" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')"><i class="fa-solid fa-cart-plus">&nbsp;</i>Agregar</button>
                                    <div class="btn-group">
                                        <a href="views/detalles.views.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"  class="btn btn-outline-success"><i class="fa-solid fa-circle-info">&nbsp;</i>Detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

    <!--footer-->
    <div class="container">
        <footer class="d-flex flex-wrap">
            <a href="#" class="d-flex">
                <img src="img/perfil.png" alt="logo" width="60" height="60">
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
        <p class="text-center py-2"><img src="img/logo1.png" alt="logo-footer" width="90" height="30" class="me-4"> &copy; Tienda creada por RFBS23 <a href="#" class="btn btn-light btn-sm"><i class="fas fa-chevron-circle-up fa-2x"></i></a> </p>
    </footer>
    <!--fin footer-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        function addProducto(id, token) {
            let url = 'models/carrito.models.php';
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json()) //minuscula
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                        /**/
                        Swal.fire({
                            title: 'EL PRODUCTO FUE AGREGADO CORRECTAMENTE',
                            icon: 'success',
                            backdrop: 'true',
                            timer: 2000,
                            timerProgressBar: 'true',
                            toast: true,
                            position: 'top-end'
                        })
                        return true;
                    }
                })
        }
    </script>
</body>
</html>