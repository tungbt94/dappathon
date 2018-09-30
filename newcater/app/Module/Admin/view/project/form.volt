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
                            <div><a href="{{ url('project') }}" class="btn btn-dark">Back</a></div>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="withdraw_form">

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label text-right">Name</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="name" type="text" name="name" placeholder="Name" class="form-control" value="{{ object.name }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label text-right">Avatar</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="file" name="avatar" value="{{ object.avatar }}" data-default-file="{{ object ? object.getAvatar() : '' }}" class="dropify" data-height="220" data-allowed-file-extensions="png jpg jpeg gif"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="description" class="col-sm-3 col-form-label text-right">Description</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="description" type="text" name="description" placeholder="Description" class="form-control" value="{{ object.description }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contact_email" class="col-sm-3 col-form-label text-right">Contact Email</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="contact_email" type="text" name="contact_email" placeholder="Contact Email" class="form-control" value="{{ object.contact_email }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contact_person" class="col-sm-3 col-form-label text-right">Contact Person</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="contact_person" type="text" name="contact_person" placeholder="Contact Person" class="form-control" value="{{ object.contact_person }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="video" class="col-sm-3 col-form-label text-right">Video</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input id="video" type="text" name="video" placeholder="Video" class="form-control" value="{{ object.video }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="category_id" class="col-sm-3 col-form-label text-right">Category</label>
                                        <div class="col-sm-9 col-md-9">
                                            <select name="category_id" class="form-control" id="category_id">
                                                {% for item in list_category %}
                                                    <option value="{{ item.id }}" {{ object.category_id == item.id ? 'selected' : '' }}>{{ item.name }}</option>
                                                {% endfor %}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="content" class="col-sm-3 col-form-label text-right">Content</label>
                                        <div class="col-md-9 col-sm-9">
                                            <textarea class="form-control form_editor" type="text" id="content" name="content">{{ object.content }}</textarea>
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
<script src='/admin_resource/custom/plugin/tinymce/jquery.tinymce.min.js'></script>
<link rel="stylesheet" href="/admin_resource/custom/plugin/dropify/css/dropify.css">
<script src="/admin_resource/custom/plugin/dropify/js/dropify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.dropify').dropify();
        loadTinyMce('form_editor');

    });
</script>