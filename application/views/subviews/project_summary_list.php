<ul class="project_list unstyled <?php echo isset($list_class) && strlen($list_class) > 0 ? $list_class : '' ?>  ">
    <?php if (isset($list_title) && strlen($list_title) > 0) { ?>
        <lh><h2><?php echo $list_title ?></h2></lh>
    <?php } ?>

    <?php foreach ($lProjects as $iProject) { ?>
        <li class="well">

            <div class="pull-right right-text">
                <p>
                    <!-- old way of displaying the skills -->
                    <!-- <?php echo $iProject->getlSkillNames() ?> -->
                    <?php $this->load->view('subviews/skills_list', array('lSkills' => $iProject->lSkills) )?>
                </p>

                <p>
                    <?php $this->load->view('subviews/join_leave_buttons', array('projectDetails' => $iProject)) ?>
                </p>
            </div>

            <h4>
                <?php echo anchor('project/'.$iProject->project->id, $iProject->project->title) ?>
            </h4>

            <p>
                <?php echo $iProject->getShortDescription() ?>
                <?php echo anchor('project/'.$iProject->project->id, 'More Info...') ?>
            </p>

            <ul class="unstyled inline">
                <lh class="muted">Proposed By:</lh>
                <li>
                    <?php $this->load->view('subviews/user_summary_full_name', array('user_summary' => $iProject->proposedBySummary) )?>
                </li>
            </ul>

            <ul class="unstyled inline">
                <lh class="muted">Mentors:</lh>

                <?php if (isset($iProject->lMentorSummaries) && count($iProject->lMentorSummaries) > 0) { ?>
                    
                    <?php foreach ($iProject->lMentorSummaries as $iMentorSumm) { ?>
                        <li>
                            <?php $this->load->view('subviews/user_summary_full_name', array('user_summary' => $iMentorSumm) )?>
                        </li>
                    <?php } ?>

                <?php } else { ?>

                    <li>This team needs a mentor...</li>

                <?php }?>
            </ul>


            <ul class="unstyled inline">
                <lh class="muted">Team Members:</lh>

                <?php if (isset($iProject->lTeamMemberSummaries) && count($iProject->lTeamMemberSummaries) > 0) { ?>

                    <?php foreach ($iProject->lTeamMemberSummaries as $iMemberSumm) { ?>
                        <li>
                            <?php $this->load->view('subviews/user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
                        </li>
                    <?php } ?>

                <?php } else { ?>

                    <li>Join this team for a free beer! Not really...</li>

                <?php }?>
            </ul>

            Delivery Term: <?php echo strtoupper($iProject->term->name) ?>
            <!--Project Status: <?php echo $iProject->project->status->name ?> -->
        </li>
    <?php } ?>
</ul>