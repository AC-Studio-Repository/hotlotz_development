<script>

    function chooseFile( selector ){
        $( selector ).click();
    }

    function readImage(input,image_preview) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(image_preview).attr('style','display:block');
                $(image_preview)
                    .attr('src', e.target.result)
                    .width($(image_preview).attr('width'))
                    .height($(image_preview).attr('height'));
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>