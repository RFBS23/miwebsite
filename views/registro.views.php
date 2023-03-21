<?php
    require '../controllers/config.controllers.php';
    require '../controllers/personas.controllers.php';
    require '../models/conexion.models.php';
    $db = new Conexion();
    $con = $db->Conectar();
    $errors = [];
    if(!empty($_POST)) {
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $dni = trim($_POST['dni']);
        $usuario = trim($_POST['usuario']);
        $claveAcceso = trim($_POST['claveAcceso']);
        $reclaveAcceso = trim($_POST['reclaveAcceso']);

        if (esNulo([$nombre, $apellidos, $email, $telefono, $dni, $usuario, $claveAcceso, $reclaveAcceso])){
            $errors[] = "Debe llenar todos los campos";
        }
        if (!esEmail($email)){
            $errors[] = "La dirección de correo no es valida";
        }
        if(!validarPassword($claveAcceso, $reclaveAcceso)) {
            $errors[] = "Las contraseñas no coinciden";
        }
        if(usuarioExistente($usuario, $con)){
            $errors[] = "El usuario $usuario ya se encuentra registrado";
        }
        if(emailExistente($email, $con)){
            $errors[] = "El correo $email ya se encuentra registrado";
        }

        if(count($errors) == 0) {
            $id = registrarPersonas([$nombre, $apellidos, $email, $telefono, $dni], $con);
            if($id > 0) {
                $pass_hash = password_hash($claveAcceso, PASSWORD_DEFAULT);
                $token = generarToken();
                if(!registrarUsuarios([$usuario, $pass_hash, $token, $id], $con)){
                    $errors[] = "Error al registrar el usuario";
                }
            } else {
                $errors[] = "Error al registrar cliente";
            }
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REGISTRO</title>
    <link rel="icon" href="../img/usuario.png">
    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous">
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/scss/inicio.css">
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
        <div class="col">
            <form class="row g-3 py-4" action="registro.views.php" method="post">
                <h2>DATOS DEL CLIENTES</h2>
                <?php mostrarMensaje($errors); ?>
                <div class="col-md-6">
                    <label for="nombre" class="form-label">NOMBRE</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="tu nombre" onkeypress="return SoloLetras(event);">
                </div>
                <div class="col-md-6">
                    <label for="apellidos" class="form-label">APELLIDOS</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="tu apellido" onkeypress="return SoloLetras(event);">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">CORREO ELECTRONICO</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="correo@algo.com">
                    <span id="validaEmail" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="telefono" class="form-label">TELEFONO</label>
                    <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="telefono" onkeypress="return SoloNumeros(event);" maxlength="9">
                </div>
                <div class="col-md-6">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" name="dni" id="dni" class="form-control" placeholder="dni" maxlength="8" onkeypress="return SoloNumeros(event);">
                </div>
                <div class="col-md-6">
                    <label for="usuario" class="form-label">USUARIOS</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="usuario">
                    <span id="validaUsuario" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="claveAcceso" class="form-label">CONTRASEÑA</label>
                    <input type="password" name="claveAcceso" id="claveAcceso" class="form-control" placeholder="contraseña">
                </div>
                <div class="col-md-6">
                    <label for="reclaveAcceso" class="form-label">REPETIR CONTRASEÑA</label>
                    <input type="password" name="reclaveAcceso" id="reclaveAcceso" class="form-control" placeholder="repetir contraseña">
                </div>
                <i><b>NOTA:</b> Los campos son obligatorios</i>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Registrar <i class="fa-solid fa-right-to-bracket"></i></button>
                </div>
            </form>
        </div>
    </main>
    <!--footer-->
    <footer class="border-top futer">
        <p class="text-center py-2"><img src="../img/logo1.png" alt="logo-footer" width="90" height="30" class="me-4"> &copy; Tienda creada por RFBS23 <a href="#" class="btn btn-light btn-sm"><i class="fas fa-chevron-circle-up fa-2x"></i></a> </p>
    </footer>
    <!--fin footer-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        //solo números
        function SoloNumeros(evt){
            if(window.event) {
                keynum = evt.keyCode;
            }
            else {
                keynum = evt.which;
            }
            if((keynum > 47 && keynum < 58) || keynum == 8 || keynum== 13) {
                return true;
            }
            else  {
                Swal.fire({
                    icon: 'error',
                    title: 'Ingresar Solo Números'
                })
                //alert("Ingresar solo numeros");
                return false;
            }
        }

        //solo numeros
        function SoloLetras(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toString();
            letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnopqrstuvwxyzáéíóú ";
            especiales = [8,13];
            tecla_especial = false
            for(var i in especiales) {
                if(key == especiales[i]) {
                    tecla_especial = true;
                    break;
                }
            }
            if(letras.indexOf(tecla) == -1 && !tecla_especial) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ingresar Solo Letras'
                })
                return false;
            }
        }

        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur", function (){
            usuarioExistente(txtUsuario.value)
        }, false)
        function  usuarioExistente(usuario){
            let url = "../models/personas.models.php"
            let formData = new FormData()
            formData.append("action", "usuarioExistente")
            formData.append("usuario", usuario)
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if(data.ok){
                        document.getElementById('usuario').value = ''
                        document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible <i class="fa-solid fa-face-sad-tear"></i>'
                    } else {
                        document.getElementById('validaUsuario').innerHTML = ''
                    }
                })
        }
        //email
        let txtEmail= document.getElementById('email')
        txtEmail.addEventListener("blur", function (){
            emailExistente(txtEmail.value)
        }, false)
        function  emailExistente(email){
            let url = "../models/personas.models.php"
            let formData = new FormData()
            formData.append("action", "emailExistente")
            formData.append("email", email)
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if(data.ok){
                        document.getElementById('email').value = ''
                        document.getElementById('validaEmail').innerHTML = 'Email no disponible <i class="fa-solid fa-face-sad-tear"></i>'
                    } else {
                        document.getElementById('validaEmail').innerHTML = ''
                    }
                })
        }

    </script>
</body>
</html>