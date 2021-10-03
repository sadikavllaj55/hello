<?php
require 'header.php';
$countries = get_countries();
?>
<main>
    <form enctype="multipart/form-data" action="includes/actions/edit.inc.php" method="post" class="col-md-4 mx-auto">
        <div class="col-md-12 mb-2">
            <label for="Username" class="form-label text-light ">Username</label>
            <input value="<?= $_SESSION['user']['username'] ?>" type="text" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="col-md-12 mb-2">
            <label for="mail" class="form-label text-light">E-mail</label>
            <input value="<?= $_SESSION['user']['email'] ?>" type="text" name="mail" class="form-control" placeholder="E-mail">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label text-light">Profile Image</label><br>
            <img class="img-fluid mx-auto mb-2" style="width: 50%" src="<?= BASE_URL . ($_SESSION['user']['profile_image'] ?? '/img/missing_profile.png') ?>">
            <input type="file" class="form-control" accept="image/*" name="profile_image" placeholder="Select an image">
        </div>
        <div class="col-md-12 mb-2">
            <label for="country" class="form-label text-light">Country</label>
            <select id="country" name="country" id="country" class="form-control">
            <?php foreach ($countries as $country): ?>
                <option value="<?= $country ?>" <?php if ($_SESSION['user']['country'] == $country): ?>selected<?php endif; ?>><?= $country ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <?php if(isset($_GET['login']) && $_GET['login'] =='success'): ?>
            <p class="alert alert-dark mb-2">Successfully Updated</p>
        <?php endif; ?>
        <div style="">
            <button type="submit" class="btn btn-primary active" name="edit">Edit</button>
        </div>
    </form>
</main>

<?php
require('footer.php');
?>
