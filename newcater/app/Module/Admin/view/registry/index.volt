<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Cấu hình chung</h4>
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

                        <div class="col-sm-12"><h4 class="mt-0 header-title">Thông tin cấu hình chung</h4></div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    <div class="form-group row">
                                        <label for="slogan" class="col-sm-3 col-form-label text-right">Slogan</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="slogan" type="text" name="slogan" placeholder="Slogan" class="form-control" value="{{ object.slogan }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="logo" class="col-sm-3 col-form-label text-right">Logo</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="logo" value="{{ object.logo }}" data-default-file="{{ config.media.host ~ object.logo }}" class="dropify" data-height="220" data-allowed-file-extensions="png jpg jpeg gif"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="logo" class="col-sm-3 col-form-label text-right">Favicon</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="favicon" value="{{ object.favicon }}" data-default-file="{{ config.media.host ~ object.favicon }}" class="dropify" data-height="220" data-allowed-file-extensions="png jpg jpeg gif"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="logo" class="col-sm-3 col-form-label text-right">Ảnh Khuyến Mại</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="sale_img" value="{{ object.sale_img }}" data-default-file="{{ config.media.host ~ object.sale_img }}" class="dropify" data-height="220" data-allowed-file-extensions="png jpg jpeg gif"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="map" class="col-sm-3 col-form-label text-right">Bản đồ</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="map" type="text" name="map" placeholder="Bản đồ" class="form-control" value='{{ object.map|json_decode }}'>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="link_facebook" class="col-sm-3 col-form-label text-right">Link facebook</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="link_facebook" type="text" name="link_facebook" placeholder="Link facebook" class="form-control" value="{{ object.link_facebook }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="link_google" class="col-sm-3 col-form-label text-right">Link google</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="link_google" type="text" name="link_google" placeholder="Link google" class="form-control" value="{{ object.link_google }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="link_twitter" class="col-sm-3 col-form-label text-right">Link twitter</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="link_twitter" type="text" name="link_twitter" placeholder="Link twitter" class="form-control" value="{{ object.link_twitter }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="seo_title" class="col-sm-3 col-form-label text-right">Tiêu đề trang chủ</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="seo_title" type="text" name="seo_title" placeholder="Tiêu đề trang chủ" class="form-control" value="{{ object.seo_title }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="seo_description" class="col-sm-3 col-form-label text-right">Mô tả trang chủ</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="seo_description" type="text" name="seo_description" placeholder="Mô tả trang chủ" class="form-control" value="{{ object.seo_description }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="seo_keyword" class="col-sm-3 col-form-label text-right">Từ khóa trang chủ</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="seo_keyword" type="text" name="seo_keyword" placeholder="Từ khóa trang chủ" class="form-control" value="{{ object.seo_keyword }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="hot_line" class="col-sm-3 col-form-label text-right">Hot line</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="hot_line" type="text" name="hot_line" placeholder="Hot line" class="form-control" value="{{ object.hot_line }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="content" class="col-sm-3 col-form-label text-right">Địa chỉ</label>
                                        <div class="col-md-9 col-sm-9">
                                            <textarea class="form-control form_editor" type="text" id="address" name="address">{{ object.address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="script_head" class="col-sm-3 col-form-label text-right">Chèn code vào head</label>
                                        <div class="col-md-9 col-sm-9">
                                            <textarea class="form-control" type="text" rows="5" id="script_head" name="script_head">{{ object.script_head }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="script_body" class="col-sm-3 col-form-label text-right">Chèn code vào body</label>
                                        <div class="col-md-9 col-sm-9">
                                            <textarea class="form-control" type="text" rows="5" id="script_body" name="script_body">{{ object.script_body }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right"></label>
                                        <div class="col-md-9 col-sm-9">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Lưu</button>
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
<script src='/admin_resource/custom/plugin/tinymce/jquery.tinymce.min.js'></script>
<link rel="stylesheet" href="/admin_resource/custom/plugin/dropify/css/dropify.css">
<script src="/admin_resource/custom/plugin/dropify/js/dropify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.dropify').dropify();
        loadTinyMce('form_editor');
    });
</script>