<?php if ($user_summary->invite) { ?>

<div class="center-text">
    <button type="button" class="btn myUserInviter <?php echo isset($btnClass) && strlen($btnClass) > 0 ? $btnClass : '' ?>"
        data-idtoinvite="<?php echo $user_summary->user->id ?>">Invite</button>
</div>

<?php } ?>