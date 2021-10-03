<?php
require 'header.php';

if (isset($_GET['error'])) {
    if ($_GET['error'] == 'emptyfields') {
    $error_msg = "Fill all the necessary fields.";
    } else {
    $error_msg = "Something went wrong.";
    }
}
?>

<section class="container">
    <form enctype="multipart/form-data" action="includes/actions/posts.inc.php" method="post">
        <div class="mb-3">
            <label for="title" class="form-label text-light">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Post Title">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label text-light">Image</label>
            <input type="file" class="form-control" accept="image/*" name="image" placeholder="Select an image">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label text-light">Article</label>
            <textarea class="form-control" id="content" name="content" rows="10" placeholder="Article"></textarea>
        </div>
        <input type="hidden" name="action" value="new">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= $error_msg ?></div>
        <?php endif; ?>
        <button class="btn btn-primary" type="submit" name="posts-submit">Edit Post</button>
    </form>
</section>
<?php
require('footer.php');
?>