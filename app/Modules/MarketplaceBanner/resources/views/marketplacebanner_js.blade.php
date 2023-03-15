<script type="text/javascript">

    function chooseFile( selector ){
        $( selector ).click();
    }

    function readImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image_preview').attr('style','display:block');
                $('#image_preview')
                    .attr('src', e.target.result)
                    .width(895)
                    .height(240);

            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>