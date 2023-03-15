$('#time_start').focus(function(){
	$('#time_start').click();
});
$('#time_first_lot_ends').focus(function(){
	$('#time_first_lot_ends').click();
});
$('#email').focus(function(){
	$("#email").keyup();
});
$( "#time_start" ).click(function() {
	$('#date').hide()
	$('#date').pickadate({
		onSet:function(){
			$('#time').hide()
			$('#time').pickatime({
				onSet:function(){
					let date = $('#date').val()
					let time = $('#time').val()
					$('#time_start').val(date+' '+time)
				}
			})
			$('#time').click()
		}
	})  
	$('#date').click()
})
$( "#time_first_lot_ends" ).click(function() {
	$('#lot_date').hide()
	$('#lot_date').pickadate({
		onSet:function(){
			$('#lot_time').hide()
			$('#lot_time').pickatime({
				onSet:function(){
					let date = $('#lot_date').val()
					let time = $('#lot_time').val()
					$('#time_first_lot_ends').val(date+' '+time)
				}
			})
			$('#lot_time').click()
		}
	})  
	$('#lot_date').click()
})

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
$(document).ready(function(){
	let tof = $("input[name='automatic_deposite']:checked").val();
	if(tof == 1){
	$('#minimun_deposite').show();
	}
});
