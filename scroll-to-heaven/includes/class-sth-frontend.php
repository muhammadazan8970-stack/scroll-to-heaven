<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class STH_Frontend {

	public function init() {
		add_action( 'wp_head', array( $this, 'output_dynamic_css' ), 999 );
	}

	public function output_dynamic_css() {
		$options = get_option( 'sth_options' );
		
		// Setup Defaults in case they are missing
		$defaults = array(
			'y_width' => '12',
			'y_track_color' => '#1a1a1a',
			'y_track_opacity' => '100',
			'y_thumb_color' => '#ff0055',
			'y_thumb_hover_color' => '#ff3377',
			'y_thumb_radius' => '6',
			
			'x_height' => '12',
			'x_track_color' => '#1a1a1a',
			'x_track_opacity' => '100',
			'x_thumb_color' => '#00ffcc',
			'x_thumb_hover_color' => '#33ffd6',
			'x_thumb_radius' => '6',
			
			'corner_color' => '#0f0f0f',
			'corner_opacity' => '100',
			
			'neon_glow' => '0',
			'glow_color' => '#ff0055',
			
			'apply_globally' => '1',
			'custom_selector' => '',
		);

		$options = wp_parse_args( $options, $defaults );

		$selector = ( ! empty( $options['apply_globally'] ) || empty( $options['custom_selector'] ) ) ? 'html, body, *' : esc_html( $options['custom_selector'] );

		// Convert Opacity to Hex Alpha
		$y_track_alpha = dechex( round( intval( $options['y_track_opacity'] ) * 255 / 100 ) );
		$y_track_alpha = str_pad( $y_track_alpha, 2, '0', STR_PAD_LEFT );
		$x_track_alpha = dechex( round( intval( $options['x_track_opacity'] ) * 255 / 100 ) );
		$x_track_alpha = str_pad( $x_track_alpha, 2, '0', STR_PAD_LEFT );
		$corner_alpha = dechex( round( intval( $options['corner_opacity'] ) * 255 / 100 ) );
		$corner_alpha = str_pad( $corner_alpha, 2, '0', STR_PAD_LEFT );

		$y_track_color = $options['y_track_color'] . $y_track_alpha;
		$x_track_color = $options['x_track_color'] . $x_track_alpha;
		$corner_color = $options['corner_color'] . $corner_alpha;

		// Box Shadow for glow
		$glow_css = '';
		$glow_css_hover = '';
		if ( ! empty( $options['neon_glow'] ) ) {
			$glow_css = "box-shadow: 0 0 10px {$options['glow_color']} !important;";
			$glow_css_hover = "box-shadow: 0 0 15px {$options['glow_color']} !important;";
		}

		ob_start();
		?>
		<style id="sth-custom-scrollbar-css">
			/* STH Custom Scrollbar */
			
			/* Width and Height */
			<?php echo $selector; ?>::-webkit-scrollbar {
				width: <?php echo esc_html( $options['y_width'] ); ?>px !important;
				height: <?php echo esc_html( $options['x_height'] ); ?>px !important;
			}
			
			<?php if ( ! empty( $options['apply_globally'] ) || empty( $options['custom_selector'] ) ) : ?>
			/* Firefox fallback */
			html {
				scrollbar-width: thin !important;
				scrollbar-color: <?php echo esc_html( $options['y_thumb_color'] ); ?> <?php echo esc_html( $options['y_track_color'] ); ?> !important;
			}
			<?php endif; ?>

			/* Track X and Y */
			<?php echo $selector; ?>::-webkit-scrollbar-track:vertical {
				background: <?php echo esc_html( $y_track_color ); ?> !important;
			}
			<?php echo $selector; ?>::-webkit-scrollbar-track:horizontal {
				background: <?php echo esc_html( $x_track_color ); ?> !important;
			}

			/* Thumb X and Y */
			<?php echo $selector; ?>::-webkit-scrollbar-thumb:vertical {
				background: <?php echo esc_html( $options['y_thumb_color'] ); ?> !important;
				border-radius: <?php echo esc_html( $options['y_thumb_radius'] ); ?>px !important;
				<?php echo $glow_css; ?>
			}
			<?php echo $selector; ?>::-webkit-scrollbar-thumb:horizontal {
				background: <?php echo esc_html( $options['x_thumb_color'] ); ?> !important;
				border-radius: <?php echo esc_html( $options['x_thumb_radius'] ); ?>px !important;
				<?php echo $glow_css; ?>
			}

			/* Thumb Hover X and Y */
			<?php echo $selector; ?>::-webkit-scrollbar-thumb:vertical:hover {
				background: <?php echo esc_html( $options['y_thumb_hover_color'] ); ?> !important;
				<?php echo $glow_css_hover; ?>
			}
			<?php echo $selector; ?>::-webkit-scrollbar-thumb:horizontal:hover {
				background: <?php echo esc_html( $options['x_thumb_hover_color'] ); ?> !important;
				<?php echo $glow_css_hover; ?>
			}

			/* Scrollbar Corner */
			<?php echo $selector; ?>::-webkit-scrollbar-corner {
				background: <?php echo esc_html( $corner_color ); ?> !important;
			}
		</style>
		<?php
		echo ob_get_clean();
	}
}
