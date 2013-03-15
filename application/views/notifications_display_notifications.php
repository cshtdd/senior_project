<?php $this->load->view("template_header"); ?>

<h2>Notifications</h2>

<?php if($no_results) { ?>
    <p>You don't have pending notifications</p>
<?php } else { ?>

    <ul class="unstyled">
        <?php foreach ($lNotifications as $iNotification) { ?>
            <li>
                <?php echo $iNotification->id ?>
                <?php echo $iNotification->message ?>


                <?php if ($iNotification->displayTwoButtons) { ?>

                    <?php echo $iNotification->buttonLeftText ?>
                    <?php echo $iNotification->getLeftButtonAction() ?>


                    <?php echo $iNotification->buttonRightText ?>
                    <?php echo $iNotification->getRightButtonAction() ?>

                <?php } else { ?>

                    <?php echo $iNotification->buttonText ?>
                    <?php echo $iNotification->getSingleButtonAction() ?>

                <?php } ?>
            </li>
        <?php } ?>
    </ul>

<?php } ?>

<?php $this->load->view("template_footer"); ?>