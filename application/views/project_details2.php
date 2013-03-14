<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>No data for the specified project</p>

<?php } else { ?>


        <h2><?php echo $projectDetails->project->title ?></h2>


            <div class="pull-right right-text">
                <p>
                    <?php $this->load->view('subviews/skills_list', array('lSkills' => $projectDetails->lSkills) )?>
                </p>

                <p>
                    <?php if ($projectDetails->displayJoin) { ?>
                        <button class="btn btn-primary" type="button">Join</button>
                    <?php } ?>
                    
                    <?php if ($projectDetails->displayLeave) { ?>
                        <button class="btn btn-warning" type="button">Leave</button>
                    <?php } ?>
                </p>
            </div>

            <p>
                <?php echo $projectDetails->project->description ?>
            </p>

            <?php $this->load->view('subviews/user_summaries_full_list', array(
                'listTitle' => 'Proposed By:',
                'lUserSummaries' => array($projectDetails->proposedBySummary)
            )) ?>


            <?php $this->load->view('subviews/user_summaries_full_list', array(
                'listTitle' => 'Mentors:',
                'lUserSummaries' => $projectDetails->lMentorSummaries,
                'errorMessage' => 'This team needs a mentor...'
            )) ?>


            <?php $this->load->view('subviews/user_summaries_full_list', array(
                'listTitle' => 'Team Members:',
                'lUserSummaries' => $projectDetails->lTeamMemberSummaries,
                'errorMessage' => 'Join this team for a free beer! Not really...'
            )) ?>

            Delivery Term: <?php echo strtoupper($projectDetails->term->name) ?>
            <!--Project Status: <?php echo $projectDetails->project->status->name ?> -->
        

<?php }?>

<?php $this->load->view("template_footer"); ?>