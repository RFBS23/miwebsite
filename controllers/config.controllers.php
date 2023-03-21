<?php
    define("CLIENT_ID", "AZbcnenBsiwEZwmtAo38iI_uOkNZTFRThqGRPGX5dxtXRbj5QebR1omwyBOqi7zI2eUD9f2z6fx8rkcq");
    define("TOKEN_MP", "TEST-1532524805791238-030818-f2c9a199af885abdd2a766dd4d117dd7-1100679250");
    define("CURRENCY", "USD");
    define("KEY_TOKEN", "ABCabc-123");
    define("MONEDA", "S/");

    session_start();
    
    $num_cart = 0;
    if(isset($_SESSION['carrito']['productos'])){
        $num_cart = count($_SESSION['carrito']['productos']);
    }
?>