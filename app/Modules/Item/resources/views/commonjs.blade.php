<script type="text/javascript">
$(function(){

    // $.fn.select2.defaults.set("placeholder", '--- Select ---');

    // var sel2customer = [{"id": '',"text": ''}];

    $('#email').change(function(){
        $.ajax({
            url: "/manage/customers/check_unique_customer_email",
            type: 'post',
            data: "email="+$(this).val()+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(data) {
                if(data.status == 'success' && data.count > 0){
                    $('#error_email').html('The email has already been taken.');
                }else{
                    $('#error_email').html('');
                }
            }
        });
    });

});

function getItemCode(customer_id){
    if(page_action == 'edit'){
        var old_customer_id = {!! json_encode($item->customer_id) !!};
        if(old_customer_id != customer_id){
            generateItemCode(customer_id);
        }
        if(old_customer_id == customer_id){
            $('#itemnumber').val({!! json_encode($item->item_number) !!});
            $('#itemcode').val({!! json_encode($item->item_number) !!});
            $('#itemcode_id').val({!! json_encode($item->item_code_id) !!});
        }
    }
    if(page_action == 'create'){
        generateItemCode(customer_id);
    }
}

function checkDisable(obj, disableObj){
    if(obj.prop('checked') == true){
        disableObj.removeAttr('disabled');
    }else{
        disableObj.attr('disabled','disabled');
    }
}

function checkCategoryCollaborations() {
    var category_id = $('#category_id').val();
    console.log('category_id',category_id);
    if(category_id != 13 && category_id != ''){
        $('.divSubCategory').show();
        $('.divCategoryPropertyMain').show();
        getSubCategory(category_id);
        getCategoryProperty(category_id);
    }
    if(category_id == 13 || category_id == ''){
        $('.divSubCategory').hide();
        $('.divCategoryPropertyMain').hide();
    }
    if(category_id == 5){
        $('#is_tree_planted').prop('checked', true);
    }else{
        $('#is_tree_planted').prop('checked', false);
    }
    if(category_id == 6){
        $('#is_pspm').prop('checked', true);
    }else{
        $('#is_pspm').prop('checked', false);
    }
}

function getCondition() {
    var category_id = $('#category_id').val();
    $.ajax({
        url: "/manage/items/getConditions",
        type: 'post',
        data: "category_id="+category_id+"&_token="+_token,
        dataType: 'json',
        async: false,
        success: function(res) {
            if(res.status == 'success'){
                var conditions = res.data;
                $('#condition').empty();
                $.each(conditions, function( key, value ) {
                    if(condition != '' && condition == key){
                        $('#condition').append('<option value="'+key+'" selected>'+value+'</option>');
                    }else{
                        $('#condition').append('<option value="'+key+'">'+value+'</option>');
                    }
                });
            }
        }
    });
}

function showHideSpecificConditionValue() {
    let condition = $('#condition').val();
    if(condition == 'specific_condition' || condition == 'general_condition'){
        $('#divSpecificCondition').show();
        $('#specific_condition_value').attr('required','true');
    }else{
        $('#divSpecificCondition').hide();
        $('#specific_condition_value').removeAttr('required');
    }
}
function getConditionSolution(condition){
    $.ajax({
        url: "/manage/items/getConditionSolution",
        type: 'post',
        data: "condition="+condition+"&_token="+_token,
        dataType: 'json',
        async: false,
        success: function(res) {
            if(res.status == 'success'){
                $('#specific_condition_value').val(res.condition_solution);
            }
        }
    });
}

function checkMandatory(obj){
    var type = obj.attr('data-type');
    var type_span = obj.attr('data-type_span');
    if(obj.is(":checked")){
        $('#'+type).attr('required','true');
        $('.'+type_span).html('*');
    }else{
        $('#'+type).removeAttr('required');
        $('.'+type_span).html('');
        var parsley_id = $('#'+type).attr('data-parsley-id');
        if(parsley_id != undefined){
            $('#parsley-id-'+parsley_id).removeClass('filled');
            $('#parsley-id-'+parsley_id).html('');
            $('#'+type).removeClass('parsley-error');
            $('#'+type).addClass('parsley-success');
        }
    }
}

function toggleForSubCategoryOther(sub_category){
    if(sub_category == 'Other'){
        $('.divSubCategoryforOther').show();
    }else{
        $('.divSubCategoryforOther').hide();
    }
}

function generateItemCode(customer_id){
    $.ajax({
        url: "/manage/items/"+customer_id+"/generateItemCode",
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(result) {
            if(result.status == 'success'){
                var itemcode_data = result.item_code_data;
                $('#itemnumber').val(itemcode_data['item_code']);
                $('#itemcode').val(itemcode_data['item_code']);
                $('#itemcode_id').val(itemcode_data['item_code_id']);
            }
        }
    });
}

function select2add_btn(e){
    var d = $(e.currentTarget).data();
    var obj_id='';
    if(e.currentTarget.id){
        obj_id = ' data-obj_id="'+e.currentTarget.id+'" ';
    }
    create_link = $('<button type="button" class="btn btn-default createLink" id="addNewSeller" data-toggle="modal" data-target="#addNewSellerModal">Add new Seller</button>');

    create_link.on('click',function(){
        $(e.currentTarget).select2('close');
    });

    $('.select2-results').find('.createLink').remove();
    $('.select2-results').append(create_link);
}

// function getSelect2Customer(init=false){
//     if(init==false){
//         $.ajax({
//             url: "/manage/customers/select2_all_customer",
//             type: 'get',
//             dataType: 'json',
//             async: false,
//             success: function(data) {
//                 sel2customer = data;
//             }
//         });
//     }
// }

// function fnCallbackCustomer(init=false){
//     getSelect2Customer();
//     var old_val = $('#customer_id').val();
//     $('#customer_id').val('');
//     $('#customer_id').select2().empty();
//     $('#customer_id').select2({data:sel2customer}).on('select2:open', select2add_btn);
//     $('#customer_id').select2();
// }

function getSubCategory(category_id){
    $.ajax({
        url: "/manage/categories/"+category_id+"/getSubCategory",
        type: 'get',
        dataType: 'json',
        async: false,
        success: function(res) {
            if(res.status == 'success'){
                var data = res.data;
                $('#sub_category').empty();
                $.each(data.subcategories, function( id, name ) {
                    if(sub_category != '' && sub_category == id){
                        $('#sub_category').append('<option value="'+id+'" selected>'+name+'</option>');
                    }else{
                        $('#sub_category').append('<option value="'+id+'">'+name+'</option>');
                    }
                });
            }
        }
    });
}

function getCategoryProperty(category_id){
    var item_id = 0;
    if(page_action == 'edit'){
        item_id = "{{ $item->id }}";
    }

    $.ajax({
        url: "/manage/items/getCategoryProperty",
        type: 'post',
        data: "category_id="+category_id+"&item_id="+item_id+"&_token="+_token,
        dataType: 'json',
        async: false,
        success: function(response) {
            if(response.status == 'success'){
                $('#divCategoryProperty').html(response.html);

                $('.divOther').hide();
                $(".multiselect").multiselect({
                    includeSelectAllOption:true,
                    includeSelectAllIfMoreThan: 0,
                    selectAllText:' Select all',
                    selectAllValue:'multiselect-all',
                    selectAllName:false,
                    selectAllNumber:true,
                    // enableFiltering:true,
                    buttonWidth: '100%',
                    // enableClickableOptGroups:false
                });

                $(".multiselect").change(function(){
                    var selected_list = $(this).val();
                    var obj_index = $(this).data('index');

                    $('.divOther'+obj_index).hide();
                    if(selected_list.length > 0){
                        $.each( selected_list, function( key, value ) {
                            if(value === 'Other'){
                                $('.divOther'+obj_index).show();
                                //Add Mandatory
                                $('#pid_'+obj_index+'_other').attr('required','true');
                            }
                            else{
                                $('.divOther'+obj_index).hide();
                                $('#pid_'+obj_index+'_other').val('');
                                //Remove Mandatory
                                $('#pid_'+obj_index+'_other').removeAttr('required');
                                var parsley_id = $('#pid_'+obj_index+'_other').attr('data-parsley-id');
                                if(parsley_id != undefined){
                                    $('#parsley-id-'+parsley_id).removeClass('filled');
                                    $('#parsley-id-'+parsley_id).html('');
                                    $('#pid_'+obj_index+'_other').removeClass('parsley-error');
                                    $('#pid_'+obj_index+'_other').addClass('parsley-success');
                                }
                            }
                        });
                    }
                });

                $(".selectbox").change(function(){
                    var selected_value = $(this).find('option:selected').text();
                    var obj_index = $(this).data('index');

                    if(selected_value === 'Other'){
                        $('.divOther'+obj_index).show();
                        //Add Mandatory
                        $('#pid_'+obj_index+'_other').attr('required','true');
                    }
                    else{
                        $('.divOther'+obj_index).hide();
                        $('#pid_'+obj_index+'_other').val('');
                        //Remove Mandatory
                        $('#pid_'+obj_index+'_other').removeAttr('required');
                        var parsley_id = $('#pid_'+obj_index+'_other').attr('data-parsley-id');
                        if(parsley_id != undefined){
                            $('#parsley-id-'+parsley_id).removeClass('filled');
                            $('#parsley-id-'+parsley_id).html('');
                            $('#pid_'+obj_index+'_other').removeClass('parsley-error');
                            $('#pid_'+obj_index+'_other').addClass('parsley-success');
                        }
                    }
                });

                var checkbox_selected_list = [];
                $(".checkbox").click(function(){
                    var item_value = $(this).val();
                    if( $(this).is(":checked") ){
                        checkbox_selected_list.push( $(this).val() );
                    }else{
                        var item_index = checkbox_selected_list.indexOf(item_value);
                        if(item_index !== -1){
                            checkbox_selected_list.splice(item_index, 1);
                        }
                    }

                    var obj_index = $(this).data('index');
                    if(checkbox_selected_list.indexOf('Other') !== -1){
                        $('.divOther'+obj_index).show();
                        //Add Mandatory
                        $('#pid_'+obj_index+'_other').attr('required','true');
                    }else{
                        $('.divOther'+obj_index).hide();
                        $('#pid_'+obj_index+'_other').val('');
                        //Remove Mandatory
                        $('#pid_'+obj_index+'_other').removeAttr('required');
                        var parsley_id = $('#pid_'+obj_index+'_other').attr('data-parsley-id');
                        if(parsley_id != undefined){
                            $('#parsley-id-'+parsley_id).removeClass('filled');
                            $('#parsley-id-'+parsley_id).html('');
                            $('#pid_'+obj_index+'_other').removeClass('parsley-error');
                            $('#pid_'+obj_index+'_other').addClass('parsley-success');
                        }
                    }
                });
            }
        }
    });
}

var pageSize = 10;
function customerSelect2(customer_id, obj_customer_id) {
    console.log('customerSelect2');
    var defaultTxtOnInit = 'a';
    obj_customer_id.select2({
        // allowClear: true,
        ajax: {
            url: "/manage/customers/select2_all_customer",
            dataType: 'json',
            delay: 250,
            global: false,
            data: function (params) {
                params.page = params.page || 1;
                return {
                    search: params.term ? params.term : defaultTxtOnInit,
                    pageSize: pageSize,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.result,
                    pagination: {
                        more: (params.page * pageSize) < data.counts
                    }
                };
            },
            cache: true
        },
        placeholder: {
            id: '0', // the value of the option
            text: '--Select Customer--'
        },
        width: '100%',
        //minimumInputLength: 3,
    });

    obj_customer_id.on('select2:open', select2add_btn);
    getSelect2Customer(customer_id, obj_customer_id);
}

function getSelect2Customer(customer_id, obj_customer_id) {
    if(customer_id != '' && customer_id > 0){
        console.log('customer_id : ',customer_id);
        $.ajax({
            type: 'GET',
            url: '/manage/customers/' + customer_id + '/select2'
        }).then(function (data) {
            // create the option and append to Select2
            var option = new Option(data.text, data.id, true, true);
            obj_customer_id.append(option).trigger('change');

            // manually trigger the `select2:select` event
            // obj_customer_id.trigger({
            //     type: 'select2:select',
            //     params: {
            //         data: data
            //     }
            // });
        });
    }
}
</script>
