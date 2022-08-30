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

		blocks.registerBlockType('dicomlink/vault-uploader', {
			edit: function () {
				var v = getV();
				var uploaderElement = wp.element.createElement('div', {
					dangerouslySetInnerHTML: {
						__html: v.htmlBlock
					}
				});

				return el(
					'div',
					useBlockProps(),
					uploaderElement
				);

			},
			save: function () {
				var v = getV();
				var uploaderElement = wp.element.createElement('div', {
					dangerouslySetInnerHTML: {
						__html: v.htmlBlock
					}
				});

				return el(
					'div',
					useBlockProps.save(),
					uploaderElement
				);

			},
			supports: { multiple: false },
		});
	}(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.blockEditor));

});