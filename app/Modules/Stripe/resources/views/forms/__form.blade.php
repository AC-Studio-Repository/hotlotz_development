<div class="stripe-form">
    <fieldsets>
        <div class="row">
            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="cardholder-name" class="control-label mb-0 pt-2half px-2half text_text">Name on Card</label>
                    <input type="text" class="form-control border-0" id="cardholder-name"
                        placeholder="Enter here"><!-- Name on Card -->
                </div>
            </div>
            <div class="w-100"></div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Card Number</label>
                    <div id="card-number" class="form-group empty border-0 px-2half"></div>
                    <!-- Card Number -->
                </div>
            </div>

            <div class="w-100"></div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Expiry (MM/YY)</label>
                    <div id="card-expiry" class="form-group empty border-0 px-2half"></div><!-- Expiry Date -->
                </div>
            </div>
            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Security Code</label>
                        <div id="card-cvc" class="form-group empty border-0 px-2half"></div><!-- Security Code -->
                </div>
            </div>
        </div>

        <div class="error" role="alert">
        <span style="color:red;" class="message"></span></div>

        <h3 class="font_18 js-bold pt-3">Billing Address</h3>
        <div class="row">
            <div class="w-100"></div>
            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1"
                        class="control-label mb-0 pt-2half px-2half text_text">Country</label>
                        {{ Form::select('cardholder-country', $stripeCountries, 'SG', [
                            'id' => 'cardholder-country', 'class' => 'selectpicker select2 form-control form-control-md border-0'
                            ])
                        }}
                    <!-- Country on Card -->
                </div>
            </div>

            <div class="w-100"></div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Address Line 1</label>
                    <input type="text" class="form-control border-0" id="cardholder-line1" placeholder="Enter here">
                    <!-- Line 1 on Card -->
                </div>
            </div>

            <div class="w-100"></div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Address Line 2</label>
                    <input type="text" class="form-control border-0" id="cardholder-line2" placeholder="Enter here">
                    <!-- Line 2 on Card-->
                </div>
            </div>

            <div class="w-100"></div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1"
                        class="control-label mb-0 pt-2half px-2half text_text">City</label>
                    <input type="text" class="form-control border-0" id="cardholder-city" placeholder="Enter here">
                    <!-- City on Card -->
                </div>
            </div>

            <div class="col">
                <div class="form-group mb-custom_2 border">
                    <label for="exampleFormControlInput1" class="control-label mb-0 pt-2half px-2half text_text">Post
                        Code</label>
                    <input type="text" class="form-control border-0" id="cardholder-post-code" placeholder="Enter here">
                    <!-- Post Code on Card -->
                </div>
            </div>
        </div>
    </fieldsets>
</div>