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
  }, function getId() {
    var root = $(document.documentElement);
    return root.data('pageid') || 'no-match';
  });
}());
