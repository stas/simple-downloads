<?php
class SDW_Membership {
    /**
     * Static constructor
     */
    function init() {
        // Skip loading this if Membership is not installed
        if( !class_exists( 'membershipadmin' ) && !class_exists( 'M_Membership' ) )
            return;
        
        add_filter( 'editable_roles', array( __CLASS__, 'sync_levels' ) );
        add_filter( 'editable_roles', array( __CLASS__, 'sync_subscriptions' ) );
        add_filter( 'simple_downloads_check_restriction', array( __CLASS__, 'check_restriction' ) );
    }
    
    /**
     * sync_levels( $all_roles )
     *
     * Syncs the roles with Membership levels
     * @param Mixed $all_roles initial roles list
     * @return Mixed synced list
     */
    function sync_levels( $all_roles ) {
        $membershipadmin = new membershipadmin();
        $levels = $membershipadmin->get_membership_levels();
        
        if( !empty( $levels ) )
            foreach ( $levels as $l ) 
                $all_roles['membership_level_' . $l->id ] = array(
                    'name' => __( 'Membership: ', 'sdw' ) . $l->level_title
                );
        
        ksort( $all_roles );
        return $all_roles;
    }
    
    /**
     * sync_subscriptions( $all_roles )
     *
     * Syncs the roles with Membership subscriptions
     * @param Mixed $all_roles initial roles list
     * @return Mixed synced list
     */
    function sync_subscriptions( $all_roles ) {
        $membershipadmin = new membershipadmin();
        $subs = $membershipadmin->get_subscriptions();
        
        if( !empty( $subs ) )
            foreach ( $subs as $s ) 
                $all_roles['membership_sub_' . $s->id ] = array(
                    'name' => __( 'Membership: ', 'sdw' ) . $s->sub_name
                );
        
        ksort( $all_roles );
        return $all_roles;
    }
    
    /**
     * check_restriction( $level )
     *
     * Checks if a current user passes membership level/subscription check
     * @param String $level to check for
     * @return Boolean true if user passes, false if not
     */
    function check_restriction( $level ) {
        // It's ok if is true or something else
        if( !is_string( $level ) && $level == true )
            return true;
        
        // Extract the subscription ID
        $level_id = strtok( $level, 'membership_sub_' );
        if( is_numeric( $level_id ) )
            if( current_user_on_subscription( intval( $level_id ) ) )
                return true;
        
        // Extract the level ID
        $level_id = strtok( $level, 'membership_level_' );
        if( is_numeric( $level_id ) )
            if( current_user_on_level( intval( $level_id ) ) )
                return true;
        
        return false;
    }
}
?>