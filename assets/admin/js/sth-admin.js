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
        var yTrackGradientType = $('#y_track_gradient_type').val() || 'solid';
        var yTrackColor = $('#y_track_color').val() || '#1a1a1a';
        var yTrackColor2 = $('#y_track_color_2').val() || '#1a1a1a';
        var yTrackOpacity = $('#y_track_opacity').val() || 100;
        var yThumbGradientType = $('#y_thumb_gradient_type').val() || 'solid';
        var yThumbColor = $('#y_thumb_color').val() || '#ff0055';
        var yThumbColor2 = $('#y_thumb_color_2').val() || '#ff0055';
        var yThumbHoverColor = $('#y_thumb_hover_color').val() || '#ff3377';
        var yThumbHoverColor2 = $('#y_thumb_hover_color_2').val() || '#ff3377';
        var yThumbRadius = $('#y_thumb_radius').val() || 6;

        var xHeight = $('#x_height').val() || 12;
        var xTrackGradientType = $('#x_track_gradient_type').val() || 'solid';
        var xTrackColor = $('#x_track_color').val() || '#1a1a1a';
        var xTrackColor2 = $('#x_track_color_2').val() || '#1a1a1a';
        var xTrackOpacity = $('#x_track_opacity').val() || 100;
        var xThumbGradientType = $('#x_thumb_gradient_type').val() || 'solid';
        var xThumbColor = $('#x_thumb_color').val() || '#00ffcc';
        var xThumbColor2 = $('#x_thumb_color_2').val() || '#00ffcc';
        var xThumbHoverColor = $('#x_thumb_hover_color').val() || '#33ffd6';
        var xThumbHoverColor2 = $('#x_thumb_hover_color_2').val() || '#33ffd6';
        var xThumbRadius = $('#x_thumb_radius').val() || 6;

        var cornerGradientType = $('#corner_gradient_type').val() || 'solid';
        var cornerColor = $('#corner_color').val() || '#0f0f0f';
        var cornerColor2 = $('#corner_color_2').val() || '#0f0f0f';
        var cornerOpacity = $('#corner_opacity').val() || 100;

        var glassmorphism = $('#glassmorphism').is(':checked');
        var animation = $('#animation').is(':checked');
        var neonGlow = $('#neon_glow').is(':checked');
        var glowColor = $('#glow_color').val() || '#ff0055';

        // Calculate backgrounds
        function getBackground(type, color1, color2, opacity) {
            var rgba1 = rgbaFromHex(color1, opacity);
            var rgba2 = rgbaFromHex(color2, opacity);
            if (type === 'linear') {
                return `linear-gradient(45deg, ${rgba1}, ${rgba2})`;
            } else if (type === 'radial') {
                return `radial-gradient(circle, ${rgba1}, ${rgba2})`;
            }
            return rgba1;
        }

        var yTrackBg = getBackground(yTrackGradientType, yTrackColor, yTrackColor2, yTrackOpacity);
        var yThumbBg = getBackground(yThumbGradientType, yThumbColor, yThumbColor2, 100);
        var yThumbHoverBg = getBackground(yThumbGradientType, yThumbHoverColor, yThumbHoverColor2, 100);

        var xTrackBg = getBackground(xTrackGradientType, xTrackColor, xTrackColor2, xTrackOpacity);
        var xThumbBg = getBackground(xThumbGradientType, xThumbColor, xThumbColor2, 100);
        var xThumbHoverBg = getBackground(xThumbGradientType, xThumbHoverColor, xThumbHoverColor2, 100);

        var cornerBg = getBackground(cornerGradientType, cornerColor, cornerColor2, cornerOpacity);

        // Glassmorphism effect
        var glassCss = '';
        if (glassmorphism) {
            glassCss = `backdrop-filter: blur(10px) !important; -webkit-backdrop-filter: blur(10px) !important;`;
        }

        // Animation effect
        var animationCss = '';
        var keyframesCss = '';
        if (animation) {
            animationCss = `animation: sth-pulse 2s infinite !important;`;
            keyframesCss = `
            @keyframes sth-pulse {
                0% { filter: brightness(1); }
                50% { filter: brightness(1.5); }
                100% { filter: brightness(1); }
            }
            `;
        }

        // Glow effects
        var glowCss = '';
        var glowCssHover = '';
        if (neonGlow) {
            glowCss = `box-shadow: inset 0 0 10px ${glowColor} !important;`;
            glowCssHover = `box-shadow: inset 0 0 15px ${glowColor} !important;`;
        }

        var css = `
            #sth-preview-container::-webkit-scrollbar {
                width: ${yWidth}px !important;
                height: ${xHeight}px !important;
            }
            #sth-preview-container::-webkit-scrollbar-track:vertical {
                background: ${yTrackBg} !important;
                ${glassCss}
            }
            #sth-preview-container::-webkit-scrollbar-track:horizontal {
                background: ${xTrackBg} !important;
                ${glassCss}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:vertical {
                background: ${yThumbBg} !important;
                border-radius: ${yThumbRadius}px !important;
                ${glassCss}
                ${glowCss}
                ${animationCss}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:horizontal {
                background: ${xThumbBg} !important;
                border-radius: ${xThumbRadius}px !important;
                ${glassCss}
                ${glowCss}
                ${animationCss}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:vertical:hover {
                background: ${yThumbHoverBg} !important;
                ${glassCss}
                ${glowCssHover}
            }
            #sth-preview-container::-webkit-scrollbar-thumb:horizontal:hover {
                background: ${xThumbHoverBg} !important;
                ${glassCss}
                ${glowCssHover}
            }
            #sth-preview-container::-webkit-scrollbar-corner {
                background: ${cornerBg} !important;
            }

            ${keyframesCss}
        `;

        styleEl.html(css);
    }
});