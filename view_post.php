<?php
require 'header.php';
require 'includes/database/posts.php';
require 'includes/database/comments.php';

increment_viewers(intval($_GET['id']));
$post = get_post($_GET['id']);
$comments = get_post_comments($post['id']);

$status = $_GET['status'] ?? null;

if ($status != null) {
    $msg = '';
    if ($_GET['type'] == 'deleted_comment') {
        $msg = 'The comment was deleted.';
    } elseif ($_GET['type'] == 'new_comment') {
        $msg = 'Your comment was added successfully.';
    } elseif ($_GET['type'] == 'error_deleted_comment') {
        $msg = 'The comment was not deleted. Something went wrong.';
    } elseif ($_GET['type'] == 'error_new_comment') {
        $msg = 'Your comment was not added. Something went wrong.';
    }
}

?>
<section class="container">
    <div class="row">
        <?php if ($status == 'success'): ?>
            <div class="alert alert-success"><?= $msg; ?></div>
        <?php endif; ?>

        <?php if ($status == 'error'): ?>
            <div class="alert alert-danger"><?= $msg; ?></div>
        <?php endif; ?>
        <div class="col-lg-8 col-md-9" id="blog-post">
            <h2 class="text-light"><?= $post['title'] ?></h2>
            <div class="d-flex flex-row align-items-center">
                <i class="fa fa-eye"></i> <span class="ms-1 views text-light" ><?= $post['views'] ?></span>
                <span class="ms-3 text-light"><em class="text-light">Author: </em><?= $post['author'] ?></span>
            </div>
            <img class="img-fluid my-2" src="<?= BASE_URL . ($post['image'] ?? '/img/missing_image.jpeg'); ?>">
            <p class="text-light"><?= nl2br($post['description']) ?></p>
        </div>
        <?php if (is_logged_in()): ?>
            <form method="post" action="includes/actions/comment.inc.php">
                <textarea name="description" class="form-control mt-2 mb-2" placeholder="Write a comment"></textarea>
                <input type="hidden" name="action" value="new_comment">
                <input type="hidden" name="postId" value="<?= $post['id'] ;?>">
                <button type="submit" name="comments-submit" class="btn btn-primary">Komento</button>
            </form>
        <?php elseif (!is_logged_in()): ?>
            <form>
                <textarea name="description" class="form-control mt-2 mb-2" placeholder="Write a comment"></textarea>
                <input type="hidden" name="postId" value="<?= $post['id'] ;?>">
                <a href="login.php" type="button" class="btn btn-primary">Komento</a>
            </form>
        <?php endif; ?>
            <div class="mt-2">
                <?php foreach ($comments as $comment):?>
                    <div class="card p-3 mb-2 bg-opacity-75 bg-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="user d-flex flex-row align-items-center">
                                <img src="<?= BASE_URL . ($_SESSION['user']['profile_image'] ?? '/img/missing_profile.png') ?>" width="30" height="30" class="rounded-circle me-2"><?= $_SESSION['user']['username']; ?>
                <span>
                    <small class="font-weight-bold text-primary"><?= $comment['author'] ;?></small>
                </span>
                            </div>
                            <small><?= $comment['published'] ;?></small>
                        </div>
                        <p class="mt-2"><?= $comment['description'] ;?></p>
                        <?php if ( isset($_SESSION['user']['id']) && $comment['user_id'] == $_SESSION['user']['id']): ?>
                        <form action="includes/actions/comment.inc.php" method="post">
                            <input type="hidden" name="comment" value="<?= $comment['id'] ?>">
                            <input type="hidden" name="action" value="delete_comment">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> Delete comment</button>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach;?>
            </div>
        <div class="col-lg-4 col-md-3" id="related-articles">
        </div>
    </div>
</section>
<?php
require('footer.php');
?>