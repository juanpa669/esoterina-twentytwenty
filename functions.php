<?php
/**
 * 
 * @package Esoterina
 * 
 * @since 1.0.0
 * 
 * functions and imports
 * 
 */

 class Esoterina
{   
    private static $name = 'Esoterina Theme';
    private static $textDomain = 'esoterina';
    private static $customHeaderWidth = '1920px';
    private static $customHeaderHeight = 'auto';

    public function __construct($name, $textDomain)
    {
        self::$name = $name;
        self::$textDomain = $textDomain;
    }

    public static function themeName() { 
        return self::$name;
    }

    public static function textDomain() {
        return self::$textDomain;
    }

    public static function init() {
        self::loadStyles();
    }

    public static function setCustomHeaderWidth($width) {
        self::$customHeaderWidth = $width;
    }

    public static function setCustomHeaderHeight($height) {
        self::$customHeaderHeight = $height;
    }

    private static function loadStyles() {
        if(!is_admin()) {
          wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css', array(), NULL, NULL);
          wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/style.css', array(), NULL, NULL);
        }
    }

    public static function getPostOpenLink() {
        echo "<a href='" . get_permalink() . "' title='" . get_the_title() . "' >";
    }

    public static function themeSetup() {
         /**
         * Make theme available for translation.
         * Translations can be placed in the /languages/ directory.
         */
        load_theme_textdomain( self::textDomain(), get_template_directory() . '/languages' );
     
        /**
         * Add default posts and comments RSS feed links to <head>.
         */
        add_theme_support( 'automatic-feed-links' );
     
        /**
         * Enable support for post thumbnails and featured images.
         */
        add_theme_support( 'post-thumbnails' );
     
        /**
         * Add support for two custom navigation menus.
         */
        // register_nav_menus( array(
        //     'primary'   => __( 'Primary Menu', self::textDomain() ),
        //     'secondary' => __('Secondary Menu', self::textDomain() )
        // ));
     
        /**
         * Enable support for the following post formats:
         * aside, gallery, quote, image, and video
         */
        add_theme_support( 'post-formats', array ( 'aside', 'gallery', 'quote', 'image', 'video' ) );

        /**
         * Enable Header Image
         */
        $args = array(
            'flex-width'    => true,
            'width'         => self::$customHeaderWidth,
            'flex-height'   => true,
            'height'        => self::$customHeaderHeight,
            'default-image' => get_stylesheet_directory_uri() . '/assets/images/header/header_1280.jpg',
            'uploads'       => true
        );

        add_theme_support( 'custom-header', $args );
    }


    public static function addImageSize($name, $width, $height, $mode = null) {
        
        $name = strtolower($name);

        if (((int)$width <= 0 || (int)$height <= 0) && strlen($name) <= 0 ) {
            return false;
        } else {
            if (!$mode) {
                    return add_image_size( $name, $width, $height );
            } else {
                return add_image_size( $name, $width, $height, $mode );
            }
        }
    }

    public static function getCustomFields($id) {
        $html = "<div class='meta-data-div'><h3>Commandez le livre sur :</h3><ul class='meta-data-list'>";
        if ( strlen(get_post_meta($id,'amazon', true)) > 0) {
            $html .= "<li class='post-meta-data'><i class='fas fa-shopping-cart'></i>&nbsp;<a href='" . get_post_meta(get_the_ID(),'amazon', true) . "'>Amazon</a></li>";
        }
        if ( strlen(get_post_meta($id,'editions_st_honore_paris', true)) > 0) {
            $html .= "<li class='post-meta-data'><i class='fas fa-shopping-cart'></i>&nbsp;<a href='" . get_post_meta(get_the_ID(),'amazon', true) . "'>Editions St Honoré</a></li>";            
        } else {
            return;
        }

        $html .= "</ul></div>";
        return $html;
    }
}

// if ( ! isset( $content_width ) )  $content_width = 800; /* pixels */
 
//if ( ! function_exists( 'esoterina_setup' ) ) :
function esoterina_setup() {
    Esoterina::themeSetup();
    add_theme_support( 'post-thumbnails' );
}

add_action( 'after_setup_theme', 'esoterina_setup' );


function custom_enqueue_scripts() {
    Esoterina::init();
}

add_action( 'wp_enqueue_scripts', 'custom_enqueue_scripts' );


if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 150, 150, true ); // default Featured Image dimensions (cropped)
 
    // additional image sizes
    // delete the next line if you do not need additional image sizes
    add_image_size( 'category-thumb', 300, 9999 ); // 300 pixels wide (and unlimited height)
 }
 
// Images sizes
Esoterina::addImageSize('sidebar-thumb', 120, 120);
Esoterina::addImageSize('homepage-thumb', 220, 180, true);
Esoterina::addImageSize('singlepost-thumb', 590, 9999);        
