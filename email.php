<?php
require('header.php');
?>
    <main>
        <section class="login-inner">
            <div class="login-middle">
                <div class="login-details">
                    <form action="includes/actions/contact.php" method="POST">
                        <h2>Email</h2>
                        <label>
                            <input type="text" class="form-control" name="mail" placeholder="E-mail"/>
                        </label>
                        <label>
                            <input type="text" class="form-control" name="subject" placeholder="subject"/>
                        </label>
                        <label>
                            <input type="text" class="form-control" name="message" placeholder="message">
                        </label>
                        <div class="btn-sub">
                            <button type="submit" class="pink-btn" name="email">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php
require('footer.php');
?>