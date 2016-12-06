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
      var url = this.getBaseUrl() + "/attachment/upload/dispute"
      	,that = this;

      $('#fileupload').fileupload({
          url : url,
          dataType : 'json',
          done : function(e, data) {
              if (data.result.error === false) {
                  // Add text filename
            	  //var deleteBtn = '<a data-id="' + data.result.attachment.id + '" class="prg-deleteFile"><span class="glyphicon glyphicon-remove"></span></a>';
            	  var deleteBtn = $('<a/>')
            	  	.attr('data-id', data.result.attachment.id)
            	  	.attr('class', 'prg-deleteFile glyphicon glyphicon-remove')
            	  	.on('click', $.proxy(that, 'deleteFileUpload'));
                  $('<p/>').css('color', 'blue').text(data.result.attachment.name).append(deleteBtn).appendTo('#files');
                  // Add input attachment id
                  $('<input>').attr({
                      type: 'hidden',
                      value: data.result.attachment.id,
                      name: 'attachment[]'
                  }).appendTo('#prg-disputeForm');
              }

              that.doneProgress();
          },
          progressall : function(e, data) {
              var progress = parseInt(data.loaded / data.total * 100, 10);
              that.updateProgress(progress);
          }
      }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
    },
    /**
     * delete file ajax
     */
    deleteFileUpload : function(event) {
    	event.stopPropagation();
    	event.preventDefault();

        var deleteBtn = $(event.target)
        	, delete_id = deleteBtn.attr('data-id')
        	, url = this.getBaseUrl() + "/attachment/delete?id=" + delete_id + "&token=" + $("#csrf_token_attachment").val();
        var jqxhr = $.get( url );
        jqxhr.done(function(data) {
        	if (data.error === false) {
        		deleteBtn.closest( "p" ).remove();
                $("input[name='attachment\[\]'][value='" + delete_id + "']").remove();
        	} else {
        		// Khong thanh cong
        		alert(data.error);
        	}
        })
        .fail(function() {
        	// Khong thanh cong
        })
        .always(function() {
            // Do something
        });
    },
    /**
     * Get base url
     */
    getBaseUrl : function() {
        if (typeof location.origin === 'undefined')
            location.origin = location.protocol + '//' + location.host;
        return location.origin;
    },
    /**
     * Progress done
     */
    doneProgress : function() {
        $('#prg-uploadProgress .progress-bar').css('width', 0);
        $('#prg-uploadProgress').hide();
    },
    /**
     * Progress update
     */
    updateProgress : function(progress) {
    	$('#prg-uploadProgress').show();
    	$('#prg-uploadProgress .progress-bar').css('width', progress + '%');
    }
  });
}(jQuery, require_joo()));
