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
                    <div class="card-header">
                        <h5>Withdrawals List</h5>
                    </div>
                    <div class="card-body">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="p-3" id="home" role="tabpanel">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <form action="" class="search_form" method="get">
                                            <div class="form-group row">

                                                <div class="col-sm-4 pl-0"></div>

                                                <div class="col-sm-2 offset-2">
                                                    <label for="id_status">Status</label>
                                                    <select name="status" id="id_status" class="form-control">
                                                        <option {{ data_get['status'] == '' ? 'selected' : '' }} value="">All</option>
                                                        <option {{ data_get['status'] == '0' ? 'selected' : '' }} value="">Pending</option>
                                                        <option {{ data_get['status'] == '1' ? 'selected' : '' }} value="1">Approve</option>
                                                        <option {{ data_get['status'] == '2' ? 'selected' : '' }} value="2">Closed</option>
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
                                                    <th>Project Info</th>
                                                    <th>User Raised</th>
                                                    <th>Caption</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {% for item in list_data %}
                                                    {% set project = item.Project %}
                                                    {% set user = item.User %}
                                                    <tr>
                                                        <td> {{ item.id }} </td>
                                                        <td>
                                                            <div><b>Project Name:</b> {{ project.name }}</div>
                                                            <div><b>Current Balance:</b> {{ number_format(project.balance, 2) }} ETH</div>
                                                            <div><b>Raised:</b> {{ number_format(project.raised, 2) }} ETH</div>
                                                        </td>
                                                        <td>
                                                            <div><b>Address: </b><a href="https://ropsten.etherscan.io/address/{{ user.eth_address }}" target="_blank">{{ user.eth_address }}</a></div>
                                                        </td>
                                                        <td>
                                                            {{ item.caption }}
                                                        </td>
                                                        <td>
                                                            {{ number_format(item.amount, 2) }} ETH
                                                        </td>


                                                        <td>
                                                            <button class="btn btn-{{ item.getStatusClass() }}">{{ item.getStatusText() }}</button>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <a class="form-group btn btn-info btn-xs" href="{{ url('withdraw/form?id=' ~ item.id) }}" title="Edit">
                                                                    <i class="fa fa-pencil-square-o"></i>
                                                                </a>
                                                                <a class="form-group btn btn-danger btn-xs delete-item" href="{{ url('withdraw/delete?id=' ~ item.id) }}" title="Delete">
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

