<div class="row">
    @for ($i = 0; $i < 4; $i++)
    <div class="col-md-3">
        <div class="card" style="width: 100%">

        <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <img
                src="{{ asset('images/default.jpg') }}"
                class="card-img-top"
                alt="..."
            />
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Cras justo odio <span class="float-right"> <h4>111 </h4></span></li>
            <li class="list-group-item">Dapibus ac facilisis in <span class="float-right"><h4>111 </h4></span></li>
            <li class="list-group-item">Vestibulum at eros <span class="float-right"> <h4>111 </h4></span></li>
        </ul>

        </div>
    </div>
    @endfor
</div>
