(function($) {
	tinymce.create('tinymce.plugins.mtprssimporter', {
		init: function(ed,url) {
			ed.addButton('mtprssimporter', {
				title: 'Add a MTP RSS Importer shortcode',
				image: url+'/icon.png',
				onclick: function() {
					ed.selection.setContent('[rss length="100" more="Read more..." number="2" page="/mulher-net/" title="mulher.net" url="http://mulher.net/feed/"]'+ed.selection.getContent());
				}
			});
		},
		createControl: function(n,cm) {
			return null;
		},
	});
	tinymce.PluginManager.add('mtprssimporter',tinymce.plugins.mtprssimporter);
})(jQuery);