console.log('a');

(function() {
	tinymce.PluginManager.add( 'tinymce_duechiacchiere', function( editor, url ) {
		
		editor.addButton( 'currentdate', {
			image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDcuMS1jMDAwIDc5LmVkYTJiM2ZhYywgMjAyMS8xMS8xNy0xNzoyMzoxOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIzLjEgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjNDOUE3MzE3N0FFODExRUNBRTZFRTAxN0M4MjJCNzAzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjNDOUE3MzE4N0FFODExRUNBRTZFRTAxN0M4MjJCNzAzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6M0M5QTczMTU3QUU4MTFFQ0FFNkVFMDE3QzgyMkI3MDMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6M0M5QTczMTY3QUU4MTFFQ0FFNkVFMDE3QzgyMkI3MDMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7tvYqHAAABRFBMVEX///8AAAASEhLg4ODy8vIDAwMBAQH09PQRERHr6+sFBQX8/PwMDAz7+/uGhobu7u7l5eUrKysxMTHe3t5CQkLx8fHo6OiYmJhvb294eHj9/f0ODg4bGxsCAgLz8/OUlJT19fUpKSnDw8OwsLBXV1fi4uIwMDDk5ORzc3NZWVnCwsJiYmK6uroEBARHR0empqbGxsZsbGzOzs7j4+P39/cWFhYKCgoiIiLt7e0XFxf+/v6CgoILCwva2trw8PA7OzvR0dFSUlLNzc20tLQfHx8yMjL5+fn4+PhERESxsbENDQ36+vrc3NxlZWVBQUGenp5LS0uioqLp6emNjY3T09MnJyd0dHQGBgZWVlbMzMy8vLxFRUVGRkbq6urHx8fAwMDh4eF1dXWHh4e9vb2Kiorf398UFBRVVVVRUVGcnJwVFRWurq7i783JAAAAAXRSTlMAQObYZgAAASJJREFUeNqEUVVbw0AQnGnTJBQo1Cl13N3d3d3d+f/vnLRJKA/Mw93szN7ufnvAv+jqnLQ/vv/qeSo8VuqX5NlwIkQ2Vxh9bJBXkuONrjjUsuIjqxQn05mDe60fquIRHdyoICTpAjl495AoP79tXZomnwVb5G5J21gtaHLMojh9jIvzuijr1yMWtkRksh2ooW5aLVIscc8qww/E+ARpS8NAljvAJkfF0D1kv/fFK5rOuS+rpMg8IltTqsepjWXyQg/xQiYxAbRl4ljDFfUSJD7JOmcPQabcpXTzvUw7OO9Z4VGAXz4J+43mgMeotelgbNtjWEGeFAyBnDFjznmMXLa0dSDtmQMwSDMYEOgN0ElRPfbcHuH1X18b9ZcQHdHCjwADAKBFGcHWiXd5AAAAAElFTkSuQmCC',
			tooltip: "Insert Current Date",
			onclick: function () {
				 var html = '<h2>Ingredienti per 4 persone</h2>Ingredienti<h2>Tempi e strumenti</h2>Tempi';
				 editor.insertContent(html);
			}
		});

		// Add Buttons to Visual Editor Toolbar
		editor.addButton( 'tinymce_abbr', {
			title: 'Abbreviation',
			image: 'data:image/png;base64,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',
			id: 'mce-wp-abbr',
			cmd: 'tinymce_abbr_modal',
			stateSelector: 'abbr'
		});

		// Get current editor selection and toggle class and aria-pressed attributes on abbr tinymce button
		editor.on('NodeChange', function(e){
			var node = editor.selection.getNode();
			if (node.nodeName == 'ABBR') {
				jQuery('#mce-wp-abbr').addClass('mce-active');
				jQuery('#mce-wp-abbr').attr('aria-pressed', 'true');
			} else {
				jQuery('#mce-wp-abbr').removeClass('mce-active');
				jQuery('#mce-wp-abbr').attr('aria-pressed', 'false');
			}
		});

		// Called when we click the abbreviation button
		editor.addCommand( 'tinymce_abbr_modal', function() {
			// Check we have selected some text that we want to link
			var text = editor.selection.getContent({
				'format': 'html'
			});
			if ( text.length === 0 ) {
				alert( 'No text selected' );
				return;
			}

			// Check current editor selection and fire the good behavior based on the node selected
			var node = editor.selection.getNode();
			if (node.nodeName == 'ABBR') {
				// If ABBR is already present then remove it
				editor.dom.remove(node, true);
			} else {
				// else, in means this node is not an abbr, then call abbreviation modal dialog
				editor.windowManager.open({
					// Modal settings
					title: 'Add',
					id: 'tinymce-abbr-insert-dialog',
					body: [
						{
							type : 'textbox',
							id: 'tinymce-abbr-title',
							name : 'abbrTitle',
							label : 'Title',
							tooltip: 'The meaning of your abbreviation',
            },
						{
							type : 'textbox',
							id: 'tinymce-abbr-lang',
							name : 'abbrLang',
							label : 'Language (optional)',
							tooltip: 'Example: fr, en, de, etc.',
						}
					],

					onsubmit: function(e) {
						var text = editor.selection.getContent({
							'format': 'html'
						});
						if ( jQuery('#tinymce-abbr-lang').val() ) {
							editor.execCommand('mceReplaceContent', false, '<abbr title="' + jQuery('#tinymce-abbr-title').val() + '" lang="' + jQuery('#tinymce-abbr-lang').val() + '">' + text + '</abbr>');
						} else {
							editor.execCommand('mceReplaceContent', false, '<abbr title="' + jQuery('#tinymce-abbr-title').val() + '">' + text + '</abbr>');
						}
						editor.windowManager.close();
					}
				});
			}
		});
	});
})();