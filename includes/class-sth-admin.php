<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class STH_Admin {

	private $options;

	public function init() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_scroll-to-heaven' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'sth-admin-css', STH_PLUGIN_URL . 'assets/admin/css/sth-admin.css', array(), STH_VERSION );

		wp_enqueue_script( 'sth-admin-js', STH_PLUGIN_URL . 'assets/admin/js/sth-admin.js', array( 'jquery', 'wp-color-picker' ), STH_VERSION, true );
		wp_localize_script( 'sth-admin-js', 'sthSettings', array(
			'options' => get_option( 'sth_options' ),
		) );
	}

	public function add_plugin_page() {
		add_menu_page(
			'Scroll to Heaven Settings',
			'Scroll to Heaven',
			'manage_options',
			'scroll-to-heaven',
			array( $this, 'create_admin_page' ),
			'dashicons-leftright',
			80
		);
	}

	public function create_admin_page() {
		$this->options = get_option( 'sth_options' );
		
		// Fill defaults for preview
		$defaults = $this->get_default_options();
		$this->options = wp_parse_args( $this->options, $defaults );

		?>
		<div class="wrap sth-wrap">
			<h1>Scroll to Heaven <span class="neon-text">Customizer</span></h1>
			
			<div class="sth-layout">
				<div class="sth-controls-panel">
					<form method="post" action="options.php" id="sth-settings-form">
						<?php
						settings_fields( 'sth_option_group' );
						do_settings_sections( 'scroll-to-heaven' );
						submit_button('Save Changes', 'primary', 'submit', true, array('id' => 'sth-submit-btn'));
						?>
					</form>
				</div>
				
				<div class="sth-preview-panel">
					<h2>Live Preview</h2>
					<div class="sth-preview-container" id="sth-preview-container">
						<div class="sth-preview-content">
							<p>Scroll down and across to see the magic!</p>
							<div class="sth-dummy-blocks">
								<div class="dummy-block"></div>
								<div class="dummy-block"></div>
								<div class="dummy-block"></div>
								<div class="dummy-block"></div>
								<div class="dummy-block"></div>
							</div>
						</div>
					</div>
					<style id="sth-live-preview-css"></style>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_default_options() {
		return array(
			// Vertical Scrollbar
			'y_width' => '12',
			'y_track_gradient_type' => 'solid',
			'y_track_color' => '#1a1a1a',
			'y_track_color_2' => '#1a1a1a',
			'y_track_opacity' => '100',
			'y_thumb_gradient_type' => 'solid',
			'y_thumb_color' => '#ff0055',
			'y_thumb_color_2' => '#ff0055',
			'y_thumb_hover_color' => '#ff3377',
			'y_thumb_hover_color_2' => '#ff3377',
			'y_thumb_radius' => '6',
			
			// Horizontal Scrollbar
			'x_height' => '12',
			'x_track_gradient_type' => 'solid',
			'x_track_color' => '#1a1a1a',
			'x_track_color_2' => '#1a1a1a',
			'x_track_opacity' => '100',
			'x_thumb_gradient_type' => 'solid',
			'x_thumb_color' => '#00ffcc',
			'x_thumb_color_2' => '#00ffcc',
			'x_thumb_hover_color' => '#33ffd6',
			'x_thumb_hover_color_2' => '#33ffd6',
			'x_thumb_radius' => '6',
			
			// Corner
			'corner_gradient_type' => 'solid',
			'corner_color' => '#0f0f0f',
			'corner_color_2' => '#0f0f0f',
			'corner_opacity' => '100',
			
			// Effects
			'glassmorphism' => '0',
			'animation' => '0',
			'neon_glow' => '0',
			'glow_color' => '#ff0055',
			
			// Application
			'apply_globally' => '1',
			'custom_selector' => '',
		);
	}

	public function page_init() {
		register_setting(
			'sth_option_group',
			'sth_options',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'sth_setting_section_y',
			'Vertical Scrollbar (Y-Axis)',
			array( $this, 'print_section_y_info' ),
			'scroll-to-heaven'
		);

		add_settings_field( 'y_width', 'Width (px)', array( $this, 'render_number_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_width', 'min' => 1, 'max' => 50 ) );
		add_settings_field( 'y_track_gradient_type', 'Track Gradient Type', array( $this, 'render_select_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_track_gradient_type', 'options' => array('solid' => 'Solid', 'linear' => 'Linear Gradient', 'radial' => 'Radial Gradient') ) );
		add_settings_field( 'y_track_color', 'Track Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_track_color' ) );
		add_settings_field( 'y_track_color_2', 'Track Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_track_color_2' ) );
		add_settings_field( 'y_track_opacity', 'Track Opacity (%)', array( $this, 'render_range_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_track_opacity' ) );
		add_settings_field( 'y_thumb_gradient_type', 'Thumb Gradient Type', array( $this, 'render_select_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_gradient_type', 'options' => array('solid' => 'Solid', 'linear' => 'Linear Gradient', 'radial' => 'Radial Gradient') ) );
		add_settings_field( 'y_thumb_color', 'Thumb Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_color' ) );
		add_settings_field( 'y_thumb_color_2', 'Thumb Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_color_2' ) );
		add_settings_field( 'y_thumb_hover_color', 'Thumb Hover Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_hover_color' ) );
		add_settings_field( 'y_thumb_hover_color_2', 'Thumb Hover Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_hover_color_2' ) );
		add_settings_field( 'y_thumb_radius', 'Thumb Border Radius (px)', array( $this, 'render_number_field' ), 'scroll-to-heaven', 'sth_setting_section_y', array( 'id' => 'y_thumb_radius' ) );

		add_settings_section(
			'sth_setting_section_x',
			'Horizontal Scrollbar (X-Axis)',
			array( $this, 'print_section_x_info' ),
			'scroll-to-heaven'
		);

		add_settings_field( 'x_height', 'Height (px)', array( $this, 'render_number_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_height', 'min' => 1, 'max' => 50 ) );
		add_settings_field( 'x_track_gradient_type', 'Track Gradient Type', array( $this, 'render_select_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_track_gradient_type', 'options' => array('solid' => 'Solid', 'linear' => 'Linear Gradient', 'radial' => 'Radial Gradient') ) );
		add_settings_field( 'x_track_color', 'Track Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_track_color' ) );
		add_settings_field( 'x_track_color_2', 'Track Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_track_color_2' ) );
		add_settings_field( 'x_track_opacity', 'Track Opacity (%)', array( $this, 'render_range_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_track_opacity' ) );
		add_settings_field( 'x_thumb_gradient_type', 'Thumb Gradient Type', array( $this, 'render_select_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_gradient_type', 'options' => array('solid' => 'Solid', 'linear' => 'Linear Gradient', 'radial' => 'Radial Gradient') ) );
		add_settings_field( 'x_thumb_color', 'Thumb Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_color' ) );
		add_settings_field( 'x_thumb_color_2', 'Thumb Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_color_2' ) );
		add_settings_field( 'x_thumb_hover_color', 'Thumb Hover Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_hover_color' ) );
		add_settings_field( 'x_thumb_hover_color_2', 'Thumb Hover Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_hover_color_2' ) );
		add_settings_field( 'x_thumb_radius', 'Thumb Border Radius (px)', array( $this, 'render_number_field' ), 'scroll-to-heaven', 'sth_setting_section_x', array( 'id' => 'x_thumb_radius' ) );

		add_settings_section(
			'sth_setting_section_corner',
			'Scrollbar Corner',
			array( $this, 'print_section_corner_info' ),
			'scroll-to-heaven'
		);

		add_settings_field( 'corner_gradient_type', 'Corner Gradient Type', array( $this, 'render_select_field' ), 'scroll-to-heaven', 'sth_setting_section_corner', array( 'id' => 'corner_gradient_type', 'options' => array('solid' => 'Solid', 'linear' => 'Linear Gradient', 'radial' => 'Radial Gradient') ) );
		add_settings_field( 'corner_color', 'Corner Color 1', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_corner', array( 'id' => 'corner_color' ) );
		add_settings_field( 'corner_color_2', 'Corner Color 2', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_corner', array( 'id' => 'corner_color_2' ) );
		add_settings_field( 'corner_opacity', 'Corner Opacity (%)', array( $this, 'render_range_field' ), 'scroll-to-heaven', 'sth_setting_section_corner', array( 'id' => 'corner_opacity' ) );

		add_settings_section(
			'sth_setting_section_effects',
			'Effects & Application',
			array( $this, 'print_section_effects_info' ),
			'scroll-to-heaven'
		);

		add_settings_field( 'glassmorphism', 'Enable Glassmorphism', array( $this, 'render_checkbox_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'glassmorphism' ) );
		add_settings_field( 'animation', 'Enable Animation', array( $this, 'render_checkbox_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'animation' ) );
		add_settings_field( 'neon_glow', 'Enable Neon Glow', array( $this, 'render_checkbox_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'neon_glow' ) );
		add_settings_field( 'glow_color', 'Glow Color', array( $this, 'render_color_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'glow_color' ) );
		add_settings_field( 'apply_globally', 'Apply Globally', array( $this, 'render_checkbox_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'apply_globally' ) );
		add_settings_field( 'custom_selector', 'Custom Selectors (comma separated)', array( $this, 'render_text_field' ), 'scroll-to-heaven', 'sth_setting_section_effects', array( 'id' => 'custom_selector' ) );

	}

	public function sanitize( $input ) {
		$sanitized = array();
		$defaults = $this->get_default_options();

		foreach ( $defaults as $key => $default_val ) {
			if ( isset( $input[ $key ] ) ) {
				if ( in_array( $key, array( 'y_track_color', 'y_track_color_2', 'y_thumb_color', 'y_thumb_color_2', 'y_thumb_hover_color', 'y_thumb_hover_color_2', 'x_track_color', 'x_track_color_2', 'x_thumb_color', 'x_thumb_color_2', 'x_thumb_hover_color', 'x_thumb_hover_color_2', 'corner_color', 'corner_color_2', 'glow_color' ) ) ) {
					$sanitized[ $key ] = sanitize_hex_color( $input[ $key ] );
				} elseif ( in_array( $key, array( 'y_width', 'y_thumb_radius', 'x_height', 'x_thumb_radius', 'y_track_opacity', 'x_track_opacity', 'corner_opacity' ) ) ) {
					$sanitized[ $key ] = absint( $input[ $key ] );
				} elseif ( in_array( $key, array( 'glassmorphism', 'animation', 'neon_glow', 'apply_globally' ) ) ) {
					$sanitized[ $key ] = 1;
				} else {
					$sanitized[ $key ] = sanitize_text_field( $input[ $key ] );
				}
			} else {
				if ( in_array( $key, array( 'glassmorphism', 'animation', 'neon_glow', 'apply_globally' ) ) ) {
					$sanitized[ $key ] = 0;
				}
			}
		}

		return $sanitized;
	}

	public function print_section_y_info() {
		print 'Customize the vertical scrollbar.';
	}

	public function print_section_x_info() {
		print 'Customize the horizontal scrollbar.';
	}

	public function print_section_corner_info() {
		print 'Customize the intersection corner between vertical and horizontal scrollbars.';
	}

	public function print_section_effects_info() {
		print 'Configure special effects and where the custom scrollbar is applied.';
	}

	public function render_number_field( $args ) {
		$id = $args['id'];
		$min = isset( $args['min'] ) ? $args['min'] : 0;
		$max = isset( $args['max'] ) ? $args['max'] : 100;
		$val = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';
		printf(
			'<input type="number" id="%s" name="sth_options[%s]" value="%s" min="%s" max="%s" class="small-text sth-input" />',
			$id, $id, $val, $min, $max
		);
	}

	public function render_range_field( $args ) {
		$id = $args['id'];
		$val = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : 100;
		printf(
			'<input type="range" id="%s" name="sth_options[%s]" value="%s" min="0" max="100" class="sth-range sth-input" oninput="this.nextElementSibling.value = this.value" /> <output>%s</output>%%',
			$id, $id, $val, $val
		);
	}

	public function render_text_field( $args ) {
		$id = $args['id'];
		$val = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';
		printf(
			'<input type="text" id="%s" name="sth_options[%s]" value="%s" class="regular-text sth-input" />',
			$id, $id, $val
		);
	}

	public function render_color_field( $args ) {
		$id = $args['id'];
		$val = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';
		printf(
			'<input type="text" id="%s" name="sth_options[%s]" value="%s" class="sth-color-picker sth-input" data-default-color="%s" />',
			$id, $id, $val, $val
		);
	}

	public function render_checkbox_field( $args ) {
		$id = $args['id'];
		$val = isset( $this->options[ $id ] ) ? $this->options[ $id ] : 0;
		printf(
			'<input type="checkbox" id="%s" name="sth_options[%s]" value="1" %s class="sth-input" />',
			$id, $id, checked( 1, $val, false )
		);
	}

	public function render_select_field( $args ) {
		$id = $args['id'];
		$options = $args['options'];
		$val = isset( $this->options[ $id ] ) ? esc_attr( $this->options[ $id ] ) : '';

		$html = sprintf( '<select id="%s" name="sth_options[%s]" class="sth-input">', $id, $id );
		foreach( $options as $opt_value => $opt_label ) {
			$html .= sprintf( '<option value="%s" %s>%s</option>', esc_attr( $opt_value ), selected( $val, $opt_value, false ), esc_html( $opt_label ) );
		}
		$html .= '</select>';

		echo $html;
	}
}
