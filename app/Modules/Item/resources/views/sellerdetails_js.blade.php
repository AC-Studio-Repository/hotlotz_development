<script type="text/javascript">
	$(function() {

        $('#btnSellerDetail').click(function(){

			if($('#sellerDetailForm').parsley().validate() == true){
				var customer_id = $('#customer_id').val();
				var data = {
					_token:_token,
                    customer_id:customer_id
				};

				if(page_action == 'create'){

                    var obj_sellwithus = $.parseJSON(sessionStorage.getItem('sellwithus'));

                    data.title = obj_sellwithus.title;
                    data.category_id = obj_sellwithus.category_id;
                    data.country_id = obj_sellwithus.country_id;
                    data.long_description = obj_sellwithus.long_description;
                    data.currently_in_hotlotz_warehouse = obj_sellwithus.currently_in_hotlotz_warehouse;

                    $.ajax({
                        url: "/manage/items",
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        async: false,
                        success: function(response) {
                            if(response.status == '1'){
                                window.location.href = response.redirectURL;
                                window.open(response.redirectURL);
                            }else{
                                alert(response.message);
                            }
                        }
                    });

	        	}else{
                    var item_id = $('#item_id').val();
                    data.item_id = item_id;
                    $.ajax({
                        url: "/manage/items/"+item_id+"/seller_update",
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        async: false,
                        success: function(response) {
                            alert(response.message);
                            location.reload(true);
                        }
                    });
	        	}
			}

        });
	});
</script>