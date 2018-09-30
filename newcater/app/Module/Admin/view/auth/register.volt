<div class="card">
    <div class="card-body">

        <h3 class="text-center mt-0 m-b-15">
            <a href="/" class="logo logo-admin"><img src="/admin_resource/assets/images/logo.png" height="24" alt="logo"></a>
        </h3>

        <div class="p-3">
            {{ flash.output() }}
        </div>

        <div class="p-3">
            <form class="form-horizontal" action="" method="post">

                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}"/>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="email" required="" placeholder="Email" value="{{ data['email'] }}" name="email">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="text" required="" placeholder="Username" value="{{ data['username'] }}" name="username">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="password" required="" placeholder="Password" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="password" required="" placeholder="Repeat Password" name="repassword">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" required>
                            <label class="custom-control-label font-weight-normal" for="customCheck1">I accept <a href="javascript:" class="text-muted">Terms and Conditions</a></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <div class="g-recaptcha" data-sitekey="{{ config.google.recaptcha.key }}"></div>
                    </div>

                </div>

                <div class="form-group text-center row m-t-20">
                    <div class="col-12">
                        <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Register</button>
                    </div>
                </div>


                <div class="form-group m-t-10 mb-0 row">
                    <div class="col-12 m-t-20 text-center">
                        <a href="{{ url('/auth/login') }}" class="text-muted">Already have account?</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>