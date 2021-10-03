<?php
require 'header.php';
require 'includes/database/posts.php';
require 'includes/database/comments.php';
$id = $_SESSION['user']['id'];
$posts = get_user_posts($id);
$profiles = get_prof_image($id);


?>
<section class="container">
    <h2 class="text-light">My Posts</h2>
    <?php foreach ($posts as $post): ?>
    <div class="card p-3 mb-2">
        <div class="row">
            <div class="col-md-4">
                <div class="position-relative snipimage">
                    <a href="view_post.php?id=<?= $post['id']; ?>">
                        <img src="<?= BASE_URL . ($post['image'] ?? '/img/missing_image.jpeg'); ?>" class="rounded img-fluid w-100 img-responsive">
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="view_post.php?id=<?= $post['id']; ?>">
                            <h5 class="mb-1"><?= $post['title'] ?></h5>
                        </a>
                    </div>
                    <div class="d-flex justify-content-md-start justify-content-between views-content mt-2">
                        <div class="d-flex flex-row align-items-center">
                            <i class="fa fa-eye"></i> <span class="ms-1 views"><?= $post['views'] ?></span>
                        </div>
                    </div>
                    <div class="d-flex flex-row mt-3">
                        <div class="ms-2 d-flex flex-column">
                            <div class="d-flex flex-row align-items-center">
                                <h6><?= $_SESSION['user']['username'] ?></h6>
                                <span class="dots"></span>
                            </div>
                            <span class="days-ago"><?= $post['created_at'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="edit-post.php" type="button" class="btn btn-cyan btn-sm">Edit</a>
    </div>
    <?php endforeach;?>
</section>
<?php
require('footer.php');
?>