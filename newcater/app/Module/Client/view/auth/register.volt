<div class="sufee-login d-flex align-content-center flex-wrap">

    <div class="container">
        <div class="login-content">

            <div class="login-form">
                <div class="login-logo">
                    <a href="/">
                        <img class="align-content" src="/client_resource/assets/img/nct-brand.png" alt="">
                    </a>
                </div>
                <div class="form-group">
                </div>

                <form method="post" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="username" value="" required="">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password" required="">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Repeat Password" name="repassword" required="">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" required> Agree the terms and policy
                        </label>
                    </div>
                    <div class="form-group">
                        <script src="https://www.google.com/recaptcha/api.js?hl=en"></script>
                        <div class="g-recaptcha" data-sitekey="{{ config.google.recaptcha.key }}"></div>
                        <small class="form-text text-muted">Make sure to check captcha</small>
                    </div>


                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" style="background: #b74d9d">Register</button>
                    <div class="register-link m-t-15 text-center">
                        <p>Already have account ? <a href="/auth/login"> login in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>