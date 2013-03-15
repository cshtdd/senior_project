<?php $this->load->view("template_header"); ?>

<h2>Notifications</h2>

<?php if($no_results) { ?>
    <p>You don't have pending notifications</p>
<?php } else { ?>

    <ul id="list-notifications" class="unstyled">
        <?php foreach ($lNotifications as $iNotification) { ?>
            <!-- <li class="alert alert-block <?php echo $iNotification->alertClass ?>"> -->
            <li class="well">
                <div class="row-fluid">

                    <div class="span9">
                        <p>
                            <!-- <?php echo $iNotification->id ?> -->
                            <?php echo $iNotification->message ?>
                        </p>
                    </div>

                    <div class="span3 right-text">
                        <?php if ($iNotification->displayTwoButtons) { ?>

                            <?php $this->load->view('subviews/notification_button', array(
                                    'form_action' => $iNotification->getLeftButtonAction(),
                                    'btn_text' => $iNotification->buttonLeftText,
                                    'btn_class' => 'btn btn-danger'
                                ))?>
                        
                            <?php $this->load->view('subviews/notification_button', array(
                                    'form_action' => $iNotification->getRightButtonAction(),
                                    'btn_text' => $iNotification->buttonRightText,
                                    'btn_class' => 'btn btn-primary'
                                ))?>

                        <?php } else { ?>

                            <?php $this->load->view('subviews/notification_button', array(
                                    'form_action' => $iNotification->getSingleButtonAction(),
                                    'btn_text' => $iNotification->buttonText,
                                    'btn_class' => 'btn btn-info'
                                ))?>

                        <?php } ?>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>

<?php } ?>

<?php $this->load->view("template_footer"); ?>