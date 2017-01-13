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
        $("#matchTransaction-frm").on('submit', $.proxy(this, 'matchTransaction'));
        $("#cleanUp-frm").on('submit', $.proxy(this, 'cleanUp'));
        $("#resetUser-frm").on('submit', $.proxy(this, 'resetUser'));
        $("#forceRequest-frm").on('submit', $.proxy(this, 'forceRequest'));
        $("#forceDone-frm").on('submit', $.proxy(this, 'forceDone'));
      },
      createPin: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (data.error === false) {
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
      matchTransaction: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (typeof data.output !== "undefined") {
              $('#prg-generalModal .modal-title').text("Running log");
              $('#prg-generalModal .modal-body').html(data.output);
              $('#prg-generalModal').modal('show');
            } else {
              that.ajaxMessage( false, $('#matchTransaction-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            }
          })
          .fail(function() {
              that.ajaxMessage( false, $('#matchTransaction-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      cleanUp: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (data.error === false) {
                that.ajaxMessage( true, $('#cleanUp-message'), "Da xac nhan yeu cau cua ban. Cam on da su dung." );
            } else {
              that.ajaxMessage( false, $('#cleanUp-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            }
          })
          .fail(function() {
              that.ajaxMessage( false, $('#cleanUp-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      resetUser: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (data.error === false) {
                that.ajaxMessage( true, $('#resetUser-message'), "Da xac nhan yeu cau cua ban. Cam on da su dung." );
            } else {
              that.ajaxMessage( false, $('#resetUser-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            }
          })
          .fail(function() {
              that.ajaxMessage( false, $('#resetUser-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      forceRequest: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (data.error === false) {
                that.ajaxMessage( true, $('#forceRequest-message'), "Da xac nhan yeu cau cua ban. Cam on da su dung." );
            } else {
              that.ajaxMessage( false, $('#forceRequest-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            }
          })
          .fail(function() {
              that.ajaxMessage( false, $('#forceRequest-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      forceDone: function (event) {
        event.preventDefault();
        var r = confirm("Are you sure?");
        if (r == true) {
          var that = this
            , target = $(event.currentTarget)
            , url = this.getBaseUrl() + target.attr('action')
            , data = target.serialize();
          var jqxhr = $.post( url, data );
          jqxhr.done(function(data) {
            if (data.error === false) {
                that.ajaxMessage( true, $('#forceDone-message'), "Da xac nhan yeu cau cua ban. Cam on da su dung." );
            } else {
              that.ajaxMessage( false, $('#forceDone-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            }
          })
          .fail(function() {
              that.ajaxMessage( false, $('#forceDone-message'), "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
          });
        }
      },
      ajaxMessage : function(is_success, div, message) {
        var content = '<div class="alert alert-' + (is_success?'info':'danger') + ' alert-dismissible">' +
          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
          message +
          '</div>';
        div.html(content);
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
