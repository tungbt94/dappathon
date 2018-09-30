<div class="content" style="padding-top: 100px">
    <div class="container">
        <div class="detail">
            <div class="header-detail">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="avarta">
                            <img src="{{ object.User.getAvatar() }}"/>
                        </div>
                        <div class="title-avarta">
                            <span class="name">By <a href="javascript:">{{ object.User.fullname }}</a></span>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <h2 class="title" style="color: #363636;">
                            {{ object.name }}
                        </h2>
                        <span class="description title">{{ object.description }}</span>
                    </div>
                </div>
            </div>
            <div class="content-detail">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="image-detail">
                            <img src="{{ object.getAvatar() }}" title="{{ object.name }}"/>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="information-donate">
                            <h2 class="title">1. CHOOSE AMOUNT (ETH)</h2>
                            <div class="d-flex">
                                <div class="input-donate donate-input">
                                    <input type="button" class="form-control btn_donate_amount" id="price-donate1" title="" value="50">

                                </div>
                                <div class="input-donate donate-input">
                                    <input type="button" class="form-control btn_donate_amount" id="price-donate2" title="" value="100">

                                </div>
                                <div class="input-donate donate-input">
                                    <input type="button" class="form-control btn_donate_amount" id="price-donate3" title="" value="150">

                                </div>
                                <div class="input-donate donate-input" style="margin-right: 0">
                                    <input type="button" class="form-control btn_donate_amount" id="price-donate4" title="" value="200">

                                </div>
                            </div>

                            <div class="input-donate-a donate-input">
                                <input type="text" class="form-control donate_amount" id="price-donate5" title="" placeholder="Insert custom value " value="">
                            </div>


                        </div>
                        <div class="information-donate">
                            <div class="input-donate-a donate-input">
                                <label>Deposit address:</label>
                                <a href="https://ropsten.etherscan.io/address/{{ object.contribute_address }}" target="_blank">{{ object.contribute_address }}</a>
                                <input type="hidden" id="contribute_address" value="{{ object.contribute_address }}">
                                <input type="hidden" id="funding_receipt" value="{{ object.funding_receipt }}">
                            </div>

                            <div class="input-donate-a donate-input">
                                <label>Fundraiser: </label>
                                <a href="https://ropsten.etherscan.io/address/{{ object.funding_receipt }}" target="_blank">{{ object.funding_receipt }}</a>
                            </div>

                            <div class="input-donate-a donate-input">
                                <label>Target: </label>
                                <p><span class="project_target" data-target="{{ object.target }}">{{ number_format(object.target, 2) }}</span> ETH</p>
                            </div>

                            <div class="input-donate-a donate-input">
                                <label>Raised: </label>
                                <p><span class="project_total_raised">{{ number_format(object.raised, 2) }}</span> ETH</p>
                            </div>

                            <div class="input-donate-a donate-input">
                                <label>Balance: </label>
                                <p><span class="project_total_balance">{{ number_format(object.balance, 2) }}</span> ETH</p>
                            </div>
                        </div>

                        {#{% if object.contribute_end_time > 0 or time() < object.contribute_end_time %}#}
                            <div class="button_back">
                                <button type="submit" class="btn bg-donate btn-detail" id="donate-fund">Donate</button>
                            </div>
                        {#{% endif %}#}

                        {% if user_info.id == object.usercreate %}
                            <a style="margin-top: 10px" href="/project/withdraw?id={{ object.id }}" class="btn bg-blue text-white btn-detail">Withdraw</a>
                        {% endif %}
                        <p style="margin-top: 8px"><a href="/project/history_withdraw?id={{ object.id }}"><i class="fa fa-history"></i> History withdraw</a></p>

                        <div class="remind-social">
                            <div class="remind w50">
                                <button class="btn btn-default w-100"><i class="fa fa-heart"></i>
                                    Remind me
                                </button>
                            </div>
                            <div class="social w50">
                                <a href="#" class="icon-social"><span class="fa fa-facebook"></span></a>
                                <a href="#" class="icon-social"><span class="fa fa-twitter"></span></a>
                                <a href="#" class="icon-social"><span class="fa fa-instagram"></span></a>
                                <a href="#" class="icon-social"><span class="fa fa-linkedin"></span></a>
                                <a href="#" class="icon-social"><span class="fa fa-pinterest"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-detail">
            <div class="row">
                <div class="col-sm-8">

                    {% if contribute is not empty and withdraw.vote_end_time > time() %}
                        <div class="text-center">
                            <h3 class="name_time">Voting Withdrawal starts in</h3>
                            <div id="clockdiv">
                                <div>
                                    <span class="days"></span>
                                    <div class="smalltext">Days</div>
                                </div>
                                <div>
                                    <span class="hours"></span>
                                    <div class="smalltext">Hours</div>
                                </div>
                                <div>
                                    <span class="minutes"></span>
                                    <div class="smalltext">Minutes</div>
                                </div>
                                <div>
                                    <span class="seconds"></span>
                                    <div class="smalltext">Seconds</div>
                                </div>
                            </div>
                            <div class="text-center mt-10 mb-10">
                                <a class="btn btn-default {{ user_approve is not empty ? 'bg-success' : 'bg-gray' }}" id="btn_accept" data-user-approve="{{ user_approve.id }}">Yes</a>
                            </div>
                        </div>
                    {% endif %}


                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home"><b>Description</b></a></li>
                        <li><a data-toggle="tab" href="#menu1"><b class="hidden-sm-down">Documents</b> <i class="fa fa-file hidden-sm-up"></i></a></li>
                        <li><a data-toggle="tab" href="#menu2"><b>Donations</b></a></li>
                        <li><a data-toggle="tab" href="#menu3"><b class="hidden-sm-down">Comments</b> <i class="fa fa-comments hidden-sm-up"></i></a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <h3>Description</h3>
                            <p>
                                {{ object.content }}
                            </p>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <h3>OUR DOCUMENTS</h3>
                            <table class="table box_document">
                                <tr>
                                    <td><img class="nd_donations_float_left" alt="" width="25" src="http://www.nicdarkthemes.com/themes/charity/wp/demo/charity-foundation/wp-content/uploads/sites/3/2017/04/icon-pdf.png"> PDF</td>
                                    <td>

                                        Project Details
                                    </td>
                                    <td class="text-right">
                                        <a class="btn_view_more">PREVIEW</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img class="nd_donations_float_left" alt="" width="25" src="http://www.nicdarkthemes.com/themes/charity/wp/demo/charity-foundation/wp-content/uploads/sites/3/2017/04/icon-pdf.png"> PDF</td>
                                    <td>

                                        Project Details
                                    </td>
                                    <td class="text-right">
                                        <a class="btn_view_more">PREVIEW</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <h3>Donations</h3>
                            <table class="table box_document">
                                {% for item in list_contribute %}
                                    <tr>
                                        <td>
                                            <b>
                                                <a href="https://ropsten.etherscan.io/address/{{ item['contribute_address'] }}" target="_blank">{{ item['contribute_address'] }}</a>
                                            </b>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format(item['sumatory'], 2) }} ETH
                                        </td>
                                    </tr>
                                {% endfor %}
                            </table>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <h3>Post comment</h3>
                            <div class="fb-comments" data-href="{{ object.getLink() }}" data-numposts="5" data-width="100%"></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    {% for item in list_data %}
                        <div class="row item_relate">
                            <div class="col-xs-4 col-sm-3 p-0">
                                <img alt="" class="nd_donations_postgrid_causes_2_single_cause_img nd_donations_position_absolute"
                                     src="{{ item.getAvatar() }}" title="{{ item.name }}">
                            </div>
                            <div class="col-xs-8 col-sm-9">
                                <h4><a>{{ item.name }}</a></h4>
                                <p>TARGET : {{ number_format(item.target, 2) }} ETH RAISED : {{ number_format(item.raised, 2) }} ETH</p>
                                <a class="btn_view_more bg-blue" href="{{ item.getLink() }}">Read More</a>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="project_info"
       data-start="{{ withdraw.vote_start_time * 1000 }}"
       data-end="{{ withdraw.vote_end_time * 1000 }}"
       data-id="{{ object.id }}"
       data-target="{{ object.target }}"
       data-raised="{{ object.raised }}"
       data-balance="{{ object.balance }}"
>
<script src="/client_resource/custom/js/project_index.js?v={{ time }}"></script>