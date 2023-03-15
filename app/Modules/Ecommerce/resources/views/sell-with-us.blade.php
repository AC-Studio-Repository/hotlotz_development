@extends('ecommerce::layouts.master')

@push('styles')
<style>

</style>
@endpush

@section('content')
<!-- Sub Menu -->
<nav class="navbar navbar_submenu navbar-light bg-white px-0 py-3 mt-7" id="header">
    <div class="container navbar-expand-md px-0">
        <ul class="sub_menu ws-medium text-uppercase list-unstyled mb-0">
            <li class="border-0 mr-0 list-inline-item gray_600">Home</li>
            <li class="border-0 mr-0 list-inline-item gray_600">Sell With Us</li>
        </ul>
    </div>
</nav>
<!-- End of Sub Menu -->

<!-- Banner -->
<div class="container-fluid px-0">
    <div class="sell_banner position-relative">
        <img onclick="imagepreview(this)" lazyload="on" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
            class="sell_banner_img d-block w-100"
            style="background-image: url({!! asset('ecommerce/images/sell-with-us/banner.png'); !!});">
        <p class="sell_banner_caption pt-italic position-absolute">
            Cartier - A Tanzanite & Diamond Ring. Sold for $6,000 SGD inc. Buyer’s Premium
        </p>
    </div>
</div>
<!-- End of Banner -->

<!-- Gray section -->
<div class="bg-flat pt-5 pb-custom_2">
    <div class="container">
        <h3 class="ws-black font_32 text_text">The Smart Way to Sell</h3>
        <p class="pt-2">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mauris lectus, tincidunt in lectus et,
            ultrices tempor dui. Integer quam sapien, ultrices eu cursus ut, ultrices nec augue. Proin nec turpis ut
            sapien congue ultrices quis ut tellus. Suspendisse potenti. Ut eget elit est. Quisque nec lacinia leo.
            Maecenas ut semper metus, nec facilisis dui. Nullam gravida tortor sed elit vestibulum, ut ultricies magna
            fringilla. <br><br>

            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse mauris lectus, tincidunt in
            lectus et, ultrices tempor dui. Integer quam sapien, ultrices eu cursus ut, ultrices nec augue. Proin nec
            turpis ut sapien congue ultrices quis ut tellus. Suspendisse potenti. Ut eget elit est. Quisque nec lacinia
            leo. Maecenas ut semper metus, nec facilisis dui. Nullam gravida tortor sed elit vestibulum, ut ultricies
            magna fringilla.
        </p>

        <div class="request mt-5">
            <h4 class="pt-bold text_text font_24">Requesting an Auction Estimate is Easy</h4>
            <div class="row mt-3">
                <div class="col-sm">
                    <div class="card mb-3 bg-transparent border-0" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-md-2 d-flex align-items-center">
                                <i class="demo-icon icon-contact font_56 text_active"></i>
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h5 class="card-title ws-extra-bold text-uppercase font_16 mb-1half">Sign In</h5>
                                    <p class="card-text ws-regular">
                                        Joining is free and quick
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card mb-3 bg-transparent border-0" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-md-2 d-flex align-items-center">
                                <i class="demo-icon icon-document font_56 text_active"></i>
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h5 class="card-title ws-extra-bold text-uppercase font_16 mb-1half">Provide
                                        Information</h5>
                                    <p class="card-text ws-regular">
                                        Dimensions, materials & history
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card mb-3 bg-transparent border-0" style="max-width: 540px;">
                        <div class="row no-gutters">
                            <div class="col-md-2 d-flex align-items-center">
                                <i class="demo-icon icon-camera text_active" style="font-size: 2.5rem"></i>
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h5 class="card-title ws-extra-bold text-uppercase font_16 mb-1half">Submit
                                        Photograph</h5>
                                    <p class="card-text ws-regular">
                                        Clear front and back colour images
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Gray section -->

<!-- Form -->
<div class="container mt-5">
    <h3 class="pt-bold font_24 mb-custom_2">Get Started</h3>
    <form class="form_of_sell mt-2">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlInput1" class="control-label mb-0">First Name *</label>
                    <input type="email" class="form-control border_less" id="exampleFormControlInput1"
                        placeholder="Enter your first name">
                </div>
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlInput1" class="control-label mb-0">Last Name *</label>
                    <input type="email" class="form-control border_less" id="exampleFormControlInput1"
                        placeholder="Enter your last name">
                </div>
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlInput1" class="control-label mb-0">Email Address *</label>
                    <input type="email" class="form-control border_less" id="exampleFormControlInput1"
                        placeholder="example@email.com">
                </div>
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlInput1" class="control-label mb-0">Title *</label>
                    <input type="email" class="form-control border_less" id="exampleFormControlInput1"
                        placeholder="e.g. A Rolex Oyster Perpetual Men’s Watch">
                </div>
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlSelect2" class="control-label">Category</label>
                    <select class="form-control border_less" id="exampleFormControlSelect2">
                        <option>Art</option>
                        <option>Art</option>
                        <option>Art</option>
                        <option>Art</option>
                        <option>Art</option>
                    </select>
                </div>
                <div class="form-group mb-custom_2">
                    <label for="exampleFormControlSelect2" class="control-label">Where is your neares</label>
                    <select class="form-control border_less" id="exampleFormControlSelect2">
                        <option>Singapore</option>
                        <option>Myanmar</option>
                        <option>Thailand</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="control-label">Example textarea</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="10"
                        placeholder="e.g. Lorem Ipsum Dol"></textarea>
                </div>
                <p>
                    By clicking submit you consent to the use of your personal information for the purposes described in the <span class="text_active">privacy policy</span>, and you agree to the <span class="text_active">conditions</span> for website use and to the <span class="text_active">Important Information</span> outlined below.
                </p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1" class="control-label">Images</label>
                    <div class="d-flex flex-column align-items-center text-uppercase border border-dashed pt-5">
                        <i class="demo-icon icon-upload font_32"></i>
                        <p class="ws-medium font_18 text_text mb-0">Drag & Drop Image Here</p>
                        <span class="ws-medium font_12 text_text_light_sm">Or</span>
                        <!-- <input type="file">Browse File -->
                        <button class="btn btn-outline-primary btn-inline">Browse File</button>
                        <p class="ws-medium pt-6 text_glabel font_14">Size Limit Per File is 10MB. JPEG, PNG & PDF FILES ONLY
                        </p>
                    </div>
                </div>

                <div class="media align-items-center mt-4">
                    <img onclick="imagepreview(this)" lazyload="on" src="{!! asset('ecommerce/images/sell-with-us/sell-item.png') !!}" class="align-self-center mr-3" alt="..." width="80">
                    <div class="media-body font_16">
                        <h5 class="mt-0 font_16">hotlotzs 2019-07-24 full day17234.jpeg</h5>
                        <p class="mb-0 text_glabel">3.56 MB</p>
                    </div>
                    <i class="align-self-center icon-demo icon-cancel-circled-outline text_active font_28"></i>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End of Form -->

@endsection

@push('scripts')
<script>
    $('.main_carousel').carousel({
        interval: 50000,
        full_width: true,
        pause: true
    })
    $('.carousel').carousel({
        interval: 50000,
        full_width: true
    })
    $('.carousel_testimonial').carousel({
        interval: 50000,
        full_width: true
    })

</script>
@endpush
