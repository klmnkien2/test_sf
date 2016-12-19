(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.admin.widget.List'
  ).it.provides({
      build: function () {
        this.initialize();
      },
      /**
       *  manage an event
       */
      initialize: function () {
        var url = this.getBaseUrl() + "/admin/account/ajax_list";
        $('#prg-adminTable').DataTable({
          "processing": true,
           "serverSide": true,
           "ajax": url
          });
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
