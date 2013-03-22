<?php if (isset($lExperiences) && count($lExperiences) > 0) { ?>
    <h4>Experience</h4>
    <ul>
        <?php foreach($lExperiences as $iExperience) { ?>
            <li class="well">
                <h5>
                    <?php echo $iExperience->title ?>
                </h5>

                <p>
                    <?php echo $iExperience->description ?>
                </p>
            </li>
        <?php } ?>
    </ul>
<?php } ?>