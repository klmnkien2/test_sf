(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.user_manage.widget.List'
  ).it.provides({
      build: function () {
        this.initialize();
        //this.bindAllListeners();
      },
      /**
       * manage an event
       */
      bindAllListeners : function() {
      	//$(".prg-userBlock").on('change', $.proxy(this, 'onBlockedChanged'));
      },
      /**
       * initialize third party lib or somthing...
       */
      initialize: function () {
        var url = this.getBaseUrl() + "/admin/user/ajax_list";
        var that = this;
        var dt = $('#prg-adminTable').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": url
        });
        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {
          $(".prg-userBlock").on('change', $.proxy(that, 'onBlockedChanged'));
        } );
      },
      onBlockedChanged: function (evt) {
        evt.preventDefault();
        var r = confirm("Do you really want to change this user?");
        if (r == true) {
          var select = $(evt.currentTarget)
          , method = 'POST'
          , action = this.getBaseUrl() + "/admin/user/block_status?id=" + select.data('id') + "&blocked=" + select.val()
          // Create a form on click
          ,form = $('<form/>', {
            style:  "display:none;",
            method: method,
            action: action,
          });

        form.appendTo(select);

        // Submit the form
        form.submit();
        }
      },
      deleteAction: function (event) {
        event.preventDefault();

        var r = confirm("Do you really want to delete this record?");
        if (r == true) {
          var target = $(event.currentTarget)
            , method = 'POST'
            , action = target.attr('href')
            // Create a form on click
            ,form = $('<form/>', {
              style:  "display:none;",
              method: method,
              action: action,
            });

          form.appendTo(target);

          // Submit the form
          form.submit();
        }
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
