<script>
    window.onload = function () {
        $('.post-action').click(function(e){
          e.preventDefault();
          var msg = "This action cannot be undone, Are you sure to flag?";
          @role('student')
          msg = "为保证您的课时有效期，您每月只有2次自助请假机会，超过请联系专属课程顾问。本次请假操作不可撤销，确定请假？";
          @endrole
          if (confirm(msg)) {
            var that = $(this);
            thisException = that.data('exception');
            thisParent = that.parent('td')

            var actions = that.parent('td');
            var nextType = that.data('type')=='aol'?'absent':'aol';
            var next = actions.find('a[data-type='+nextType+']');
            var statusText = that.attr('label');
            var target = actions.parent('tr').find('.exception');
             $.ajax({
              type:"GET",
              url:that.attr('href'),
              success: function(data) {
                if(data.success){
                  target.text(statusText);
                  that.removeClass('btn-outline-danger').addClass('btn-warning');
                  thisParent.find('.post-action').each(function(){
                    thatException = $(this).data('exception');
                    if(thisException != thatException){
                      $(this).removeClass('btn-warning').addClass('btn-outline-danger');
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
