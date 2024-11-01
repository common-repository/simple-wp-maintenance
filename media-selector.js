jQuery(document).ready(function($) {
    var custom_uploader;

    $('#upload_image_button').click(function(e) {
        e.preventDefault();

        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Wählen Sie das Hintergrundbild',
            button: {
                text: 'Bild auswählen'
            },
            multiple: false
        });

        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#maintenance_background').val(attachment.url);
        });

        custom_uploader.open();
    });
});

