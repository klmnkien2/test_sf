(function () {
  var route = require_router();

  route(function (map) {
    // common modules
    map.common('app.common.mapping');

    // routing
    map.go({
      id: 'default_pd',
      to: 'app.default.mapping_pd'
    });
    map.go({
      id: 'default_dispute',
      to: 'app.default.mapping_dispute'
    });
    map.go({
      id: 'no_match',
      to: 'app.no_match.mapping'
    });
  }, function getId() {
    var root = $(document.documentElement);
    return root.data('pageid') || 'no-match';
  });
}());
