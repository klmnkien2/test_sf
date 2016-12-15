(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
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
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      }
  });
}(jQuery, require_joo()));
