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

                <div class="form-group m-t-10 mb-0 row">
                    <div class="col-6 m-t-20 text-center">
                        <a href="{{ url('/auth/login') }}" class="text-muted">Already have account?</a>
                    </div>

                    <div class="col-6 m-t-20 text-center">
                        <a href="{{ url('/auth/register') }}" class="text-muted">Register</a>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>