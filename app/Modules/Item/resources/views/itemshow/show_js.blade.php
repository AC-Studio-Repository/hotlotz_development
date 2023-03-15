<script type="text/javascript">
	$(function(){

		$('#btnCataloguingApprove').click(function(){
			var cataloguing_approver_id = $('#cataloguing_approver_id').val();
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/approvedCataloguing",
	            type: 'post',
	            data: "cataloguing_approver_id="+cataloguing_approver_id+"&_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
	                    $('#divApproveButton').hide();
	                    $('#cataloguing_approver_id').attr('disabled','true');
	                    location.reload();
	                }
	            }
	        });
		});

        $('#btnValuationApprove').click(function(){
            var valuation_approver_id = $('#valuation_approver_id').val();
            $.ajax({
                url: "/manage/items/{{ $item->id }}/approvedValuation",
                type: 'post',
                data: "valuation_approver_id="+valuation_approver_id+"&_token="+_token,
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success'){
                        $('#divValuationApproveButton').hide();
                        $('#valuation_approver_id').attr('disabled','true');
                        location.reload();
                    }else{
                    	bootbox.alert(response.message);
                        return false;
                    }
                }
            });
        });

        $('#btnFeeStructureApprove').click(function(){
            var fee_structure_approver_id = $('#fee_structure_approver_id').val();
            $.ajax({
                url: "/manage/items/{{ $item->id }}/approvedFeeStructure",
                type: 'post',
                data: "fee_structure_approver_id="+fee_structure_approver_id+"&_token="+_token,
                dataType: 'json',
                async: false,
                success: function(data) {
                    if(data.status == 'success'){
                        $('#divFeeStructureApproveButton').hide();
                        $('#fee_structure_approver_id').attr('disabled','true');
                        location.reload();
                    }
                }
            });
        });

		$('#btnDeclinedItem').click(function(){
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/decline",
	            type: 'post',
	            data: "_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
						$('#btnDeclinedItemModalClose').trigger('click');
	                    $('#btnDeclined').hide();
	                    $('#status').val('Declined');
	                    location.reload(true);
	                }else{
	                	bootbox.alert(data.message);
                        return false;
	                }
	            }
	        });
		});

		$('#btnWithdrawnItem').click(function(){
			$.ajax({
				url: "/manage/items/{{ $item->id }}/withdraw",
				type: 'post',
				data: "_token="+_token,
				dataType: 'json',
				async: false,
				success: function(data) {
					if(data.status == 'success'){
						$('#btnWithdrawnItemModal').modal('toggle');
						$('#status').val('Withdrawn');
						location.reload(true);
					}
				}
			});
		});

		$('#btnInternalWithdrawnItem').click(function(){
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/internal_withdraw",
	            type: 'post',
	            data: "_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
	                    $('#btnInternalWithdrawnItemModalClose').trigger('click');
	                    $('#btnInternalWithdrawn').hide();
	                    location.reload(true);
	                }
	            }
	        });
		});


		$("#dispatched_or_collected_date").click(function() {
	        var parsley_id = $('#dispatched_or_collected_date').attr('data-parsley-id');

	        $('#dispatched_date').hide();
	        $('#dispatched_date').pickadate({
	            container: 'body',
	            format: 'yyyy-mm-dd',
	            onSet:function(){
	            	let date = $('#dispatched_date').val();
                    let time = $('#dispatched_time').val();
                    $('#dispatched_or_collected_date').val(date);


                    if(parsley_id != undefined){
                    	$('#parsley-id-'+parsley_id).removeClass('filled');
                        $('#parsley-id-'+parsley_id).html('');
                        $('#dispatched_or_collected_date').removeClass('parsley-error');
                        $('#dispatched_or_collected_date').addClass('parsley-success');
                    }
	                if($('#dispatched_date').val() != ''){
	                    $('#dispatched_time').click();
	                }else{
	                    $('#dispatched_or_collected_date').val('');
	                    if(parsley_id != undefined){
		                    var required_html = '<li class="parsley-required">This value is required.</li>';
		                    $('#parsley-id-'+parsley_id).addClass('filled');
		                    $('#dispatched_or_collected_date').addClass('parsley-error');
		                    $('#parsley-id-'+parsley_id).html(required_html);
		                }
	                }
	            },
	        })
	        $('#dispatched_date').click();
	    });

		$('#btnDispatchedItem').click(function(){
			if( $('#frmDispatchedItem').parsley().validate() == true ){
				var dispatched_or_collected_date = $('#dispatched_or_collected_date').val();
				var dispatched_person = $('#dispatched_person').val();
				var dispatched_remark = $('#dispatched_remark').val();

				$.ajax({
		            url: "/manage/items/{{ $item->id }}/dispatch",
		            type: 'post',
		            data: "_token="+_token+"&dispatched_or_collected_date="+dispatched_or_collected_date+"&dispatched_person="+dispatched_person+"&dispatched_remark="+dispatched_remark,
		            dataType: 'json',
		            async: false,
		            success: function(data) {
		                if(data.status == 'success'){
		                    $('#btnDispatchedItemModalClose').trigger('click');
		                    $('#btnDispatched').hide();
		                    $('#status').val('Dispatched');
		                    location.reload(true);
		                }
		            }
		        });
			}
		});


		$("#delivery_booked_date").click(function() {
	        var parsley_id = $('#delivery_booked_date').attr('data-parsley-id');

	        $('#booked_date').hide();
	        $('#booked_date').pickadate({
	            container: 'body',
	            format: 'yyyy-mm-dd',
	            onSet:function(){
	                $('#booked_time').hide();
	                $('#booked_time').pickatime({
	                    container: 'body',
	                    onSet:function(){
	                        let date = $('#booked_date').val();
	                        let time = $('#booked_time').val();
	                        $('#delivery_booked_date').val(date+' '+time);

	                        if(parsley_id != undefined){
	                        	$('#parsley-id-'+parsley_id).removeClass('filled');
		                        $('#parsley-id-'+parsley_id).html('');
		                        $('#delivery_booked_date').removeClass('parsley-error');
		                        $('#delivery_booked_date').addClass('parsley-success');
	                        }
	                    },
	                })
	                if($('#booked_date').val() != ''){
	                    $('#booked_time').click();
	                }else{
	                    $('#delivery_booked_date').val('');
	                    if(parsley_id != undefined){
		                    var required_html = '<li class="parsley-required">This value is required.</li>';
		                    $('#parsley-id-'+parsley_id).addClass('filled');
		                    $('#delivery_booked_date').addClass('parsley-error');
		                    $('#parsley-id-'+parsley_id).html(required_html);
		                }
	                }
	            },
	        })
	        $('#booked_date').click();
	    });

		$('#btnDeliveryBookedItem').click(function(){
			if( $('#frmDeliveryBookedItem').parsley().validate() == true ){
				var delivery_booked_date = $('#delivery_booked_date').val();

				$.ajax({
		            url: "/manage/items/{{ $item->id }}/delivery_book",
		            type: 'post',
		            data: "_token="+_token+"&delivery_booked_date="+delivery_booked_date,
		            dataType: 'json',
		            async: false,
		            success: function(data) {
		                if(data.status == 'success'){
		                    $('#btnDeliveryBookedModalClose').trigger('click');
		                    $('#btnDeliveryBooked').hide();
		                    location.reload(true);
		                }
		            }
		        });
			}
		});

		$('#btnCancelSaleItem').click(function(){
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/cancel_sale",
	            type: 'post',
	            data: "_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
	                    $('#btnCancelSaleModalClose').trigger('click');
	                    location.reload(true);
	                }
	            }
	        });
		});

		// ### For Private Sale Item
		$.fn.select2.defaults.set("placeholder", '---Select Customer---');
		var sel2customer = [{"id": '',"text": ''}];

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

	    // fnCallbackCustomer(false);
	    customerSelect2();
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
	                        customerSelect2();
	    					getSelect2Customer(data.customer_id, $('#buyer_id'));

	                        // $('#buyer_id').val(data.customer_id);
	                        // $('#buyer_id').select2();
	                        // $('#addNewSellerModalClose').trigger('click');
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

	 	// function fnCallbackCustomer(init=false){
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

		//     var old_val = $('#buyer_id').val();
		//     $('#buyer_id').val('');
		//     $('#buyer_id').select2().empty();
		//     $('#buyer_id').select2({data:sel2customer}).on('select2:open', select2add_btn);
		//     $('#buyer_id').select2();
		// }

		// function select2add_btn(e){
		//     var d = $(e.currentTarget).data();
		//     var obj_id='';
		//     if(e.currentTarget.id){
		//         obj_id = ' data-obj_id="'+e.currentTarget.id+'" ';
		//     }

		//     create_link = $('<button type="button" class="btn btn-default createLink" id="addNewSeller" data-toggle="modal" data-target="#addNewSellerModal">Add new Seller</button>');

		//     create_link.on('click',function(){
		//         $(e.currentTarget).select2('close');
		//     });

		//     $('.select2-results').find('.createLink').remove();
		//     $('.select2-results').append(create_link);
		// }

		$('#btnCreditNoteItem').click(function(){
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/credit_note",
	            type: 'post',
	            data: "_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
	                    $('#btnCreditNoteModalClose').trigger('click');
	                    location.reload(true);
	                }
	            }
	        });
		});

		$('#btnCancelDispatchItem').click(function(){
			$.ajax({
	            url: "/manage/items/{{ $item->id }}/cancel_dispatch",
	            type: 'post',
	            data: "_token="+_token,
	            dataType: 'json',
	            async: false,
	            success: function(data) {
	                if(data.status == 'success'){
	                    $('#btnCancelDispatchModalClose').trigger('click');
	                    location.reload(true);
	                }
	            }
	        });
		});

	});

	var pageSize = 10;
	function customerSelect2() {
	    console.log('customerSelect2');
	    var defaultTxtOnInit = 'a';
	    $("#buyer_id").select2({
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

	    var obj_customer_id = $('#buyer_id');
	    obj_customer_id.on('select2:open', select2add_btn);
	}

	function getSelect2Customer(customer_id, obj_customer_id) {
	    if(customer_id != ''){
	        console.log('customer_id : ',customer_id);
	        $.ajax({
	            type: 'GET',
	            url: '/manage/customers/'+ customer_id +'/select2'
	        }).then(function (data) {
	            // create the option and append to Select2
	            var option = new Option(data.text, data.id, true, true);
	            obj_customer_id.append(option).trigger('change');
	        });
	    }
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

	function withdrawFeeSetting(id, isChecked) {
		$.ajax({
			url: '/manage/items/'+id+'/withdraw_fee_setting',
			type: 'post',
			data: "isChecked="+isChecked+"&_token="+_token,
			dataType: 'json',
			async: false,
			success: function(data) {

			}
		});
		$.ajax({
			url: "/manage/items/{{ $item->id }}/withdraw",
			type: 'post',
			data: "_token="+_token,
			dataType: 'json',
			async: false,
			success: function(data) {
				if(data.status == 'success'){
					$('#btnWithdrawnItemModal').modal('toggle');
					$('#status').val('Withdrawn');
					location.reload(true);
				}
			}
		});
	}

	function chargeWithdrawlFee(id){
		withdrawFeeSetting(id, true)

	}

	function chargeWithoutWithdrawlFee(id){
		withdrawFeeSetting(id, false)
	}
</script>