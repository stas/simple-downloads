<?php
class SDW {
    // Our post type
    public static $post_type = 'download';
    
    // Post type meta keys
    public static $meta_keys = array(
        'file_id',
        'restrict_to'
    );
    
    /**
     * Static constructor
     */
    function init() {
        add_action( 'init', array( __CLASS__, 'post_type' ) );
        add_action( 'save_post', array( __CLASS__, 'save_settings' ) );
    }
    
    /**
     * post_type()
     * 
     * Register our post type
     */
    function post_type() {
        register_post_type( self::$post_type, array(
            'labels' => array(
                'name' => __( 'Downloads', 'sdw' ),
                'singular_name' => __( 'Download', 'sdw' ),
                'add_new_item' => __( 'New Download', 'sdw' ),
                'edit_item' => __( 'Edit Download', 'sdw' ),
            ),
            'public' => true,
            'map_meta_cap' => true,
            'rewrite' => array( 'slug' => self::$post_type ),
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'register_meta_box_cb' => array( __CLASS__, 'meta_boxes' ),
            'show_ui' => true
        ) );
    }
    
    /**
     * meta_boxes( $post )
     * 
     * Activate the meta boxes
     * @param Object $post, the post/page object
     */
    function meta_boxes( $post ) {
        add_meta_box( 
            'download_settings',
            __( 'Settings', 'sdw' ),
            array(__CLASS__, 'settings_box'),
            self::$post_type,
            'side' 
        );
    }
    
    /**
     * get_map_settings( $post_id )
     * 
     * Fetch the settings for given ID
     * @param Int $post_id, the ID of the post
     * @return Mixed $settings, the fetched settings array
     */
    function get_settings( $post_id = null ) {
        if( !$post_id )
            return;
        
        $settings = array();
        
        foreach ( self::$meta_keys as $s )
            $settings[$s] = get_post_meta( $post_id, $s, true );
        
        $settings['file_ids'] = get_children(
            array(
                'post_parent' => $post_id,
                'post_type' => 'attachment'
            )
        );
        return $settings;
    }
    
    /**
     * save_settings( $post_id )
     * 
     * Save sent settings for current $post_id
     * @param Int $post_id, the ID of the post
     * @return Int $post_id, the ID of the post
     */
    function save_settings( $post_id ) {
        $file_id = null;
        $restrict_to = null;
        
        if ( isset( $_POST['sdw_settings_nonce'] ) && !wp_verify_nonce( $_POST['sdw_settings_nonce'], 'sdw' ))
            return $post_id;
        
        if ( !current_user_can( 'edit_post', $post_id ) )
            return $post_id;
        
        if( isset( $_POST['file_id'] ) && !empty( $_POST['file_id'] ) )
            $file_id = intval( $_POST['file_id'] );
        
        if( isset( $_POST['restrict_to'] ) && !empty( $_POST['restrict_to'] ) )
            $restrict_to = sanitize_key( $_POST['restrict_to'] );
        
        update_post_meta( $post_id, self::$meta_keys[0], $file_id );
        update_post_meta( $post_id, self::$meta_keys[1], $restrict_to );
        
        return $post_id;
    }
    
    /**
     * settings_box( $post )
     * 
     * Render the downloads settings meta box
     * @param Object $post, the post/page object
     */
    function settings_box( $post ) {
        self::template_render(
            'settings_box',
            self::get_settings( $post->ID )
        );
    }
    
    /**
     * template_render( $name, $vars = null, $echo = true )
     *
     * Helper to load and render templates easily
     * @param String $name, the name of the template
     * @param Mixed $vars, some variables you want to pass to the template
     * @param Boolean $echo, to echo the results or return as data
     * @return String $data, the resulted data if $echo is `false`
     */
    function template_render( $name, $vars = null, $echo = true ) {
        ob_start();
        if( !empty( $vars ) )
            extract( $vars );
        
        if( !isset( $path ) )
            $path = dirname( __FILE__ ) . '/templates/';
        
        include $path . $name . '.php';
        
        $data = ob_get_clean();
        
        if( $echo )
            echo $data;
        else
            return $data;
    }
}
?>
