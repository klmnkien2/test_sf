(function () {
  var route = require_router();

  route(function (map) {
    // common modules
    map.common('app.common.mapping');

    // routing
    map.go({
      id: 'no_match',
      to: 'app.no_match.mapping'
    });
    map.go({
      id: 'admin',
      to: 'app.admin.mapping'
    });
    map.go({
      id: 'user_manage',
      to: 'app.user_manage.mapping'
    });
    map.go({
      id: 'admin_default',
      to: 'app.admin_default.mapping'
    });
    map.go({
      id: 'system',
      to: 'app.system.mapping'
    });
    map.go({
      id: 'dispute',
      to: 'app.admin.dispute.mapping'
    });
  }, function getId() {
    var root = $(document.documentElement);
    return root.data('pageid') || 'no-match';
  });
}());
