(function($, def) {
  // this is sample code of widget
  /**
   * manage thumbnail of the realestate atrticle
   */
    def(function() {
        this.root = $('#prg-formFilter');
    }).as('app.default.widget.History').it.provides({
        build : function() {
            this.bindAllListeners();
        },
        /**
         * manage an event
         */
        bindAllListeners : function() {
            this.root.on('click', '#prg-filterResult', $.proxy(this, 'doFilter'));
        },
        doFilter : function(evt) {
        	var pd_or_gd = this.root.find('#pd_or_gd').val()
        		, tran_status = this.root.find('#tran_status').val()
        		, url = this.getBaseUrl() + "/c5/his?pd_or_gd=" + pd_or_gd + "&tran_status=" + tran_status;
        	document.location.href = url;
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
