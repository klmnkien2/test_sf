(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
  def(function() {
    this.root = $('#frmAuction');
  }).as('app.shop.widget.AddAuction').it.provides({
    build : function() {
      this.bindAllListeners();
      this.initDatePicker();
      this.initEditor();
    },
    /**
     * manage an event
     */
    bindAllListeners : function() {
      // this.root.find('.thumbViewSlider').on('click', '.nav .prev', $.proxy(this, 'prev'));
    },
    /**
     * initDatePicker fields
     * 
     */
    initDatePicker : function() {
    	$('#auction_startTime_date').datetimepicker({
            format: "d/m/Y",
            timepicker: false,
            datepicker: true,
        });
        $('#auction_startTime_time').datetimepicker({
            format: "H:i",
            timepicker: true,
            datepicker: false,
            step:5
        });
        $('#auction_endTime_date').datetimepicker({
        	format: "d/m/Y",
            timepicker: false,
            datepicker: true,
        });
        $('#auction_endTime_time').datetimepicker({
            format: "H:i",
            timepicker: true,
            datepicker: false,
            step:5
        });
    },
    
    initEditor : function() {
    	$(".editor").jqte();
    }
  });
}(jQuery, require_joo()));
