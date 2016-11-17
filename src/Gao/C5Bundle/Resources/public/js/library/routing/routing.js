/*! @license
 * Copyright (c) 2013 nazomikan
 * https://github.com/nazomikan/routingjs
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

(function (global, doc) {

  function Map(pageInfo) {
    this.id = pageInfo.id;
    this.widget = [];
    this.core = [];
    this.isAlreadyDispatch = false;
  }

  Map.prototype.common = function (path) {
    var setting = parse(path) || {}
      , core = setting.core || []
      , widget = setting.widget || []
      ;

    this.core = core.concat(this.core);
    this.widget = widget.concat(this.widget);
  }

  Map.prototype.go = function (config) {
    var that = this
      , mapping
      ;

    config = config || {};

    if (this.isAlreadyDispatch || config.id !== this.id) {
      return;
    }

    mapping = parse(config.to);
    this.core = this.core.concat(mapping.core || []);
    this.widget = this.widget.concat(mapping.widget || []);

    $(document).ready(function () {
      dispatch(that.core, that.widget);
    });

    this.isAlreadyDispatch = true;
  };



  function dispatch(widgets, cores) {
    var i
      , iz
      , modules = []
      , Module
      , module
      ;

    modules = modules.concat(widgets, cores);

    for (i = 0, iz = modules.length; i < iz; i++) {
      Module = parse(modules[i]);
      module = new Module();
      if (!module.build) {
        throw new Error(modules[i] + ' does not have #build api');
      }
      module.build();
    }
  }

  function parse(path) {
    var chain = path.split('.')
      , parent = global
      , i
      , iz
      ;

    for (i = 0, iz = chain.length; i < iz; i++) {
      parent = parent[chain[i]];
      if (!parent) {
      throw new Error('can not parse: ' + path);
      }
    }

    return parent;
  }

  global.require_router = function () {
    return function route(fn, getId) {
      var id = getId()
        , map = new Map({id: id})
        ;

      fn(map);
    };
  };
}(window, document));
