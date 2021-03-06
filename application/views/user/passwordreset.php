<div class="row">
    <div class="col-md-8 col-sm-6">
        <div class="card">
            <div class="card-body align-self-center">
                <img src="<?= base_url('assets/images/security.png') ?>" style="max-width:100%;max-height:100%;" alt="">
                <?php $text = ($user && $validated) ? 'Secure your account by creating a strong password' : '?' ?>
                <h6 class="text-center"><?= $text ?></h6>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="card">
            <div class="card-body">
                <?php if ($user && $validated) : ?>
                    <h4 class="text-pink">Reset Password</h4>
                    <?php $hidden = array('user_id' => $userid, 'securitycode' => $securitycode) ?>
                    <?= form_open('resetpassword', '', $hidden) ?>
                    <div class="form-group">
                        <label for="password1">Create new password</label>
                        <input type="password" name="password1" id="password1" class="form-control">
                        <div id="passwordmessage1" class=""></div>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirm password</label>
                        <input type="password" name="password2" id="password2" class="form-control">
                        <div id="passwordmessage2" class=""></div>
                    </div>
                    <button id="submitpassword" class="btn btn-dark" disabled>Submit</button>
                    <?= form_close() ?>
                <?php else : ?>
                    <?php if (!$user) : ?>
                        <p>Sorry, we cannot find user with ID: <?= $userid ?></p>
                    <?php endif ?>
                    <?php if (!$validated) : ?>
                        <p>The security code that you entered is not correct. Please check and try again.</p>
                    <?php endif ?>
                    <p>Error: <?= $errormessage ?></p>
                    <a class="btn btn-dark" href="<?= site_url('login') ?>">Back to Sign In</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<script>
    var input1 = document.getElementById("password1");
    var input2 = document.getElementById("password2");
    var message1 = document.getElementById("passwordmessage1");
    var message2 = document.getElementById("passwordmessage2");
    var submitbtn = document.getElementById("submitpassword");

    function checkPasswordMatch() {
        var password = $("#password1").val();
        var confirmPassword = $("#password2").val();
        if (password != confirmPassword) {
            input2.className = "form-control is-invalid";
            message2.className = "invalid-feedback";
            message2.innerHTML = "Password mismatch!";
            submitbtn.disabled = true;
        } else {
            if (password != '' || confirmPassword != '') {
                input2.className = "form-control is-valid";
                message2.className = "valid-feedback";
                message2.innerHTML = "Password match!";
                submitbtn.disabled = false;
            }
        }
    }

    function checkEmpty() {
        var password = $("#password1").val();
        var confirmPassword = $("#password2").val();
        if (password == '') {
            input1.className = "form-control is-invalid";
            message1.className = "invalid-feedback";
            message1.innerHTML = "Password cannot be empty!";
        } else {
            input1.className = "form-control is-valid";
            message1.className = "valid-feedback";
            message1.innerHTML = "";
        }
    }
    $(document).ready(function() {
        $("#password1").keyup(checkEmpty);
        $("#password1, #password2").keyup(checkPasswordMatch);
        // $('#submitpassword').
    });
</script>