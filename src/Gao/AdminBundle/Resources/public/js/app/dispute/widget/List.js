(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.admin.dispute.widget.List'
  ).it.provides({
      build: function () {
        this.initialize();
        //this.bindAllListeners();
      },
      /**
       * manage an event
       */
      bindAllListeners : function() {
        //$(".deletelink").on('click', $.proxy(this, 'deleteAction'));
      },
      /**
       * initialize third party lib or somthing...
       */
      initialize: function () {
        var url = this.getBaseUrl() + "/admin/dispute/ajax_list";
        var that = this;
        var dt = $('#prg-adminTable').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": url
        });
        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {
          $(".deletelink").on('click', $.proxy(that, 'deleteAction'));
        } );
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
