(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.system.widget.Tool'
  ).it.provides({
      build: function () {
        this.bindAllListeners();
      },
      /**
       * manage an event
       */
      bindAllListeners : function() {
        $("#create-pin").on('submit', $.proxy(this, 'createPin'));
      },
      createPin: function (event) {
        event.preventDefault();

        alert(1);
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();

          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
          	if (data.status == 'success') {
                that.ajaxMessage( true, $('#create-pin-message'), "Da xac nhan yeu cau cua ban. Cam on da su dung." );
          	} else {
          		that.ajaxMessage( false, $('#create-pin-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          	}
          })
          .fail(function() {
              that.ajaxMessage( false, $('#create-pin-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      ajaxMessage : function(is_success, div, message) {
      	
      },
      /**
       * Get base url
       */
      getBaseUrl : function() {
          if (typeof location.origin === 'undefined')
              location.origin = location.protocol + '//' + location.host;
          return location.origin;
      }
  });
}(jQuery, require_joo()));
