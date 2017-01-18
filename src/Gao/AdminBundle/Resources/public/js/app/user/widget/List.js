(function ($, def) {

  /**
   * this widget manage global header
   */
  def(function () {
    
  }).as(
    'app.user_manage.widget.List'
  ).it.provides({
      build: function () {
        this.initialize();
        this.checkBoxSetup();
        this.bindAllListeners();
      },
      /**
       * manage an event
       */
      bindAllListeners : function() {
        $(".prg-action-menu").on('click', $.proxy(this, 'onActionCall'));
      },
      /**
       * initialize third party lib or somthing...
       */
      initialize: function () {
        var url = $('#prg-userTable').data('loadUrl');
        var that = this;
        var dt = $('#prg-userTable').DataTable({
          "processing": true,
          "serverSide": true,
          "ajax": url,
          "order": [],
          "aoColumns": [
            { "sTitle": "ID", "sWidth": "40px", "bSearchable": false, "bSortable": false },
            { "sTitle": "Username" },
            { "sTitle": "Fullname", "search": "user" },
            { "sTitle": "Phone" },
            { "sTitle": "Level" },
            { "sTitle": "Status" },
            { "sTitle": "Action", "bSearchable": false, "bSortable": false },
            {
            	"sTitle": "<input type='checkbox' id='prg-selectAll' />",
            	"sWidth": "30px",
            	"sClass": "col-center",
            	"bSearchable": false,
            	"bSortable": false,
              "mRender": function ( data, type, full ) {
                return '<input name="user_ids[]" value="' + data + '" type="checkbox" />';
              }
            }
          ]
        });
        // On each draw, loop over the `detailRows` array and show any child rows
        dt.on( 'draw', function () {
          $(".prg-userBlock").on('change', $.proxy(that, 'onBlockedChanged'));
        } );
      },
      checkBoxSetup: function () {
        // Handle click on "Select all" control
        $('#prg-selectAll').on('click', function(){
           // Check/uncheck checkboxes for all rows in the table
           $('input[type="checkbox"]').prop('checked', this.checked);
        });

        // Handle click on checkbox to set state of "Select all" control
        $('#prg-userTable tbody').on('change', 'input[type="checkbox"]', function(){
           // If checkbox is not checked
           if(!this.checked){
              var el = $('#prg-selectAll').get(0);
              // If "Select all" control is checked and has 'indeterminate' property
              if(el && el.checked && ('indeterminate' in el)){
                 // Set visual state of "Select all" control 
                 // as 'indeterminate'
                 el.indeterminate = true;
              }
           }
        });
      },
      onBlockedChanged: function (evt) {
        evt.preventDefault();
        var r = confirm("Do you really want to change this user?");
        if (r == true) {
          var select = $(evt.currentTarget)
          , method = 'POST'
          , action = this.getBaseUrl() + "/admin/user/block_status?id=" + select.data('id') + "&blocked=" + select.val()
          // Create a form on click
          ,form = $('<form/>', {
            style:  "display:none;",
            method: method,
            action: action,
          });

        form.appendTo(select);

        // Submit the form
        form.submit();
        }
      },
      onActionCall: function (event) {
        event.preventDefault();

        var r = confirm("Do you really want to run this action?");
        if (r == true) {
          var target = $(event.currentTarget)
            , method = 'POST'
            , action = target.attr('href')
            // Create a form on click
            ,form = $('#prg-userListForm');

          form.attr('method', method);
          form.attr('action', action);

          // Submit the form
          form.submit();
        }
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
