<?php
require 'header.php';
require 'includes/database/posts.php';
require 'includes/database/comments.php';

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$posts = get_posts_per_page($page, 5);
$comments = get_all_comments();
$num_pages = number_of_pages(5);
$status = $_GET['status'] ?? null;

if ($status != null) {
    $msg = '';
    if ($_GET['type'] == 'post_deleted') {
        $msg = 'The post was deleted.';
    } elseif ($_GET['type'] == 'error_post_deleted') {
        $msg = 'The post was not deleted. Something went wrong.';
    } elseif ($_GET['type'] == 'error_delete_permission') {
        $msg = 'The post can\'t be deleted. You are not the author.';
    }
}
?>
    <main class="container">
        <h2 class="text-light">Postimet</h2>
        <?php if ($status == 'success'): ?>
        <div class="alert alert-success"><?= $msg; ?></div>
        <?php endif; ?>

        <?php if ($status == 'error'): ?>
        <div class="alert alert-danger"><?= $msg; ?></div>
        <?php endif; ?>

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
                                <h5 class="mb-1">
                                    <a href="view_post.php?id=<?= $post['id']; ?>">
                                        <?= $post['title']; ?>
                                    </a>
                                </h5>
                                <div class="d-flex justify-content-md-start justify-content-between views-content mt-2">
                                    <div class="d-flex flex-row align-items-center">
                                        <i class="fa fa-eye"></i> <span class="ms-1 views"><?= $post['views']; ?></span>
                                    </div>
                                </div>
                                <div class="d-flex flex-row mt-3">
                                    <div class="ms-2 d-flex flex-column">
                                        <div class="d-flex flex-row align-items-center">
                                            <h6><?= $post['author']; ?></h6>
                                            <span class="dots"></span>
                                        </div>
                                        <span class="days-ago"><?= $post['created_at']; ?></span>
                                    </div>
                                    <button class="btn btn-danger ms-2 delete-post-btn"><i class="fa fa-trash-o"></i></button>
                                    <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <ul class="pagination">

            <li class="page-item<?php if (1 == $page): ?> disabled<?php endif; ?>">
                <a class="page-link"<?php if (1 !== $page): ?> href="/new/index.php?page=<?= $page - 1; ?>"<?php endif; ?>>Previous</a>
            </li>
            <?php for ($i = 1; $i <= $num_pages; $i++): ?>
                <?php if ($i == $page): ?>
                    <li class="page-item active"><a class="page-link"><?= $i ?></a></li>
                <?php else: ?>
                    <li class="page-item "><a class="page-link" href="/new/index.php?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>
            <li class="page-item<?php if ($num_pages == $page): ?> disabled<?php endif; ?>">
                <a class="page-link"<?php if ($num_pages !== $page): ?> href="/new/index.php?page=<?= $page + 1; ?>"<?php endif; ?>>Next</a>
            </li>
        </ul>
    </main>
<?php if (is_logged_in()): ?>
    <div class="modal" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePostModalLabel">Comfirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this post?</p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="includes/actions/posts.inc.php">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="post_id" id="deletePostId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete it</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var deleteModal = new bootstrap.Modal(document.getElementById('deletePostModal'), {});
        $('.delete-post-btn').on('click', function () {
            var post_id = $(this).next().val();
            document.getElementById('deletePostId').value = post_id;
            deleteModal.show();
        });
    </script>
<?php endif; ?>
<?php
require('footer.php');
?>