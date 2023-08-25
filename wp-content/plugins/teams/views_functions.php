<?php
// Callback function for displaying the team members table
function team_members_display_table() {
    $current_user_id = get_current_user_id();
    
    if(current_user_can('administrator')){
        $query = new WP_Query( array(
            'post_type'      => 'team_member',
            'posts_per_page' => -1,
        ) );
    }
    else{
        $query = new WP_Query( array(
            'post_type'      => 'team_member',
            'posts_per_page' => -1,
            'author'         => $current_user_id,
        ) );
    }
   
    ?>
    <div class="wrap">
    <h1>Team Members Table</h1>
        <div class="team-members-table">
        
        <div class="table-header">
        </div>
        <table id="team-members-table" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>WebHR Id</th>
                    <th>Role Category</th>
                    <th>Reporting To</th>
                    <th>Lead</th>
                    <th>Lead %</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                $counter = 1;
                while ( $query->have_posts() ) : $query->the_post();
                $post_ID = get_the_ID();
                $post_slug = get_post_field('post_name', get_post()); ?>

                    <?php
                    $name = get_post_meta( get_the_ID(), '_team_member_name', true );
                    $designation = get_post_meta( get_the_ID(), '_team_member_designation', true );
                    $email = get_post_meta( get_the_ID(), '_team_member_email', true );
                    $webhr_id = get_post_meta( get_the_ID(), '_team_member_webhr_id', true );
                    $report_to = get_post_meta( get_the_ID(), '_team_member_report_to', true );
                    $is_lead = get_post_meta( get_the_ID(), '_team_member_is_lead', true );
                    $member_status = get_post_meta( get_the_ID(), '_team_member_status', true ); 
                    if($is_lead == "Yes"){
                    $percentage = get_post_meta( get_the_ID(), '_team_member_percentage', true ) . "%";
                    }
                    else{
                        $percentage = "-";
                    }
                    if($name){
                    ?>

                    <tr>
                        <td><?php echo $counter;?></td>
                        <td><?php echo esc_html($name);?></td>
                        <td><?php echo esc_html( $webhr_id ); ?></td>
                        <td><?php echo esc_html( $designation ); ?></td>
                        <td><?php echo esc_html( $report_to ); ?></td>
                        <td><?php echo esc_html( $is_lead ); ?></td>
                        <td><?php echo esc_html($percentage);?></td>
                        <td><?php echo esc_html($member_status);?></td>
                        <td><a href="<?php echo home_url();?>/wp-admin/post.php?post=<?php echo $post_ID ;?>&action=edit"><span class="dashicons dashicons-edit"></span></a>
                        <a href="<?php echo home_url();?>/team_member/<?php echo $post_slug?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>
                        
                    </tr>
                <?php 
                    }
             $counter++;
            endwhile; 
           ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php
    wp_reset_postdata();
}


// Display the content of the evaluate table
function team_members_evaluate_page() {
    $current_user_id = get_current_user_id();
   
    ?>
    <div class="wrap">
        <div class="evaluate-table-view">
           <div><h1>Performance Evaluation</h1></div>
               <div class="evaluate-table-filters">
      
               </div>
       </div>
        <div class="team-members-table" id="evaluation-form-container">

        <form method="post" id="submit-evaluations">
        <table id="team-members-evaluate-table" class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="week_col">Week</th>
                    <th>Work Quality Rating</th>
                    <th>Quality Score</th>
                </tr>
            </thead>
            <tbody>
                <?php 
             
              global $wpdb;
              $table_name = $wpdb->prefix . 'team_members';

              $query = $wpdb->prepare( "SELECT * FROM $table_name");
              $results = $wpdb->get_results( $query );
              if ( ! empty( $results ) ) {
                  foreach ( $results as $result ) {
                    $post_id = $result->post_id;
                    $name = $result->user_name;
                    $email = $result->user_email;
                    $designation = $result->user_designation;
                    $percentage = $result->user_lead_percentage;
                    $is_lead = $result->user_is_lead;
                    $report_to = $result->user_report_to;
                    $lead_password = $result->user_lead_password;
                    $webhr_id = $result->user_webhrID;
                    $work_rating_value = $result->work_rating_value;
                    $management_rating = $result->management_rating;
                    $work_rating_comment = $result->work_rating_comment;
                    $management_comment = $result->management_comment;
                    $member_status = $result->user_status;
                    $leaves_hours = $result->user_on_leaves;
                    $jira_log_hours = $result->jira_work_logs;
                    $week_number = $result->week_number;
                    $month_number = $result->month_number;
                    $quality_overall = $result->quality_overall;
                    $current_user = wp_get_current_user();
                    $current_user_email = $current_user->user_email;
                    
                    if($name && $webhr_id != 1851 && $webhr_id != 3636 && $webhr_id != 3153 && $current_user_email==$report_to){
                  
                   ?>

                    <tr>
                     
                    <td><?php echo esc_html($name);?></td>
                    <td class="week_col">
                        <span class="week-value"><?php echo esc_html($week_number);?></span>
                            <input type="number" id="team_member_week_<?php echo $post_id;?>" class="week-value-field" name="team_member_week_<?php echo $post_id;?>" value="<?php echo esc_html($week_number);?>" />
                    </td>
                    <td>
                        <div class="rating-fields">
                        <span class="work-rating-value"><?php echo esc_html($work_rating_value);?></span>
                        <span class="work-rating-value-field"> 
                        <label>Work Rating
                        <input type="number" id="team_member_work_rating_<?php echo $post_id;?>" class="min-max-range" name="team_member_work_rating_<?php echo $post_id;?>" value="<?php echo esc_html($work_rating_value);?>" /></label></span>
                        
                        <span class="work-rating-comment"> <?php echo esc_html($work_rating_comment);?></span>
                        <span class="work-rating-comment-field" >
                        <label> Comments
                        <input type="text" id="team_member_work_rating_comment_<?php echo $post_id;?>"  name="team_member_work_rating_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($work_rating_comment);?>" /></label></span>
                        </div>

                        <div class="rating-fields">
                       <?php  if($is_lead == "Yes"){?>
                        <span class="team-management-value"><?php echo esc_html($management_rating);?></span>
                        <span class="team-management-value-field">
                        <label>Management Rating
                        <input type="number" id="team_member_management_<?php echo $post_id;?>" class="min-max-range" name="team_member_management_<?php echo $post_id;?>" value="<?php echo esc_attr( $management_rating ); ?>" /></label></span>

                        <span class="work-management-comment"><?php echo esc_html($management_comment);?></span>
                        <span class="work-management-comment-field">
                        <label>Comments
                        <input type="text" id="team_member_work_management_comment_<?php echo $post_id;?>" name="team_member_work_management_comment_<?php echo $post_id;?>" value="<?php echo esc_html($management_comment);?>" /></label></span>
                        <?php }?> 
                        </div>
                    </td>

                    <td>
          
                            <span class="quality-score" data-row="<?php echo $post_id;?>"><?php echo esc_html($quality_overall);?></span>
                            <input type="hidden" id="team_member_quality_overall_<?php echo $post_id;?>" class="quality_overall-field quality-score" name="team_member_quality_overall_<?php  echo $post_id;?>" value="<?php echo esc_html($quality_overall);?>" data-row="<?php echo $post_id;?>"/>
                            
                        </td>
                    </tr>

                    </tr>
                 
                <?php
                   

            // Check if the form is submitted
            
            if ( isset( $_POST['submit_evaluation'] ) ) {
               
                $work_rating_value = intval( $_POST['team_member_work_rating_' . $post_id] );
                $work_rating_comment = sanitize_text_field( $_POST['team_member_work_rating_comment_' . $post_id] );
                $week_value = intval( $_POST['team_member_week_' . $post_id] );
                if($is_lead == "Yes"){
                $management_rating = intval( $_POST['team_member_management_' . $post_id] );
                $management_comment = sanitize_text_field( $_POST['team_member_work_management_comment_' . $post_id] );

                 }
                

                // Check if the record already exists in the custom table
                global $wpdb;
                // $current_week = date( 'W' );
                $current_week = $week_value;
                $current_year = date( 'Y' );
                $existing_record = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}team_members WHERE post_id = %d AND week_number = %d AND year = %d",
                        $post_id,
                        $current_week,
                        $current_year
                    )
                );

                if ( $existing_record ) {
                   
                    // Update the existing record
              
                    $data = array(
                        'work_rating_value' => $work_rating_value,
                        'work_rating_comment' => $work_rating_comment,
                        'management_rating' => $management_rating,
                        'management_comment' => $management_comment,
                        'week_number' => $current_week,
                    );

                    $wpdb->update( $wpdb->prefix . 'team_members', $data, array( 'post_id' => $post_id, 'week_number' => $current_week, 'year' => $current_year ) );
                } 
                else {
                   
                    $data = array(
                        'post_id' => $post_id,
                        'user_name' => $name,
                        'user_designation' => $designation,
                        'user_email' => $email,
                        'user_webhrID' => $webhr_id,
                        'user_report_to' => $report_to,
                        'user_is_lead' => $is_lead,
                        'user_lead_percentage' => $percentage,
                        'user_status' => $member_status,
                        'lead_username' => '',
                        'lead_password' => $lead_password,
                        'work_rating_value' => $work_rating_value,
                        'work_rating_comment' => $work_rating_comment,
                        'management_rating' => $management_rating,
                        'management_comment' => $management_comment,
                        'week_number'  => $current_week,
                        'year'  => $current_year,
                        'user_on_leaves' => $leaves_hours ,
                        'jira_work_logs' => $jira_log_hours,
                    );

                    $wpdb->insert( $wpdb->prefix . 'team_members', $data );
                }
            }
          
        }
    }
    }
           // endwhile; 
 
            if ( isset( $_POST['submit_evaluation'] ) ) {
                echo '<script>
                setTimeout(function() {
                    window.location.reload();
                }, 1000); // 3000 milliseconds = 3 seconds
            </script>';
            echo '<div class="notice notice-success"><p>Evaluation data submitted successfully.</p></div>';
            }
           ?>
            </tbody>
        </table>
      <div class="edit-button">
    <button class="btn btn-dark edit-evaluation">Evaluate</button>
    <button class="btn btn-info save-evaluation" name="submit_evaluation" type="submit">Submit</button>
</div>
        <?php
    //    }
    //  else{
    //   echo"<div class='edit-button evaluation-message'></div>";
    //   }
        ?>
        </form>
        </div>
    </div>
    <?php

}

function team_members_personality_evaluate_page() {
    ?>
     <div class="wrap">
        <div class="evaluate-table-view">
            <div><h1>Personality Evaluation</h1></div>
       
        <div class="personality-evaluate-table-filters">
      
      </div>
      </div>
    
    <div class="team-members-table" id="evaluation-form-container">

<form method="post" id="submit-personality-evaluations">
<table id="team-members-personality-evaluate-table" class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th>Name</th>
            <th class="month_col">Month</th>
            <th class="week_col">Month</th>
            <th>Integrity</th>
            <th>Respect</th>
            <th>Reliability</th>
            <th>Innovation</th>
            <th>Drive</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php 
     
      global $wpdb;
      $table_name = $wpdb->prefix . 'team_members';

      $query = $wpdb->prepare( "SELECT *  FROM $table_name
      WHERE (month_number, week_number) IN ( SELECT month_number, MAX(week_number) AS highest_week FROM  $table_name
          GROUP BY month_number)");
      $results = $wpdb->get_results( $query );
      if ( ! empty( $results ) ) {
          foreach ( $results as $result ) {
            $post_id = $result->post_id;
            $name = $result->user_name;
            $email = $result->user_email;
            $designation = $result->user_designation;
            $percentage = $result->user_lead_percentage;
            $is_lead = $result->user_is_lead;
            $report_to = $result->user_report_to;
            $lead_password = $result->user_lead_password;
            $webhr_id = $result->user_webhrID;
            $work_rating_value = $result->work_rating_value;
            $management_rating = $result->management_rating;
            $work_rating_comment = $result->work_rating_comment;
            $management_comment = $result->management_comment;
            $member_status = $result->user_status;
            $leaves_hours = $result->user_on_leaves;
            $jira_log_hours = $result->jira_work_logs;
            $week_number = $result->week_number;
            $month_number = $result->month_number;
            $integrity = $result->integrity;
            $respect = $result->respect;
            $reliability = $result->reliability;
            $innovation = $result->innovation;
            $drive = $result->drive;
            $integrity_comment = $result->integrity_comment;
            $respect_comment = $result->respect_comment;
            $reliability_comment = $result->reliability_comment;
            $innovation_comment = $result->innovation_comment;
            $drive_comment = $result->drive_comment;
            $personality_overall = $result->personality_overall;
            $current_user = wp_get_current_user();
            $current_user_email = $current_user->user_email;
            
            if($name && $webhr_id != 1851 && $webhr_id != 3636 && $webhr_id != 3153 && $current_user_email==$report_to){
          
           ?>


            <tr>
             
            <td><?php echo esc_html($name);?></td>
            <td class="month_col">
            <span class="month-value"><?php echo esc_html($month_number);?></span>
                    <input type="number" id="team_member_month_<?php echo $post_id;?>" class="month-value-field" name="team_member_month_<?php echo $post_id;?>" value="<?php echo esc_html($month_number);?>" />
           </td>

           <td class="week_col">
            <span class="week-value"><?php echo esc_html($week_number);?></span>
                    <input type="number" id="team_member_week_<?php echo $post_id;?>" class="week-value-field" name="team_member_week_<?php echo $post_id;?>" value="<?php echo esc_html($week_number);?>" />
           </td>

            <td>
                <span class="integrity-value"><?php echo esc_html($integrity);?></span>
                <input type="number" placeholder="Score" id="team_member_integrity_<?php echo $post_id;?>" class="integrity-value-field min-max-range" name="team_member_integrity_<?php echo $post_id;?>" data-row="<?php echo $post_id;?>" value="<?php echo esc_html($integrity);?>" />

                <span class="integrity-comment"> <?php echo esc_html($integrity_comment);?></span>
                <input type="text" placeholder="Enter comment" id="team_member_integrity_comment_<?php echo $post_id;?>" class="integrity-comment-field" name="team_member_integrity_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($integrity_comment);?>" />
            </td>

            <td>
                <span class="respect-value"><?php echo esc_html($respect);?></span>
                <input type="number" placeholder="Score" id="team_member_respect_<?php echo $post_id;?>" class="respect-value-field min-max-range" name="team_member_respect_<?php echo $post_id;?>" data-row="<?php echo $post_id;?>" value="<?php echo esc_html($respect);?>" />

                <span class="respect-comment"> <?php echo esc_html($respect_comment);?></span>
                <input type="text" placeholder="Enter comment" id="team_member_respect_comment_<?php echo $post_id;?>" class="respect-comment-field" name="team_member_respect_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($respect_comment);?>" />
            </td>

            <td>
                <span class="reliability-value"><?php echo esc_html($reliability);?></span>
                <input type="number" placeholder="Score" id="team_member_reliability_<?php echo $post_id;?>" class="reliability-value-field min-max-range" name="team_member_reliability_<?php echo $post_id;?>" data-row="<?php echo $post_id;?>" value="<?php echo esc_html($reliability);?>" />

                <span class="reliability-comment"> <?php echo esc_html($reliability_comment);?></span>
                <input type="text" placeholder="Enter comment" id="team_member_reliability_comment_<?php echo $post_id;?>" class="reliability-comment-field" name="team_member_reliability_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($reliability_comment);?>" />
            </td>

            <td>
                <span class="innovation-value"><?php echo esc_html($innovation);?></span>
                <input type="number" placeholder="Score" id="team_member_innovation_<?php echo $post_id;?>" class="innovation-value-field min-max-range" name="team_member_innovation_<?php echo $post_id;?>" data-row="<?php echo $post_id;?>" value="<?php echo esc_html($innovation);?>" />

                <span class="innovation-comment"> <?php echo esc_html($innovation_comment);?></span>
                <input type="text" placeholder="Enter comment" id="team_member_innovation_comment_<?php echo $post_id;?>" class="innovation-comment-field" name="team_member_innovation_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($innovation_comment);?>" />
            </td>

            <td>
                <span class="drive-value"><?php echo esc_html($drive);?></span>
                <input type="number" placeholder="Score" id="team_member_drive_<?php echo $post_id;?>" class="drive-value-field min-max-range" name="team_member_drive_<?php echo $post_id;?>" data-row="<?php echo $post_id;?>" value="<?php echo esc_html($drive);?>" />
                <span class="drive-comment"> <?php echo esc_html($drive_comment);?></span>
                <input type="text" placeholder="Enter comment" id="team_member_drive_comment_<?php echo $post_id;?>" class="drive-comment-field" name="team_member_drive_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($drive_comment);?>" />
            </td>

            <td>
          
                <span class="personality-score" data-row="<?php echo $post_id;?>"><?php echo esc_html($personality_overall);?></span>
                <input type="hidden" id="team_member_personality_overall_<?php echo $post_id;?>" class="personality_overall-field personality-score" name="team_member_personality_overall_<?php  echo $post_id;?>" value="<?php echo esc_html($personality_overall);?>" data-row="<?php echo $post_id;?>"/>
               
            </td>

            </tr>
         
        <?php
           

    // Check if the form is submitted
    
    if ( isset( $_POST['submit_personality_evaluation'] ) ) {

        $month_value = intval( $_POST['team_member_month_' . $post_id] );
       
        $integrity = intval( $_POST['team_member_integrity_' . $post_id] );
        $integrity_comment = sanitize_text_field( $_POST['team_member_integrity_comment_' . $post_id] );
       
        $respect = intval( $_POST['team_member_respect_' . $post_id] );
        $respect_comment = sanitize_text_field( $_POST['team_member_respect_comment_' . $post_id] );

        $reliability = intval( $_POST['team_member_reliability_' . $post_id] );
        $reliability_comment = sanitize_text_field( $_POST['team_member_reliability_comment_' . $post_id] );

        $innovation = intval( $_POST['team_member_innovation_' . $post_id] );
        $innovation_comment = sanitize_text_field( $_POST['team_member_innovation_comment_' . $post_id] );

        $drive = intval( $_POST['team_member_drive_' . $post_id] );
        $drive_comment = sanitize_text_field( $_POST['team_member_drive_comment_' . $post_id] );

        $personality_overall_val = floatval( $_POST['team_member_personality_overall_' . $post_id] );

        // Check if the record already exists in the custom table
        global $wpdb;
        $current_month = $month_value;
        $current_year = date( 'Y' );
        $existing_record = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}team_members WHERE post_id = %d AND month_number = %d AND year = %d",
                $post_id,
                $current_month,
                $current_year
            )
        );

        if ( $existing_record ) {
      
            $data = array(
                'integrity' => $integrity,
                'integrity_comment' => $integrity_comment,
                'respect' => $respect,
                'respect_comment' => $respect_comment,
                'reliability' => $reliability,
                'reliability_comment' => $reliability_comment,
                'innovation' => $innovation,
                'innovation_comment' => $innovation_comment,
                'drive' => $drive,
                'drive_comment' => $drive_comment,
                'personality_overall' =>  $personality_overall_val,
                'month_number' => $current_month,
            );
         

            $wpdb->update( $wpdb->prefix . 'team_members', $data, array( 'post_id' => $post_id, 'month_number' => $current_month, 'year' => $current_year ) );
        } 
        else {
           
            $data = array(
                'post_id' => $post_id,
                'user_name' => $name,
                'user_designation' => $designation,
                'user_email' => $email,
                'user_webhrID' => $webhr_id,
                'user_report_to' => $report_to,
                'user_is_lead' => $is_lead,
                'user_lead_percentage' => $percentage,
                'user_status' => $member_status,
                'lead_username' => '',
                'lead_password' => $lead_password,
                'work_rating_value' => $work_rating_value,
                'work_rating_comment' => $work_rating_comment,
                'management_rating' => $management_rating,
                'management_comment' => $management_comment,
                'week_number'  => $current_week,
                'month_number'  => $current_month,
                'year'  => $current_year,
                'user_on_leaves' => $leaves_hours ,
                'jira_work_logs' => $jira_log_hours,
                'integrity' => $integrity,
                'integrity_comment' => $integrity_comment,
                'respect' => $respect,
                'respect_comment' => $respect_comment,
                'reliability' => $reliability,
                'reliability_comment' => $reliability_comment,
                'innovation' => $innovation,
                'innovation_comment' => $innovation_comment,
                'drive' => $drive,
                'drive_comment' => $drive_comment,
                'personality_overall' =>  $personality_overall_val,
            );

            $wpdb->insert( $wpdb->prefix . 'team_members', $data );
        }
    }
  
}
}
      }

    if ( isset( $_POST['submit_personality_evaluation'] ) ) {
        echo '<script>
        setTimeout(function() {
            window.location.reload();
        }, 1000); // 3000 milliseconds = 3 seconds
    </script>';
    echo '<div class="notice notice-success"><p>Personality evaluation data submitted successfully.</p></div>';
    }
   ?>
    </tbody>
</table>
<?php 
//if(date('N') === '5'){
    
    ?>
<div class="edit-button">
    <button class="btn btn-dark edit-evaluation">Evaluate</button>
    <button class="btn btn-info save-evaluation" name="submit_personality_evaluation" type="submit">Submit</button>
</div>
<?php
//    }
//  else{
//   echo"<div class='edit-button evaluation-message'></div>";
//   }
?>
</form>
</div>

       <?php
}


function team_members_public_holiday(){

    global $wpdb;

    if (isset($_POST['add_public_holidays'])) {
        $public_holidays = isset($_POST['public_holidays']) ? explode(',', $_POST['public_holidays'][0]) : array();

        foreach ($public_holidays as $holiday) {
            $current_date = strtotime($holiday);
            $holiday_week = date('W', $current_date);


            $num_holidays = count($public_holidays);
            $holiday_value = $num_holidays * 8;

            $table_name = $wpdb->prefix . 'team_members';
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table_name SET public_holidays = %s WHERE week_number = %s",
                    $holiday_value,
                    $holiday_week
                )
            );
        }
        
        echo '<script>
        setTimeout(function() {
            window.location.reload();
        }, 3000);
    </script>';
        echo '<div class="notice notice-success"><p>Holidays has been submitted successfully.</p></div>';
    }
   

    ?>

    <div class="wrap">
        <h2>Add Public Holidays</h2>
        <form method="post" action="">
            <div class="form-group">
            <input type="text form-control" name="public_holidays[]" id="public_holidays" value="" placeholder="Select Dates" multiple autocomplete="off" required>
            <input class="btn btn-primary" type="submit" name="add_public_holidays" value="Submit">
            <button type="button" class="btn btn-secondary" id="clear_dates">Clear</button>
             </div>
        </form>
    </div>

    <div class="wrap">
        <h2>Public Holidays List</h2>
        <table class="holidays-table wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Week Number</th>
                    <th>Public Holidays (8 hrs per holiday)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $table_name = $wpdb->prefix . 'team_members';
                $results = $wpdb->get_results("SELECT week_number, public_holidays FROM $table_name GROUP BY week_number");
                foreach ($results as $row) {
                 if($row->week_number ){
                    echo '<tr>';
                    echo '<td>' . $row->week_number . '</td>';
                    echo '<td>' . $row->public_holidays . '</td>';
                    $delete_icon = '';
                    if ($row->public_holidays !== null) {
                        $delete_icon = '<span class="dashicons dashicons-trash delete-holiday" data-holiday-id="' . $row->week_number . '"></span>';
                    }
                    
                    echo '<td>' . $delete_icon . '</td>';
                    echo '</tr>';
                 }
                    
                }
                ?>
            </tbody>
        </table>
    </div>
           <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Success!</h5>
                        </div>
                        <div class="modal-body">
                            <p>Holidays have been Deleted successfully.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="failModal" tabindex="-1" role="dialog" aria-labelledby="failModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Something went wrong!</h5>
                        </div>
                        <div class="modal-body">
                            <p>Please Try Again.</p>
                        </div>
                    </div>
                </div>
            </div>
    <?php
}