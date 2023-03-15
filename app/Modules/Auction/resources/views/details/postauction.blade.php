<div class="row">
    <div class="col-md-12">
        <div class="btn-group float-right ml-3">
          <button type="button" class="btn btn-outline-success dropdown-toggle mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Generate Sale Report
          </button>
          <div class="dropdown-menu">

            <a class="dropdown-item" onclick="generatePdf('{!! $auction->id !!}')" href="#">Internal Sale Report</a>
            <a class="dropdown-item" onclick="generateSellerReport('{!! $auction->id !!}')" href="#">Seller Report</a>
            <a class="dropdown-item" onclick="download_table_as_csv('salereport-table');" href="#">CSV</a>
          </div>
        </div>
        <a href="{{ route('auction.auctions.generateBuyerLabel', $auction) }}" target="_bank" class="btn btn-outline-warning float-right mb-3">{{ __('Generate Buyer Label') }}</a>
    </div>
</div>

<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-4">
                <label class="form-control-label">{{ __('Filter By Seller') }} <span style="color:red;">*</span></label>
                <div class="form-group">
                    <select name="filter_sellers" id="filter_seller" class="select2 form-control">
                        <option value="">{{ __('Search & Select Seller') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-control-label">{{ __('Filter By Sold/Unsold') }} <span style="color:red;">*</span></label>
                <div class="form-group">
                    {{ Form::select('title', [''=>'All','Sold'=>'Sold','UnSold'=>'UnSold'], null, [
                            'class' => 'form-control form-control-md select2',
                            'id' => 'filter_sold_unsold',
                        ])
                    }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3" style="color: red;">
                <label class="form-control-label">{{ __('Total Settlement Amount') }} ({{ $total_settlement }})</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-control-label">{{ __('Sale Report') }}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div style="overflow-x: auto;overflow-y: auto;height:500px;max-height:500px" id="sale_report_table_view">
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Quick and simple export target #table_id into a csv
    function download_table_as_csv(table_id, separator = ',') {
        // Select rows from table_id
        var rows = document.querySelectorAll('table#' + table_id + ' tr');
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

    function generatePdf(auction_id) {
        var seller_id = $('#filter_seller').val();
        var status = $('#filter_sold_unsold').val();
        var redirectURL = '/manage/auctions/'+auction_id+'/generateSaleReport?seller='+seller_id+'&status='+status;
        window.open(redirectURL, '_blank');
    }

    function generateSellerReport(auction_id) {
        var seller_id = $('#filter_seller').val();
        var status = $('#filter_sold_unsold').val();
        if(seller_id){
            var redirectURL = '/manage/auctions/'+auction_id+'/generateSellerReport?seller='+seller_id+'&status='+status;
            window.open(redirectURL, '_blank');
        }else{
            bootbox.alert('Select Seller..');
        }
    }
</script>