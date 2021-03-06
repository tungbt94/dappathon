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

                <h6>Reset your password</h6>

                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}"/>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="password" required="" placeholder="Fill your new password" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="password" required="" placeholder="Re-fill your password" name="repassword">
                    </div>
                </div>

                <div class="form-group text-center row m-t-20">
                    <div class="col-12">
                        <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Accept</button>
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

