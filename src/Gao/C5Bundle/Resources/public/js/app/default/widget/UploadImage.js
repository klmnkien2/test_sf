(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
  def(function() {
    //this.root = $('#prg-raThumbnail');
  }).as('app.default.widget.UploadImage').it.provides({
    build : function() {
      this.bindAllListeners();
      this.initUpload();
    },
    /**
     * manage an event
     */
    bindAllListeners : function() {
      // Example listener
      // this.root.find('.thumbViewSlider').on('click', '.nav .prev', $.proxy(this, 'prev'));
    },
    /**
     * init calling to upload lib
     * 
     */
    initUpload : function() {
      var url = this.getBaseUrl() + "/attachment/upload/dispute";

      $('#fileupload').fileupload(
          {
            url : url,
            dataType : 'json',
            done : function(e, data) {
              if (data.result.status == 'success') {
                $('<p/>').text(data.result.message).appendTo('#files');
              } else {

              }
              $('#prg-uploadProgress .progress-bar').css('width', 0);
              $('#prg-uploadProgress').hide();
            },
            progressall : function(e, data) {
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('#prg-uploadProgress').show();
              $('#prg-uploadProgress .progress-bar').css('width', progress + '%');
            }
          }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
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
