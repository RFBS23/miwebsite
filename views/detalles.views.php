<?php
    require '../controllers/config.controllers.php';
    require '../models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $token = isset($_GET['token']) ? $_GET['token'] : '';

    if ($id == '' || $token == '') {
        echo 'Error en la peticion';
        exit;
    } else {
        $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

        if ($token == $token_tmp) {

            $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND estado=1");
            $sql->execute([$id]);

            if ($sql->fetchColumn() > 0) {

                $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND estado=1 LIMIT 1");
                $sql->execute([$id]);
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                $nombre = $row['nombre'];
                $descripcion = $row['descripcion'];
                $precio = $row['precio'];
                $descuento = $row['descuento'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                /* imagenes */
                $dir_images = '../img/proyectos/' . $id . '/';
                $rutaImg = $dir_images . 'imagenes.jpg';
                if (!file_exists($rutaImg)) {
                    $rutaImg = '../img/sinfoto.png'; //img sin foto
                }
                $imagenes = array();
                if (file_exists($dir_images)) {
                    $dir = dir($dir_images);
                    while (($archivo = $dir->read()) != false) {
                        if ($archivo != 'imagenes.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                            $imagenes[] = $dir_images . $archivo;
                        }
                    }
                    $dir->close();
                }
                $sqlCaracter = $con->prepare("SELECT DISTINCT(det.id_caracteristicas) AS idCaract, caract.caracteristicas FROM caract_productos AS det INNER JOIN caracteristicas AS caract ON det.id_caracteristicas=caract.id WHERE det.id_productos=?");
                $sqlCaracter->execute([$id]);
            } else {
                echo 'error otra vez';
                exit;
            }
        } else {
            echo 'error otra vez papurri';
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DETALLES</title>
    <link rel="icon" href="../img/usuario.png">
    <!--FONTAWESOME-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/scss/inicio.css">
</head>
<body>
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

    <main class="container">
        <div class="container py-lg-5">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <div id="sliderImg" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner shadow-lg" style="border-radius: 30px;">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="<?php echo $rutaImg; ?>" alt="imagen"><!-- width="95%" height="500"-->
                            </div>

                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item">
                                    <img class="d-block w-100" src="<?php echo $img; ?>" alt="img carrusel">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#sliderImg" data-bs-slide="prev">
                            <span class="fa-solid fa-backward fa-2xl" aria-hidden="true" style="color: #000;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#sliderImg" data-bs-slide="next">
                            <span class="fa-solid fa-forward fa-2xl" aria-hidden="true" style="color: #000;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 order-md-1 px-5">
                    <div class="row">
                        <h3 class="py-3"><?php echo $nombre; ?></h3>

                        <?php if ($descuento > 0) { ?>
                            <p>Antes &nbsp;<del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                            <h4 class="py-2">AHORA
                                <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                                <small class="text-success"><?php echo $descuento; ?>% descuento</small>
                            </h4>

                        <?php } else { ?>
                            <h4 class="py-2"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h4>
                        <?php } ?>

                        <div class="card card-header">
                            <div class="modal-header justify-content-center">
                                <h5 class="modal-title">DESCRIPCION</h5>
                            </div>
                            <div class="modal-body">
                                <div class="lead" style="text-align: justify; font-size: 15px;"><?php echo $row['descripcion']; ?></div>
                            </div>
                        </div>

                        <div class="col-3 my-3">
                            <?php
                                while($row_caract = $sqlCaracter->fetch(PDO::FETCH_ASSOC)){
                                    $idCaract = $row_caract['idCaract'];
                                    echo $row_caract['caracteristicas'] . ": ";
                                    echo  "<select class='form-select' id='caract_$idCaract'>";
                                    $sqlDet = $con->prepare("SELECT id, valor, stock FROM caract_productos WHERE id_productos=? AND id_caracteristicas=?");
                                    $sqlDet->execute([$id, $idCaract]);
                                    while($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)){
                                        echo "<option id=' " .$row_det['id'] . " '>" .$row_det['valor']. "</option>";
                                    }
                                    echo  "</select>";
                                }
                            ?>
                        </div>
                        <div class="col-3 my-3">
                            CANTIDAD: <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                        </div>

                        <div class="d-grid gap-3 col-10 mx-auto py-4 px-5">
                            <a href="pagos.views.php" class="btn btn-outline-primary py-3" type="button" style="border-radius: 10px;"><i class="fa-solid fa-bag-shopping">&nbsp;</i>Comprar Ahora</a>
                            <button class="btn btn-outline-success py-3" id="btnAgregar" type="button" style="border-radius: 10px;" onclick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')"><i class="fa-solid fa-cart-plus">&nbsp;</i>Agregar al Carrito</button>
                        </div>
                    </div>

                </div>
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
   
    <script>

        function addProducto(id, cantidad, token) {
            let url = '../models/carrito.models.php';
            let formData = new FormData()
            formData.append('id', id)
            formData.append('cantidad', cantidad)
            formData.append('token', token)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())//minuscula
            .then(data => {
                if(data.ok){
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero

                    Swal.fire({
                            title: 'EL PRODUCTO FUE AGREGADO CORRECTAMENTE',
                            icon: 'success',
                            backdrop: 'true',
                            timer: 2000,
                            timerProgressBar: 'true',
                            toast: true,
                            position: 'top-end'
                        })
                        //alert ("hola");
                        return true;
                }
            })
        }
    </script>
</body>
</html>