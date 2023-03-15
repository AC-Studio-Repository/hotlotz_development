<div class="row">
    <div class="form-group col-md-12">
        <a href="{{ route('auction.auctions.generateLabel',$auction) }}" target="_bank" class="btn btn-outline-warning">{{ __('Generate Label') }}</a>

        <a href="{{ route('auction.auctions.generateCatelogue',$auction) }}" target="_bank" class="btn btn-outline-success">{{ __('Generate Catalogue') }}</a>

        <a href="{{ route('auction.auctions.generateKycReport',$auction) }}" target="_bank" class="btn btn-outline-success">{{ __('Generate KYC Report') }}</a>
    </div>
</div>

@include('auction::details.catelogue')

<hr>
<div class="row">
    <div id="pre_auction_item_table_view">
    	{{-- @include('auction::details.pre_auction_item_table') --}}
    </div>
</div>

@section('scripts')
<!-- @parent -->
<script>
    // $(function() {
    //     alert("hi 1");
    // });
    // // after full window load including image src css file
    // $(window).on('load', function() {
    //     alert("hi 2");
    // });

    // call javascript function after page load complete
    // document.addEventListener('readystatechange', event => {
    //     // When HTML/DOM elements are ready:
    //     if (event.target.readyState === "interactive") {   //does same as:  ..addEventListener("DOMContentLoaded"..
    //         alert("hi 1");
    //     }

    //     // When window loaded ( external resources are loaded too- `css`,`src`, etc...) 
    //     if (event.target.readyState === "complete") {
    //         alert("hi 2");
    //     }
    // });
    $(window).bind("load", function() {
        // alert("hi 3");
        getPreAuctionItem();
    });

    // Quick and simple export target #table_id into a csv
    function download_table_as_csv(table_id, separator = ',') {
        // Select rows from table_id
        var rows = document.querySelectorAll('table#' + table_id + ' tr');
        console.log('rows.length :',(rows.length)-1);
        // Construct csv
        var csv = [];
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            for (var j = 0; j < cols.length; j++) {
                // Clean innertext to remove multiple spaces and jumpline (break csv)
                var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                data = data.replace(/"/g, '""');
                // Push escaped string
                row.push('"' + data + '"');
            }
            csv.push(row.join(separator));
        }
        var csv_string = csv.join('\n');
        // Download it
        var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
        var link = document.createElement('a');
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@stop