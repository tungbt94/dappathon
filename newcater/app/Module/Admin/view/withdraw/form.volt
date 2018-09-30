<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Withdraw</h4>
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

                        <div class="col-sm-12"><h4 class="mt-0 header-title">Withdraw Information</h4></div>

                        <div class="col-sm-12">
                            <div><a href="{{ url('withdraw') }}" class="btn btn-dark">Back</a></div>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    {% set project = object.Project %}

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label text-right">Project Name</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="name" type="text" placeholder="Name" class="form-control" value="{{ project.name }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label text-right">Project Address</label>
                                        <div class="col-md-9 col-sm-9">
                                            <a href="https://ropsten.etherscan.io/address/{{ project.contribute_address }}" target="_blank">{{ project.contribute_address }}</a>
                                            <input type="hidden" id="contribute_address" value="{{ project.contribute_address }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="description" class="col-sm-3 col-form-label text-right">Description</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="description" type="text" placeholder="Description" class="form-control" value="{{ project.description }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="amount" class="col-sm-3 col-form-label text-right">Withdrawal amount </label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="amount" type="text" placeholder="Amount" class="form-control" value="{{ number_format(object.amount, 2) }} ETH" disabled data-amount="{{ object.amount }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="caption" class="col-sm-3 col-form-label text-right">Caption</label>
                                        <div class="col-md-9 col-sm-9">
                                            <textarea name="" id="caption" cols="30" rows="5" class="w-100-pc" disabled>{{ object.caption }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="hash" class="col-sm-3 col-form-label text-right">Hash</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="hash" type="text" placeholder="Hash" class="form-control" value="{{ object.hash }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="vote_start_time" class="col-sm-3 col-form-label text-right">Vote Start Time</label>
                                        <div class="col-md-9 col-sm-9">
                                            <div class="">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>
                                                    <input type="text" value="{{ object.vote_start_time ? date('m/d/Y - h:i', object.vote_start_time) : '' }}" class="form-control datetimeinput" id="vote_start_time" name="vote_start_time" data-value="{{ object.vote_start_time }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="vote_end_time" class="col-sm-3 col-form-label text-right">Vote End Time</label>
                                        <div class="col-md-9 col-sm-9">
                                            <div class="">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>
                                                    <input type="text" value="{{ object.vote_end_time ? date('m/d/Y - h:i', object.vote_end_time) : '' }}" class="form-control datetimeinput" id="vote_end_time" name="vote_end_time" data-value="{{ object.vote_end_time }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="id_status" class="col-sm-3 col-form-label text-right">Status</label>
                                        <div class="col-sm-9 col-md-9">
                                            <select name="status" class="form-control" id="id_status">
                                                <option value="0" {{ object.status == '0' ? 'selected' : '' }}>Pending</option>
                                                <option value="1" {{ object.status == '1' ? 'selected' : '' }}>Approve</option>
                                                <option value="2" {{ object.status == '2' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right"></label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="hidden" class="withdraw_id" value="{{ object.id }}">
                                            <button type="submit" class="btn btn-success" id="save-withdraw"><i class="fa fa-save"></i> Save</button>
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
<link href="/admin_resource/custom/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">
<link href="/admin_resource/custom/datetimepicker/css/bootstrap-datetimepicker-standalone.css" rel="stylesheet">
<script src="/admin_resource/custom/datetimepicker/js/moment.js"></script>
<script src="/admin_resource/custom/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        $('.datetimeinput').datetimepicker({
            format: "mm/dd/yyyy - hh:ii"
        });


    });
</script>