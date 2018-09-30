<form id="headerSearchBox" class="form-inline my-md-0 search-area" method="get" action="https://acumen.org" role="search">
    <div class="inner">
        <div class="container">
            <div class="searchBox" id="searchBox">
                <input class="form-control" type="search" name="s" title="Search for:" aria-label="Search">
                <button class="icon-btn" type="submit" role="button">
                    <img src="/client_resource/assets/img/iconsearch.png"/>
                </button>
            </div>

            <a class="search-close-btn" href="javascript:void(0)">
                <svg width="23px" height="21px" viewBox="0 0 23 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="Acumen-Website-v2-Final" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="square">
                        <g id="Navigation-1---Burger-Tray-B" transform="translate(-901.000000, -40.000000)" stroke="#FFFFFF">
                            <g id="X" transform="translate(902.000000, 40.000000)">
                                <path d="M0,0 L20.9632932,20.9632932" id="Line"></path>
                                <path d="M0,20.9632932 L20.9632932,0" id="Line-Copy-2"></path>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </div>
    </div>
</form>
<header>
    <div class="container">
        <div class="d-flex">
            <a class="logo" href="https://caterfund.com/"><img src="/client_resource/assets/img/nct-brand.png?v={{ time }}" style="width: 350px !important; height: 66px !important;"></a>
            <div class="collapse navbar-collapse w-100" id="navbarsExample07">
                <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
                    <ul id="menu-main-menu" class="nav navbar-nav">
                        <li class="menu-item  dropdown">
                            <a title="About" href="#" lass="dropdown-toggle">About <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a title="About Us" href="#">About Us</a></li>
                                <li><a title="Manifesto" href="#">Manifesto</a></li>
                                <li><a title="Team" href="#">Team</a></li>
                                <li><a title="Partners" href="#">Partners</a></li>
                            </ul>
                        </li>
                        <li class="menu-item dropdown"><a title="Investments" href="#" class="dropdown-toggle">Our work <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a title="Our Approach" href="#">Our work</a></li>
                                <li><a title="Companies" href="#">Companies</a></li>
                                <li><a title="Regions" href="#">Regions</a></li>
                                <li><a title="Sectors" href="#">Sectors</a></li>
                                <li><a title="Lean Data" href="#">Lean Data</a></li>
                            </ul>
                        </li>
                        <li class="menu-item dropdown"><a title="Investments" href="#" class="dropdown-toggle">Help US <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a title="Our Approach" href="#">Our Approach</a></li>
                                <li><a title="Companies" href="#">Companies</a></li>
                                <li><a title="Regions" href="#">Regions</a></li>
                                <li><a title="Sectors" href="#">Sectors</a></li>
                                <li><a title="Lean Data" href="#">Lean Data</a></li>
                            </ul>
                        </li>
                        <li class="menu-item dropdown"><a title="Investments" href="/faq" class="dropdown-toggle">FAQs </a></li>
                        {% if user_info is empty %}
                            <li class="menu-item dropdown hidden-sm-up"><a title="Investments" href="/auth/login" class="dropdown-toggle"><i class="fa fa-sign-in"></i> Login </a></li>
                        {% else %}
                            <li class="menu-item dropdown hidden-sm-up">
                                <a title="Investments" href="javascript:" class="dropdown-toggle">{{ user_info.username }} <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/user">Profile</a></li>
                                    <li><a href="/project/add">Add Funding Project</a></li>
                                    <li><a href="/project/add_auction">Add Auction Project</a></li>
                                    <li><a href="/project/add_token">Tokenize Artwork</a></li>
                                    <li><a href="/auth/logout">Logout</a></li>
                                </ul>
                            </li>
                        {% endif %}

                    </ul>
                </div>
            </div>
            <div class="button-area">
                <a href="javascript:void(0)" class="icon-btn showSearchBar"><img width="20" src="/client_resource/assets/img/icon/icon-search.png"></a>
                <div class="special-nav active">
                    <a href="/project/add_token" class="btn">Up Project</a>
                </div>

                <ul class="nav navbar-nav hidden-sm-down">
                    <li class="menu-item  dropdown">
                        {% if user_info is empty %}
                            <a title="Login" href="/auth/login" class="dropdown-toggle text-center">
                                <i class="fa fa-sign-in"></i> Login
                            </a>
                        {% else %}
                            <a title="{{ user_info.eth_address }}" href="javascript:void(0);" style="white-space: nowrap" class="dropdown-toggle text-center">
                                <span class="user_address" data-address="{{ user_info.eth_address }}">{{ user_info.getAddress() }}</span> <span class="caret"></span>
                            </a>
                        {% endif %}
                        {% if user_info is not empty %}
                            <ul class="dropdown-menu">
                                <li><a href="/user">Profile</a></li>
                                <li><a href="/project/add">Add Funding Project</a></li>
                                <li><a href="/project/add_auction">Add Auction Project</a></li>
                                <li><a href="/project/add_token">Tokenize Artwork</a></li>
                                <li><a href="/auth/logout">Logout</a></li>
                            </ul>
                        {% endif %}
                    </li>

                </ul>
                <button class="js-toggle-sidenav hamburger hidden-sm-up">
                    <span></span>
                </button>
            </div>
        </div>

    </div>
</header>