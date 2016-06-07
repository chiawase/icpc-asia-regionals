(function() {
  tinymce.create('tinymce.plugins.Form_Maker_mce', {
    init : function(ed, url) {
      ed.addCommand('mceForm_Maker_mce', function() {
        ed.windowManager.open({
          file : form_maker_admin_ajax,
					width : 550 + ed.getLang('Form_Maker_mce.delta_width', 0),
					height : 300 + ed.getLang('Form_Maker_mce.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
      ed.addButton('Form_Maker_mce', {
        title : 'Insert Form Maker',
        cmd : 'mceForm_Maker_mce',
        image: url + '/images/form_maker_edit_but.png'
      });
    }
  });
  tinymce.PluginManager.add('Form_Maker_mce', tinymce.plugins.Form_Maker_mce);
})();