<div class='user-summary-full center-text'>
    <?php 
        $imgUrl = 'http://www.gravatar.com/avatar/';
        if (isset($user_summary->user->picture) &&
            strlen($user_summary->user->picture) > 0)
        {
            $imgUrl = $user_summary->user->picture;
        }

        echo '<a href="'.base_url().'user/'.$user_summary->user->id.'">';

            echo img(array(
                'src' => $imgUrl,
                'class' => 'user-img',
                'alt' => $user_summary->getFullName()
            ));

        echo '</a>';
    ?>
    <?php echo anchor('user/'.$user_summary->user->id, $user_summary->getFullName()) ?>
</div>