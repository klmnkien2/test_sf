(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
    def(function() {
        this.root = $('#prg-tranTable');
        this.alertMessage = $('#prg-tranAlert');
        this.homeLoader = $('body').loadingIndicator(
            {
                useImage: false,
                showOnInit: false
            }
        ).data("loadingIndicator");
    }).as('app.default.widget.Pd').it.provides({
        build : function() {
            this.bindAllListeners();
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
            var that = this
            	, url = this.getBaseUrl() + "/transaction/approve";
            this.homeLoader.show();
	        // Assign handlers immediately after making the request,
	        // and remember the jqxhr object for this request
	        var jqxhr = $.post( url, { id: 3 } );
	        jqxhr.done(function(data) {
	        	that.alertMessage.html( "Da xac nhan yeu cau cua ban. Cam on da su dung." );
	        	that.alertMessage.attr('class', 'alert alert-info');
	        	that.alertMessage.show();
	        })
	        .fail(function() {
	        	that.alertMessage.html( "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
	        	that.alertMessage.attr('class', 'alert alert-danger');
	        	that.alertMessage.show(); 
	        })
	        .always(function() {
	        	that.homeLoader.hide();
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
