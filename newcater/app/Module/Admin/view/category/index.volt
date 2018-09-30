<div class="page-content-wrapper ">

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Category</h4>
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
                    <div class="card-header">
                        <h5>Categories List</h5>
                    </div>
                    <div class="card-body">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <form action="" class="search_form" method="get">
                                            <div class="form-group row">

                                                <div class="col-sm-4 pl-0">
                                                    <a class="btn btn-info btn-sm waves-effect waves-light" href="{{ url('category/form') }}">Add</a>
                                                </div>

                                                <div class="col-sm-2 offset-2">
                                                    <label for="id_status">Status</label>
                                                    <select name="status" id="id_status" class="form-control">
                                                        <option {{ data_get['status'] == '' ? 'selected' : '' }} value="">All</option>
                                                        <option {{ data_get['status'] == '1' ? 'selected' : '' }} value="1">Hide</option>
                                                        <option {{ data_get['status'] == '2' ? 'selected' : '' }} value="2">Show</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-4 pr-0">

                                                    <label for="search">Search</label>

                                                    <div class="input-group">
                                                        <input placeholder="Keyword" id="search" type="text" class="form-control" name="q" value="{{ data_get['q'] }}">
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-rep-plugin">
                                    <div class="table-wrapper">
                                        <div class="table-responsive b-0">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr class="table-success">
                                                    <th>#ID</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Usercreate</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {% for item in list_data %}
                                                    <tr>
                                                        <td> {{ item.id }} </td>
                                                        <td>
                                                            <img src="{{ item.getAvatar() }}" alt="{{ item.name }}" width="120px" height="120px">
                                                            {{ item.name }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-{{ item.getStatusClass() }}">{{ item.getStatusText() }}</button>
                                                        </td>
                                                        <td>
                                                            <div><b>User Created:</b> {{ item.User.username }}</div>
                                                            <div><b>Date Created:</b> {{ date('d/m/Y H:i', item.datecreate) }}</div>

                                                        </td>
                                                        <td>
                                                            <div>
                                                                <a class="form-group btn btn-info btn-xs" href="{{ url('category/form?id=' ~ item.id) }}" title="Cập nhật">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                                <a class="form-group btn btn-danger btn-xs delete-item" href="{{ url('category/delete?id=' ~ item.id) }}" title="Xóa">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                {% endfor %}
                                                </tbody>
                                            </table>
                                        </div>

                                        {% include 'layouts/paging.volt' %}
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div><!-- container -->

</div>

