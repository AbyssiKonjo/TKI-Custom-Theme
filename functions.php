<?php
	add_theme_support( 'post-thumbnails' );

    // Custom Logo
    add_theme_support('custom-logo');

    // CORS Support
    function add_cors_http_header() {
        header("Access-Control-Allow-Origin: *");
    }
    add_action('init', 'add_cors_http_header');

    function enqueue_parent_and_custom_styles() {
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

        wp_enqueue_style('child-style', get_template_directory_uri() . '/custom.css', array('parent-style'));
    }
    add_action('wp_enqueue_scripts', 'enqueue_parent_and_custom_styles');

    function custom_excerpt_length($length) {
        return 10; // Number of characters
    }

    add_filter('excerpt_length', 'custom_excerpt_length' , 999 );

    // Cutomiser settings:
    function custom_theme_customize_register( $wp_customize ) {
        
        // Register and customizer settings:
        $wp_customize->add_setting('background_color', array(
            'default' => '#1d2b54',
            'transport' => 'postMessage',
        ));

        // Control for the background color
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'background_color', array(
            'label' => __('Background Color', 'custom-theme'),
            'section' => 'colors',
        )));

        // Font Family Section
        $wp_customize->add_section('fonts', array(
            'title' => __('Fonts', 'custom-theme'),
            'priority' => 30,
        ));

        // Font Family setting
        $wp_customize->add_setting('font_family_h1', array(
            'default' => 'Quicksand',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_setting('font_family_h2', array(
            'default' => 'Quicksand',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_setting('font_family_h3', array(
            'default' => 'Open Sans',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_setting('font_family_body', array(
            'default' => 'Open Sans',
            'transport' => 'postMessage',
        ));

        // Control for Font Family
        $wp_customize->add_control('font_family_h1_control', array(
            'label' => 'Font for Title',
            'section' => 'fonts',
            'settings' => 'font_family_h1',
            'type' => 'select',
            'choices' => array(
                'Quicksand' => 'Quicksand',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans'
            ),
        ));
        $wp_customize->add_control('font_family_h2_control', array(
            'label' => 'Font for Header',
            'section' => 'fonts',
            'settings' => 'font_family_h2',
            'type' => 'select',
            'choices' => array(
                'Quicksand' => 'Quicksand',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans'
            ),
        ));
        $wp_customize->add_control('font_family_h3_control', array(
            'label' => 'Font for Sub-header',
            'section' => 'fonts',
            'settings' => 'font_family_h3',
            'type' => 'select',
            'choices' => array(
                'Quicksand' => 'Quicksand',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans'
            ),
        ));
        $wp_customize->add_control('font_family_body_control', array(
            'label' => 'Font for Body',
            'section' => 'fonts',
            'settings' => 'font_family_body',
            'type' => 'select',
            'choices' => array(
                'Quicksand' => 'Quicksand',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans'
            ),
        ));

        // Mobile Menu BG Color
        $wp_customize->add_setting('mobile_menu_color', array(
            'default' => '#ffffff',
            'transport' => 'postMessage',
        ));

        // Mobile Menu
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_color', array(
            'label' => __('Mobile Menu Color', 'custom-theme'),
            'section' => 'colors',
        )));

        // Navbar Bg Color
        $wp_customize->add_setting('navbar_color', array(
            'default' => '#ffffff',
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_color', array (
            'label' => __('Navbar Color', 'custom-theme'),
            'section' => 'colors',
        )));
    }

    add_action('customize_register', 'custom_theme_customize_register');

    // Custom Rest API endpoint to retreive customizer settings
    function get_customizer_settings() {
        $settings = array(
            'backgroundColor' => get_theme_mod('background_color', '#1d2b54'),
            'fontFamilyH1' => get_theme_mod('font_family_h1', 'Quicksand'),
            'fontFamilyH2' => get_theme_mod('font_family_h2', 'Quicksand'),
            'fontFamilyH3' => get_theme_mod('font_family_h3', 'Open Sans'),
            'fontFamilyBody' => get_theme_mod('font_family_body', 'Open Sans'),
            'mobileMenu' => get_theme_mod('mobile_menu_color', '#ffffff'),
            'navbarColor' => get_theme_mod('navbar_color', '#ffffff'),
        );

        return rest_ensure_response($settings);
    }

    add_action('rest_api_init', function () {
        register_rest_route('custom-theme/v1', '/customizer-settings', array(
            'methods' => 'GET',
            'callback' => 'get_customizer_settings'
        ));
    });

    // GET NAV LOGO
    function get_nav_logo() {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

        return $logo;
    }

    add_action('rest_api_init', function () {
        register_rest_route('custom/v1', 'nav-logo', array(
            'methods' => 'GET',
            'callback' => 'get_nav_logo',
        ));
    });
?>