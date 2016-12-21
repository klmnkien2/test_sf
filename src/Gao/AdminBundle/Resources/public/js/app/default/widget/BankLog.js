(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.admin_default.widget.BankLog'
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
        var url = this.getBaseUrl() + "/admin/ajax_bank";
        var that = this;
        var dt = $('#prg-adminTable').DataTable({
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
