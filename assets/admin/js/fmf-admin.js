jQuery(document).ready(function($) {
    // Initialize color pickers
    if ( typeof $.fn.wpColorPicker !== 'undefined' ) {
        $('.fmf-color-picker').wpColorPicker({
            change: function(event, ui) {
                updatePreview();
            },
            clear: function() {
                updatePreview();
            }
        });
    }

    // Cache elements
    var previewFooter = $('#fmf-preview-footer');
    var inputs = {
        bg_color: $('#fmf_bg_color'),
        text_color: $('#fmf_text_color'),
        phone: $('#fmf_phone'),
        whatsapp: $('#fmf_whatsapp'),
        email: $('#fmf_email'),
        custom_link: $('#fmf_custom_link'),
        custom_link_text: $('#fmf_custom_link_text')
    };

    // Live update on keystrokes
    $('#fmf-settings-form input').on('input change keyup', function() {
        updatePreview();
    });

    function updatePreview() {
        var bgColor = inputs.bg_color.val() || '#ffffff';
        var textColor = inputs.text_color.val() || '#333333';
        
        previewFooter.css({
            'background-color': bgColor,
            'color': textColor
        });

        // Collect configured buttons
        var buttonsHtml = '';
        var btnCount = 0;

        if ( inputs.phone.val().trim() !== '' ) {
            buttonsHtml += '<div class="fmf-preview-btn"><span class="dashicons dashicons-phone" style="color:' + textColor + '"></span><span style="color:' + textColor + '">Call</span></div>';
            btnCount++;
        }

        if ( inputs.whatsapp.val().trim() !== '' ) {
            buttonsHtml += '<div class="fmf-preview-btn"><span class="dashicons dashicons-whatsapp" style="color:' + textColor + '"></span><span style="color:' + textColor + '">WhatsApp</span></div>';
            btnCount++;
        }

        if ( inputs.email.val().trim() !== '' ) {
            buttonsHtml += '<div class="fmf-preview-btn"><span class="dashicons dashicons-email-alt" style="color:' + textColor + '"></span><span style="color:' + textColor + '">Email</span></div>';
            btnCount++;
        }

        if ( inputs.custom_link.val().trim() !== '' ) {
            var valText = inputs.custom_link_text.val().trim() || 'Link';
            buttonsHtml += '<div class="fmf-preview-btn"><span class="dashicons dashicons-admin-links" style="color:' + textColor + '"></span><span style="color:' + textColor + '">' + $('<div>').text(valText).html() + '</span></div>';
            btnCount++;
        }

        if ( btnCount === 0 ) {
            buttonsHtml = '<div style="color:' + textColor + '; margin:auto; font-size:12px; opacity:0.6;">No buttons configured</div>';
        }

        previewFooter.html(buttonsHtml);
    }

    // Initial load
    updatePreview();
});
