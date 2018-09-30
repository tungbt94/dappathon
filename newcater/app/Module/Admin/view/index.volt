<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{ header['title'] }}</title>
    <meta content="{{ header['description'] }}" name="description"/>
    <meta content="Mannatthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <link rel="shortcut icon" href="/admin_resource/assets/images/favicon.ico">

    <!-- jvectormap -->
    <link href="/admin_resource/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

    <link href="/admin_resource/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/admin_resource/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="/admin_resource/assets/css/style.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <link href="/admin_resource/custom/css/custom.css?v={{ time() }}" rel="stylesheet" type="text/css">
    <script src="/admin_resource/assets/js/jquery.min.js"></script>
    <script src="/admin_resource/custom/jquery-ui/jquery-ui.js"></script>
	<script src="/client_resource/assets/js/web3.min.js"></script>
	<script src="/client_resource/assets/js/truffle-contract.js?v={{ time() }}"></script>
	<script src="/admin_resource/custom/js/contracts.js?v={{ time() }}"></script>
</head>


<body class="fixed-left">

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner"></div>
    </div>
</div>

<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="ion-close"></i>
        </button>

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="{{ url('dashboard') }}" class="logo"><i class="mdi mdi-assistant"></i> CATERFUND</a>
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">

            <div id="sidebar-menu">
                <ul>
                    {% for item in side_bar %}
                        {% if in_array(user_info.role, item['role']) %}
                            <li class="{{ active_menu == item['active_key'] ? 'active' : '' }} {{ item['child']|length >0 ? "has_sub" : '' }}">
                                <a target="{{ item['target'] }}" href="{{ item['link'] }}" class="waves-effect">
                                    <i class="{{ item['icon'] }}"></i><span>{{ item['name'] }}</span>
                                    {% if item['child']|length > 0 %}
                                        <span class="float-right"><i class="mdi mdi-chevron-right"></i></span>
                                    {% endif %}
                                </a>
                                {% if item['child']|length>0 %}
                                    <ul class="list-unstyled" style="display: none;">
                                        {% for citem in item['child'] %}
                                            {% if in_array(user_info.role,citem['role']) %}
                                                <li><a target="{{ citem['target'] }}" href="{{ citem['link'] }}" class="{{ active_menu == citem['active_key'] ? 'active' : '' }}">{{ citem['name'] }}</a></li>
                                            {% endif %}
                                        {% endfor %}
                                    </ul>
                                {% endif %}
                            </li>
                        {% endif %}
                    {% endfor %}

                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <!-- Top Bar Start -->
            <div class="topbar">

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">
                        <!-- language-->

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="/admin_resource/custom/images/avatar-blank.jpg" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5>Welcome</h5>
                                </div>
                                <a class="dropdown-item" href="{{ url('auth/logout') }}"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                            </div>
                        </li>

                    </ul>
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                    </ul>

                    <div class="clearfix"></div>

                </nav>

            </div>
            <!-- Top Bar End -->

            {{ content() }}


        </div> <!-- content -->

        <footer class="footer">
            © 2018 Copyright by Dev.
        </footer>

    </div>
    <!-- End Right content here -->

</div>
<!-- END wrapper -->


<!-- jQuery  -->

<script src="/admin_resource/assets/js/popper.min.js"></script>
<script src="/admin_resource/assets/js/bootstrap.min.js"></script>
<script src="/admin_resource/assets/js/modernizr.min.js"></script>
<script src="/admin_resource/assets/js/detect.js"></script>
<script src="/admin_resource/assets/js/fastclick.js"></script>
<script src="/admin_resource/assets/js/jquery.slimscroll.js"></script>
<script src="/admin_resource/assets/js/jquery.blockUI.js"></script>
<script src="/admin_resource/assets/js/waves.js"></script>
<script src="/admin_resource/assets/js/jquery.nicescroll.js"></script>
<script src="/admin_resource/assets/js/jquery.scrollTo.min.js"></script>

<!--Morris Chart-->
<script src="/admin_resource/assets/plugins/morris/morris.min.js"></script>
<script src="/admin_resource/assets/plugins/raphael/raphael-min.js"></script>
<script src="/admin_resource/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

<script src="/admin_resource/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="/admin_resource/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<link rel="stylesheet" href="/admin_resource/custom/plugin/jquery-toast-plugin/jquery.toast.min.css">
<script src="/admin_resource/custom/plugin/jquery-toast-plugin/jquery.toast.min.js"></script>

<link rel="stylesheet" href="/admin_resource/custom/select2/css/select2.css">
<script src="/admin_resource/custom/select2/js/select2.full.js"></script>

<!-- App js -->
<script src="/admin_resource/assets/js/app.js"></script>
<script src="/admin_resource/custom/js/custom.js?v={{ time() }}"></script>
<script>

    $(document).ready(function () {
        $("#boxscroll").niceScroll({cursorborder: "", cursorcolor: "#cecece", boxzoom: true});
        $("#boxscroll2").niceScroll({cursorborder: "", cursorcolor: "#cecece", boxzoom: true});
    });

</script>


</body>
</html>