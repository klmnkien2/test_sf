(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
  def(function() {
      this.root = $('#prg-tranTable');
  }).as('app.default.widget.Pd').it.provides({
      build : function() {
	      this.bindAllListeners();
	      this.initDatePicker();
	      this.initEditor();
      },
    /**
     * manage an event
     */
    bindAllListeners : function() {
        this.root.on('click', '.prg-approveTran', $.proxy(this, 'approveTransaction'));
    },
    /**
     * initDatePicker fields
     * 
     */
    approveTransaction : function() {
    	alert(1);
    }
  });
}(jQuery, require_joo()));
