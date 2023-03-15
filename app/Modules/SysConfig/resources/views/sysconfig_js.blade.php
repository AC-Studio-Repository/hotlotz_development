<script>
    $(document).ready(function() {

        $( ".monday_start_time" ).click(function() {
            $('#monday_start').hide();
            $('#monday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#monday_start').val();
                    $('#monday_start_time').val(time);
                }
            });
            $('#monday_start').click();
        });
        $( ".monday_end_time" ).click(function() {
            $('#monday_end').hide();
            $('#monday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#monday_end').val();
                    $('#monday_end_time').val(time);
                }
            });
            $('#monday_end').click();
        });

        $( ".tuesday_start_time" ).click(function() {
            $('#tuesday_start').hide();
            $('#tuesday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#tuesday_start').val();
                    $('#tuesday_start_time').val(time);
                }
            });
            $('#tuesday_start').click();
        });
        $( ".tuesday_end_time" ).click(function() {
            $('#tuesday_end').hide();
            $('#tuesday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#tuesday_end').val();
                    $('#tuesday_end_time').val(time);
                }
            });
            $('#tuesday_end').click();
        });

        $( ".wednesday_start_time" ).click(function() {
            $('#wednesday_start').hide();
            $('#wednesday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#wednesday_start').val();
                    $('#wednesday_start_time').val(time);
                }
            });
            $('#wednesday_start').click();
        });
        $( ".wednesday_end_time" ).click(function() {
            $('#wednesday_end').hide();
            $('#wednesday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#wednesday_end').val();
                    $('#wednesday_end_time').val(time);
                }
            });
            $('#wednesday_end').click();
        });

        $( ".thursday_start_time" ).click(function() {
            $('#thursday_start').hide();
            $('#thursday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#thursday_start').val();
                    $('#thursday_start_time').val(time);
                }
            });
            $('#thursday_start').click();
        });
        $( ".thursday_end_time" ).click(function() {
            $('#thursday_end').hide();
            $('#thursday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#thursday_end').val();
                    $('#thursday_end_time').val(time);
                }
            });
            $('#thursday_end').click();
        });

        $( ".friday_start_time" ).click(function() {
            $('#friday_start').hide();
            $('#friday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#friday_start').val();
                    $('#friday_start_time').val(time);
                }
            });
            $('#friday_start').click();
        });
        $( ".friday_end_time" ).click(function() {
            $('#friday_end').hide();
            $('#friday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#friday_end').val();
                    $('#friday_end_time').val(time);
                }
            });
            $('#friday_end').click();
        });

        $( ".saturday_start_time" ).click(function() {
            $('#saturday_start').hide();
            $('#saturday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#saturday_start').val();
                    $('#saturday_start_time').val(time);
                }
            });
            $('#saturday_start').click();
        });
        $( ".saturday_end_time" ).click(function() {
            $('#saturday_end').hide();
            $('#saturday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#saturday_end').val();
                    $('#saturday_end_time').val(time);
                }
            });
            $('#saturday_end').click();
        });

        $( ".sunday_start_time" ).click(function() {
            $('#sunday_start').hide();
            $('#sunday_start').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#sunday_start').val();
                    $('#sunday_start_time').val(time);
                }
            });
            $('#sunday_start').click();
        });
        $( ".sunday_end_time" ).click(function() {
            $('#sunday_end').hide();
            $('#sunday_end').pickatime({
                interval: 5,
                onSet:function(){
                    let time = $('#sunday_end').val();
                    $('#sunday_end_time').val(time);
                }
            });
            $('#sunday_end').click();
        });

    });
</script>