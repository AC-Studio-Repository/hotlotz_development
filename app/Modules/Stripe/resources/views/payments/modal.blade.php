<style>
.modal.left .modal-dialog {
    position:fixed;
    right: 0;
    margin: auto;
    width: 720px;
    height: 100%;
    -webkit-transform: translate3d(0%, 0, 0);
    -ms-transform: translate3d(0%, 0, 0);
    -o-transform: translate3d(0%, 0, 0);
    transform: translate3d(0%, 0, 0);
}

.modal.left .modal-content {
    height: 100%;
    overflow-y: auto;
}

.modal.right .modal-body {
    padding: 15px 15px 80px;
}

.modal.right.fade .modal-dialog {
    left: -720px;
    -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
    -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
    -o-transition: opacity 0.3s linear, left 0.3s ease-out;
    transition: opacity 0.3s linear, left 0.3s ease-out;
}

.modal.right.fade.show .modal-dialog {
    right: 0;
}

/* ----- MODAL STYLE ----- */
.modal-content {
    border-radius: 0;
    border: none;
}

</style>

 <div class="modal left fade" id="chargesModal{{ $invoice->id }}" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">&nbsp;</div>

                <div class="row">
                    <div class="col-md-12">
                    @if($payment_methods != null)
                        <h3>Total Amount <span style="float:right;">{{ $total }}</span></h3>
                        <hr>
                        <h5>Payment cards</h5>
                        @foreach($payment_methods as $key => $method)
                       <div class="col-md-12" style="margin-bottom:10px;">
                            <div class="well well-sm border">
                                <div class="media" style="margin-left:10px;margin-top:10px;">
                                    <a class="thumbnail pull-left" href="#">
                                        @php $brand = $method->card->brand @endphp
                                        <img onclick="imagepreview(this)" lazyload="on" class="media-object" src="{{ asset("ecommerce/images/payments/$brand.png") }}" alt="{{ $method->card->brand }}" width="60px" height="50px">
                                    </a>
                                    <div class="media-body" style="padding:0 10px;">
                                        <h4 class="media-heading">{{ ucfirst($method->billing_details->name) ? ucfirst($method->billing_details->name) : 'No Name' }}</h4>
                                        @php
                                            $monthNum  = $method->card->exp_month;
                                            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                            $monthName = $dateObj->format('F');
                                        @endphp

                                        <p><span class="label label-info"> **** {{ $method->card->last4 }}</span><br><span class="label label-warning">Exp - {{ $monthName }} {{ $method->card->exp_year }}</span></p>
                                        <p>
                                             <button type="button" class="btn btn-outline-success" onclick="this.disabled=true;this.value='Sending, please wait...';event.preventDefault(); chargeWithAmount('{!! $method->id !!}', '{!! $invoice->id !!}', '{!! $total !!}')">
                                                Charge with card
                                            </button>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <h5>No payment cards</h5>
                    @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
