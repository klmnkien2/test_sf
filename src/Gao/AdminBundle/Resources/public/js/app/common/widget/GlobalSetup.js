(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    this.popupMessage = $('#popup-message');
  }).as(
    'app.common.widget.GlobalSetup'
  ).it.provides({
      build: function () {
        this.initialize();
      },
      /**
       *  manage an event
       */
      initialize: function () {
    	  this.popupMessage.dialog({
		    autoOpen : false, modal : true, show : "blind", hide : "blind"
		  });
      }
  });
}(jQuery, require_joo()));
