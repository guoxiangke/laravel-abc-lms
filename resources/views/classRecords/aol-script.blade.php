<script>
    window.onload = function () {
        $('.post-action').click(function(e){
          e.preventDefault();
          var msg = "This action cannot be undone, Are you sure to flag?";
          @role('student')
          msg = "为保证您的课时有效期，您每月只有2次自助请假机会，超过请联系专属课程顾问。本次请假操作不可撤销，确定请假？";
          @endrole
          var that = $(this);
          if(!that.hasClass('btn-outline-danger')){
            alert('不可再次点击');
            return 0;
          }

          if (confirm(msg)) {
            thisException = that.data('exception');
            thisParent = that.parent('td');

            var actions = that.parent('td');
            // var nextType = that.data('type')=='aol'?'absent':'aol';
            // var next = actions.find('a[data-type='+nextType+']');
            var statusText = that.attr('label');
            var target = actions.parent('tr').find('.exception');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
              url:that.attr('href'),
              type:"POST",
              success: function(data) {
                if(data.success){
                  target.text(statusText);
                  that.removeClass('btn-outline-danger').addClass('btn-warning');
                  if(thisException==0){
                    that.removeClass('btn-warning').addClass('btn-success');
                  }
                  thisParent.find('.post-action').each(function(){
                    thatException = $(this).data('exception');
                    if(thisException != thatException){
                      $(this).removeClass('btn-warning').addClass('btn-outline-danger');
                      if(thatException==0){
                        $(this).removeClass('btn-success');
                      }
                    }


                  })
                  @role('student')
                  actions.text('--');
                  @endrole
                }
              },
            });
          }
        });
    }
</script>
