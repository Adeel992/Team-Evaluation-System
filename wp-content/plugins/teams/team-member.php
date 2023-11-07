<?php
/*
Plugin Name: Prism - Team Members Evaluation System
Plugin URI: https://www.graana.com/
Description: A plugin to manage and display team members.
Version: 1.0
Author: Adeel Ahmed
Author URI: https://www.graana.com/
License: GPL2
*/

// Include custom functions file
include plugin_dir_path( __FILE__ ) . 'custom-functions.php';
include plugin_dir_path( __FILE__ ) . 'views_functions.php';
include plugin_dir_path( __FILE__ ) . 'leaderboard_functions.php';

// Register custom post type for team members
function team_members_custom_post_type() {
    $labels = array(
        'name'               => 'Team Members',
        'singular_name'      => 'Team Member',
        'menu_name'          => 'Team Members',
        'name_admin_bar'     => 'Team Member',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Team Member',
        'new_item'           => 'New Team Member',
        'edit_item'          => 'Edit Team Member',
        'view_item'          => 'View Team Member',
        'all_items'          => 'All Team Members',
        'search_items'       => 'Search Team Members',
        'not_found'          => 'No team members found.',
        'not_found_in_trash' => 'No team members found in trash.',
        'parent_item_colon'  => '',
        'menu_icon'          => 'dashicons-admin-users',
       
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-admin-users',
        'supports'           => array( 'title', 'thumbnail', 'editor' ),
        'show_in_rest'       => true, 
    );

    register_post_type( 'team_member', $args );
}
add_action( 'init', 'team_members_custom_post_type' );

// Add custom meta box for team member details
function team_members_add_meta_box() {
    add_meta_box(
        'team_member_details',
        'Team Member Details',
        'team_members_render_meta_box',
        'team_member',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'team_members_add_meta_box' );

global $wpdb;
$table_name = $wpdb->prefix . 'team_members';

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        post_id INT NOT NULL,
        user_name VARCHAR(255),
        user_designation VARCHAR(255),
        user_email VARCHAR(255),
        user_webhrID INT,
        user_report_to VARCHAR(255),
        user_is_lead VARCHAR(255),
        user_lead_percentage INT,
        work_rating_value INT,
        work_rating_comment VARCHAR(255),
        management_rating INT,
        management_comment VARCHAR(255),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

$table_name = $wpdb->prefix . 'team_members_evaluation';

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        post_id INT,
        user_name VARCHAR(255),
        user_webhrID VARCHAR(255),
        user_email VARCHAR(255),
        user_designation VARCHAR(255),
        user_report_to VARCHAR(255),
        week_number INT,
        month_number INT,
        year INT,
        quality_of_work INT,
        quality_of_work_comment TEXT,
        server_down_incidents INT,
        server_down_incidents_comment TEXT,
        mean_time_to_repair INT,
        mean_time_to_repair_comment TEXT,
        code_quality_by_peer INT,
        code_quality_by_peer_comment TEXT,
        code_quality_by_team_lead INT,
        code_quality_by_team_lead_comment TEXT,
        survey_results INT,
        survey_results_comment TEXT,
        bug_reported INT,
        bug_reported_comment TEXT,
        defects_reported INT,
        defects_reported_comment TEXT,
        test_cases_tested INT,
        test_cases_tested_comment TEXT,
        requirements_initiation INT,
        requirements_initiation_comment TEXT,
        project_documentation INT,
        project_documentation_comment TEXT,
        backlog_management INT,
        backlog_management_comment TEXT,
        uat INT,
        uat_comment TEXT,
        post_production_support INT,
        post_production_support_comment TEXT,
        design_iterations INT,
        design_iterations_comment TEXT,
        design_reworks INT,
        design_reworks_comment TEXT,
        design_quality INT,
        design_quality_comment TEXT,
        manuals_content INT,
        manuals_content_comment TEXT,
        demo_videos INT,
        demo_videos_comment TEXT,
        training_material INT,
        training_material_comment TEXT,
        training_feedback_survey INT,
        training_feedback_survey_comment TEXT,
        evaluation_overall INT,
        Timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Render meta box fields
function team_members_render_meta_box( $post ) {
    // Retrieve existing values for fields 
    
    $user_name = get_post_meta( $post->ID, '_team_member_name', true );
    $designation = get_post_meta( $post->ID, '_team_member_designation', true );
    $email = get_post_meta( $post->ID, '_team_member_email', true );
    $webhr_id = get_post_meta( $post->ID, '_team_member_webhr_id', true );
    $report_to = get_post_meta( $post->ID, '_team_member_report_to', true );
    $is_lead = get_post_meta( $post->ID, '_team_member_is_lead', true );
    $percentage = get_post_meta( $post->ID, '_team_member_percentage', true );
    $member_status = get_post_meta( $post->ID, '_team_member_status', true );
    
   // $team_lead_user =get_post_meta( $post->ID, '_team_member_username', true);
    $team_lead_password =get_post_meta( $post->ID, '_team_member_password', true);

    $saved_username = $user_name;
    $saved_designation = $designation;
    $saved_lead =  $is_lead;
    $saved_status = $member_status ;
    $saved_reporting = $report_to;
//$saved_username = $team_lead_user;
    $saved_password=  $team_lead_password;
    // Display fields
    ?>
    <p>
 
    <?php
         $member_name_options = array(
            'No',
            'Yes'
        );
        ?>
        <label for="team-member-name-option">Add User from WebHR:</label>
        <select id="team-member-name-option" name="team-member-name-option">
        <?php foreach ( $member_name_options as $option ) : ?>
                <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $saved_username_option, $option ); ?>>
                    <?php echo esc_html( $option ); ?>
                </option>
            <?php endforeach; ?>
    </select>
    </p>


    <p id="team-member-name-field" <?php if ( $saved_username_option !== 'Yes' ) : ?>style="display: none;"<?php endif; ?>>
    <label for="team-member-name">Select WebHR User:</label>
    <select id="team-member-name" name="team-member-name">
    </select>

    </p>
    <p>
    <label for="team-member-name-input">Team Member Name:</label>
    <input type="text" id="team-member-name-input" name="team-member-name-input" value="<?php echo esc_attr( $user_name ); ?>" />
    </p>


    <p>
        <?php
        $member_status_options = array(
            'None',
            'Active',
            'Inactive'
        );
        ?>
        <label for="team_member_status">Member Status:</label>
        <select id="team_member_status" name="team_member_status">
            <?php foreach ( $member_status_options as $option ) : ?>
                <option value="<?php echo $option; ?>" <?php selected( $saved_status, $option ); ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <p>
    <?php
    $role_cat_options = array(
        'None' => 'none',
        'Unit Head' => 'unit-head',
        'QA' => 'qa',
        'QA Automation' => 'qa-automation',
        'Development' => 'development',
        'Dev Ops' => 'dev-ops',
        'GIS' => 'gis',
        'Project Management' => 'project-management',
        'Implementation' => 'implementation',
        'SAP' => 'sap',
        'Design' => 'design',
        'Product Management' => 'product-management',
        'IT Manager' => 'it-manager',
        'IT Executive' => 'it-executive',
        'Content Writers' => 'content-writers'

    );
    ?>
    <label for="team_member_designation">Role Category:</label>
    <select id="team_member_designation" name="team_member_designation">
        <?php foreach ( $role_cat_options as $name => $value ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $saved_designation, $value ); ?>>
                <?php echo esc_html( $name ); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>

    <p>
        <label for="team_member_webhr_id">WebHR ID:</label>
        <input type="number" id="team_member_webhr_id" name="team_member_webhr_id" value="<?php echo esc_attr( $webhr_id ); ?>" />
    </p>
    <p>
        <?php 
         global $wpdb;
         $table_name = $wpdb->prefix . 'users';
         $query = $wpdb->prepare( "SELECT * FROM $table_name" );
         $results = $wpdb->get_results( $query );
        ?>
        <label for="team_member_reporting">Reporting to:</label>
       <!-- <input type="text" id="team_member_reporting" name="team_member_reporting" value="<?php //echo esc_attr( $report_to ); ?>" />-->
        <select id="team_member_reporting" name="team_member_reporting">
    <?php foreach ( $results as $result) : 
        $name = $result->user_email;
        $is_lead = $result->user_is_lead;
       // if( $is_lead == "Yes"){?>
            <option value="<?php echo esc_attr( $name ); ?>" <?php selected( $saved_reporting, $name ); ?>>
                <?php echo esc_html( $name ); ?>
            </option>
        <?php //} 
    endforeach; ?>
  </select>
    </p>
    <p>
        <?php
         $is_lead_options = array(
            'No',
            'Yes'
        );
        ?>
    <label for="team_member_islead">Team Lead:</label>
    <select id="team_member_islead" name="team_member_islead">
    <?php foreach ( $is_lead_options as $option ) : ?>
            <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $saved_lead, $option ); ?>>
                <?php echo esc_html( $option ); ?>
            </option>
        <?php endforeach; ?>
  </select>
   </p>
  
   <p id="leading_percentage_field" <?php if ( $saved_lead !== 'Yes' ) : ?>style="display: none;"<?php endif; ?>>
   
        <label for="team_member_percentage">Leading Percentage:</label>
        <input type="number" id="team_member_percentage" name="team_member_percentage" value="<?php echo esc_attr( $percentage ); ?>" />
        
    </p>

    <p id="leading_email_field" <?php if ( $saved_lead !== 'Yes' ) : ?>style="display: none;"<?php endif; ?>>
        <label for="team_member_email">Email:</label>
        <input type="email" id="team_member_email" name="team_member_email" value="<?php echo esc_attr( $email ); ?>" />
    </p>

   
    <!-- <p id="username_field" <?php if ( $saved_lead !== 'Yes' ) : ?>style="display: none;"<?php endif; ?>>
        
            <label for="team_member_username">Team Lead Username:</label>
            <input type="text" id="team_member_username" name="team_member_username" value="<?php echo esc_attr( get_post_meta( $post->ID, '_team_member_username', true ) ); ?>" />
       
    </p> -->

    <p id="password_field" <?php if ( $saved_lead !== 'Yes' ) : ?>style="display: none;"<?php endif; ?>>
        
            <label for="team_member_password">Team Lead Password:</label>
            <input type="password" id="team_member_password" name="team_member_password" value="<?php echo esc_attr( get_post_meta( $post->ID, '_team_member_password', true ) ); ?>" />
       
    </p>

    <?php
   
}

// Save meta box field values
function team_members_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['team-member-name-input'] ) ) {
        update_post_meta( $post_id, '_team_member_name', sanitize_text_field( $_POST['team-member-name-input'] ) );
    }

    if ( isset( $_POST['team_member_designation'] ) ) {
        update_post_meta( $post_id, '_team_member_designation', sanitize_text_field( $_POST['team_member_designation'] ) );
    }

    if ( isset( $_POST['team_member_email'] ) ) {
        update_post_meta( $post_id, '_team_member_email', sanitize_email( $_POST['team_member_email'] ) );
    }

    if ( isset( $_POST['team_member_webhr_id'] ) ) {
        update_post_meta( $post_id, '_team_member_webhr_id', sanitize_text_field( $_POST['team_member_webhr_id'] ) );
    }
    if ( isset( $_POST['team_member_reporting'] ) ) {
        update_post_meta( $post_id, '_team_member_report_to', sanitize_text_field( $_POST['team_member_reporting'] ) );
    }
    
    if ( isset( $_POST['team_member_islead'] ) ) {
        update_post_meta( $post_id, '_team_member_is_lead', sanitize_text_field( $_POST['team_member_islead'] ) );
    }

    if ( isset( $_POST['team_member_percentage'] ) ) {
        update_post_meta( $post_id, '_team_member_percentage', sanitize_text_field( $_POST['team_member_percentage'] ) );
    }

    if ( isset( $_POST['team_member_status'] ) ) {
        update_post_meta( $post_id, '_team_member_status', sanitize_text_field( $_POST['team_member_status'] ) );
    }
   

    // if ( isset( $_POST['team_member_username'] ) ) {
    //     update_post_meta( $post_id, '_team_member_username', sanitize_text_field( $_POST['team_member_username'] ) );
    // }

    if ( isset( $_POST['team_member_password'] ) ) {
        update_post_meta( $post_id, '_team_member_password', sanitize_text_field( $_POST['team_member_password'] ) );
    }   
}
add_action( 'save_post', 'team_members_save_meta_box' );

// Custom admin menu page for team members table
if(is_admin() || !is_admin()){
    function team_members_admin_menu() {
        if (current_user_can('administrator')) {
        add_submenu_page(
            'edit.php?post_type=team_member', 
            'Team Members Table',
            'View Team Members',
            'edit_posts',
            'team-members',
            'team_members_display_table',
            20
        );

        add_submenu_page(
            'edit.php?post_type=team_member', 
            'Public Holidays',
            'View Public Holidays',
            'edit_posts',
            'team-members_public_holidays',
            'team_members_public_holiday',
            20
        );

    }

        add_submenu_page(
            'edit.php?post_type=team_member', 
            'Performance Evaluate', // Page title
            'Performance Evaluation', // Menu title
            'edit_posts',
           // 'manage_options', // Capability required
            'team_member_evaluate', // Menu slug
            'team_members_evaluate_page' // Callback function to render the page
        );

        add_submenu_page(
            'edit.php?post_type=team_member', // Parent slug (edit.php?post_type=team_member)
            'Personality Evaluate', // Page title
            'Personality Evaluation', // Menu title
            'edit_posts',
           // 'manage_options', // Capability required
            'team_member_personality_evaluate', // Menu slug
            'team_members_personality_evaluate_page' // Callback function to render the page
        );

        add_submenu_page(
            'edit.php?post_type=team_member', // Parent slug (edit.php?post_type=team_member)
            'Leaderboard', // Page title
            'Leaderboard', // Menu title
            'edit_posts',
          //  'manage_options', // Capability required
            'team_member_leader_board', // Menu slug
            'team_members_leaderboard_page' // Callback function to render the page
        );

        add_submenu_page(
            'edit.php?post_type=team_member', // Parent slug (edit.php?post_type=team_member)
            'Team Hierarchy', // Page title
            'Team Hierarchy', // Menu title
            'edit_posts',
          //  'manage_options', // Capability required
            'team_member_hierarchy_chart', // Menu slug
            'new_org_chart' // Callback function to render the page
        );


        add_submenu_page(
            'edit.php?post_type=team_member', // Parent slug (edit.php?post_type=team_member)
            'Team Attendence', // Page title
            'Team Attendence', // Menu title
            'edit_posts',
          //  'manage_options', // Capability required
            'team_member_attendence', // Menu slug
            'fetch_attendence' // Callback function to render the page
        );
        
    }
    add_action( 'admin_menu', 'team_members_admin_menu' );

}



// Remove content editor from team_member post type
function remove_team_member_editor_support() {
    remove_post_type_support( 'team_member', 'editor' );
}
add_action( 'init', 'remove_team_member_editor_support' );

