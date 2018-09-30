<div class="card">
    <div class="card-body">

        <h3 class="text-center mt-0 m-b-15">
            <a href="/" class="logo logo-admin"><i class="mdi mdi-assistant"></i> CMS System</a>
        </h3>

        <div class="p-3">
            {{ flash.output() }}
        </div>


        <div class="p-3">
            <form class="form-horizontal m-t-20" action="" method="post">

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="text" required="" placeholder="Username or Email" name="username">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <input class="form-control" type="password" required="" placeholder="Password" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <div class="g-recaptcha pull-left" data-sitekey="{{ config.google.recaptcha.key }}"></div>
                    </div>
                </div>

                <div class="form-group text-center row m-t-20">
                    <div class="col-12">
                        <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Log In</button>
                    </div>
                </div>


            </form>
        </div>

    </div>
</div>