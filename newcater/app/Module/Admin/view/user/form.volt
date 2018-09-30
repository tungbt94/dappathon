<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">User</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12">
                {{ flash.output() }}
            </div>

            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">

                        <div class="col-sm-12"><h4 class="mt-0 header-title">User Information</h4></div>

                        <div class="col-sm-12">
                            <div><a href="{{ url('user') }}" class="btn btn-dark">Back</a></div>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    <div class="form-group row">
                                        <label for="username" class="col-sm-3 col-form-label text-right">Username</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="username" type="text" name="username" placeholder="Username" class="form-control" value="{{ object.username }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label text-right">Avatar</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="avatar" value="{{ object.avatar }}" data-default-file="{{ object ? object.getAvatar() : '' }}" class="dropify" data-height="220" data-allowed-file-extensions="png jpg jpeg gif"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="fullname" class="col-sm-3 col-form-label text-right">Fullname</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="fullname" type="text" name="fullname" placeholder="Họ tên" class="form-control" value="{{ object.fullname }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 col-form-label text-right">Email</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="email" type="text" name="email" placeholder="Email" class="form-control" value="{{ object.email }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label text-right">Password</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="password" type="text" name="password" placeholder="Password" class="form-control" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="role" class="col-sm-3 col-form-label text-right">Role</label>
                                        <div class="col-sm-9 col-md-9">
                                            <select name="role" class="form-control" id="role">
                                                <option {{ object.role is empty or object.role == 0 ? 'selected' : '' }} value="0">Member</option>
                                                <option {{ object.role == 1 ? 'selected' : '' }} value="1">Admin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="id_status" class="col-sm-3 col-form-label text-right">Status</label>
                                        <div class="col-sm-9 col-md-9">
                                            <select name="status" class="form-control" id="id_status">
                                                <option {{ object.status == 1 ? 'selected' : '' }} value="1">Inactive</option>
                                                <option {{ object.status is empty or object.status == 2 ? 'selected' : '' }} value="2">Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right"></label>
                                        <div class="col-md-9 col-sm-9">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div><!-- container -->

</div>

<link rel="stylesheet" href="/admin_resource/custom/plugin/dropify/css/dropify.css">
<script src="/admin_resource/custom/plugin/dropify/js/dropify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.dropify').dropify();
    });
</script>