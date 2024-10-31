(function(blocks, editor, i18n, element, components, _) {
  var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType;

  var RichText = editor.RichText;

  registerBlockType("riddle-plugin/riddle-gutenberg", {
    title: "Riddle Embed",

    description: "Display a riddle of your choice on your site",

    category: "embed",

    keywords: ["quiz"],

    icon: "universal-access-alt",

    category: "widgets",

    attributes: {
      riddle_id: {
        type: "string",
        source: "value",
        selector: "ridid"
      }
    },

    edit: function(props) {
      function onChange(event) {
        props.setAttributes({ riddle_id: event.target.value });
      }
      return el("input", {
        value: props.attributes.riddle_id,
        onChange: onChange
      });
    },

    save: function(props) {
      /*return el( 'div', {
            value: props.attributes.riddle_id,
            el( RichText.Content, {
                tagName: 'h2', value: attributes.title
            } );
        });*/

      var attributes = props.attributes;

      return el(
        "div",
        { value: attributes.riddle_id },
        el("h3", {
          tagName: "h2",
          value: attributes.title
        })
      );
    }
  });
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.i18n,
  window.wp.element,
  window.wp.components,
  window._
);
