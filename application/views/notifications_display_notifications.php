<?php $this->load->view("template_header"); ?>

<h2>Notifications</h2>

<?php if($no_results) { ?>
    <p>You don't have pending notifications</p>
<?php } else { ?>

    <ul id="list-notifications" class="unstyled">
        <?php foreach ($lNotifications as $iNotification) { ?>
            <li class="alert alert-block <?php echo $iNotification->alertClass ?>">
                <!-- <?php echo $iNotification->id ?> -->
                <?php echo $iNotification->message ?>


                <?php if ($iNotification->displayTwoButtons) { ?>

                    <!--
                    <?php echo $iNotification->buttonLeftText ?>
                    <?php echo $iNotification->getLeftButtonAction() ?>

                    <?php echo $iNotification->buttonRightText ?>
                    <?php echo $iNotification->getRightButtonAction() ?>
                    -->

                    <?php $this->load->view('subviews/notification_button', array(
                            'form_action' => $iNotification->getLeftButtonAction(),
                            'btn_text' => $iNotification->buttonLeftText
                        ))?>

                    <?php $this->load->view('subviews/notification_button', array(
                            'form_action' => $iNotification->getRightButtonAction(),
                            'btn_text' => $iNotification->buttonRightText
                        ))?>

                <?php } else { ?>

                    <!--
                        <?php echo $iNotification->buttonText ?>
                        <?php echo $iNotification->getSingleButtonAction() ?>
                    -->

                    <?php $this->load->view('subviews/notification_button', array(
                            'form_action' => $iNotification->getSingleButtonAction(),
                            'btn_text' => $iNotification->buttonText
                        ))?>

                <?php } ?>
            </li>
        <?php } ?>
    </ul>

<?php } ?>

<?php $this->load->view("template_footer"); ?>