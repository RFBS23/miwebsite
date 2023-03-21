<?php
    require '../vendor/autoload.php';
    MercadoPago\SDK::setAccessToken('TEST-1532524805791238-030818-f2c9a199af885abdd2a766dd4d117dd7-1100679250');
    
    // Crea un objeto de preferencia
    $preference = new MercadoPago\Preference();

    // Crea un ítem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = 'Mi producto';
    $item->quantity = 1;
    $item->unit_price = 75.56;
    $preference->items = array($item);
    $preference->back_urls = array(
        "success" => "http://localhost/miwebsite/controllers/capmp.controllers.php",
        "failure" => "http://localhost/miwebsite/controllers/fallomp.controllers.php"
    );
    $preference->auto_return = "approved";
    $preference->binary_mode = true;
    
    $preference->save();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MERCADO PAGO</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <h3>Mercado Pago</h3>
    <div class="checkout-btn"></div>
    
    <script>
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