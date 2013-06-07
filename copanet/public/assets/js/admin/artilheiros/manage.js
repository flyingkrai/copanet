// Generated by CoffeeScript 1.6.2
define(['index', 'admin/manage'], function(Index) {
  Index = Index || {};
  Index.Artilheiro = (function(Artilheiro, $) {
    Artilheiro.manage = (function() {
      var bindEvents, getTimes, __departamentSelect, __tableTBody;

      __departamentSelect = '.time-select';
      __tableTBody = '.table-tbody';
      getTimes = function(selected) {
        var url;

        if (selected.length <= 0) {
          return false;
        }
        url = selected.data('url');
        return $.ajax({
          beforeSend: function() {
            return Index.uiBlocker();
          },
          url: url,
          type: 'GET',
          dataType: 'html'
        }).done(function(data) {
          if (data === void 0 || data === '') {
            return false;
          }
          return $(__tableTBody).empty().html(data);
        }).always(function() {
          return Index.uiBlocker();
        });
      };
      bindEvents = function() {
        return $(__departamentSelect).bind('change', function() {
          return getTimes($(this).find('option:selected'));
        });
      };
      return {
        init: function() {
          return bindEvents();
        }
      };
    })();
    return Artilheiro;
  })(Index.Artilheiro || {}, jQuery);
  return jQuery(function() {
    return Index.Artilheiro.manage.init();
  });
});