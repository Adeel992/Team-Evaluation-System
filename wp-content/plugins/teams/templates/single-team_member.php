<?php
/**
 * Template Name: Single Team Member
 * Template Post Type: team_member
 */

get_header();

while ( have_posts() ) :
    the_post();
    $team_member_id = get_the_ID();
    $name = get_the_title();
    $designation = get_post_meta( get_the_ID(), '_team_member_designation', true );
    $email = get_post_meta( get_the_ID(), '_team_member_email', true );
    $webhr_id = get_post_meta( get_the_ID(), '_team_member_webhr_id', true );
    $report_to = get_post_meta( get_the_ID(), '_team_member_report_to', true );
    $is_lead = get_post_meta( get_the_ID(), '_team_member_is_lead', true );
    $percentage = get_post_meta( get_the_ID(), '_team_member_percentage', true );

    $team_lead_user =get_post_meta( $post->ID, '_team_member_username', true);
    $team_lead_password =get_post_meta( $post->ID, '_team_member_password', true);

    $work_rating_value = get_post_meta(get_the_ID(), '_team_member_work_rating_value', true);
    $work_rating_comment = get_post_meta(get_the_ID(), '_team_member_work_rating_comment', true);
    $management_rating = get_post_meta(get_the_ID(), '_team_member_management_rating', true);
    $management_comment = get_post_meta(get_the_ID(), '_team_member_management_comment', true);
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>

        <div class="entry-content">
            <table>
                <tr>
                    <th>Name:</th>
                    <td><?php echo $name; ?></td>
                </tr>
                <tr>
                    <th>Designation:</th>
                    <td><?php echo $designation; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $email; ?></td>
                </tr>
                
                <tr>
                    <th>WebHR ID:</th>
                    <td><?php echo $webhr_id; ?></td>
                </tr>
                <tr>
                    <th>Reporting to:</th>
                    <td><?php echo $report_to; ?></td>
                </tr>
               <?php if($is_lead == "Yes") { ?>
              

                <tr>
                    <th>Team Lead Username:</th>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <th>Team Lead Password:</th>
                    <td><?php echo $team_lead_password; ?></td>
                </tr>
                <tr>
                    <th>Work Percentage:</th>
                    <td><?php echo $percentage; ?>%</td>
                </tr>
                <tr>
                    <th>Work Rating:</th>
                    <td><?php echo $work_rating_value; ?></td>
                </tr>
                <tr>
                    <th>Work Rating Comment:</th>
                    <td><?php echo $work_rating_comment; ?></td>
                </tr>
                <tr>
                    <th>Management Rating:</th>
                    <td><?php echo $management_rating; ?></td>
                </tr>
                <tr>
                    <th>Management Rating Comment:</th>
                    <td><?php echo $management_comment; ?></td>
                </tr>
                <?php
               }
               ?>
            </table>
        </div>


    </article>

<?php
endwhile;

get_footer();
