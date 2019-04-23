<script type="text/javascript">
    window.onload = function () {
        $('#captcha').on('click',function(){
            var captcha = $(this);
            var url = "/captcha/" + captcha.data('captcha-config') + '/?' + Math.random();
            captcha.attr('src',url);
        });
    }
</script>
