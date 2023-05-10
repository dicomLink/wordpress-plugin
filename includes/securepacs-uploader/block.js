/**
 *
 * 
 * 
 * 
 * 
 */

 wp.domReady(function () {

	(function (blocks, i18n, element, blockEditor) {
		var el = element.createElement;
		var __ = i18n.__;
		var useBlockProps = blockEditor.useBlockProps;

		function getV() {

			if (typeof Vault != "undefined") {
				return Vault;
			}

			//	wp-admin/customize.php hack
			var fv;
			for (let index = 0; index < window.frames.length; index++) {
				if (window.frames[index].Vault) {
					fv = window.frames[index].Vault;
					break;
				}
			}
			return fv;
		}

		
		blocks.registerBlockType('dicomlink/securepacs-uploader', {
			edit: function () {
				console.log('edit');
				var v = getV();
				var uploaderElement = wp.element.createElement('div', {
					className:'wp-block-dicomlink-securepacs-uploader',
					dangerouslySetInnerHTML: {
						__html: v.htmlBlock()
					}
				});

				var styleTxt = atob(v._internal.styleCss.substring(v._internal.styleCss.indexOf(',')+1));
				var styleuploaderElement = wp.element.createElement('style', {
					dangerouslySetInnerHTML: {
						__html: styleTxt
					}
				});

				return [uploaderElement,styleuploaderElement];

			},
			save: function () {
				console.log('save');
				var v = getV();
				var uploaderElement = wp.element.createElement('div', {
					className:'wp-block-dicomlink-securepacs-uploader',
					dangerouslySetInnerHTML: {
						__html: v.htmlBlock()
					}
				});

				
				return uploaderElement;

			},
			supports: { multiple: false },
		});
	}(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.blockEditor));

});