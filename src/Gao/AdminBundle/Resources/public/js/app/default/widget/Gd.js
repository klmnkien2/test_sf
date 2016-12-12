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
    }).as('app.default.widget.Gd').it.provides({
        build : function() {
            this.bindAllListeners();
        },
        /**
         * manage an event
         */
        bindAllListeners : function() {
            this.root.on('click', '.prg-approveTran', $.proxy(this, 'confirmTransaction'));
            this.root.on('click', '#prg-selectAll', $.proxy(this, 'selectAllTransaction'));
        },
        selectAllTransaction : function(evt) {
        	this.root.find('input:checkbox').prop('checked', $(evt.target).prop("checked"));
        },
        confirmTransaction : function(evt) {
            var r = confirm("Ban muon xac nhan nhung nguoi nay da chuyen tien?");
            if (r == true) {
            	var id = $(evt.target).data('id');
                this.approveTransaction(id);
            }
        },
        approveTransaction : function(transactionId) {
            var that = this
                , url = this.getBaseUrl() + "/c5/transaction/approve";
            this.homeLoader.show();
            // Assign handlers immediately after making the request,
            // and remember the jqxhr object for this request
            var jqxhr = $.post( url, { id: transactionId } );
            jqxhr.done(function(data) {
            	if (data.status == 'success') {
	                that.approveSuccess( "Da xac nhan yeu cau cua ban. Cam on da su dung." );
            	} else {
            		that.approveUnSuccess( "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            	}
            })
            .fail(function() {
                that.approveUnSuccess( "Yeu cau thuc hien khong thanh cong. Vui long thu lai." );
            })
            .always(function() {
                that.homeLoader.hide();
            });
        },
        approveSuccess: function(message) {
        	this.alertMessage.html(message);
        	this.alertMessage.attr('class', 'alert alert-info');
        	this.alertMessage.show();
        },
        approveUnSuccess: function(message) {
        	this.alertMessage.html(message);
        	this.alertMessage.attr('class', 'alert alert-danger');
        	this.alertMessage.show();
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
