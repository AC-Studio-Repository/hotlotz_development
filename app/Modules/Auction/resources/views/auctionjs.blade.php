<script type="text/javascript">
$(function() {

	$('#time_start').focus(function(){
		$('#time_start').click();
	});
	$('#time_first_lot_ends').focus(function(){
		$('#time_first_lot_ends').click();
	});

	$('#view_start_picker').focus(function(){
		$('#view_start_picker').click();
	});
	$('#view_end_picker').focus(function(){
		$('#view_end_picker').click();
	});
	$('#consignment_deadline_picker').focus(function(){
		$('#consignment_deadline_picker').click();
	});

	$('#email').focus(function(){
		$("#email").keyup();
	});
	$( "#time_start" ).click(function() {
		$('#date').hide();
		$('#date').pickadate({
			format: 'yyyy-mm-dd',
			onSet:function(){
				$('#time').hide();
				$('#time').pickatime({
					onSet:function(){
						let date = $('#date').val();
						let time = $('#time').val();
						$('#time_start').val(date+' '+time);
					},
				})
				if($('#date').val() != ''){
					$('#time').click();
				}else{
					$('#time_start').val('');
				}
			},
		})
		$('#date').click();
	});
	$( "#time_first_lot_ends" ).click(function() {
		$('#lot_date').hide();
		$('#lot_date').pickadate({
			format: 'yyyy-mm-dd',
			onSet:function(){
				$('#lot_time').hide();
				$('#lot_time').pickatime({
					onSet:function(){
						let date = $('#lot_date').val();
						let time = $('#lot_time').val();
						$('#time_first_lot_ends').val(date+' '+time);
					}
				})
				if($('#lot_date').val() != ''){
					$('#lot_time').click();
				}else{
					$('#time_first_lot_ends').val('');
				}
			}
		})
		$('#lot_date').click();
	});

	$( "#view_start_picker" ).click(function() {
		$('#view_start_date').hide();
		$('#view_start_date').pickadate({
			format: 'yyyy-mm-dd',
			onSet:function(){
				$('#view_start_time').hide();
				$('#view_start_time').pickatime({
					onSet:function(){
						let date = $('#view_start_date').val();
						let time = $('#view_start_time').val();
						$('#view_start_picker').val(date+' '+time);
					},
				})
				if($('#view_start_date').val() != ''){
					$('#view_start_time').click();
				}else{
					$('#view_start_picker').val('');
				}
			},
		})
		$('#view_start_date').click();
	});

	$( "#view_end_picker" ).click(function() {
		$('#view_end_date').hide();
		$('#view_end_date').pickadate({
			format: 'yyyy-mm-dd',
			onSet:function(){
				$('#view_end_time').hide();
				$('#view_end_time').pickatime({
					onSet:function(){
						let date = $('#view_end_date').val();
						let time = $('#view_end_time').val();
						$('#view_end_picker').val(date+' '+time);
					},
				})
				if($('#view_end_date').val() != ''){
					$('#view_end_time').click();
				}else{
					$('#view_end_picker').val('');
				}
			},
		})
		$('#view_end_date').click();
	});

	$( "#consignment_deadline_picker" ).click(function() {
		$('#consignment_deadline_date').hide();
		$('#consignment_deadline_date').pickadate({
			format: 'yyyy-mm-dd',
			onSet:function(){
				let date = $('#consignment_deadline_date').val();
				$('#consignment_deadline_picker').val(date);
			}
		})
		$('#consignment_deadline_date').click();
	});

	$('#email').keyup(function(){
		let email= $('#email').val();
		if(email != ''){
			$('#email_suggestion').show();
		}
	});

	$('#email').focusout(function(){
		setTimeout(function(){ $('#email_suggestion').hide(); }, 200);
	});

	$('#email_suggestion').click(function(){
		let email = $('#email').val();
		$('#confirmation_email').val(email);
		$('#registration_email').val(email);
		$('#payment_receive_email').val(email);

	});

	$("input[type=radio][name=automatic_deposite]").change(function(){
		let tof = $("input[name='automatic_deposite']:checked").val();
		if(tof == 1){
			$('#minimun_deposite').show();
		}else{
			$('#minimun_deposite').hide();
		}
	});

	let tof = $("input[name='automatic_deposite']:checked").val();
	if(tof == 1){
		$('#minimun_deposite').show();
	}

});

function chooseFile( selector ){
    $( selector ).click();
}

function readImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image1').attr('style','display:block');
            $('#image1')
                .attr('src', e.target.result)
                .width(300)
                .height(200);

        };

        reader.readAsDataURL(input.files[0]);
    }
}

function readBanner(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#banner1').attr('style','display:block');
            $('#banner1')
                .attr('src', e.target.result)
                .width(895)
                .height(240);

        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#auctionTitle").keyup(function(){
	$('#auctionTitleComing').val($(this).val());
});

$('#auctionTitleComing').val($('#auctionTitle').val());

</script>