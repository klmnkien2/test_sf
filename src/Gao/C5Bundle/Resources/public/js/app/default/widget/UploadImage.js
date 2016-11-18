(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
  def(function() {
      this.root = $('#prg-disputeForm');
  }).as('app.default.widget.UploadImage').it.provides({
    build : function() {
      this.bindAllListeners();
      this.initUploadAjax();
    },
    /**
     * manage an event
     */
    bindAllListeners : function() {
        //this.root.on('change', '#fileupload', $.proxy(this, 'selectFileUpload')); // for upload file no ajax
    },
    /**
     * trigger fileselect event
     */
    selectFileUpload : function(event) {
        var input = event.target,
            numFiles = input.files ? input.files.length : 1,
            label = input.files[0].name.replace(/\\/g, '/').replace(/.*\//, '');
        console.log(numFiles);
        console.log(label);
    },
    /**
     * init calling to upload lib
     * Upload file using ajax
     */
    initUploadAjax : function() {
      var url = this.getBaseUrl() + "/attachment/upload/dispute";

      $('#fileupload').fileupload({
          url : url,
          dataType : 'json',
          done : function(e, data) {
              if (data.result.error === false) {
                  // Add text filename
                  $('<p/>').text(data.result.attachment.name).appendTo('#files');
                  // Add input attachment id
                  $('<input>').attr({
                      type: 'hidden',
                      value: data.result.attachment.id,
                      name: 'attachment[]'
                  }).appendTo('#prg-disputeForm');
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
