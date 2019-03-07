window.addEventListener('load', function () {
  var lisaEditorApp = new Vue({
    el: '#lisa-editor',
    data: {
      editorCode: window.lisaEditorCode,
      code: window.lisaEditorCode
    },
    mounted () {
      var vm = this;

      var langTools = ace.require('ace/ext/language_tools');

      var editor = ace.edit('lisa_code_editor');
      editor.setTheme('ace/theme/monokai');
      editor.getSession().setMode('ace/mode/twig');
      editor.getSession().setTabSize(2);
      editor.setHighlightActiveLine(true);
      editor.setAutoScrollEditorIntoView(true);
      editor.setOptions({
        maxLines: Infinity,
        fontSize: '18px',
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true
      });

      editor.getSession().on('change', function(e) {
        vm.code = editor.getSession().getValue();
      });
    }
  });

  var lisaConditionsApp = new Vue({
    el: '#lisa-conditions',
    data: {
      placement: window.lisaConditions.placement,
      method: window.lisaConditions.method,
      hook: window.lisaConditions.hook
    }
  });

  var lisaDataApp = new Vue({
    el: '#lisa-data',
    data: {
      source: window.lisaData.source,
      query: window.lisaData.query
    },
    mounted () {
      var vm = this;

      var editor = ace.edit('lisa_query_editor');
      editor.setTheme('ace/theme/monokai');
      editor.getSession().setMode('ace/mode/json');
      editor.getSession().setTabSize(2);
      editor.setHighlightActiveLine(true);
      editor.setAutoScrollEditorIntoView(true);
      editor.setOptions({
        maxLines: Infinity,
        fontSize: '18px'
      });

      editor.getSession().on('change', function(e) {
        vm.query = editor.getSession().getValue();
      });
    }
  });
});
