<script type="text/javascript">
    var auctions = {!! json_encode($auctions) !!};
    var lifecycle_types = {!! json_encode($lifecycle_types) !!};
    var itemlifecycles = {!! json_encode($itemlifecycles) !!};
    var lifecycles = {!! json_encode($lifecycles) !!};
    var arr_lifecycles = $.map( lifecycles, function( value, i ) {
        return value;
    });
    var low_estimate = 0;
    var high_estimate = 0;
    var oldlifecycle_id = {!! json_encode($item->lifecycle_id) !!};
    // console.log('itemlifecycles',itemlifecycles);

    var $newitemlifecycle = {
        id:0,
        type:"auction",
        price:"",
        reference_id:"",
        period:30,
        second_period:3,
        status:null,
        lifecycle_id:1,
    };

    var new_data = {};
    $.extend(true, new_data, $newitemlifecycle, {'auctions':auctions});
    $.extend(true, new_data, $newitemlifecycle, {'lifecycle_types':lifecycle_types});
    $.extend(true, new_data, $newitemlifecycle, {'status_lists':['Finished','Processing']});

    function checkHotlotzOwnStock(){
        if($('#is_hotlotz_own_stock').prop('checked') == true){
            $('.divHotlotzOwnStock').show();
        }else{
            $('.divHotlotzOwnStock').hide();
            $('#supplier').val('');
            $('#purchase_cost').val('');
            $('#supplier_gst').val('');
        }
    }

    $(function() {

        Handlebars.registerHelper('if_eq', function(v1, v2, options) {
            if(v1 == v2) {
              return options.fn(this);
            }
            return  options.inverse(this);
        });

        Handlebars.registerHelper('if_not_eq', function(v1, v2, options) {
            if(v1 != v2) {
              return options.fn(this);
            }
            return  options.inverse(this);
        });

        Handlebars.registerHelper('ifInArray', function(v1, arr, options) {
            if($.inArray( v1, arr ) > -1) {
                return options.fn(this);
            }
            return options.inverse(this);
        });

        $('#btnLifecycleSave').click(function(){
            if($('#frmItemLifecycle').parsley().validate() == true){
                $(this).attr('disabled',true);
                $('#frmItemLifecycle').submit();
            }
        });
        
        checkMandatory($('#is_reserve'));
        checkDisable($('#is_reserve'), $('#reserve'));
        $('#is_reserve').click(function(){
            checkMandatory($(this));
            checkDisable($(this), $('#reserve'));

            var lifecycle_id = $('#lifecycle_id').val();
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();

            if(lifecycle_id != '' && lifecycle_id == oldlifecycle_id){
                bindItemLifecycleData(low_estimate, high_estimate);
            }
            if(lifecycle_id != '' && lifecycle_id != oldlifecycle_id){
                changeLifecycle(low_estimate, high_estimate);
            }
        });

        checkHotlotzOwnStock();
        $('#is_hotlotz_own_stock').click(function(){
            checkHotlotzOwnStock();
        });

        var custom_lifecycle_template = Handlebars.compile($("#custom_lifecycle_template").html());

        $(document).on( "click", "#removeButton", function(e){
            e.preventDefault();
            if($('#itemlifecycle .divLifecycle').length > 1){
                $(this).parents('.divLifecycle').remove();
            }else{
                alert('Please fill at least one lifecycle data.');
            }
        });

        $(document).on( "change", ".type", function(e){
            checkAuctionOrMarketplac($(this));
        });

        $(document).on( "click", "input[name='marketplace[]']", function(e){
            var reference = $(this).closest('div').find('.hid_marketplace').val();
            var arr = reference.split(",");

            if($(this).is(":checked")){
                if(reference.length > 0){
                    reference += ',' + $(this).val();
                }else{
                    reference = $(this).val();
                }
            }else{
                if($.inArray( $(this).val(), arr ) > -1){
                    arr.splice( $.inArray($(this).val(), arr), 1 );
                }
                reference = arr.toString();
            }

            $(this).closest('div').find('.hid_marketplace').val(reference);
        });

        $(".type").each(function( index ) {
            checkAuctionOrMarketplac( $(this) );
        });

        $(document).on( "change", '#low_estimate', function(){
            var lifecycle_id = $('#lifecycle_id').val();
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();

            if(lifecycle_id != '' && lifecycle_id == oldlifecycle_id){
                bindItemLifecycleData(low_estimate, high_estimate);
            }
            if(lifecycle_id != '' && lifecycle_id != oldlifecycle_id){
                changeLifecycle(low_estimate, high_estimate);
            }
        });

        $(document).on( "change", '#high_estimate', function(){
            var lifecycle_id = $('#lifecycle_id').val();
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();

            if(lifecycle_id != '' && lifecycle_id == oldlifecycle_id){
                bindItemLifecycleData(low_estimate, high_estimate);
            }
            if(lifecycle_id != '' && lifecycle_id != oldlifecycle_id){
                changeLifecycle(low_estimate, high_estimate);
            }
        });

        function checkAuctionOrMarketplac(obj){
            if(obj.val() == 'auction'){
                obj.parents('.divLifecycle').find('.divAuction').show();
                obj.parents('.divLifecycle').find('.divMarketplace').hide();
            }else{
                obj.parents('.divLifecycle').find('.divAuction').hide();
                obj.parents('.divLifecycle').find('.divMarketplace').show();
            }
        }

        $('#divAddNewStage').hide();
        if(itemlifecycles.length > 0){
            bindItemLifecycleData();
        }else{
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();
            changeLifecycle(low_estimate, high_estimate);
        }


        $(document).on( "change", '#lifecycle_id', function(){
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();
            var new_lifecycle_id = $(this).val();

            if(oldlifecycle_id > 0 && new_lifecycle_id != oldlifecycle_id){
                if(confirm("Are you sure to change lifecycle?")){
                    $('#hidden_lifecycle_id').val(new_lifecycle_id);
                    changeLifecycle(low_estimate, high_estimate);
                }
                else{
                    $(this).val(oldlifecycle_id);
                }
            }

            if(oldlifecycle_id == 0 && new_lifecycle_id > 0){
                $('#hidden_lifecycle_id').val(new_lifecycle_id);
                changeLifecycle(low_estimate, high_estimate);
            }

            if(oldlifecycle_id != 0 && new_lifecycle_id == oldlifecycle_id){
                $('#hidden_lifecycle_id').val(new_lifecycle_id);
                bindItemLifecycleData();
            }
        });

        function getAuctionIds() {
            var auction_ids = [];
            $( ".auction_id" ).each(function( index ) {
                auction_ids.push($( this ).val());
            });
            return auction_ids;
        }

        function getRoundedByIncrements(price){
            var price_with_increment = price;
            if(price < 400){
                var modulus_amount = price % 20;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 20;
                }
            }
            if(price >= 400 && price < 1000){
                var modulus_amount = price % 50;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 50;
                }
            }
            if(price >= 1000 && price < 2000){
                var modulus_amount = price % 100;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 100;
                }
            }
            if(price >= 2000 && price < 5000){
                var modulus_amount = price % 200;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 200;
                }
            }
            if(price >= 5000 && price < 10000){
                var modulus_amount = price % 500;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 500;
                }
            }
            if(price >= 10000 && price < 100000){
                var modulus_amount = price % 1000;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 1000;
                }
            }
            if(price >= 100000){
                var modulus_amount = price % 5000;
                if(modulus_amount > 0){
                    price_with_increment = (price - modulus_amount) + 5000;
                }
            }

            return price_with_increment;
        }

        function getPriceByLowHighEstimate(type,index,low_estimate,high_estimate) {
            var price = '';
            if($("#is_reserve").is(":checked") && type=='auction'){
                price = $("#reserve").val();
            }
            // if(type=='auction' && index==0 && low_estimate > 0){
            //     price = low_estimate - (low_estimate/100 * 10);
            //     price = getRoundedByIncrements(price);
            // }
            // if(type=='auction' && index==1 && low_estimate > 0){
            //     price = low_estimate - (low_estimate/100 * 30);
            //     price = getRoundedByIncrements(price);
            // }
            
            // if(type=='marketplace' && low_estimate > 0 && high_estimate > 0){
            //     price = (parseFloat(high_estimate) + parseFloat(low_estimate))/2;
            //     price = price * 1.07;
            //     price = getRoundedByIncrements(price);
            // }
            // if(type=='clearance' && low_estimate > 0){
            //     price = low_estimate - (low_estimate/100 * 50);
            //     price = price * 1.07;
            //     price = getRoundedByIncrements(price);
            // }
            if(type=='marketplace' || type == 'clearance'){
                price = '';
            }
            if(type=='storage'){
                price = 5;
            }
            return price;
        }

        function changeLifecycle(low_estimate, high_estimate){
            // console.log('changeLifecycle');
            var lifecycle_id = $('#lifecycle_id').val();
            var lifecycle = $('#lifecycle_id').find('option:selected').text();
            new_data.price = "";
            new_data.lifecycle_id = lifecycle_id;

            if(lifecycle_id > 0 && lifecycle == 'Double all'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',1,low_estimate,high_estimate);
                }
                if(auction_ids[1] != undefined){
                    new_data.reference_id = auction_ids[1];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').append( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#mp_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Double MP only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',1,low_estimate,high_estimate);
                }
                if(auction_ids[1] != undefined){
                    new_data.reference_id = auction_ids[1];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').append( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#mp_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Double Clearance only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',1,low_estimate,high_estimate);
                }
                if(auction_ids[1] != undefined){
                    new_data.reference_id = auction_ids[1];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').append( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Double only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',1,low_estimate,high_estimate);
                }
                if(auction_ids[1] != undefined){
                    new_data.reference_id = auction_ids[1];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').append( Handlebars.compile($('#auction_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Single all'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#mp_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Single MP only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#mp_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Single Clearance only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Single only'){
                var auction_ids = getAuctionIds();
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('auction',0,low_estimate,high_estimate);
                }
                if(auction_ids[0] != undefined){
                    new_data.reference_id = auction_ids[0];
                }
                // new_data.price = parseInt(new_data.price);
                $('#itemlifecycle').html( Handlebars.compile($('#auction_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'MP all'){
                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').html( Handlebars.compile($('#mp_template').html())(new_data) );

                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').append( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'MP only'){
                if(low_estimate > 0 && high_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('marketplace',0,low_estimate,high_estimate);
                }
                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').html( Handlebars.compile($('#mp_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Clearance only'){
                if(low_estimate > 0){
                    new_data.price = getPriceByLowHighEstimate('clearance',0,low_estimate,high_estimate);
                }

                // new_data.price = parseInt(new_data.price);
                new_data.period = '';
                $('#itemlifecycle').html( Handlebars.compile($('#clear_template').html())(new_data) );

                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            }

            if(lifecycle_id > 0 && lifecycle == 'Storage'){
                new_data.price = 5;
                new_data.period = 4;
                $('#itemlifecycle').html( Handlebars.compile($('#storage_template').html())(new_data) );

            }

            if( lifecycle_id == '' || lifecycle == 'Private Sale'){
                $('#itemlifecycle').html('');
                $('#divAddNewStage').hide();
            }
            // else{
            //     $('#itemlifecycle').html('');
            //     $('#divAddNewStage').show();
            // }

            // if(lifecycle_id > 0 && lifecycle == 'Private Sale'){
            //     new_data.price = '';
            //     new_data.period = 30;
            //     $('#itemlifecycle').html( Handlebars.compile($('#privatesale_template').html())(new_data) );

            //     new_data.price = 5;
            //     new_data.period = 4;
            //     $('#itemlifecycle').append( Handlebars.compile($('#storage_template').html())(new_data) );
            // }
        }

        function bindItemLifecycleData(low_estimate=null, high_estimate=null){
            // console.log('bindItemLifecycleData');
            var lifecycle_id = $('#lifecycle_id').val();
            var lifecycle = $('#lifecycle_id').find('option:selected').text();
            var auction_ids = getAuctionIds();

            if($.inArray( lifecycle, arr_lifecycles ) > -1){
                $.each(itemlifecycles,function(i,$itemlifecycle){
                    var template;
                    if($itemlifecycle.type == 'auction'){
                        if(auction_ids[0] != undefined && i == 0){
                            $itemlifecycle.reference_id = auction_ids[0];
                        }
                        if(auction_ids[1] != undefined && i == 1){
                            $itemlifecycle.reference_id = auction_ids[1];
                        }
                        template = Handlebars.compile($("#auction_template").html());
                    }
                    if($itemlifecycle.type == 'marketplace'){
                        template = Handlebars.compile($("#mp_template").html());
                    }
                    if($itemlifecycle.type == 'clearance'){
                        template = Handlebars.compile($("#clear_template").html());
                    }
                    // if($itemlifecycle.type == "privatesale"){
                    //     template = Handlebars.compile($("#privatesale_template").html());
                    // }
                    if($itemlifecycle.type == "storage"){
                        template = Handlebars.compile($("#storage_template").html());
                    }

                    var data = {};
                    // $itemlifecycle.price = parseFloat($itemlifecycle.price).toFixed(2);
                    if(low_estimate != null && high_estimate != null){
                        $itemlifecycle.price = getPriceByLowHighEstimate($itemlifecycle.type,i,low_estimate,high_estimate);
                    }
                    // $itemlifecycle.price = parseInt($itemlifecycle.price);

                    $.extend(true, data, $itemlifecycle, {'auctions':auctions});
                    $.extend(true, data, $itemlifecycle, {'status_lists':['Finished','Processing']});
                    if(i == 0){
                        $('#itemlifecycle').html(template(data));
                    }else{
                        $('#itemlifecycle').append(template(data));
                    }

                });
            }else{

                $.each(itemlifecycles,function(i,$itemlifecycle){
                    var data = {};
                    // $itemlifecycle.price = parseFloat($itemlifecycle.price).toFixed(2);
                    $itemlifecycle.price = parseInt($itemlifecycle.price);
                    $.extend(true, data, $itemlifecycle, {'auctions':auctions});
                    $.extend(true, data, $itemlifecycle, {'lifecycle_types':lifecycle_types});
                    $.extend(true, data, $itemlifecycle, {'status_lists':['Draft','Pending']});
                    $('#itemlifecycle').append(custom_lifecycle_template(data));
                });
            }
        }


        $('#reserve').change(function(){
            var lifecycle_id = $('#lifecycle_id').val();
            low_estimate = $('#low_estimate').val();
            high_estimate = $('#high_estimate').val();

            if(lifecycle_id != '' && lifecycle_id == oldlifecycle_id){
                bindItemLifecycleData(low_estimate, high_estimate);
            }
            if(lifecycle_id != '' && lifecycle_id != oldlifecycle_id){
                changeLifecycle(low_estimate, high_estimate);
            }
        });

    });



</script>