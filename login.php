<?php
require('header.php');
?>
<main>
    <section class="login-inner">
        <div class="login-middle">
            <div class="login-details">
                <form action="includes/actions/login.inc.php" method="post">
                    <h2>Log In</h2>
                    <label>
                        <input class="form-control" type="text" name="mailuid" placeholder="Email address or Username"/>
                    </label>
                    <label>
                        <input class="form-control" type="password" name="pwd" placeholder="Password"/>
                    </label>
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'emptyfields') {
                            echo '<p class="alert alert-danger text-center">Fill in all fields</p>';
                        } elseif ($_GET['error'] == 'wrongpwd') {
                            echo '<p class="alert alert-danger text-center">Wrong password</p>';
                        } elseif ($_GET['error'] == 'noresultsfromdb') {
                            echo '<p class="alert alert-danger text-center">Invalid mail or username</p>';
                        } elseif ($_GET['error'] == 'new_comment') {
                            echo '<p class="alert alert-danger text-center">Nuk shton dot koment pa pasur llogari!</p>';
                        }
                    }
                    ?>
                    <div class="btn-sub">
                        <button class="pink-btn" name="login-submit">Log In</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php
require('footer.php');
?>