<script type="text/javascript">

$(function(){
    var customer_id = '';
    if(page_action == 'edit'){
        customer_id = {!! json_encode($item->customer_id) !!};
        getItemCode(customer_id);
    }
    if(page_action == 'create'){
        customer_id = {!! json_encode($customer_id) !!};
        if(customer_id > 0){
            $("#customer_id").select2().val(customer_id).trigger("change");
            getItemCode(customer_id);
        }
    }
    // console.log('customer_id : ',customer_id);

    customerSelect2(customer_id, $('#customer_id'));
    $('.btnCreateCustomer').click(function(){
        if($('#frmCustomer').parsley().validate() == true){
            $.ajax({
                url: "/manage/customers/ajaxcreate",
                type: 'post',
                data: $('#frmCustomer').serialize()+"&_token="+_token,
                dataType: 'json',
                async: false,
                success: function(data) {
                    if(data.status == 'success'){
                        $('#addNewSellerModalClose').trigger('click');
                        customerSelect2(data.customer_id, $('#customer_id'));
                    }
                },
                error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.errors;
                    if(errors.email != undefined){
                        $('#error_email').html(errors.email[0]);
                    }
                }
            });
        }
    });

    $('#customer_id').change(function(){
        var customer_id = $(this).val();
        getItemCode(customer_id);
    });


    checkCategoryCollaborations();
    // getCondition();
    $('#category_id').change(function(){
        checkCategoryCollaborations();
        // getCondition();
    });

    $('.divSubCategoryforOther').hide();
    $('#sub_category').change(function(){
        var sub_category = $(this).val();
        toggleForSubCategoryOther(sub_category);
    });


    showHideSpecificConditionValue();
    $('#condition').change(function(){
        showHideSpecificConditionValue();
        let condition = $('#condition').val();
        if(condition == 'specific_condition' || condition == 'general_condition'){
            getConditionSolution(condition);
        }
    });


    checkMandatory($('#is_dimension'));
    checkDisable($('#is_dimension'), $('#dimensions'));
    $('#is_dimension').click(function(){
        checkMandatory($(this));
        checkDisable($(this), $('#dimensions'));
    });

    checkMandatory($('#is_weight'));
    checkDisable($('#is_weight'), $('#weight'));
    $('#is_weight').click(function(){
        checkMandatory($(this));
        checkDisable($(this), $('#weight'));
    });
});

</script>