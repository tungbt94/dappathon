<div class="content">
    <section id="submit_project" style="margin-top: 30px">
        <div class="container">
            <div class="card">
                <div class="row" style="margin-bottom: 3px">
                    <div class="col-sm-12">
                        {{ flash.output() }}
                    </div>
                </div>
                <div class="card-header">
                    <h3 class="title text-center text-uppercase  mt-15">Withdraw</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="{{ config.media.host~object.avatar }}" class="img-responsive"/>
                        </div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div><b>Project name:</b><br/> {{ object.name }}</div>
                                    <div class="mt-10"><b>Description:</b> {{ object.description }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div><b>Raised:</b><br/> {{ number_format(object.raised, 2) }} ETH</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="current_balance" data-balance="{{ object.balance }}"><b>Balance:</b><br/> {{ number_format(object.balance, 2) }} ETH</div>
                                        </div>
                                    </div>
                                    {#<div class="row mt-10">
                                        <div class="col-md-6">
                                            <div ><b>Vote Start Time:</b><br/> {{ date('d-m-Y h:i',object.contribute_start_time) }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="current_balance" ><b>End Start Time:</b><br/> {{ date('d-m-Y h:i',object.contribute_end_time) }}</div>
                                        </div>
                                    </div>#}
                                </div>
                            </div>
                        </div>
                    </div>


                    <form method="post" action="" class="mt-10" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" class="form-control" placeholder="" name="amount" required value="" id="amount">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="vote_start_time">Vote Start Time</label>
                                    <input type="text" class="form-control mydatepicker" placeholder="" name="vote_start_time" required value="" id="vote_start_time">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="vote_end_time">Vote End Time</label>
                                    <input type="text" class="form-control mydatepicker" placeholder="" name="vote_end_time" required value="" id="vote_end_time">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reason">Reason</label>
                                    <textarea type="text" rows="4" class="form-control" placeholder="" name="caption" required value="" id="caption"></textarea>
                                    <input type="hidden" id="project_id" value="{{ object.id }}">
                                    <input type="hidden" id="project_address" value="{{ object.contribute_address }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="col-form-label text-uppercase text-fader fw-500 fs-11 require"> Captcha </label>
                                    <div>
                                        <script src="https://www.google.com/recaptcha/api.js?hl=en"></script>
                                        <div class="g-recaptcha" data-sitekey="{{ config.google.recaptcha.key }}"></div>
                                        <small class="form-text text-muted">Make sure to check captcha</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="btn_submit_withdraw">Submit</button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="title text-center text-uppercase  mt-15">History Withdraw</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>TxID</th>
                            <th>Amout</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in list_data %}
                            <tr>
                                <td>{{ item.id }}</td>
                                <td> {{ item.hash }}</td>
                                <td>{{ number_format(item.amount, 2) }} ETH</td>
                                <td class="center">{{ date('m/d/Y H:i', item.datecreate) }}</td>
                                <td class="center">
                                    <span class="label label-{{ item.getStatusClass() }}">{{ item.getStatusText() }}</span>
                                </td>
                            </tr>
                        {% endfor %}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="/client_resource/custom/plugin/bootstrap-datepicker/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/client_resource/custom/plugin/bootstrap-material-datetimepicker/css/bootstrap-datetimepicker.css">
<script src="/client_resource/custom/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="/client_resource/custom/plugin/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/client_resource/custom/plugin/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="/client_resource/custom/plugin/moment/moment.js"></script>


<script type="text/javascript">
    $(document).ready(function () {

        $('.mydatepicker').datetimepicker({
            format: "mm/dd/yyyy hh:ii"
        });
    })
</script>