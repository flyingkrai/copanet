// Generated by CoffeeScript 1.6.2
define(['jquery', 'index', 'jquery.migrate', 'form'], function($, Index) {
  Index = Index || {};
  Index.Admin = (function(Admin) {
    Admin.manage = (function() {
      var bindEvents, disableToggle, getArtilheiros, getForm, getGols, getTimes, validate, __addButton, __artilheiroSelect, __comboClass, __departamentoSelect, __editButton, __formElement, __formModal, __golsInput, __onEdit, __saveButton, __timeSelect, _triggerAjaxForm, _triggerSelects;

      __formModal = '';
      __formElement = 'form.modal-form';
      __addButton = '.btn-add-gols';
      __editButton = '.btn-edit-gols';
      __saveButton = '.btn-save-widget';
      __departamentoSelect = '.departamento-select';
      __timeSelect = '.time-select';
      __artilheiroSelect = '.artilheiro-select';
      __comboClass = '.combobox';
      __golsInput = 'input.gols';
      __onEdit = false;
      getForm = function(target) {
        return $.ajax({
          beforeSend: function() {
            return Index.uiBlocker();
          },
          url: target.attr('href'),
          type: 'GET',
          dataType: 'html'
        }).done(function(data) {
          var modal;

          if (data === 'undefined' || data === '') {
            return false;
          }
          modal = $(Index.modals.widgetForm);
          $(modal).find('#modalBody').html(data);
          $(modal).find('#modalLabel').html('Adicionar/Subtrair Gols');
          $(modal).modal();
          __formModal = modal;
          $(__formModal).on('hidden', function() {
            return $(this).remove('.modal');
          });
          _triggerAjaxForm();
          return _triggerSelects();
        }).always(function() {
          return Index.uiBlocker();
        });
      };
      _triggerAjaxForm = function() {
        return $(__formModal).find(__formElement).ajaxForm({
          beforeSubmit: function() {
            return Index.uiBlocker();
          },
          success: function(data) {
            Index.uiBlocker();
            $(__formModal).modal('hide');
            if (__onEdit) {
              __onEdit = false;
              return setTimeout(function() {
                return window.location.href = document.URL;
              }, 1000);
            }
          },
          error: function() {
            return Index.uiBlocker();
          }
        });
      };
      _triggerSelects = function() {
        var form;

        form = $(__formModal);
        form.find(__departamentoSelect).bind('change', function() {
          return getTimes($(this).children('option:selected'));
        });
        return form.find(__timeSelect).bind('change', function() {
          return getArtilheiros($(this).children('option:selected'));
        });
        /*form.find(__artilheiroSelect).bind 'change', ->
          getGols $(@).children('option:selected')
        */

      };
      getTimes = function(departamento) {
        if (departamento.val() === '') {
          disableToggle('all');
          return false;
        }
        return $.ajax({
          beforeSend: function() {
            Index.uiBlocker();
            return disableToggle('all');
          },
          url: departamento.data('url'),
          type: 'GET',
          dataType: 'html'
        }).done(function(data) {
          if (data === 'undefined' || data === '') {
            return false;
          }
          disableToggle('time', false);
          return $(__timeSelect).empty().html(data);
        }).always(function() {
          return Index.uiBlocker();
        });
      };
      getArtilheiros = function(time) {
        if (time.val() === '') {
          disableToggle('artilheiro');
          return false;
        }
        return $.ajax({
          beforeSend: function() {
            Index.uiBlocker();
            return disableToggle('artilheiro');
          },
          url: time.data('url'),
          type: 'GET',
          dataType: 'html'
        }).done(function(data) {
          if (data === 'undefined' || data === '') {
            return false;
          }
          disableToggle('artilheiro', false);
          return $(__artilheiroSelect).empty().html(data);
        }).always(function() {
          return Index.uiBlocker();
        });
      };
      getGols = function(artilheiro) {
        var gols;

        gols = $(__formModal).find(__golsInput);
        gols.val(0);
        if (artilheiro.val() === '') {
          return false;
        }
        return $.ajax({
          beforeSend: function() {
            return Index.uiBlocker();
          },
          url: artilheiro.data('url'),
          type: 'GET',
          dataType: 'html'
        }).done(function(data) {
          if (data === 'undefined' || data === '') {
            return false;
          }
          return gols.val(data);
        }).always(function() {
          return Index.uiBlocker();
        });
      };
      validate = function() {
        var artilheiro, departamento, form, gols, time;

        form = $(__formModal);
        departamento = form.find(__departamentoSelect).val();
        time = form.find(__timeSelect).val();
        artilheiro = form.find(__artilheiroSelect).val();
        gols = parseInt(form.find(__golsInput).val());
        if ((departamento == null) || departamento === '' || (time == null) || time === '' || (artilheiro == null) || artilheiro === '') {
          return false;
        }
        if (isNaN(gols)) {
          return false;
        }
        return true;
      };
      disableToggle = function(which, disable) {
        if (disable == null) {
          disable = true;
        }
        switch (which) {
          case 'all':
            return $(__formModal).find(__comboClass).empty().prop('disabled', disable);
          case 'time':
            return $(__formModal).find(__timeSelect).empty().prop('disabled', disable);
          case 'artilheiro':
            return $(__formModal).find(__artilheiroSelect).empty().prop('disabled', disable);
          default:
            return true;
        }
      };
      bindEvents = function() {
        $(__addButton).bind('click', function(event) {
          event.preventDefault();
          return getForm($(this));
        });
        $(__editButton).live('click', function(event) {
          event.preventDefault();
          __onEdit = true;
          return getForm($(this));
        });
        return $(__saveButton).live('click', function(event) {
          if (!validate()) {
            return false;
          }
          return $(__formModal).find(__formElement).submit();
        });
      };
      return {
        init: function() {
          return bindEvents();
        }
      };
    })();
    return Admin;
  })(Index || {});
  return $(function() {
    return Index.Admin.manage.init();
  });
});