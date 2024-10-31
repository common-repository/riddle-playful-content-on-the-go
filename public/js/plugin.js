jQuery( document ).ready( function( $ ) {
    /* RIDDLE LIST */
    // prevent default when the user presses the enter key
    $(document).keypress(
        function(event){
            if (event.which == '13') {
            event.preventDefault();
        }
    });

    $(".riddle-select").on("change", function() {
        $(".team-select-form").submit();
    });

    $('.btn-riddle-clipboard').on('click', function() {
        var shortcode = '[' + $(this).attr('data-shortcode') + '=' + $(this).attr('data-riddle-id') + ']';
        $('#riddle-shortcode-modal-input').val(shortcode);
    });

    $('#riddle-shortcode-modal-button-copy').on('click', function() {
        var input = $('#riddle-shortcode-modal-input')

        input.select();
        document.execCommand('copy');
        input.blur(); // unfocus
    });

    /** LEAD FIELD MANAGEMENT V2 */

    var dragging = Date.now();
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    getLeadfieldsFromChildren(); // update order

    $("#sortable").on( "sortstart", function(event) {
        dragging = 0;
    });

    $("#sortable").on( "sortstop", function(event) {
        dragging = Date.now();

        getLeadfieldsFromChildren(); // update order
        $('.crp-form-input').trigger('input');
    });

    $('.label-input').on('input', function() {
        getLeadfieldsFromChildren(); // update labels
        loadCRPPreview();
    });
    
    $('.riddle-checkbox').on('change', function() {
        getLeadfieldsFromChildren();
        loadCRPPreview();
    });

    /**
     * This function generates strings that allows the backend to see what the user has selected and in which order
     */
    function getLeadfieldsFromChildren()
    {
        if (!$('#sortable').length) {
            return;
        }

        var fields = [];
        var fieldsOrder = [];
        var fieldLabels = {};

        $('#sortable').children().each(function(index, item) {
            var row = item.firstElementChild;
            var fieldName = row.attributes.field.value;
            var sanitizedFieldName = row.attributes.sanitized_field.value;
            fieldsOrder.push(fieldName);
            
            var label = row.children[1].children[1].value;

            if ('' !== label) { // is not an empty label
                fieldLabels[fieldName] = label;
            }

            if ($('#checkbox-'+sanitizedFieldName).is(':checked')) { // is an active field
                fields.push(fieldName);
            }
        });

        $('#leadfieldNames').val(fields.join(','));
        $('#leadfieldNamesOrder').val(fieldsOrder.join(','));
        $('#leadfieldLabels').val(JSON.stringify(fieldLabels));

        return fields;
    }

    /* "HOW MANY ENTRIES SHOULD BE DISPLAYED" */

    $('#displayModeSelect').on('change', function() {
        var val = $(this).val();
        var input = $('.displayModeInput');
        var inputDiv = $('.displayModeInputDiv');

        if ('-1' == val || '10' == val) {
            inputDiv.hide();
        } else {
            inputDiv.show();
        }

        input.val(val);
    });

    /** LANDINGPAGE PREVIEW */

    // /* DIV FOLLOWS CONTAINER SCROLL */
    // $('#riddle-leaderboard-creator-container').scroll(function() {
    //     $('#crp-preview').css('top', $(this).scrollTop() + 20);
    // });

    $('.crp-form-input').on('input', function() {
        loadCRPPreview();
    });

    function loadCRPPreview()
    {
        var data = [];

        for (var optionName in options) {
            var optionValue = options[optionName];
            optionName = 'riddle_type_' + optionName;
            var input = $('[name="' + optionName + '"]');

            if (input.length && input.val() !== 'null' && input.val()) {
                optionValue = input.val();
            }

            data.push(optionName + '=' + encodeURIComponent(optionValue));
        }

        var glueChar = previewUrl.includes('?') ? '&' : '?'; // example.com?test=asd&data=... VS. example.com?data=... (choose between '?' and '&')
        var url = previewUrl + glueChar + data.join('&');

        $('#crp-preview').load(url);
    }

    // load preview on start if the variable is defined (= user is on an edit leaderboard page)
    if (typeof renderPreviewOnStart !== 'undefined' && renderPreviewOnStart) {
        loadCRPPreview();
    }

    // mediaUploader

    var mediaUploader;
    $('#riddlePluginLeaderboardUploadPicture').click(function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();

            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media(
            {
                title: 'Choose Image',
                button: {
                text: 'Choose Image'
            }, 
            multiple: false 
        });
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#riddleLeaderboardPictureURL').val(attachment.url);
            $('#riddleLeaderboardPictureAlt').val(attachment.alt);

            $('.riddle-image-preview').slideToggle();
            $('.riddle-image-preview-link').attr('href', attachment.url);

            $('#btn-remove-img').show();
            $('#riddlePluginLeaderboardUploadPicture').hide();

            loadCRPPreview(); // reload preview
        });
        mediaUploader.open();
    });

    // remove image button
    $('#btn-remove-img').on('click', function() {
        $('#riddleLeaderboardPictureURL').val('null');
        $('#riddleLeaderboardPictureAlt').val('');
        options['leaderboardPictureURL'] = 'null';

        $('.riddle-image-preview').slideToggle();
        $('#btn-remove-img').hide();
        $('#riddlePluginLeaderboardUploadPicture').show();

        loadCRPPreview();
    });

    $('#riddle-leaderboard-show-tutorial-modal').on('click', function(e) {
        e.preventDefault();
        $('#leaderboardTutorialModal').modal('show');
    });
});