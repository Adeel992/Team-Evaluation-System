<?php
// Display the content of the Leader Board
// add_shortcode('leaderboard_view_2', 'team_members_leaderboard_page');
add_shortcode('leaderboard_view', 'team_members_leaderboard_page');
function team_members_leaderboard_page() {
    ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
    $current_user_id = get_current_user_id();
    
   // if(current_user_can('administrator')){?>
    <!--  <div class="custom-btn wrap">
   
  <button class='jira_logs'>Update Jira Logs</button>
    <button class='webhr_leaves'>Update Web HR Leaves Hour</button>
    </div>-->
    <?php
   // }
    ?>

    <div class="wrap">
        
    <?php if (is_user_logged_in() && ! is_admin() ) {?>
        <div class="login-bar">
           <a class="login-btn" href="<?php echo home_url();?>/wp-login.php?action=logout">Logout</a> 
           </div>
          <?php } else if (! is_admin() ) { ?>
            <div class="login-bar">
           <a class="login-btn" href="<?php echo home_url();?>/wp-login.php">Login</a> 
           </div>
          <?php } 
          else {
            echo "";
          }?>
         
      
        <ul class="nav nav-tabs leaderboards" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="weekly-tab" data-toggle="tab" href="#week" role="tab" aria-controls="week" aria-selected="true">Weekly Leaderboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false">Monthly Leaderboard</a>
            </li>
        </ul>

     <div class="tab-content" id="myTabContent">
     <div class="tab-pane fade show active" id="week" role="tabpanel" aria-labelledby="week-tab">
           <div class="leaderboard-view">
              <div><h1>Weekly Leaderboard</h1></div>
               <div class="table-custom-filters"></div>

        </div>
        <div class="team-members-table">
        
                <table id="team-members-leaderboard" class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Week</th>
                                <th>Quantity</th>
                                <th>Quality</th>
                                <th>Overall</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            $counter = 1;
                            // Loop through the results
                            global $wpdb;
                            $table_name_members = $wpdb->prefix . 'team_members';
                            $table_name_evaluation = $wpdb->prefix . 'team_members_evaluation';
                            $current_user = wp_get_current_user();
                            $query = $wpdb->prepare(
                                "SELECT tm.*, tme.evaluation_overall
                                FROM $table_name_members AS tm
                                LEFT JOIN $table_name_evaluation AS tme ON tm.user_webhrID = tme.user_webhrID AND tm.week_number = tme.week_number
                                ORDER BY tm.week_number DESC"
                            );                            
                            
                            $results = $wpdb->get_results( $query );

                            if ( ! empty( $results ) ) {
                                foreach ( $results as $result ) {

                                    // Access the values
                                    $name = $result->user_name;
                                    $email = $result->user_email;
                                    $designation = $result->user_designation;
                                    $percentage = $result->user_lead_percentage;
                                    $is_lead = $result->user_is_lead;
                                    $user_report_to = $result->user_report_to;
                                    $user_webhr = $result->user_webhrID;
                                    $work_rating_value = $result->work_rating_value;
                                    $work_rating_comment = $result->work_rating_comment;
                                    $management_rating = $result->management_rating;
                                    $week = $result->week_number;
                                    $leaves_hours = $result->user_on_leaves;
                                    $jira_log_hours = $result->jira_work_logs;
                                    $public_holidays = $result->public_holidays;
                                    $evaluation_overall = $result->evaluation_overall;
                                    $sap_id = $result->user_SAP_Id;
                                  
                                
                                    if($name && $user_webhr != 1851 && $user_webhr != 3636 && $user_webhr != 3153 && $user_webhr != 1005){

                                    
                                        if( $is_lead){
                                        $percentage = intval($percentage); 
                                        }
                                        else{
                                        $percentage = 0;
                                        }
                                //  if( $is_lead == "Yes" && !empty ($work_rating_value) && !empty ($work_rating_comment) && !empty ($management_rating) && !empty ($management_comment)){
                                
                                    $quality_value = ($management_rating/10) * $percentage + ($work_rating_value/10) * (100-$percentage);
                                    
                                    $work_hours = 40 - ($leaves_hours + $public_holidays);

                                    if ($work_hours == 0 && $jira_log_hours == 0) {
                                        $quantity_value = "NA";
                                        $overall_value = "NA";
                                    } elseif ($work_hours == 0 && $jira_log_hours > 0) {
                                        $quantity_value = 100;
                                        $overall_value = $quality_value;
                                    } else {
                                
                                        $quantity_value = ($work_hours != 0) ? $jira_log_hours / $work_hours * 100 : 0;
                                        $overall_value = $quality_value * $quantity_value / 100;

                                        $quantity_value = round($quantity_value);
                                        $overall_value = round($overall_value);
                                        $quality_value = round($quality_value);
                                    }
                                
                                
                                    
                    
                                                $quantity_box_color = '';
                                                $quality_box_color = '';
                                                $overall_box_color = '';
                    
                                                if ($quantity_value >= 80 && $quantity_value != "NA") {
                                                    $quantity_box_color = "green";
                                                } elseif ($quantity_value >= 60 && $quantity_value < 80 && $quantity_value != "NA") {
                                                    $quantity_box_color = "yellow";
                                                } elseif ($quantity_value < 60 && $quantity_value != "NA") {
                                                    $quantity_box_color = "red";
                                                } else {
                                                    $quantity_box_color = "grey";
                                                }
                    
                                                if ($quality_value < 60 && $quality_value != "NA") {
                                                    $quality_box_color = "red";
                                                } elseif ($quality_value >= 60 && $quality_value < 80 && $quality_value != "NA") {
                                                    $quality_box_color = "yellow";
                                                } elseif ($quality_value >= 80 && $quality_value != "NA") {
                                                    $quality_box_color = "green";
                                                } else {
                                                    $quality_box_color = "grey";
                                                }
                    
                                                if ($overall_value < 60 && $overall_value != "NA") {
                                                    $overall_box_color = "red";
                                                } elseif ($overall_value >= 60 && $overall_value < 80 && $overall_value != "NA") {
                                                    $overall_box_color = "yellow";
                                                } elseif ($overall_value >= 80 && $overall_value != "NA") {
                                                    $overall_box_color = "green";
                                                } else {
                                                    $overall_box_color = "grey";
                                                }
                             
                                                ?>
                                                
                                                <tr>
                                                    <td><?php echo $counter; ?></td>
                                                    <td><?php echo esc_html($name); ?></td>
                                                    <td class="user-designation"><?php echo esc_html($designation); ?></td>
                                                    <td><?php echo esc_html($week); ?></td>
                                                    <td><span class="<?php echo $quantity_box_color; ?>"><?php echo ($quantity_value != "NA") ? esc_html($quantity_value) . "%" : "NA"; ?></span></td>
                                                    <td><span class="<?php echo $quality_box_color; ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($work_rating_comment); ?>"><?php echo ($quality_value != "NA") ? esc_html($quality_value) . "%" : "NA"; ?></span></td>
                                                    <td><span class="<?php echo $overall_box_color; ?>"><?php echo ($overall_value != "NA") ? esc_html($overall_value) . "%" : "NA"; ?></span></td> 
                                                </tr>
                    
                                <?php 
                                    $counter++;
                    
                            
                                }
                            }

                            }
                                else {
                                    echo "<tr><td>No results found.</td></tr>";
                                }  
                            
                          ?>
                            
                        </tbody>
                    </table>
            </div> 
        </div>
        <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">

                    <div class="leaderboard-view">
                        <div><h1>Monthly Leaderboard</h1></div>
                        <div class="personality-evaluate-table-filters"></div>

                    </div>
                    <div class="team-members-table">
                    <table id="team-members-personality-leaderboard" class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th class="per-lb-month">Month</th>
                                <th>Quantity</th>
                                <th>Quality</th>
                                <th>Values</th>
                                <th>Overall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            $counter = 1;
                            // Loop through the results
                            global $wpdb;

                            // Define the table name
                            $table_name = $wpdb->prefix . 'team_members';
                            $current_user = wp_get_current_user();
                            // Prepare and execute the SQL query

                            $query = $wpdb->prepare( "SELECT user_name, user_lead_percentage, user_designation, user_is_lead,
                            user_webhrID, work_rating_comment, month_number, personality_overall, 
                            COUNT(DISTINCT week_number) AS weeks_count,
                            AVG(work_rating_value) AS average_work_rating, 
                            AVG(management_rating) AS average_management_rating,
                            AVG(jira_work_logs) AS average_jira_work_logs, 
                            AVG(user_on_leaves) AS average_user_on_leaves,
                            AVG(public_holidays) AS average_public_holidays FROM $table_name GROUP BY user_webhrID, month_number");

                            $results = $wpdb->get_results( $query );
                            if ( ! empty( $results ) ) {
                                foreach ( $results as $result ) {
                                    $name = $result->user_name;
                                    $designation = $result->user_designation;
                                    $percentage = $result->user_lead_percentage;
                                    $is_lead = $result->user_is_lead;
                                    $user_webhr = $result->user_webhrID;
                                    $work_rating_value = $result->average_work_rating;
                                    $work_rating_comment = $result->work_rating_comment;
                                    $management_rating = $result->average_management_rating;
                                    $week = $result->weeks_count;
                                    $month = $result->month_number;
                                    $leaves_hours = $result->average_user_on_leaves;
                                    $jira_log_hours = $result->average_jira_work_logs;
                                    $personality_overall = $result->personality_overall;
                                    $public_holidays = $result->average_public_holidays;
                                     
                                    // echo $name.' '.' '.$week.' '.$work_rating_value.' '.$management_rating.' '.$jira_log_hours.' ' .$leaves_hours . "<br>";
                                    if($name && $user_webhr != 1851 && $user_webhr != 3636 && $user_webhr != 3153 && $user_webhr != 1005){

                                    
                                        if( $is_lead){
                                        $percentage = intval($percentage); 
                                        }
                                        else{
                                        $percentage = 0;
                                        }
                                //  if( $is_lead == "Yes" && !empty ($work_rating_value) && !empty ($work_rating_comment) && !empty ($management_rating) && !empty ($management_comment)){
                                
                                    $quality_value = ($management_rating/10) * $percentage + ($work_rating_value/10) * (100-$percentage);
                                  
                                    $work_hours = ($week * 40) - ( $leaves_hours + $public_holidays );

                                    if ($work_hours == 0 && $jira_log_hours == 0) {
                                        $quantity_value = "NA";
                                        $overall_value = "NA";
                                    } elseif ($work_hours == 0 && $jira_log_hours > 0) {
                                        $quantity_value = 100;
                                        $overall_value = $quality_value;
                                    } else {
                                
                                        $quantity_value = ($work_hours != 0) ? $jira_log_hours / $work_hours * 100 : 0;
                                        $values = $personality_overall;
                                        $overall_value =  ($quantity_value*25/100)+($quality_value*25/100)+($personality_overall*50/100);
                                      

                                        $quantity_value = round($quantity_value);
                                        $overall_value = round($overall_value);
                                        $quality_value = round($quality_value);
                                        $values = round($values);

                                    }                            
                    
                                       $quantity_box_color = '';
                                        $quality_box_color = '';
                                        $overall_box_color = '';
                                        $values_box_color = '';
            
                                        if ($quantity_value >= 80 && $quantity_value != "NA") {
                                            $quantity_box_color = "green";
                                        } elseif ($quantity_value >= 60 && $quantity_value < 80 && $quantity_value != "NA") {
                                            $quantity_box_color = "yellow";
                                        } elseif ($quantity_value < 60 && $quantity_value != "NA") {
                                            $quantity_box_color = "red";
                                        } else {
                                            $quantity_box_color = "grey";
                                        }
            
                                        if ($quality_value < 60 && $quality_value != "NA") {
                                            $quality_box_color = "red";
                                        } elseif ($quality_value >= 60 && $quality_value < 80 && $quality_value != "NA") {
                                            $quality_box_color = "yellow";
                                        } elseif ($quality_value >= 80 && $quality_value != "NA") {
                                            $quality_box_color = "green";
                                        } else {
                                            $quality_box_color = "grey";
                                        }
            
                                        if ($overall_value < 60 && $overall_value != "NA") {
                                            $overall_box_color = "red";
                                        } elseif ($overall_value >= 60 && $overall_value < 80 && $overall_value != "NA") {
                                            $overall_box_color = "yellow";
                                        } elseif ($overall_value >= 80 && $overall_value != "NA") {
                                            $overall_box_color = "green";
                                        } else {
                                            $overall_box_color = "grey";
                                        }

                                        if ($values < 60 && $values != "NA") {
                                            $values_box_color = "red";
                                        } elseif ($values >= 60 && $values < 80 && $values != "NA") {
                                            $values_box_color = "yellow";
                                        } elseif ($values >= 80 && $values != "NA") {
                                            $values_box_color = "green";
                                        } else {
                                            $values_box_color = "grey";
                                        }
                        
                                        ?>
                                        <tr>
                                            <td><?php echo $counter; ?></td>
                                            <td><?php echo esc_html($name); ?></td>
                                            <td class="user-designation"><?php echo esc_html($designation); ?></td>
                                            <td class="per-lb-month"><?php echo esc_html($month); ?></td>

                                            <td><span class="<?php echo $quantity_box_color; ?>"><?php echo ($quantity_value != "NA") ? esc_html($quantity_value) . "%" : "NA"; ?></span></td>

                                            <td><span class="<?php echo $quality_box_color; ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($work_rating_comment); ?>"><?php echo ($quality_value != "NA") ? esc_html($quality_value) . "%" : "NA"; ?></span></td>

                                            <td><span class="<?php echo $values_box_color; ?>"><?php echo ($values != "NA") ? esc_html($values) . "%" : "NA"; ?></span></td>

                                            <td><span class="<?php echo $overall_box_color; ?>"><?php echo ($overall_value != "NA") ? esc_html($overall_value) . "%" : "NA"; ?></span></td> 
                                            
                                        </tr>
                    
                                <?php 
                                    $counter++;
                    
                            
                                }
                            }

                            }
                                else {
                                    echo "<tr><td>No results found.</td></tr>";
                                }  
                            
                          ?>
                            
                        </tbody>
                    </table>
                     </div>
            </div>
         </div>
    <?php
    wp_reset_postdata();
}


