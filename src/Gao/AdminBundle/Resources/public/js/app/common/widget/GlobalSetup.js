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

        //Initialize Select2 Elements
        $(".select2").select2();

        //To make Pace works on Ajax calls
        $(document).ajaxStart(function() { Pace.restart(); });
      }
  });
}(jQuery, require_joo()));
