<?php

global $xsmod_loader;

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
if (post_password_required()) { ?>
    <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php return; } ?>

<div id="comments">
<?php if (have_comments()) : ?>
    <h3><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>

<?php $comments_by_type = &separate_comments($comments); ?>

<?php if (!empty($comments_by_type['comment'])) : ?>

    <?php xss_commentsnav(); ?>
    <ol class="commentlist">
	<?php wp_list_comments('avatar_size=64&type=comment&callback=xss_comments_list'); ?>
    </ol>
    <?php xss_commentsnav(); ?>

<?php endif; ?>
<?php if (!empty($comments_by_type['pings'])) : ?>
    <div id="pings">
        <h4><?php _e("Trackbacks/Pingbacks", "xscape-newsscape"); ?></h4>
        <ol class="pinglist">
            <?php wp_list_comments('type=pings&callback=xss_pings_list'); ?>
        </ol>
    </div>
<?php endif; ?>

<?php else : ?>
    <?php if (comments_open()) : ?>
    <?php else: ?>
        <p class="nocomments"><?php _e("Comments are closed.", "xscape-newsscape"); ?></p>
    <?php endif; ?>
<?php endif; ?>
</div>

<?php include(XSCAPE_PATH."templates/comments_".xss_global_get("integration_comment_form").".php") ?>