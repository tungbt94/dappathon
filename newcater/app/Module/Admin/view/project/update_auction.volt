<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Project</h4>
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

                        <div class="col-sm-12"><h4 class="mt-0 header-title">Project Information</h4></div>

                        <div class="col-sm-12">
                            <div><a href="{{ url('project/auction') }}" class="btn btn-dark">Back</a></div>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    <input type="hidden" value="{{ object.id }}" id="project_id">

                                    <div class="form-group row">
                                        <label for="contribute_start_time" class="col-sm-3 col-form-label text-right">Contribute Start</label>
                                        <div class="col-md-9 col-sm-9">
                                            <div class="">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>
                                                    <input type="text" value="{{ object.contribute_start_time ? date('m/d/Y - h:i', object.contribute_start_time) : '' }}" class="form-control" id="contribute_start_time" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contribute_end_time" class="col-sm-3 col-form-label text-right">Contribute End</label>
                                        <div class="col-md-9 col-sm-9">
                                            <div class="">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>
                                                    <input type="text" value="{{ object.contribute_end_time ? date('m/d/Y - h:i', object.contribute_end_time) : '' }}" class="form-control" id="contribute_end_time" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="project_link" class="col-sm-3 col-form-label text-right">Project Url</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.getLink() }}" class="form-control" readonly id="project_link">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contribute_address" class="col-sm-3 col-form-label text-right">Contract Address</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.contribute_address }}" class="form-control" readonly id="contribute_address">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contribute_address" class="col-sm-3 col-form-label text-right">Transaction Hash</label>
                                        <div class="col-md-9 col-sm-9">
                                            <a target="_blank" href="https://ropsten.etherscan.io/tx/{{ object.hash }}">{{ object.hash }}</a>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="funding_receipt" class="col-sm-3 col-form-label text-right">Funding Receipt</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.funding_receipt }}" class="form-control" readonly id="funding_receipt">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="min_value" class="col-sm-3 col-form-label text-right">Min Price</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.min_value }}" class="form-control" readonly id="min_value">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="step_value" class="col-sm-3 col-form-label text-right">Step Price</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.step_value }}" class="form-control" readonly id="step_value">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="project_status" class="col-sm-3 col-form-label text-right">Status</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" value="{{ object.getStatusText() }}" class="form-control" readonly id="project_status" data-status={{ object.status }}>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="status_display" class="col-sm-3 col-form-label text-right">Status Display</label>
                                        <div class="col-sm-9 col-md-9">
                                            <select name="status_display" class="form-control" id="status_display">
                                                <option {{ object.status_display == 1 ? 'selected' : '' }} value="1">Hide</option>
                                                <option {{ object.status_display is empty or object.status_display == 2 ? 'selected' : '' }} value="2">Show</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right"></label>
                                        <input type="hidden" id="funding_start_time" value="{{ object.contribute_start_time }}"/>
                                        <input type="hidden" id="funding_end_time" value="{{ object.contribute_end_time }}"/>

                                        <div class="col-md-9 col-sm-9">
                                            <button type="submit" id="save-project-auction" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>

</div>