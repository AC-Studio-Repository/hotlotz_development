<script type="text/javascript">

	function clickTab(obj){
        var aria_selected = $(obj).attr('aria-selected');
        if(aria_selected == 'false'){
            var url = $(obj).attr('href');
            location.href = url;
        }
    }

    function checkStatus(is_active){
        if(is_active == 1){
            $('#statusActive').show();
            $('#statusBlocked').hide();
        }else{
            $('#statusActive').hide();
            $('#statusBlocked').show();
        }
    }

    function checkPasswordError() {
        if(error_old_pwd || error_new_pwd){
            $('.divOldPassword').show();
            $('.divNewPassword').show();
        }else{
            $('.divOldPassword').hide();
            $('.divNewPassword').hide();
        }
    }

	function checkMandatoryForCompanyName(){
		var company_type = $('input[name=type]:checked').val();

	    if(company_type == 'organization'){
	        $('#company_name').attr('required','true');
	        $('.company_name_span').html('*');
	    }else{
	        $('#company_name').removeAttr('required');
	        $('.company_name_span').html('');
	        var parsley_id = $('#company_name').attr('data-parsley-id');
	        if(parsley_id != undefined){
	            $('#parsley-id-'+parsley_id).removeClass('filled');
	            $('#parsley-id-'+parsley_id).html('');
	            $('#company_name').removeClass('parsley-error');
	            $('#company_name').addClass('parsley-success');
	        }
	    }
	}

	function checkBankCountry(bank_country) {
		if(bank_country == 'singapore'){
			$('.divOther').hide();
			$('#bank_country_id').val('');
			$('#swift').val('');
			$('#account_currency').val('');
			$('#bank_additional_note').val('');
			$('#bank_address').val('');
		}
		if(bank_country == 'other'){
			$('.divOther').show();
		}
	}

</script>