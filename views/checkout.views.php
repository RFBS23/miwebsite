<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <script src="https://www.paypal.com/sdk/js?client-id=AZbcnenBsiwEZwmtAo38iI_uOkNZTFRThqGRPGX5dxtXRbj5QebR1omwyBOqi7zI2eUD9f2z6fx8rkcq&currency=USD"></script>
    <!--
        <script src="https://www.paypal.com/sdk/js?client-id=AbuUZS5jutpm70bj0fAwolVM5NpqZsS9CUgqJXWjs4eSHh8yzCUEsLYjh7QYLbPPYnAZO9vbGTWsXlv5&currency=USD"></script>
    -->
</head>
<body>
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },

            createOrder: function(data, action){
                return action.order.create({
                    purchase_units: [{
                        "amount": {
                            "currency_code":"USD","value":100
                            //value: 100
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(detalles) {
                    
                    // Full available details
                    console.log('Capture result',detalles, JSON.stringify(detalles, null, 2));

                    // Show a success message within this page, e.g.
                    const element = document.getElementById('paypal-button-container');
                    element.innerHTML = '';
                    element.innerHTML = '<h3>Thank you for your payment!</h3>';

                    // Or go to another URL:  actions.redirect('thank_you.html');
                    
                });
            },
            onCancel: function(data){
                alert("Pago Cancelado");
                console.log(data)
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>