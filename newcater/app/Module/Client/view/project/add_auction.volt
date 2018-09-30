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
                    <h3 class="title text-center text-uppercase  mt-15">Submit Auction</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" placeholder="" name="name" required value="{{ data_post['name'] }}" id="name">
                                </div>
                                <div class="form-group">
                                    <label for="contact_email">Contact email</label>
                                    <input type="text" class="form-control" placeholder="" name="contact_email" required value="{{ data_post['contact_email'] }}" id="contact_email">
                                    <small class="form-text text-muted">We'll use this e-mail to contact you if some information needs clarification and inform you about your Project status.</small>
                                </div>
                                <div class="form-group">
                                    <label for="contact_person">Contact person</label>
                                    <input type="text" class="form-control" placeholder="" name="contact_person" required value="{{ data_post['contact_person'] }}" id="contact_person">
                                    <small class="form-text text-muted">We'll use this name to contact you if some information needs clarification and inform you about your Project status.</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Avatar</label>
                                    <input type="file" class="dropify" name="avatar" data-allowed-file-extensions="png jpg jpeg"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" placeholder="" name="description" required value="{{ data_post['description'] }}" id="description">

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">

                                    <label for="video">Link video</label>
                                    <input type="text" class="form-control" placeholder="" name="video" value="{{ data_post['video'] }}" id="video">

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contribute_start_time">Contribute Start Time</label>
                                    <input class="form-control mydatepicker" type="text" name="contribute_start_time" value="{{ data_post['contribute_start_time'] }}" id="contribute_start_time">

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contribute_end_time">Contribute End Time</label>
                                    <input class="form-control mydatepicker" type="text" name="contribute_end_time" value="{{ data_post['contribute_end_time'] }}" id="contribute_end_time">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">

                                    <label for="min_value">Min Price (ETH)</label>
                                    <input type="text" class="form-control" placeholder="" name="min_value" value="{{ data_post['min_value'] }}" id="min_value">

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">

                                    <label for="step_value">Step Price (ETH)</label>
                                    <input type="text" class="form-control" placeholder="" name="step_value" value="{{ data_post['step_value'] }}" id="step_value">

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea class="form-control form_editor" rows="10" placeholder="" name="content">{{ data_post['content'] }}</textarea>
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
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="/client_resource/assets/css/dropify.css">
<link rel="stylesheet" href="/client_resource/custom/plugin/bootstrap-datepicker/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="/client_resource/custom/plugin/bootstrap-material-datetimepicker/css/bootstrap-datetimepicker.css">
<script src="/client_resource/assets/js/dropify.min.js"></script>
<script src='/client_resource/custom/plugin/tinymce/jquery.tinymce.min.js'></script>
<!-- Date Picker Plugin JavaScript -->
<script src="/client_resource/custom/plugin/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="/client_resource/custom/plugin/bootstrap-material-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/client_resource/custom/plugin/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="/client_resource/custom/plugin/moment/moment.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        // Date Picker
        $('.mydatepicker').datetimepicker({
            format: "mm/dd/yyyy - hh:ii"
        });
        $('.dropify').dropify();
        loadTinyMce('form_editor');
    });
</script>