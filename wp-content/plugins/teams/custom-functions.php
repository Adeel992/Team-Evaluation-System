<?php 

// Enqueue DataTables scripts and stylesheets
function team_members_enqueue_scripts() {
    wp_enqueue_script( 'jquery', );

    wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css');
    wp_enqueue_script( 'popper','https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js');
    wp_enqueue_script( 'bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js');

    wp_enqueue_script( 'datatables', 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js', array( 'jquery' ), '1.13.5', true );
    wp_enqueue_style( 'datatables', 'https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css', array(), '1.13.5' );


    wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/font-awesome/css/font-awesome.min.css', array(), '5.15.4' );

    wp_enqueue_style( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css' );
    wp_enqueue_script( 'date-picker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js', array(), '5.15.4' );

    wp_enqueue_style( 'select', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css' );
    wp_enqueue_script( 'select', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array(), '5.15.4' );

    wp_enqueue_script('moment' , 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', array(), '5.15.4'  );
  
    wp_enqueue_script('chart' , 'https://www.gstatic.com/charts/loader.js', array(), '3.8.0'  );
    
    wp_enqueue_script( 'custom', plugin_dir_url( __FILE__ ) . 'js/custom.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . 'css/style.css' , '1.0.0');
   
    //wp_enqueue_script( 'jquery-ui','https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), '1.12.1', true );


}
add_action( 'admin_enqueue_scripts', 'team_members_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'team_members_enqueue_scripts' );

/* Shortcode for displaying team members table */ 
function team_members_display_shortcode( $atts ) {
    ob_start();
    team_members_display_table();
    return ob_get_clean();
}
add_shortcode( 'team_members_table', 'team_members_display_shortcode' );

// Allow users to view and manage their own team members
function team_members_filter_query_by_author( $query ) {
    if ( is_admin() && $query->is_main_query() && $query->get( 'post_type' ) == 'team_member' ) {
        $current_user_id = get_current_user_id();
        $query->set( 'author', $current_user_id );
    }
}
add_action( 'pre_get_posts', 'team_members_filter_query_by_author' );

function add_author_support_to_posts() {
    add_post_type_support( 'team_member', 'author' ); 
 }
 add_action( 'init', 'add_author_support_to_posts' );
 
// Grant "editor" role access to the custom admin menu page
function team_members_grant_editor_access() {
    $editor_role = get_role( 'employee' );
    $editor_role->add_cap( 'edit_posts' );
}
add_action( 'admin_init', 'team_members_grant_editor_access' );

// Add filter to include custom template for team_member single view
function team_members_single_template( $template ) {
    if ( is_singular( 'team_member' ) ) {
        $template = plugin_dir_path( __FILE__ ) . 'templates/single-team_member.php';
    }
    return $template;
}
add_filter( 'template_include', 'team_members_single_template' );


function team_members_publish_user_creation(  $post_id, $post ) {
    // Check if the post is a team member and is being published
    if ( 'team_member' === $post->post_type )  {
     

    // Get the username, password, and email from post meta
    $username = get_post_meta( $post_id, '_team_member_email', true );
    $password = get_post_meta( $post_id, '_team_member_password', true );
    $email = get_post_meta( $post_id, '_team_member_email', true );
    $percentage = get_post_meta( $post_id, '_team_member_percentage', true );


    // Validate the username, password, and email
    if ( ! empty( $username ) && ! empty( $password ) && ! empty( $email ) && ! empty (  $percentage ) ) {
      
   
            // Create the user
            $user_id = wp_create_user( $username, $password, $username );

            // Check if the user was created successfully
            if ( ! is_wp_error( $user_id ) ) {
                // Assign the "administrator" role to the user
                $user = new WP_User( $user_id );
                $user->set_role( 'employee' );
            }
    }
}

}
add_action( 'wp_insert_post', 'team_members_publish_user_creation', 20, 2 );

//save post values into DB
// function store_team_member_data( $post_id ) {
//      $post_status = get_post_status( $post_id );
//         if ('team_member' === get_post_type( $post_id )){
//              // Check if the post is published
//             if ('publish' !== get_post_status($post_id)) {
//                 return;
//             }
//         global $wpdb;

//         $name = get_post_meta( $post_id, '_team_member_name', true );
//         $designation = get_post_meta( $post_id, '_team_member_designation', true );
//         $email = get_post_meta( $post_id, '_team_member_email', true );
//         $webhr_id = get_post_meta( $post_id, '_team_member_webhr_id', true );
//         $report_to = get_post_meta( $post_id, '_team_member_report_to', true );
//         $is_lead = get_post_meta( $post_id, '_team_member_is_lead', true );
//         $percentage = get_post_meta( $post_id, '_team_member_percentage', true );
//         $member_status = get_post_meta( $post_id, '_team_member_status', true );
//        // $lead_username = get_post_meta( $post_id, '_team_member_username', true );
//         $lead_password = get_post_meta( $post_id, '_team_member_password', true );
//         $work_rating_value = get_post_meta( $post_id, '_team_member_work_rating_value', true );
//         $work_rating_comment = get_post_meta( $post_id, '_team_member_work_rating_comment', true );
//         $management_rating = get_post_meta( $post_id, '_team_member_management_rating', true );
//         $management_comment = get_post_meta( $post_id, '_team_member_management_comment', true );

        
//         $current_year = date( 'Y' );
//         $data = array(
//             'post_id' => $post_id,
//             'user_name' => $name,
//             'user_designation' => $designation,
//             'user_email' => $email,
//             'user_webhrID' => $webhr_id,
//             'user_report_to' => $report_to,
//             'user_is_lead' => $is_lead,
//             'user_lead_percentage' => $percentage,
//             'user_status' => $member_status,
//             'lead_username' => '',
//             'lead_password' => $lead_password,
//             'work_rating_value' => $work_rating_value,
//             'work_rating_comment' => $work_rating_comment,
//             'management_rating' => $management_rating,
//             'management_comment' => $management_comment,
//             'year'  => $current_year,
//         );


//         $existing_record = $wpdb->get_row(
//             $wpdb->prepare(
//                 "SELECT * FROM {$wpdb->prefix}team_members WHERE post_id = %d AND user_webhrID = %s",
//                 $post_id,
//                 $webhr_id
//             )
//         );       
//         if ( $existing_record ) {
//         $table_name = $wpdb->prefix . 'team_members';
//         $wpdb->update( $wpdb->prefix . 'team_members', $data, array( 'post_id' => $post_id )  );
        
//         }
//         else{
//             $table_name = $wpdb->prefix . 'team_members';
//             $wpdb->insert( $table_name, $data );
//         }
        
//     }
  
// }
// add_action( 'wp_insert_post', 'store_team_member_data', 10, 1 );


// Function to delete custom table entry when a post is deleted
function delete_custom_table_entry($post_id) {
    if (get_post_type($post_id) === 'team_member') { 
        global $wpdb;
        $table_name = $wpdb->prefix . 'team_members';

        $wpdb->delete(
            $table_name,
            array('post_id' => $post_id),
            array('%d')
        );
    }
}
add_action('wp_trash_post', 'delete_custom_table_entry');

// get WebHR data with API
function fetch_webhr_api_data() {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.webhr.co/api/2/api?module=Employees&submodule=Employees&request=List',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
       'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJLSXZlcHVNZjRYQ2JmMElwIiwianRpIjoiYzE5MGNmOWIwZjJkZTU1Y2M1ZDE0Nzk2Y2E4MmVhNjIzYTU5NjE2NTNjZTM2MTM3YWVjOTYwMzVjZTA2ZTM5ZGU0ZGNiN2VjODhiYWJmNDAiLCJpYXQiOjE2ODk5MjYyMTUuOTgzMTA0LCJuYmYiOjE2ODk5MjYyMTUuOTgzMTA2LCJleHAiOjE3MjE1NDg2MTUuOTQ4MzEyLCJzdWIiOiIxMDEwIiwic2NvcGVzIjpbIkZ1bGxfQWNjZXNzIiwiRW1wbG95ZWVzIiwiVGltZXNoZWV0Il19.h5C2xdxk1XBhigIlsTRhHx1woH8Jumnn7ZEe4wsZrdqh0r1G8bg3kbZxeJdZFJhU2EP5nvF1rz6aLAGXV3uc0yYDA175Vg6qCIgbHbln3--1ImZwa7Z5JmtCnfUFjdnVDVM-9aeOAcijUj4Uw05Z7o_7Y55x7uAXbgYT0gvsSGnTnXnT-JD8OtA38sNvwESEf0InjJKW0eC8EVQOhYEKxU2IqgoTiif4PX4lz0X-1HzNTDqnzzMLxK1LJwdcUgIi8n2fzi8otXHTrfaCeTUVl6lExdvMO9krF4Lin9I6qgKauy1XnEDjEUTvw59FR8400rAuacYOksInFm3hyMmdbA',
        'Cookie: PHPSESSID=ab2k8l2u6l2desb16j6n599b6k'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    
    $dataArray = json_decode($response, true);
    $fullNames = array(); // Array to store FullName values
    $emailAddress = array(); // Array to store FullName values
    $EmployeeId = array(); // Array to store FullName values

    foreach ($dataArray as $item) {
        if (isset($item['FullName']) && isset($item['EmailAddress']) && isset($item['EmployeeId'])) {
            $fullNames[] = $item['FullName'];
            $emailAddress[] = $item['EmailAddress'];
            $EmployeeId[] = $item['UserName'];
            $designation[] = $item['Designation'];
             }
    }
    $mergedArray = array_map(null, $fullNames, $emailAddress, $EmployeeId, $designation);
    wp_send_json($mergedArray); // Send JSON response
    exit;
}
add_action('wp_ajax_fetch_webhr_api_data', 'fetch_webhr_api_data');
add_action('wp_ajax_nopriv_fetch_webhr_api_data', 'fetch_webhr_api_data');

/*add_action('init', 'schedule_jira_logs_cron_job');
function schedule_jira_logs_cron_job() {
    if (!wp_next_scheduled('update_jiralogs_cron_hook')) {
        $next_friday = strtotime('next Friday 05:12 PM');
        wp_schedule_event($next_friday, 'daily', 'update_jiralogs_cron_hook');
    }
    if (!wp_next_scheduled('update_webhr_leaves_cron_hook')) {
        $next_friday = strtotime('next Friday 05:12 PM');
        wp_schedule_event($next_friday, 'daily', 'update_webhr_leaves_cron_hook');
    }
}*/


add_filter('cron_schedules', 'add_custom_cron_schedule');
function add_custom_cron_schedule($schedules) {
    $schedules['every_six_hour'] = array(
        'interval' => 21600, 
        'display'  => __('Every six hour'),
    );
    return $schedules;
}

add_action('init', 'schedule_jira_logs_cron_job');
function schedule_jira_logs_cron_job() {
    if (!wp_next_scheduled('update_jiralogs_cron_hook')) {
        $next_run = time();
        wp_schedule_event($next_run, 'every_six_hour', 'update_jiralogs_cron_hook');
    }
}

add_action('init', 'schedule_webhr_leaves_cron_job');
function schedule_webhr_leaves_cron_job() {
    if (!wp_next_scheduled('update_webhr_leaves_cron_hook')) {
        $next_run = time();
        wp_schedule_event($next_run, 'every_six_hour',  'update_webhr_leaves_cron_hook');
    }
}

add_action('init', 'schedule_user_insertion_cron_job');
function schedule_user_insertion_cron_job() {
    if (!wp_next_scheduled('insert_team_member_with_current_week_cron_hook')) {
        $next_run = time();
        wp_schedule_event($next_run, 'every_six_hour',  'insert_team_member_with_current_week_cron_hook');
    }
}


// get ranges between dates, Function to get all dates between two dates

function getDatesBetween($startDate, $endDate) {
    $dates = array($startDate);
    $currentDate = $startDate;
    while ($currentDate < $endDate) {
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        $dates[] = $currentDate;
    }
    return $dates;
}

//Leaves data from webhr API
add_action( 'update_webhr_leaves_cron_hook', 'fetch_webhr_api_leaves_data' );
function fetch_webhr_api_leaves_data() {

    $curl = curl_init();
    $previousWeekStartDate = date('Y-m-d', strtotime('last Monday'));
    $previousWeekEndDate = date('Y-m-d', strtotime('today'));
    

    date_default_timezone_set('America/Los_Angeles');
    $log_file_path = WP_CONTENT_DIR . '/jira_logs_cron.log';
    $log_message = 'Leaves cron job triggered on ' . date('Y-m-d H:i:s') . ' (week range is ' . $previousWeekStartDate .  ' to '. $previousWeekEndDate.' )' . "\n";
    file_put_contents($log_file_path, $log_message, FILE_APPEND);

    $params = array(
        "StartDate" => $previousWeekStartDate,
        "EndDate" => $previousWeekEndDate,
    );
    $params = json_encode($params);
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.webhr.co/api/2/api?module=Timesheet&submodule=Leaves&request=List&params='.$params,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJLSXZlcHVNZjRYQ2JmMElwIiwianRpIjoiYzE5MGNmOWIwZjJkZTU1Y2M1ZDE0Nzk2Y2E4MmVhNjIzYTU5NjE2NTNjZTM2MTM3YWVjOTYwMzVjZTA2ZTM5ZGU0ZGNiN2VjODhiYWJmNDAiLCJpYXQiOjE2ODk5MjYyMTUuOTgzMTA0LCJuYmYiOjE2ODk5MjYyMTUuOTgzMTA2LCJleHAiOjE3MjE1NDg2MTUuOTQ4MzEyLCJzdWIiOiIxMDEwIiwic2NvcGVzIjpbIkZ1bGxfQWNjZXNzIiwiRW1wbG95ZWVzIiwiVGltZXNoZWV0Il19.h5C2xdxk1XBhigIlsTRhHx1woH8Jumnn7ZEe4wsZrdqh0r1G8bg3kbZxeJdZFJhU2EP5nvF1rz6aLAGXV3uc0yYDA175Vg6qCIgbHbln3--1ImZwa7Z5JmtCnfUFjdnVDVM-9aeOAcijUj4Uw05Z7o_7Y55x7uAXbgYT0gvsSGnTnXnT-JD8OtA38sNvwESEf0InjJKW0eC8EVQOhYEKxU2IqgoTiif4PX4lz0X-1HzNTDqnzzMLxK1LJwdcUgIi8n2fzi8otXHTrfaCeTUVl6lExdvMO9krF4Lin9I6qgKauy1XnEDjEUTvw59FR8400rAuacYOksInFm3hyMmdbA',
        'Cookie: PHPSESSID=ab2k8l2u6l2desb16j6n599b6k'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    
    $dataArray = json_decode($response, true);
    
    foreach ($dataArray as $item) {
        if (isset($item['UserName'])) {
            $user_id =  $item['UserName'];
            $user_firstname = $item['FirstName'];
            $leavehours = $item['LeaveHours'];
            $leaveduration = $item['LeaveDuration'];
            $leavefrom = $item['LeaveFrom'];
            $leaveto = $item['LeaveTo'];
       
            if ($leaveduration != 0.5){
            // Check if all leave dates are within the previous week range
            $leaveDates = getDatesBetween($leavefrom, $leaveto);
            $myDates = getDatesBetween($previousWeekStartDate, $previousWeekEndDate);
            $commonDates = array_intersect(getDatesBetween($previousWeekStartDate, $previousWeekEndDate), $leaveDates);
            $numCommonDates = count($commonDates);
            $user_leaves_hour = $numCommonDates * 8;
            }
            else{
                $user_leaves_hour = 0.5 * 8;
            }
            

             // Update user_on_leaves column for matching user_name in wp_team_members table
             global $wpdb;
             $current_week = date( 'W' );
             $table_name = $wpdb->prefix . 'team_members';
             $wpdb->query(
                 $wpdb->prepare(
                     "UPDATE $table_name SET user_on_leaves = %s WHERE user_webhrID = %s AND week_number = %s",
                     $user_leaves_hour,
                     $user_id,
                     $current_week
                 )
             );
            
         }
      
    }

   wp_send_json($dataArray); 
   exit;
   
}


add_action( 'update_jiralogs_cron_hook', 'update_new_jira' );

function update_new_jira() {

    // $current_date = date('j-m-Y');

    // $file_name = 'jira_logs_csv_' . $current_date . '.csv';
    
    // $directory_path =  ABSPATH.'jira_logs/';

    // $csv_file_path = $directory_path . $file_name;
    
    $directory_path = ABSPATH . 'jira_logs/';
    $files = scandir($directory_path);
    $files = array_diff($files, array('.', '..'));
     $files = array_filter($files, function ($file) {
        return $file !== '.DS_Store' && $file !== '.ftpquota';
    });
    usort($files, function ($a, $b) use ($directory_path) {
        return filemtime($directory_path . $b) - filemtime($directory_path . $a);
    });
    $latest_file = reset($files);
    
    if ($latest_file !== false) {
        $csv_file_path = $directory_path . $latest_file;
    } 
    
    

    if($csv_file_path){

        date_default_timezone_set('America/Los_Angeles');
        $log_file_path = WP_CONTENT_DIR . '/jira_logs_cron.log';
        $log_message = 'Jira logs cron job triggered on ' . date('Y-m-d H:i:s') . ' ( File path is ' . $csv_file_path .  ' )' . "\n";
        file_put_contents($log_file_path, $log_message, FILE_APPEND);

        $csv_data = array_map('str_getcsv', file($csv_file_path));   

        $headers = array_shift($csv_data);

        $user_email_index = array_search('Email', $headers);
        $jira_work_logs_index = array_search('time_spent_hrs', $headers);
        $jira_work_logs_time_index = array_search('log_time', $headers);

        $combined_work_logs = array();
        
        // Loop through the CSV data
        foreach ($csv_data as $row) {
            $user_email = $row[$user_email_index];
            $jira_work_logs = $row[$jira_work_logs_index];
            $jira_work_log_time = strtotime($row[$jira_work_logs_time_index]);
            $week_number = date('W', $jira_work_log_time);
        
                // If the user_email already exists in the array, add the jira_work_logs value
                if (isset($combined_work_logs[$user_email])) {
                    $combined_work_logs[$user_email] += $jira_work_logs;
                
                } else {
                    $combined_work_logs[$user_email] = $jira_work_logs;
                
                }

        }
        $log_message = 'Jira logs by week number ' . $week_number. "\n";
        file_put_contents($log_file_path, $log_message, FILE_APPEND);

        // Loop through the user_work_logs array and update the 'jira_work_logs' column in the WordPress table
        foreach ($combined_work_logs as $user_email => $jira_work_logs) {
            // Update the 'jira_work_logs' column in the WordPress table for the matching 'user_email'
            global $wpdb;
          $current_week = $week_number;
       
            $table_name = $wpdb->prefix . 'team_members';
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table_name SET jira_work_logs = %s WHERE user_email = %s AND week_number = %s",
                    $jira_work_logs,
                    $user_email,
                    $current_week
                )
            );
        }
    }
    else{
  
        $log_message = 'Jira logs cron job can not triggered on ' . date('Y-m-d H:i:s') . "\n";
        file_put_contents($log_file_path, $log_message, FILE_APPEND);
    }
     
}

/*add_action('wp_ajax_update_new_jira', 'update_new_jira');
 add_action('wp_ajax_nopriv_update_new_jira', 'update_new_jira');*/


add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_safe_redirect( home_url() );
  exit;
}


// function block_all_rest_endpoints($result) {
//     if (is_user_logged_in()) {
//         return $result; // Allow access for logged-in users
//     }
//     return new WP_Error('rest_forbidden', __('REST API access is forbidden.'), array('status' => rest_authorization_required_code()));
// }
// add_filter('rest_authentication_errors', 'block_all_rest_endpoints');


//redirect team leads to evaluation page after login
function admin_default_page($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            return home_url('/wp-admin');
        }
         else {
            return home_url('/wp-admin/edit.php?post_type=team_member&page=team_member_evaluate');
        }
    } 
  
}
add_filter('login_redirect', 'admin_default_page', 10, 3);

//hide some menu from admin for non-admin users
function hide_admin_menu_links() {
    if (!current_user_can('administrator')) {
        remove_menu_page('index.php'); 
        remove_menu_page('edit.php');  
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');    
        remove_menu_page('profile.php');
    }
}
add_action('admin_menu', 'hide_admin_menu_links');

//hide some sub-menu from admin for non-admin users
function hide_team_member_submenu_links() {
    if (!current_user_can('administrator')) {
        remove_submenu_page('edit.php?post_type=team_member', 'edit.php?post_type=team_member');
        remove_submenu_page('edit.php?post_type=team_member', 'post-new.php?post_type=team_member'); 
    }
}
add_action('admin_menu', 'hide_team_member_submenu_links');

//hide some admin bar menu items from admin for non-admin users
function hide_admin_bar_new_option($wp_admin_bar) {
    if (!current_user_can('administrator')) {
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('comments');
    }
}
add_action('admin_bar_menu', 'hide_admin_bar_new_option', 999);


function remove_dashboard_widgets() {
        global $wp_meta_boxes;
        if (!current_user_can('administrator')) {
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
        unset($wp_meta_boxes['dashboard']['normal']['high']['rank_math_dashboard_widget']);
    }
}
      
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


add_action( 'insert_team_member_with_current_week_cron_hook', 'insert_team_member_with_current_week' );
function insert_team_member_with_current_week() {
 
    date_default_timezone_set('America/Los_Angeles');
    $log_file_path = WP_CONTENT_DIR . '/jira_logs_cron.log';
    $log_message = 'Users insertion cron job triggered on ' . date('Y-m-d H:i:s') . "\n";
    file_put_contents($log_file_path, $log_message, FILE_APPEND); 
   // if (date('N') == 4) {
    global $post;
      $args = get_posts( array(
		'post_type'   => 'team_member',
        'posts_per_page' => -1
	) );
      
    if ( $args ) {
		foreach ( $args as $post ) : 

            $post_id = get_the_ID();
            $name = get_post_meta($post_id, '_team_member_name', true);
            $designation = get_post_meta($post_id, '_team_member_designation', true);
            $email = get_post_meta($post_id, '_team_member_email', true);
            $webhr_id = get_post_meta($post_id, '_team_member_webhr_id', true);
            $report_to = get_post_meta($post_id, '_team_member_report_to', true);
            $is_lead = get_post_meta( $post_id, '_team_member_is_lead', true );
            $percentage = get_post_meta( $post_id, '_team_member_percentage', true );
            $current_week =  date('W');
            $current_month=  date('m');
            $current_year = date('Y');

        
            $data = array(
                'post_id' => $post_id,
                'user_name' => $name,
                'user_designation' =>  $designation,
                'user_email' => $email ,
                'user_webhrID' => $webhr_id,
                'user_report_to' => $report_to,
                'user_is_lead' => $is_lead,
                'user_lead_percentage' => $percentage,
                'week_number' => $current_week,
                'month_number' => $current_month,
                'year' => $current_year, 
            );
        
            global $wpdb;
            $table_name = $wpdb->prefix . 'team_members';
       
            $existing_record = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}team_members WHERE post_id = %d AND week_number = %s",
                    $post_id,
                    $current_week
                )
            );

            if ($existing_record) {
                // If an entry already exists, update the existing record
                $wpdb->update($table_name, $data, array('post_id' => $post_id, 'week_number' => $current_week));
            } else {
                // If no entry exists, insert a new record
                $wpdb->insert($table_name, $data);
            }
        endforeach;

        wp_reset_postdata(); 
   // }
}

}

function user_hierarchy_chart_shortcode() {

    global $wpdb;
    $sql = "SELECT DISTINCT
e1.user_name,
e1.user_email,
e1.user_report_to
FROM
    {$wpdb->prefix}team_members e1
LEFT JOIN
    {$wpdb->prefix}team_members e2 ON e1.user_report_to = e2.user_name
LEFT JOIN
    {$wpdb->prefix}team_members e3 ON e2.user_report_to = e3.user_name

WHERE
    e1.user_report_to IS NULL
    OR e2.user_report_to IS NULL
    OR e3.user_report_to IS NULL";

    $hierarchicalData = $wpdb->get_results($sql, ARRAY_A);
    $chart_data = array(array('Name', 'Manager', 'user email'));
    $uniqueChartRows = array(); 

    foreach ($hierarchicalData as $employee) {
        $userName = $employee['user_name'];
        $userReportTo = $employee['user_report_to'];
        $userEmail = $employee['user_email'];

        if ($userReportTo == 'adeel.ahmed@graana.com' || $userReportTo == 'None') {
            $userReportTo = 'Graana Innovation Lab';
        }

        if ($userReportTo == null) {
            $uniqueChartRows[$userEmail] = array($userEmail, '', $userEmail);
        } else {
            $uniqueChartRows[$userEmail] = array($userEmail, $userReportTo, $userEmail);
        }
    }

    $chart_data = array_values($uniqueChartRows);
    $chart_data_json = json_encode($chart_data);

 
    
    ?>
    <h2>Organogram</h2>
    <div id="chart_div"></div>
    <style>
          #chart_div{
            width: 100%; 
            height: 100vh; 
            margin: auto;
            overflow:auto;
          }
        </style>
    <script>
    var chartData = <?php echo $chart_data_json; ?>;
    google.charts.load('current', { 'packages': ['orgchart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Manager');
    data.addColumn('string', 'ToolTip');
    data.addRows(chartData);

    const chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
    chart.draw(data, { allowHtml: true });
    }
    
    </script>
<?php
}

function delete_holiday_callback() {
    if (isset($_POST['holiday_id'])) {
        $holiday_id = $_POST['holiday_id'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'team_members';
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE $table_name SET public_holidays = null WHERE week_number = %s",
                $holiday_id
            )
        );

        wp_send_json(['success' => true]);
       
    }
    wp_send_json(['success' => false]);
}

add_action('wp_ajax_delete_holiday_callback', 'delete_holiday_callback');
add_action('wp_ajax_nopriv_delete_holiday_callback', 'delete_holiday_callback');
