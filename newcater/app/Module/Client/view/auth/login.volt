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
                <form action="" method="post">

                    {{ flash.output() }}

                    <div class="form-group">

                        <input type="text" class="form-control" placeholder="Username or Email" name="username" required>
                    </div>
                    <div class="form-group">

                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>


                    <div class="form-group clearfix">
                        <div class="g-recaptcha pull-left" data-sitekey="6LcoHj8UAAAAANmVEpHDUdmRc5vlzXlwf4i4yH-M"></div>
                        <div class="checkbox" style="    display: inline-block; width: 176px; text-align: right;margin-top: 10px">
                            <label class="w-100">
                                <input type="checkbox"> Remember Me
                            </label>
                            <label class="w-100">
                                <a href="/auth/forget_password">Forgotten Password?</a>
                            </label>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30" style="background: #b74d9d">Sign in</button>

                    <div class="register-link m-t-15 text-center">
                        <p>Don't have account ? <a href="/auth/register"> Sign Up Here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>