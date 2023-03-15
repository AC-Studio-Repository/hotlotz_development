<script type="text/javascript">

    function getSubCategory(category_id){
        $.ajax({
            url: "/manage/categories/"+category_id+"/getSubCategory",
            type: 'get',
            dataType: 'json',
            async: false,
            success: function(res) {
                if(res.status == 'success'){
                    var data = res.data;
                    $('#category_name').val(data.category_name);
                    $('#sub_category').empty();
                    $.each(data.subcategories, function( id, name ) {
                        $('#sub_category').append('<option value="'+id+'">'+name+'</option>');
                    });
                }
            }
        });
    }

	$(function() {

        $('#btnSellWithUs').click(function(){

			if($('#sellWithUsForm').parsley().validate() == true){

                var title = $('#title').val();
                var category_id = $('#category_id').val();
                var country_id = $('#country_id').val();
                var long_description = $('#long_description').val();
                var currently_in_hotlotz_warehouse = 0;
                if ($('#currently_in_hotlotz_warehouse').is(':checked'))
                {
                    currently_in_hotlotz_warehouse = 1;
                }else{
                    currently_in_hotlotz_warehouse = 0;
                }

                var data = {
                    title:title,
                    category_id:category_id,
                    country_id:country_id,
                    long_description:long_description,
                    currently_in_hotlotz_warehouse:currently_in_hotlotz_warehouse
                };

                if(page_action == 'create'){

    				sessionStorage.setItem('sellwithus', JSON.stringify(data));
    				var obj_sellwithus = $.parseJSON(sessionStorage.getItem('sellwithus'));

    	        	if(obj_sellwithus){
    	        		$('#seller_details-tab').removeClass('disabled');
                        $('#seller_details-tab').click();
    	        	}

    			}else{
                    var item_id = $('#item_id').val();
                    data._token = _token;
                    data.item_id = item_id;
                    $.ajax({
                        url: "/manage/items/"+item_id+"/sellwithus_update",
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        async: false,
                        success: function(response) {
                            if(response.status == '1'){
                                var category_id = $('#category_id').val();
                                getSubCategory(category_id);
                                getCategoryProperty(category_id);
                                location.reload(true);
                            }
                        }
                    });
                }
            }

        });
	});
</script>