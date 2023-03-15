<script type="text/javascript">
    var field_types = {!! json_encode($field_types) !!};
    var categoryproperties = {!! json_encode($categoryproperties) !!};

    var $newcategoryproperty = {
        id:0,
        type:"auction",
        init_price:"",
        reference_id:"",
        status:"",
    };

    var new_data = {};
    $.extend(true, new_data, $newcategoryproperty, {'field_types':field_types});
    $.extend(true, new_data, $newcategoryproperty, {'req_lists':{'Required':'Required','Optional':'Optional'}});
    $.extend(true, new_data, $newcategoryproperty, {'filter_lists':{'Y':'Yes','N':'No'}});

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


        var category_property_template = Handlebars.compile($("#category_property_template").html());

        $.each(categoryproperties,function(i,$categoryproperty){
            var data = {};
            $categoryproperty.init_price = parseFloat($categoryproperty.init_price).toFixed(2);
            $.extend(true, data, $categoryproperty, {'field_types':field_types});
            $.extend(true, data, $categoryproperty, {'req_lists':{'Required':'Required','Optional':'Optional'}});
            $.extend(true, data, $categoryproperty, {'filter_lists':{'Y':'Yes','N':'No'}});
            $('#categoryproperty').append(category_property_template(data));
        });

        $(document).on( 'click', '#addButton', function(){
            if($('#categoryproperty').children().length > 0){
                $('#categoryproperty').children().last().after( category_property_template(new_data) );
            }else{
                $('#categoryproperty').append(category_property_template(new_data));
            }
        });

        $(document).on( "click", "#removeButton", function(e){
            e.preventDefault();
            $(this).parents('.divCatProperty').remove();
        });

        $(".property_value").each(function( index ) {
            $(this).tagit({
                allowSpaces: true,
            });
        });

    });

</script>