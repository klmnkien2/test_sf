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
  }, function getId() {
    var root = $(document.documentElement);
    return root.data('pageid') || 'no-match';
  });
}());
