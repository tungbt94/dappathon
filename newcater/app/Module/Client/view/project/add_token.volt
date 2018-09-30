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
                    <h3 class="title text-center text-uppercase  mt-15">Submit Project</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data" id="token_form">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="token_name">Token Name</label>
                                    <input type="text" class="form-control" placeholder="" name="token_name" required value="{{ data_post['token_name'] }}" id="token_name">
                                </div>
                                <div class="form-group">
                                    <label for="symbol">Token Symbol</label>
                                    <input type="text" class="form-control" placeholder="" name="symbol" required value="{{ data_post['symbol'] }}" id="symbol">
                                </div>
                                <div class="form-group">
                                    <label for="total_supply">Total Supply</label>
                                    <input type="text" class="form-control" placeholder="" name="total_supply" required value="{{ data_post['total_supply'] }}" id="total_supply">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">File</label>
                                    <input type="file" class="dropify" name="avatar" data-allowed-file-extensions="png jpg jpeg mp3" id="file_upload"/>
                                    <input type="hidden" class="file_url" data-url="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" placeholder="" name="name" required value="{{ data_post['name'] }}" id="name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_email">Contact email</label>
                                    <input type="text" class="form-control" placeholder="" name="contact_email" required value="{{ data_post['contact_email'] }}" id="contact_email">
                                    {#<small class="form-text text-muted">We'll use this e-mail to contact you if some information needs clarification and inform you about your Project status.</small>#}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_person">Contact person</label>
                                    <input type="text" class="form-control" placeholder="" name="contact_person" required value="{{ data_post['contact_person'] }}" id="contact_person">
                                    {#<small class="form-text text-muted">We'll use this name to contact you if some information needs clarification and inform you about your Project status.</small>#}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" placeholder="" name="description" required value="{{ data_post['description'] }}" id="description">

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



                        {#<div class="row">
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
                        </div>#}

                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="create_token">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="/client_resource/assets/css/dropify.css">

<script src="/client_resource/assets/js/dropify.min.js"></script>
<script src='/client_resource/custom/plugin/tinymce/jquery.tinymce.min.js'></script>


<script type="text/javascript">
    $(document).ready(function () {
        $('.dropify').dropify();

        loadTinyMce('form_editor');

        $('#file_upload').on('change', function () {
            let obj = $(this);
            let file_data = obj.prop('files')[0];
            let type = file_data.type;
            let match = ["image/gif", "image/png", "image/jpg"];
            if (type == match[0] || type == match[1] || type == match[2]) {
                let form_data = new FormData();
                form_data.append('avatar', file_data);
                $.ajax({
                    url: '/project/upload',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (res) {
                        res = JSON.parse(res);
                        $('.file_url').val(res.data.url);
                    }
                });
            } else {
                obj.val('');
            }
            return false;
        });
    });
</script>