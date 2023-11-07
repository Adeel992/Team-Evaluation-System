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
                        <td class="user-designation"><?php echo esc_html( $designation ); ?></td>
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
              $table_name = $wpdb->prefix . 'team_members_evaluation';

              $query = $wpdb->prepare( "SELECT * FROM $table_name");
              $results = $wpdb->get_results( $query );
              if ( ! empty( $results ) ) {
                  foreach ( $results as $result ) {
                    $post_id = $result->post_id;
                    $name = $result->user_name;
                    $email = $result->user_email;
                    $designation = $result->user_designation;
                    $report_to = $result->user_report_to;
                    $webhr_id = $result->user_webhrID;
                    $week_number = $result->week_number;
                    $month_number = $result->month_number;
                    $year_number = $result->year;
                    $current_user = wp_get_current_user();
                    $current_user_email = $current_user->user_email;

                    $quality_of_work = $result->quality_of_work;
                    $quality_of_work_comment = $result->quality_of_work_comment;

                    $server_down_incidents = $result->server_down_incidents;
                    $server_down_incidents_comment = $result->server_down_incidents_comment;

                    $mean_time_to_repair = $result->mean_time_to_repair;
                    $mean_time_to_repair_comment = $result->mean_time_to_repair_comment;

                    $code_quality_by_peer = $result->code_quality_by_peer;
                    $code_quality_by_peer_comment = $result->code_quality_by_peer_comment;

                    $code_quality_by_team_lead = $result->code_quality_by_team_lead;
                    $code_quality_by_team_lead_comment = $result->code_quality_by_team_lead_comment;
                    
                    $survey_results = $result->survey_results;
                    $survey_results_comment = $result->survey_results_comment;

                    $bug_reported = $result->bug_reported;
                    $bug_reported_comment = $result->bug_reported_comment;

                    $defects_reported = $result->defects_reported;
                    $defects_reported_comment = $result->defects_reported_comment;

                    $test_cases_tested = $result->test_cases_tested;
                    $test_cases_tested_comment = $result->test_cases_tested_comment;

                    $requirements_initiation = $result->requirements_initiation;
                    $requirements_initiation_comment = $result->requirements_initiation_comment;

                    $project_documentation = $result->project_documentation;
                    $project_documentation_comment = $result->project_documentation_comment;

                    $backlog_management = $result->backlog_management;
                    $backlog_management_comment = $result->backlog_management_comment;

                    $uat = $result->uat;
                    $uat_comment = $result->uat_comment;

                    $post_production_support = $result->post_production_support;
                    $post_production_support_comment = $result->post_production_support_comment;

                    $design_iterations = $result->design_iterations;
                    $design_iterations_comment = $result->design_iterations_comment;

                    $design_reworks = $result->design_reworks;
                    $design_reworks_comment = $result->design_reworks_comment;

                    $design_quality = $result->design_quality;
                    $design_quality_comment = $result->design_quality_comment;

                    $manuals_content = $result->manuals_content;
                    $manuals_content_comment = $result->manuals_content_comment;

                    $demo_videos = $result->demo_videos;
                    $demo_videos_comment = $result->demo_videos_comment;

                    $training_material = $result->training_material;
                    $training_material_comment = $result->training_material_comment;

                    $training_feedback_survey = $result->training_feedback_survey;
                    $training_feedback_survey_comment = $result->training_feedback_survey_comment;

                    $evaluation_overall = $result->evaluation_overall;
        
                    
                    if($name && $webhr_id != 3636 && $current_user_email==$report_to){
                  
                   ?>

                    <tr>
                     
                    <td><?php echo esc_html($name);?></td>
                    <td class="week_col">
                        <span class="week-value"><?php echo esc_html($week_number);?></span>
                            <input type="number" id="team_member_week_<?php echo $post_id;?>" class="week-value-field" name="team_member_week_<?php echo $post_id;?>" value="<?php echo esc_html($week_number);?>" />
                    </td>
                    <td>
                        
                        <?php  if($designation == "dev-ops"){?>
                            <!-- dev ops  ratings -->
                        <div class="rating-fields">
                            <span class="work-rating-value server_down_incidents"><?php echo esc_html($server_down_incidents);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_server_down_incidents_<?php echo $post_id;?>" class="min-max-range" name="team_member_server_down_incidents_<?php echo $post_id;?>" value="<?php echo esc_html($server_down_incidents);?>" data-row="<?php echo $post_id;?>" placeholder="Server Down Incidents" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($server_down_incidents_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_server_down_incidents_comment_<?php echo $post_id;?>"  name="team_member_server_down_incidents_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($server_down_incidents_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value mean_time_to_repair"><?php echo esc_html($mean_time_to_repair);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_mean_time_to_repair_<?php echo $post_id;?>" class="min-max-range" name="team_member_mean_time_to_repair_<?php echo $post_id;?>" value="<?php echo esc_html($mean_time_to_repair);?>" data-row="<?php echo $post_id;?>" placeholder="Mean Time To Repair" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($mean_time_to_repair_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_mean_time_to_repair_comment_<?php echo $post_id;?>"  name="team_member_mean_time_to_repair_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($mean_time_to_repair_comment);?>" placeholder="Comments" /></label></span>
                        </div>
                        <?php } ?>

                       
                        <?php  if($designation == "development"){?>
                             <!-- development -->
                        <div class="rating-fields">
                         <span class="work-rating-value code_quality_by_peer"><?php echo esc_html($code_quality_by_peer);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_code_quality_by_peer_<?php echo $post_id;?>" class="min-max-range" name="team_member_code_quality_by_peer_<?php echo $post_id;?>" value="<?php echo esc_html($code_quality_by_peer);?>" data-row="<?php echo $post_id;?>" placeholder="Code Quality Review By Peer" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($code_quality_by_peer_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_code_quality_by_peer_comment_<?php echo $post_id;?>"  name="team_member_code_quality_by_peer_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($code_quality_by_peer_comment);?>" placeholder="Comments" /></label></span> 
                        </div>

                        <div class="rating-fields">
                         <span class="work-rating-value code_quality_by_team_lead"><?php echo esc_html($code_quality_by_team_lead);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_code_quality_by_team_lead_<?php echo $post_id;?>" class="min-max-range" name="team_member_code_quality_by_team_lead_<?php echo $post_id;?>" value="<?php echo esc_html($code_quality_by_team_lead);?>" data-row="<?php echo $post_id;?>" placeholder="Code Quality Review By Team Lead"/></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($code_quality_by_team_lead_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_code_quality_by_team_lead_comment_<?php echo $post_id;?>"  name="team_member_code_quality_by_team_lead_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($code_quality_by_team_lead_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                        <span class="work-rating-value bugs_reported"><?php echo esc_html($bug_reported);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_bug_reported_<?php echo $post_id;?>" class="min-max-range" name="team_member_bug_reported_<?php echo $post_id;?>" value="<?php echo esc_html($bug_reported);?>" data-row="<?php echo $post_id;?>" placeholder="Bugs Reported, QA iterations" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($bug_reported_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_bug_reported_comment_<?php echo $post_id;?>"  name="team_member_bug_reported_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($bug_reported_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                          <span class="work-rating-value survey_results"><?php echo esc_html($survey_results);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_survey_results_<?php echo $post_id;?>" class="min-max-range" name="team_member_survey_results_<?php echo $post_id;?>" value="<?php echo esc_html($survey_results);?>" data-row="<?php echo $post_id;?>" placeholder="Survey Results" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($survey_results_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_survey_results_comment_<?php echo $post_id;?>"  name="team_member_survey_results_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($survey_results_comment);?>" placeholder="Comments" /></label></span>
                        </div>
                        <?php } ?>

                         
                         <?php  if($designation == "qa" || $designation == "qa-automation"){?>
                            <!-- QA -->
                        <div class="rating-fields">
                            <span class="work-rating-value defects_reported"><?php echo esc_html($defects_reported);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_defects_reported_<?php echo $post_id;?>" class="min-max-range" name="team_member_defects_reported_<?php echo $post_id;?>" value="<?php echo esc_html($defects_reported);?>" data-row="<?php echo $post_id;?>" placeholder="Number Of Defects Reported By End User" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($defects_reported_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_defects_reported_comment_<?php echo $post_id;?>"  name="team_member_defects_reported_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($defects_reported_comment);?>" placeholder="Comments" /></label></span> 
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value test_cases_tested"><?php echo esc_html($test_cases_tested);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_test_cases_tested_<?php echo $post_id;?>" class="min-max-range" name="team_member_test_cases_tested_<?php echo $post_id;?>" value="<?php echo esc_html($test_cases_tested);?>" data-row="<?php echo $post_id;?>" placeholder="Number Of Test Cases Tested vs Assigned"/></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($test_cases_tested_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_test_cases_tested_comment_<?php echo $post_id;?>"  name="team_member_test_cases_tested_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($test_cases_tested_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value survey_results"><?php echo esc_html($survey_results);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_survey_results_<?php echo $post_id;?>" class="min-max-range" name="team_member_survey_results_<?php echo $post_id;?>" value="<?php echo esc_html($survey_results);?>" data-row="<?php echo $post_id;?>" placeholder="Survey Results" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($survey_results_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_survey_results_comment_<?php echo $post_id;?>"  name="team_member_survey_results_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($survey_results_comment);?>" placeholder="Comments" /></label></span>
                        </div>
                        <?php } ?>

                        
                        <?php  if($designation == "product-management"){?>
                            <!-- Product -->
                        <div class="rating-fields">
                            <span class="work-rating-value requirements_initiation"><?php echo esc_html($requirements_initiation);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_requirements_initiation_<?php echo $post_id;?>" class="min-max-range" name="team_member_requirements_initiation_<?php echo $post_id;?>" value="<?php echo esc_html($requirements_initiation);?>" data-row="<?php echo $post_id;?>" placeholder="Requirement Gathering / Idea Initiation" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($requirements_initiation_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_requirements_initiation_comment_<?php echo $post_id;?>"  name="team_member_requirements_initiation_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($requirements_initiation_comment);?>" placeholder="Comments" /></label></span> 
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value project_documentation"><?php echo esc_html($project_documentation);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_project_documentation_<?php echo $post_id;?>" class="min-max-range" name="team_member_project_documentation_<?php echo $post_id;?>" value="<?php echo esc_html($project_documentation);?>" data-row="<?php echo $post_id;?>" placeholder="Project Documentation"/></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($project_documentation_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_project_documentation_comment_<?php echo $post_id;?>"  name="team_member_project_documentation_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($project_documentation_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value backlog_management"><?php echo esc_html($backlog_management);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_backlog_management_<?php echo $post_id;?>" class="min-max-range" name="team_member_backlog_management_<?php echo $post_id;?>" value="<?php echo esc_html($backlog_management);?>" data-row="<?php echo $post_id;?>" placeholder="Backlog Management" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($backlog_management_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_backlog_management_comment_<?php echo $post_id;?>"  name="team_member_backlog_management_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($backlog_management_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value uat"><?php echo esc_html($uat);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_uat_<?php echo $post_id;?>" class="min-max-range" name="team_member_uat_<?php echo $post_id;?>" value="<?php echo esc_html($uat);?>" data-row="<?php echo $post_id;?>" placeholder="UAT" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($uat_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_uat_comment_<?php echo $post_id;?>"  name="team_member_uat_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($uat_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value post_production_support"><?php echo esc_html($post_production_support);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_post_production_support_<?php echo $post_id;?>" class="min-max-range" name="team_member_post_production_support_<?php echo $post_id;?>" value="<?php echo esc_html($post_production_support);?>" data-row="<?php echo $post_id;?>" placeholder="Post Production Support" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($post_production_support_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_post_production_support_comment_<?php echo $post_id;?>"  name="team_member_post_production_support_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($post_production_support_comment);?>" placeholder="Comments" /></label></span>
                        </div>


                        <div class="rating-fields">
                            <span class="work-rating-value survey_results"><?php echo esc_html($survey_results);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_survey_results_<?php echo $post_id;?>" class="min-max-range" name="team_member_survey_results_<?php echo $post_id;?>" value="<?php echo esc_html($survey_results);?>" data-row="<?php echo $post_id;?>" placeholder="Survey Results" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($survey_results_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_survey_results_comment_<?php echo $post_id;?>"  name="team_member_survey_results_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($survey_results_comment);?>" placeholder="Comments" /></label></span>
                        </div>
                        <?php } ?>

                           
                        <?php  if($designation == "design"){?>
                            <!-- Design -->
                        <div class="rating-fields">
                            <span class="work-rating-value design_iterations"><?php echo esc_html($design_iterations);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_design_iterations_<?php echo $post_id;?>" class="min-max-range" name="team_member_design_iterations_<?php echo $post_id;?>" value="<?php echo esc_html($design_iterations);?>" data-row="<?php echo $post_id;?>" placeholder="Number of design iteration based on the product requirement" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($design_iterations_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_design_iterations_comment_<?php echo $post_id;?>"  name="team_member_design_iterations_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($design_iterations_comment);?>" placeholder="Comments" /></label></span> 
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value design_reworks"><?php echo esc_html($design_reworks);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_design_reworks_<?php echo $post_id;?>" class="min-max-range" name="team_member_design_reworks_<?php echo $post_id;?>" value="<?php echo esc_html($design_reworks);?>" data-row="<?php echo $post_id;?>" placeholder="Number of reworks done on design"/></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($design_reworks_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_design_reworks_comment_<?php echo $post_id;?>"  name="team_member_design_reworks_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($design_reworks_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value design_quality"><?php echo esc_html($design_quality);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_design_quality_<?php echo $post_id;?>" class="min-max-range" name="team_member_design_quality_<?php echo $post_id;?>" value="<?php echo esc_html($design_quality);?>" data-row="<?php echo $post_id;?>" placeholder=" Design Quality: UI and UX audit" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($design_quality_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_design_quality_comment_<?php echo $post_id;?>"  name="team_member_design_quality_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($design_quality_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <div class="rating-fields">
                            <span class="work-rating-value survey_results"><?php echo esc_html($survey_results);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_survey_results_<?php echo $post_id;?>" class="min-max-range" name="team_member_survey_results_<?php echo $post_id;?>" value="<?php echo esc_html($survey_results);?>" data-row="<?php echo $post_id;?>" placeholder="Survey Results" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($survey_results_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_survey_results_comment_<?php echo $post_id;?>"  name="team_member_survey_results_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($survey_results_comment);?>" placeholder="Comments" /></label></span>
                        </div>

                        <?php } ?>

                        
                        <?php  if($designation == "implementation"){?>
                            <!-- Implementation -->
                            <div class="rating-fields">
                                <span class="work-rating-value manuals_content"><?php echo esc_html($manuals_content);?></span>
                                <span class="work-rating-value-field"> 
                                <label>
                                <input type="number" id="team_member_manuals_content_<?php echo $post_id;?>" class="min-max-range" name="team_member_manuals_content_<?php echo $post_id;?>" value="<?php echo esc_html($manuals_content);?>" data-row="<?php echo $post_id;?>" placeholder="Manuals Content" /></label></span>
                                
                                <span class="work-rating-comment"> <?php echo esc_html($manuals_content_comment);?></span>
                                <span class="work-rating-comment-field" >
                                <label> 
                                <input type="text" id="team_member_manuals_content_comment_<?php echo $post_id;?>"  name="team_member_manuals_content_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($manuals_content_comment);?>" placeholder="Comments" /></label></span>
                            </div>
                            <div class="rating-fields">
                                <span class="work-rating-value demo_videos"><?php echo esc_html($demo_videos);?></span>
                                <span class="work-rating-value-field"> 
                                <label>
                                <input type="number" id="team_member_demo_videos_<?php echo $post_id;?>" class="min-max-range" name="team_member_demo_videos_<?php echo $post_id;?>" value="<?php echo esc_html($demo_videos);?>" data-row="<?php echo $post_id;?>" placeholder="Demo Videos" /></label></span>
                                
                                <span class="work-rating-comment"> <?php echo esc_html($demo_videos_comment);?></span>
                                <span class="work-rating-comment-field" >
                                <label> 
                                <input type="text" id="team_member_demo_videos_comment_<?php echo $post_id;?>"  name="team_member_demo_videos_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($demo_videos_comment);?>" placeholder="Comments" /></label></span>
                            </div>
                            <div class="rating-fields">
                                <span class="work-rating-value training_material"><?php echo esc_html($training_material);?></span>
                                <span class="work-rating-value-field"> 
                                <label>
                                <input type="number" id="team_member_training_material_<?php echo $post_id;?>" class="min-max-range" name="team_member_training_material_<?php echo $post_id;?>" value="<?php echo esc_html($training_material);?>" data-row="<?php echo $post_id;?>" placeholder="Training Material" /></label></span>
                                
                                <span class="work-rating-comment"> <?php echo esc_html($training_material_comment);?></span>
                                <span class="work-rating-comment-field" >
                                <label> 
                                <input type="text" id="team_member_training_material_comment_<?php echo $post_id;?>"  name="team_member_training_material_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($training_material_comment);?>" placeholder="Comments" /></label></span>
                            </div>
                            <div class="rating-fields">
                                <span class="work-rating-value training_feedback_survey"><?php echo esc_html($training_feedback_survey);?></span>
                                <span class="work-rating-value-field"> 
                                <label>
                                <input type="number" id="team_member_training_feedback_survey_<?php echo $post_id;?>" class="min-max-range" name="team_member_training_feedback_survey_<?php echo $post_id;?>" value="<?php echo esc_html($training_feedback_survey);?>" data-row="<?php echo $post_id;?>" placeholder="Training Feedback Survey" /></label></span>
                                
                                <span class="work-rating-comment"> <?php echo esc_html($training_feedback_survey_comment);?></span>
                                <span class="work-rating-comment-field" >
                                <label> 
                                <input type="text" id="team_member_training_feedback_survey_comment_<?php echo $post_id;?>"  name="team_member_training_feedback_survey_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($training_feedback_survey_comment);?>" placeholder="Comments" /></label></span>
                            </div>
                        <?php }?> 

                        
                        <?php $allowed_designations = array("unit-head", "project-management", "sap", "it-manager", "it-executive", "content-writers");
                          if (in_array($designation, $allowed_designations)) {?>
                          <!-- Unit head, project manager, sap, IT manager, IT exec, Content writers -->
                            <div class="rating-fields">
                            <span class="work-rating-value quality_of_work"><?php echo esc_html($quality_of_work);?></span>
                            <span class="work-rating-value-field"> 
                            <label>
                            <input type="number" id="team_member_quality_of_work_<?php echo $post_id;?>" class="min-max-range" name="team_member_quality_of_work_<?php echo $post_id;?>" value="<?php echo esc_html($quality_of_work);?>" data-row="<?php echo $post_id;?>" placeholder="Quality Of Work" /></label></span>
                            
                            <span class="work-rating-comment"> <?php echo esc_html($quality_of_work_comment);?></span>
                            <span class="work-rating-comment-field" >
                            <label> 
                            <input type="text" id="team_member_quality_of_work_comment_<?php echo $post_id;?>"  name="team_member_quality_of_work_comment_<?php  echo $post_id;?>" value="<?php echo esc_html($quality_of_work_comment);?>" placeholder="Comments" /></label></span>
                        </div>
                        <?php }?> 
                    </td>
                    <td>
          
                            <span class="quality-score" data-row="<?php echo $post_id;?>"><?php echo ($evaluation_overall != null) ? esc_html($evaluation_overall) . "%" : null;?> </span>
                            <input type="hidden" id="team_member_quality_overall_<?php echo $post_id;?>" class="quality_overall-field quality-score" name="team_member_quality_overall_<?php  echo $post_id;?>" value="<?php echo esc_html($evaluation_overall);?>" data-row="<?php echo $post_id;?>"/>
                            
                        </td>
                    </tr>

                    </tr>
                 
                <?php
                   

            // Check if the form is submitted
            
            if ( isset( $_POST['submit_evaluation'] ) ) {
               
                $week_value = intval( $_POST['team_member_week_' . $post_id] );

                $allowed_designations = array("unit-head", "project-management", "sap", "it-manager", "it-executive", "content-writers");
                if (in_array($designation, $allowed_designations)) {
                $quality_of_work = intval( $_POST['team_member_quality_of_work_' . $post_id] );
                $quality_of_work_comment = sanitize_text_field( $_POST['team_member_quality_of_work_comment_' . $post_id] );
                }

                if($designation == "dev-ops"){
                $server_down_incidents = intval( $_POST['team_member_server_down_incidents_' . $post_id] );
                $server_down_incidents_comment = sanitize_text_field( $_POST['team_member_server_down_incidents_comment_' . $post_id] );

                $mean_time_to_repair = intval( $_POST['team_member_mean_time_to_repair_' . $post_id] );
                $mean_time_to_repair_comment = sanitize_text_field( $_POST['team_member_mean_time_to_repair_comment_' . $post_id] );
                }

                if($designation == "development"){
                $code_quality_by_peer = intval( $_POST['team_member_code_quality_by_peer_' . $post_id] );
                $code_quality_by_peer_comment = sanitize_text_field( $_POST['team_member_code_quality_by_peer_comment_' . $post_id] );

                $code_quality_by_team_lead = intval( $_POST['team_member_code_quality_by_team_lead_' . $post_id] );
                $code_quality_by_team_lead_comment = sanitize_text_field( $_POST['team_member_code_quality_by_team_lead_comment_' . $post_id] );

                $bug_reported = intval( $_POST['team_member_bug_reported_' . $post_id] );
                $bug_reported_comment = sanitize_text_field( $_POST['team_member_bug_reported_comment_' . $post_id] );

                $survey_results = intval( $_POST['team_member_survey_results_' . $post_id] );
                $survey_results_comment = sanitize_text_field( $_POST['team_member_survey_results_comment_' . $post_id] );
                }

                if($designation == "qa" || $designation == "qa-automation"){
                $survey_results = intval( $_POST['team_member_survey_results_' . $post_id] );
                $survey_results_comment = sanitize_text_field( $_POST['team_member_survey_results_comment_' . $post_id] );

                $defects_reported = intval( $_POST['team_member_defects_reported_' . $post_id] );
                $defects_reported_comment = sanitize_text_field( $_POST['team_member_defects_reported_comment_' . $post_id] );

                $test_cases_tested = intval( $_POST['team_member_test_cases_tested_' . $post_id] );
                $test_cases_tested_comment = sanitize_text_field( $_POST['team_member_test_cases_tested_comment_' . $post_id] );
                }

                if($designation == "product-management"){
                $requirements_initiation = intval( $_POST['team_member_requirements_initiation_' . $post_id] );
                $requirements_initiation_comment = sanitize_text_field( $_POST['team_member_requirements_initiation_comment_' . $post_id] );

                $project_documentation = intval( $_POST['team_member_project_documentation_' . $post_id] );
                $project_documentation_comment = sanitize_text_field( $_POST['team_member_project_documentation_comment_' . $post_id] );

                $backlog_management = intval( $_POST['team_member_backlog_management_' . $post_id] );
                $backlog_management_comment = sanitize_text_field( $_POST['team_member_backlog_management_comment_' . $post_id] );

                $uat = intval( $_POST['team_member_uat_' . $post_id] );
                $uat_comment = sanitize_text_field( $_POST['team_member_uat_comment_' . $post_id] );

                $post_production_support = intval( $_POST['team_member_post_production_support_' . $post_id] );
                $post_production_support_comment = sanitize_text_field( $_POST['team_member_post_production_support_comment_' . $post_id] );

                $survey_results = intval( $_POST['team_member_survey_results_' . $post_id] );
                $survey_results_comment = sanitize_text_field( $_POST['team_member_survey_results_comment_' . $post_id] );
                }

                if($designation == "design"){
                $design_iterations = intval( $_POST['team_member_design_iterations_' . $post_id] );
                $design_iterations_comment = sanitize_text_field( $_POST['team_member_design_iterations_comment_' . $post_id] );
                
                $design_reworks = intval( $_POST['team_member_design_reworks_' . $post_id] );
                $design_reworks_comment = sanitize_text_field( $_POST['team_member_design_reworks_comment_' . $post_id] );

                $design_quality = intval( $_POST['team_member_design_quality_' . $post_id] );
                $design_quality_comment = sanitize_text_field( $_POST['team_member_design_quality_comment_' . $post_id] );

                $survey_results = intval( $_POST['team_member_survey_results_' . $post_id] );
                $survey_results_comment = sanitize_text_field( $_POST['team_member_survey_results_comment_' . $post_id] );
                }

                if($designation == "implementation"){
                $manuals_content = intval( $_POST['team_member_manuals_content_' . $post_id] );
                $manuals_content_comment = sanitize_text_field( $_POST['team_member_manuals_content_comment_' . $post_id] );

                $demo_videos = intval( $_POST['team_member_demo_videos_' . $post_id] );
                $demo_videos_comment = sanitize_text_field( $_POST['team_member_demo_videos_comment_' . $post_id] );

                $training_material = intval( $_POST['team_member_training_material_' . $post_id] );
                $training_material_comment = sanitize_text_field( $_POST['team_member_training_material_comment_' . $post_id] );
               
                $training_feedback_survey = intval( $_POST['team_member_training_feedback_survey_' . $post_id] );
                $training_feedback_survey_comment = sanitize_text_field( $_POST['team_member_training_feedback_survey_comment_' . $post_id] );
                }
                
                $evaluation_overall_val = round(floatval( $_POST['team_member_quality_overall_' . $post_id] ));

                global $wpdb;
                $current_week = $week_value;
                $current_year = date( 'Y' );
                $existing_record = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}team_members_evaluation WHERE post_id = %d AND week_number = %d AND year = %d",
                        $post_id,
                        $current_week,
                        $current_year
                    )
                );

                if ( $existing_record ) {
              
                    $data = array(
                        'quality_of_work' => $quality_of_work,
                        'quality_of_work_comment' => $quality_of_work_comment,
                        'server_down_incidents' => $server_down_incidents,
                        'server_down_incidents_comment' => $server_down_incidents_comment,
                        'mean_time_to_repair' => $mean_time_to_repair,
                        'mean_time_to_repair_comment' => $mean_time_to_repair_comment,
                        'code_quality_by_peer' => $code_quality_by_peer,
                        'code_quality_by_peer_comment' => $code_quality_by_peer_comment,
                        'code_quality_by_team_lead' => $code_quality_by_team_lead,
                        'code_quality_by_team_lead_comment' => $code_quality_by_team_lead_comment,
                        'survey_results' => $survey_results,
                        'survey_results_comment' => $survey_results_comment,
                        'bug_reported' => $bug_reported,
                        'bug_reported_comment' => $bug_reported_comment,
                        'defects_reported'=> $defects_reported,
                        'defects_reported_comment' => $defects_reported_comment,
                        'test_cases_tested' => $test_cases_tested,
                        'test_cases_tested_comment' => $test_cases_tested_comment,
                        'requirements_initiation' => $requirements_initiation,
                        'requirements_initiation_comment' => $requirements_initiation_comment,
                        'project_documentation' => $project_documentation,
                        'project_documentation_comment' => $project_documentation_comment,
                        'backlog_management'=>$backlog_management,
                        'backlog_management_comment'=>$backlog_management_comment,
                        'uat' => $uat,
                        'uat_comment' => $uat_comment,
                        'post_production_support' => $post_production_support,
                        'post_production_support_comment' => $post_production_support_comment,
                        'design_iterations' => $design_iterations,
                        'design_iterations_comment' => $design_iterations_comment,
                        'design_reworks' => $design_reworks,
                        'design_reworks_comment' => $design_reworks_comment,
                        'design_quality' => $design_quality,
                        'design_quality_comment' => $design_quality_comment,
                        'manuals_content' => $manuals_content,
                        'manuals_content_comment' => $manuals_content_comment,
                        'demo_videos' => $demo_videos,
                        'demo_videos_comment' => $demo_videos_comment,
                        'training_material' => $training_material,
                        'training_material_comment' => $training_material_comment,
                        'training_feedback_survey' => $training_feedback_survey,
                        'training_feedback_survey_comment' => $training_feedback_survey_comment,
                        'evaluation_overall' => $evaluation_overall_val,
                    );

                    $wpdb->update( $wpdb->prefix . 'team_members_evaluation', $data, array( 'post_id' => $post_id, 'week_number' => $current_week, 'year' => $current_year ) );
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
          
                
                <span class="personality-score" data-row="<?php echo $post_id;?>"><?php echo ($personality_overall != null) ? esc_html($personality_overall) . "%" : null;?> </span>
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
        $holiday_days = implode(',', $public_holidays);
       
        foreach ($public_holidays as $holiday) {
            $current_date = strtotime($holiday);
            $holiday_week = date('W', $current_date);


            $num_holidays = count($public_holidays);
          
            $holiday_value = $num_holidays * 8;
          
            $table_name = $wpdb->prefix . 'team_members';
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table_name SET public_holidays = %s, date_holidays = %s WHERE week_number = %s",
                    $holiday_value,
                    $holiday_days,
                    $holiday_week
                )
            );
        }
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
                    <th>Days</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $table_name = $wpdb->prefix . 'team_members';
                $results = $wpdb->get_results("SELECT week_number, public_holidays, date_holidays FROM $table_name GROUP BY week_number");
                $date_holidays_array = array();
                foreach ($results as $row) {
                 if($row->week_number ){
                    echo '<tr>';
                    echo '<td>' . $row->week_number . '</td>';
                    echo '<td>' . $row->public_holidays . '</td>';
                    echo '<td>' . $row->date_holidays . '</td>';
                    $delete_icon = '';
                    if ($row->public_holidays !== null) {
                        $delete_icon = '<span class="dashicons dashicons-trash delete-holiday" data-holiday-id="' . $row->week_number . '"></span>';
                    }
                    
                    echo '<td>' . $delete_icon . '</td>';
                    echo '</tr>';
                    
                    $date_holiday = $row->date_holidays;
                    if (!is_null($date_holiday) && $date_holiday !== "") {
                        $date_holidays_array[] = $date_holiday;
                    }
                 }
                    
                }
                $date_holidays_csv = implode(',', $date_holidays_array);
         
                ?>
                    <script>
                       var dateHolidaysCSV = <?php echo json_encode($date_holidays_csv); ?>;
                        console.log(dateHolidaysCSV);
                    </script>
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
                            <h5 class="modal-title" id="failModalLabel">Something went wrong!</h5>
                        </div>
                        <div class="modal-body">
                            <p>Please Try Again.</p>
                        </div>
                    </div>
                </div>
            </div>
    <?php
}