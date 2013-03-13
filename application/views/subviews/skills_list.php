<?php 
    if (isset($lSkills) && count($lSkills) > 0)
    {
?>
        <ul class="inline">
        <?php
            foreach ($lSkills as $iSkill) 
            {
        ?>
                <li class="label skill"><?php echo $iSkill->name ?></li>
        <?php   
            }
        ?>
        </ul>
<?php
    }
?>