<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Thông tin vận chuyển</h4>
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

                        <div class="col-sm-12"><h4 class="mt-0 header-title">Danh sách</h4></div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    <div class="form-group row">
                                        <label for="" class="col-sm-3 col-form-label text-right">Hình ảnh</label>
                                        <div class="col-sm-9 col-md-9 list_slide">

                                            {% set list_ship = object.ship|json_decode %}

                                            {% for item in list_ship %}

                                                <div class="row slide_item pt-3 border border-info mt-3 mb-3">
                                                    <div class="col-sm-2 mt-3">
                                                        <label for="" class="text-right">Icon</label>
                                                    </div>
                                                    <div class="col-sm-9 mt-3 mb-3">
                                                        <input type="text" class="form-control" value="{{ item.icon }}" name="icon[]"  placeholder="Ví dụ: credit-card">
                                                        <small>Tham khảo icon <a target="_blank" href="https://fontawesome.com/icons?from=io">tại đây</a></small>
                                                    </div>
                                                    <div class="col-sm-1 col-md-1">
                                                        <button class="btn btn-sm btn-danger waves-effect waves-light btn_delete_slide_item"><i class="fa fa-trash-o"> Xóa</i></button>
                                                    </div>
                                                    <div class="col-sm-2 mt-3">
                                                        <label for="" class="text-right">Tiêu đề</label>
                                                    </div>
                                                    <div class="col-sm-9 mt-3 mb-3">
                                                        <input type="text" class="form-control" value="{{ item.name }}" name="name[]">
                                                    </div>

                                                    <div class="col-sm-2 mt-3">
                                                        <label for="" class="text-right">Liên kết</label>
                                                    </div>
                                                    <div class="col-sm-9 mt-3 mb-3">
                                                        <input type="text" class="form-control" value="{{ item.des }}" name="des[]">
                                                    </div>
                                                </div>

                                            {% endfor %}

                                            <div class="row slide_item pt-3 border border-info mt-3 mb-3">
                                                <div class="col-sm-2 mt-3">
                                                    <label for="" class="text-right">Icon</label>
                                                </div>
                                                <div class="col-sm-9 mt-3 mb-3">
                                                    <input type="text" class="form-control" value="" name="icon[]"  placeholder="Ví dụ: credit-card">
                                                    <small>Tham khảo icon <a  target="_blank" href="https://fontawesome.com/icons?from=io">tại đây</a></small>
                                                </div>
                                                <div class="col-sm-1 col-md-1">
                                                    <button class="btn btn-sm btn-danger waves-effect waves-light btn_delete_slide_item"><i class="fa fa-trash-o"> Xóa</i></button>
                                                </div>
                                                <div class="col-sm-2 mt-3">
                                                    <label for="" class="text-right">Tiêu đề</label>
                                                </div>
                                                <div class="col-sm-9 mt-3 mb-3">
                                                    <input type="text" class="form-control" value="" name="name[]">
                                                </div>

                                                <div class="col-sm-2 mt-3">
                                                    <label for="" class="text-right">Liên kết</label>
                                                </div>
                                                <div class="col-sm-9 mt-3 mb-3">
                                                    <input type="text" class="form-control" value="" name="des[]">
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-8 offset-3 pt-3">
                                            <button type="button" class="btn btn-sm btn-info waves-effect waves-light btn_add_slide"><i class="fa fa-plus-square"> Thêm khối</i></button>
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
<script type="text/javascript">
    $(document).ready(function () {

        $('.btn_add_slide').click(function () {
            $('.list_slide').append($('#slide_item').html());
        });

        $(document).on('click', '.btn_delete_slide_item', function () {
            if ($('.slide_item').length <= 1) return false;
            $(this).closest('.slide_item').remove();
        });

    });
</script>

<script type="text/html" id="slide_item">
    <div class="row slide_item pt-3 border border-info mt-3 mb-3">
        <div class="col-sm-2 mt-3">
            <label for="" class="text-right">Icon</label>
        </div>
        <div class="col-sm-9 mt-3 mb-3">
            <input type="text" class="form-control" value="" name="icon[]" placeholder="Ví dụ: credit-card">
            <small>Tham khảo icon <a  target="_blank" href="https://fontawesome.com/icons?from=io">tại đây</a></small>
        </div>
        <div class="col-sm-1 col-md-1">
            <button class="btn btn-sm btn-danger waves-effect waves-light btn_delete_slide_item"><i class="fa fa-trash-o"> Xóa</i></button>
        </div>

        <div class="col-sm-2 mt-3">
            <label for="" class="text-right">Tiêu đề</label>
        </div>
        <div class="col-sm-9 mt-3 mb-3">
            <input type="text" class="form-control" value="" name="name[]">
        </div>

        <div class="col-sm-2 mt-3">
            <label for="" class="text-right">Liên kết</label>
        </div>
        <div class="col-sm-9 mt-3 mb-3">
            <input type="text" class="form-control" value="" name="des[]">
        </div>

    </div>
</script>