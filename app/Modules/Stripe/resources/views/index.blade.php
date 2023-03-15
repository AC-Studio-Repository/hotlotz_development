<html>
  <head>
    <title>Hotlotz - Payment Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>

    .bd-example-modal-lg .modal-dialog{
        display: table;
        position: relative;
        margin: 0 auto;
        top: calc(50% - 24px);
    }

    .bd-example-modal-lg .modal-dialog .modal-content{
        background-color: transparent;
        border: none;
    }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>

  </head>
  <body>

    <div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="width: 48px">
                <span class="fa fa-spinner fa-spin fa-3x"></span>
            </div>
        </div>
    </div>
    <script type = "text/javascript">
        let payload = @json($data);
        let stripeKey = '{!! setting('services.stripe.key') !!}';
        var stripe = Stripe(stripeKey);
        function modal(){
            $('.modal').modal('show');
            setTimeout(function () {
                $('.modal').modal('hide');
            }, 3000);
        }
        modal();
        document.addEventListener("DOMContentLoaded", function(event) {
            fetch('/stripe/checkout', {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest"
                    },
                method: 'post',
                credentials: "same-origin",
                body: JSON.stringify(payload)
            })
            .then((response) => {
                return response.json();

            })
            .then(function (session) {
                if(session.status == 200){
                    return stripe.redirectToCheckout({
                        sessionId: session.id
                    });
                }else{
                    window.location.href = session.url;
                }

            })
            .catch(function(error) {
            });
        });

    </script>
  </body>
</html>