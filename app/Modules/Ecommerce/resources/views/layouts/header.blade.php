<nav class="navbar main_menu fixed-top navbar-light bg-white px-0 py-2half" #id="header">
    <div class="container navbar-expand-md">
        <a class="navbar-brand" href="http://localhost:8000/ecommerce/home">
            <img onclick="imagepreview(this)" lazyload="on" src="{!! asset('ecommerce/images/logo/logo.png'); !!}" class="logo img-fluid" alt="Hotlotz logo"
                title="Hotlotz logo">
        </a>
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarCollapse" style="">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active dropdown  px-2half">
                    <a class="nav-link d-flex text-uppercase dropdown-toggle px-0" href="#" data-toggle="dropdown"
                        aria-expanded="false">Auctions<span class="sr-only">(current)</span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item">ForthComing Auctions</li>
                        <li class="dropdown-item">Auction Results</li>
                        <li class="dropdown-item">Past Catalogues</li>
                        <li class="dropdown-item">Useful Information</li>
                    </ul>
                </li>
                <li class="nav-item px-2half">
                    <a class="nav-link d-flex text-uppercase px-0" href="#">MarkectPlace</a>
                </li>
                <li class="nav-item px-2half">
                    <a class="nav-link d-flex text-uppercase px-0" href="#">Services</a>
                </li>
                <li class="nav-item px-2half">
                    <a class="nav-link d-flex text-uppercase px-0" href="#">Discover</a>
                </li>
            </ul>

            <div class="btn-group btn-header align-items-center" role="group" aria-label="Basic example">
                <a href="{{route('sell-with-us')}}" class="btn btn-active text-uppercase my-2 my-sm-0 mx-2half font_md">Sell With Us</a>
                <div class="auth border border-active py-1half px-2half text-uppercase font_md ws-medium mx-2half">
                    <a href="#" class="text_active">Join</a> | <a href="#" class="text_active">Sign In</a>
                </div>
                <!-- <button class="btn btn-outline-active text-uppercase my-2 my-sm-0 mx-2half">Sell With Us</button> -->
                <img onclick="imagepreview(this)" lazyload="on" src="{!! asset('ecommerce/icons/search.svg'); !!}" alt="Search" width="19" height="19"
                    title="Search" class="ml-2half">
            </div>

        </div>
    </div>
</nav>
