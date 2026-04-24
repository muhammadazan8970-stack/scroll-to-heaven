jQuery(document).ready(function($) {
    // Initialize WP Color Picker
    $('.sth-color-picker').wpColorPicker({
        change: function(event, ui) {
            updateLivePreview();
        },
        clear: function() {
            updateLivePreview();
        }
    });

    // Listen to changes on all inputs
    $('.sth-input').on('input change', function() {
        updateLivePreview();
    });

    // Initial Live Preview update
    updateLivePreview();

    function hexToRgb(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    function rgbaFromHex(hex, opacityPercent) {
        var rgb = hexToRgb(hex);
        if(!rgb) return hex;
        var alpha = opacityPercent / 100;
        return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
    }

    function updateLivePreview() {
        var styleEl = $('#sth-live-preview-css');
        if (!styleEl.length) return;

        // Get values
        var yWidth = $('#y_width').val() || 12;
        var yTrackColor = $('#y_track_color').val() || '#1a1a1a';
        var yTrackOpacity = $('#y_track_opacity').val() || 100;
        var yThumbColor = $('#y_thumb_color').val() || '#ff0055';
        var yThumbHoverColor = $('#y_thumb_hover_color').val() || '#ff3377';
        var yThumbRadius = $('#y_thumb_radius').val() || 6;

        var xHeight = $('#x_height').val() || 12;
        var xTrackColor = $('#x_track_color').val() || '#1a1a1a';
        var xTrackOpacity = $('#x_track_opacity').val() || 100;
        var xThumbColor = $('#x_thumb_color').val() || '#00ffcc';
        var xThumbHoverColor = $('#x_thumb_hover_color').val() || '#33ffd6';
        var xThumbRadius = $('#x_thumb_radius').val() || 6;

        var cornerColor = $('#corner_color').val() || '#0f0f0f';
        var cornerOpacity = $('#corner_opacity').val() || 100;

        var neonGlow = $('#neon_glow').is(':checked');
        var glowColor = $('#glow_color').val() || '#ff0055';

        // Calculate rgba colors
        var yTrackRgba = rgbaFromHex(yTrackColor, yTrackOpacity);
        var xTrackRgba = rgbaFromHex(xTrackColor, xTrackOpacity);
        var cornerRgba = rgbaFromHex(cornerColor, cornerOpacity);

        // Glow effects
        var glowCss = '';
        var glowCssHover = '';
        if (neonGlow) {
            glowCss = `box-shadow: 0 0 10px ${glowColor} !important;`;
            glowCssHover = `box-shadow: 0 0 15px ${glowColor} !important;`;
        }

        var css = `
            #sth-preview-container::-webkit-scrollbar {
                width: ${yWidth}px !important;
                height: ${xHeight}px !important;
            }
            #sth-preview-container::-webkit-scrollbar-track:vertical {
                background: ${yTrackRgba} !important;
            }
            #sth-preview-container::-webkit-scrollbar-track:horizontal {
                background: ${xTrackRgba} !important;
            }
            #sth-preview-container::-webkit-scrollbar-thumb:vertical {
                background: ${yThumbColor} !important;
                border-radius: ${yThumbRadius}px !important;
                ${glowCss}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:horizontal {
                background: ${xThumbColor} !important;
                border-radius: ${xThumbRadius}px !important;
                ${glowCss}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:vertical:hover {
                background: ${yThumbHoverColor} !important;
                ${glowCssHover}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:horizontal:hover {
                background: ${xThumbHoverColor} !important;
                ${glowCssHover}
            }
            #sth-preview-container::-webkit-scrollbar-corner {
                background: ${cornerRgba} !important;
            }
        `;

        styleEl.html(css);
    }
});