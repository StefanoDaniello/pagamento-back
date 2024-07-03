<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fake Payment Form</title>
    <script src="https://js.braintreegateway.com/web/dropin/1.30.0/js/dropin.min.js"></script>
    <style>
        .braintree-dropin-wrapper {
            max-width: 400px;
            margin: auto;
        }
        #payment-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="braintree-dropin-wrapper">
        <h2>Fake Payment Form</h2>
        @if (session('success'))
            <div>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div>{{ session('error') }}</div>
        @endif
        <form id="payment-form" action="/payment" method="POST">
            @csrf
            <div id="dropin-container"></div>
            <input type="hidden" id="nonce" name="payment_method_nonce">
            <button type="submit">Paga</button>
        </form>
    </div>
    <script>
        var clientToken = "{{ $clientToken }}";

        braintree.dropin.create({
            authorization: clientToken,
            container: '#dropin-container'
        }, function (createErr, instance) {
            if (createErr) {
                console.error(createErr);
                return;
            }
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.error(err);
                        return;
                    }

                    document.getElementById('nonce').value = payload.nonce;
                    form.submit();
                });
            });
        });
    </script>
</body>
</html>
