<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>
<script type="text/javascript">
    var private_items = {!! json_encode($private_items) !!};
    // console.log(private_items);
    var $newitem = {
        id:0,
        item_id:1,
        price:"",
        buyer_premium:"",
    };

    var new_data = {};
    $.extend(true, new_data, $newitem, {'private_items':private_items});

    $(function() {

        getPrivateInvoiceList();

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


        var private_item_template = Handlebars.compile($("#private_item_template").html());

        $(document).on( 'click', '#addNewItemButtonPrivate', function(){
            $('#customerprivateitem').append(private_item_template(new_data));
            $('.select2').select2();
        });

        $(document).on( "click", "#removeButtonPrivate", function(e){
            e.preventDefault();
            $(this).parents('.divPrivateItem').remove();
        });

        $('.btnCreateInvoicePrivate').click(function(){

            if($('#frmNewInvoicePrivate').parsley().validate() == true){
                $.ajax({
                    url: "/manage/customers/"+customer_id+"/createNewInvoicePrivate",
                    type: 'post',
                    data: $('#frmNewInvoicePrivate').serialize()+"&_token="+_token,
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
        $('.select2').select2();

    });

    function getPrivateInvoiceList(timesRun = true) {
        if (timesRun) {
            progressBar();
        }
        $.ajax({
            url: '/manage/customers/'+customer_id+'/getPrivateInvoiceList',
            type: 'get',
            data: {},
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    // $('#private_invoice_view').html(response.html);
                }
            }
        });
    }

</script>