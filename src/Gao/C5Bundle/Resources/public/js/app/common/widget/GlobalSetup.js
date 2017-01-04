(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    //this.popupMessage = $('#prg-popup-message');
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
    	  //do something
      }
  });
}(jQuery, require_joo()));
