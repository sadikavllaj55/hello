<?php
require('header.php');
$countries = get_countries();
?>
<main>
    <section class="login-inner">
        <div class="login-middle">
            <div class="login-details">
                <form action="includes/actions/signup.inc.php" method="post">
                    <h2>Signup</h2>
                    <label>
                        <input type="text" class="form-control" name="username" placeholder="Username"/>
                    </label>
                    <label>
                        <input type="text" class="form-control" name="mail" placeholder="E-mail"/>
                    </label>
                    <label>
                        <input type="password" class="form-control" name="pwd" placeholder="Password"/>
                    </label>
                    <label>
                        <input type="password" class="form-control" name="pwd-repeat" placeholder="Repeat password">
                    </label>
                    <label>
                        <select id="country" name="country" class="form-control">
                        <?php foreach ($countries as $country): ?>
                        <option value="<?= $country ?>"><?= $country ?></option>
                        <?php endforeach; ?>
                        </select>
                    </label>
                    <?php
                    if (isset($_GET['error'])) {
                        $msg = '';
                        if($_GET['error'] =='emptyfields') {
                            $msg = 'Fill in all fields';
                        } else if ($_GET['error'] =='invalidmailuid') {
                            $msg = 'Invalid username and email!';
                        } else if ($_GET['error'] == 'invalidmail') {
                            $msg = 'Invalid email!';
                        } else if ($_GET['error'] == 'invaliduid') {
                            $msg = 'Invalid username';
                        } else if ($_GET['error'] == 'passwordtooshort') {
                            $msg = 'Your Password Must Contain At Least 8 Characters!';
                        } else if ($_GET['error'] == 'passwordmustincludeatleastonenumber') {
                            $msg = 'Your Password Must Contain At Least 1 Number!';
                        } else if ($_GET['error'] == 'passwordmustincludeatleastoneletter') {
                            $msg = 'Your Password Must Contain At Least 1 Capital Letter!';
                        } else if ($_GET['error'] == 'passwordcheckuid') {
                            $msg = 'Your password do not match!';
                        } else if($_GET['error'] == 'usertaken'){
                            $msg = 'An account already exist with this email or username!';
                        }

                        if (!empty($msg)) {
                            echo "<p class='alert alert-danger alert-dismissible fade show text-center'>" . $msg . "</p>";
                        }
                    }
                    ?>
                    <div class="btn-sub">
                        <button class="pink-btn" name="signup-submit">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php
require('footer.php');
?>