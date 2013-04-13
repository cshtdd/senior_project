<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

    <p>No data for the specified project</p>

<?php } else { ?>

        <div class="pull-right hor-margin">
                <?php $this->load->view('subviews/join_leave_buttons', array('projectDetails' => $projectDetails)) ?>
        </div>

        <div class="pull-right">
            <?php $this->load->view('subviews/skills_list', array('lSkills' => $projectDetails->lSkills) )?>
        </div>

        <h2><?php echo $projectDetails->project->title ?></h2>


        <p>
            <?php echo $projectDetails->project->description ?>
        </p>

        <p>
            <h4 class="muted inline">Delivery Term:</h4> <?php echo strtoupper($projectDetails->term->name) ?>
        </p>

        <p>
            <h4 class="muted inline">Status:</h4> <?php echo ucfirst($projectDetails->statusName) ?>                 
        </p>

        <p>
            <h4 class="muted inline">Maximum project capacity:</h4> <?php echo $projectDetails->project->max_students.' students' ?>
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

<?php }?>

<?php $this->load->view("template_footer"); ?>