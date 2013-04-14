<?php if (isset($lExperiences) && count($lExperiences) > 0) { ?>
    <h4>Experience</h4>
    <ul>
        <?php foreach($lExperiences as $iExperience) { ?>
            <li class="well">
                <h5>

                    <?php 
                        //if (isset($iExperience->company_industry) && 
                        //    strlen($iExperience->company_industry) > 0)
                        //{
                        //    echo $iExperience->company_industry.' '; 
                        //}

                        $hasTitle = isset($iExperience->title) && 
                            strlen($iExperience->title) > 0;

                        $hasCompany_name = isset($iExperience->company_name) && 
                            strlen($iExperience->company_name) > 0;

                        if ($hasTitle)
                        {
                            echo $iExperience->title;
                        }

                        if ($hasTitle && $hasCompany_name)
                        {
                            echo ' at ';
                        }

                        if($hasCompany_name)
                        {
                            echo $iExperience->company_name;
                        }
                    ?>
                </h5>


                    <?php 
                        if (isset($iExperience->start_date) && 
                            strlen($iExperience->start_date) > 0)
                        {
                            $endDateStr = 'Present';
                            if (isset($iExperience->end_date) && 
                                strlen($iExperience->end_date) > 0)
                            {
                                $endDateStr = $iExperience->end_date;
                            }
                    ?>
                            <p class="muted">
                                <?php echo $iExperience->start_date.' to '.$endDateStr ?>
                            </p>
                    <?php
                        }
                    ?>
                

                <p>
                    <?php echo $iExperience->summary ?>
                </p>
            </li>
        <?php } ?>
    </ul>
<?php } ?>