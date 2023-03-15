<script type="text/javascript">
    var xero_items = {!! json_encode($xero_items) !!};

    var $newxeroitem = {
        id:0,
        xero_item_id:1,
        price:"",
        notes:"",
    };

    var new_data = {};
    $.extend(true, new_data, $newxeroitem, {'xero_items':xero_items});

    $(function() {

        getAdhocInvoiceList();

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


        var xero_item_template = Handlebars.compile($("#xero_item_template").html());

        $(document).on( 'click', '#addNewItemButton', function(){
            $('#customerxeroitem').append(xero_item_template(new_data));
        });

        $(document).on( "click", "#removeButton", function(e){
            e.preventDefault();
            $(this).parents('.divXeroItem').remove();
        });

        $('.btnCreateInvoice').click(function(){

            if($('#frmNewInvoice').parsley().validate() == true){
                $.ajax({
                    url: "/manage/customers/"+customer_id+"/createNewInvoice",
                    type: 'post',
                    data: $('#frmNewInvoice').serialize()+"&_token="+_token,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        if(data.status == '1'){
                            location.reload(true);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }
        });

    });

    function getAdhocInvoiceList(timesRun = true) {
        if (timesRun) {
            progressBar();
        }
        $.ajax({
            url: '/manage/customers/'+customer_id+'/getAdhocInvoiceList',
            type: 'get',
            data: {},
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    // $('#adhoc_invoice_view').html(response.html);
                }
            }
        });
    }

</script>