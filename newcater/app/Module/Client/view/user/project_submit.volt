<section class="profile">
    <div class="container">
        <h2>User Profile Page</h2>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <img class="img-responsive" src="http://venmond.com/demo/vendroid/img/avatar/big.jpg">
                        <h2 class="mt-15">Mariah Caraiban</h2>
                        <h4>Owner at Our Company, Inc.</h4>
                        <p>Ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                        <table class="table table-striped table-hover">
                            <tbody>
                            <tr>
                                <td style="width:60%;">Status</td>
                                <td><span class="label label-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>User Rating</td>
                                <td><i class="fa fa-star vd_yellow fa-fw"></i><i class="fa fa-star vd_yellow fa-fw"></i><i class="fa fa-star vd_yellow fa-fw"></i><i class="fa fa-star vd_yellow fa-fw"></i><i class="fa fa-star vd_yellow fa-fw"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>Member Since</td>
                                <td> Jan 07, 2014</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="tabs widget">
                            <ul class="nav nav-tabs widget">
                                <li class=""><a  href="/user"> Profile </a></li>
                                <li class="active"><a href="javascript:void (0)"> Projects Submit</a></li>
                                <li class=""><a href="/user/project_donated"> Projects Donated</a></li>
                            </ul>
                            <div class="tab-content">

                                <div id="projects-tab" class="tab-pane active">
                                    <div class="pd-20">
                                        <h3 class="mgbt-xs-15 mgtp-10 font-semibold"><i class="fa fa-bolt mgr-10 profile-icon"></i> PROJECTS</h3>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Logo / Photos</th>
                                                <th>Name</th>
                                                <th>Start Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {% for key, item in list_submited %}
                                                <tr>
                                                    <td>{{ item.id }}</td>
                                                    <td><img height="80" src="{{ item.getAvatar() }}" alt="example image"></td>
                                                    <td>{{ item.name }}</td>
                                                    <td class="center">{{ date('m/d/Y', item.datecreate) }}</td>
                                                    <td class="center">
                                                        <span class="label label-success">Finish</span>
                                                    </td>
                                                    <td class="menu-action rounded-btn">
                                                        <a class="btn menu-icon vd_bg-green" data-placement="top" data-toggle="tooltip" data-original-title="view">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a class="btn menu-icon vd_bg-yellow" data-placement="top" data-toggle="tooltip" data-original-title="edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <a class="btn menu-icon vd_bg-red" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Withdraw" href="/project/withdraw?id={{ item.id }}">
                                                            <i class="fa fa-download"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                            {% endfor %}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <!-- tab-content -->
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>