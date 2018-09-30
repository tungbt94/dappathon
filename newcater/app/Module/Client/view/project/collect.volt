<div class="content" style="padding-top: 100px">
    <div class="container">

        <div class="col-sm-12 col-xs-12 paddingrigt">

            <div class="extended_view single_product">
                <div class="product_photo_wrp">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <img src="{{ object.getAvatar() }}" style="max-width: 100%" draggable="false">
                        </div>
                        <div class="col-sm-6 col-xs-12 product__view__content">
                            <div class="content_product_detail">
                                <div class="content-detail-block">
                                    <h3 class="title_name">{{ object.name }}</h3>
                                    <div class="product-detail-rating"></div>
                                </div>


                                <div class="product__quantity product__view__quantity content-detail-block" style="margin-bottom: 10px">
                                    <div class="product__price product__price__regular">
                                        <p class="mb-0" style="display:inline-block; margin-right: 10px; color: #515151">
                                            <b>Token name: <span id="price_bid">{{ object.token_name }} </span> ({{ object.symbol }}) </b>
                                        </p>
                                        <br>

                                        <p class="mb-0" style="display:inline-block; margin-right: 10px; color: #515151">
                                            <b>Total Supply: <span id="total_supply">{{ number_format(object.total_supply) }} </span> ({{ object.symbol }}) </b>
                                        </p>

                                        <p class="mb-0" style="display:inline-block; margin-right: 10px; color: #515151">
                                            <b>Token Address:
                                                <a href="https://ropsten.etherscan.io/address/{{ object.contribute_address }}" target="_blank">{{ object.contribute_address }}</a>
                                                <input type="hidden" id="contribute_address" value="{{ object.contribute_address }}">
                                            </b>
                                        </p>

                                    </div>
                                    <div class="social_shared ">
                                        <a target="_blank" class="fa fa-facebook" href="http://www.facebook.com/sharer/sharer.php?u={{ object.getLink() }}"></a>
                                        <a target="_blank" class="fa fa-google" href="https://plus.google.com/share?url={{ object.getLink() }}"></a>
                                        <a target="_blank" class="fa fa-twitter" href="https://twitter.com/intent/tweet?text={{ object.name }}&url={{ object.getLink() }}&via=TWITTER-HANDLER">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div style="color: #449d44;font-size: 20px;" id="msg_big_success"></div>
                            <div style="color: #04a2bc;font-size: 20px;" id="msg_big"></div>


                            <div class="name-info-conten">
                                <div class="input-group" style="width: 200px;">
                                    <span class="input-group-addon">ETH</span><input type="text" name="price_bid_user" id="price_bid_user" class="form-control input-number" value="0">
                                </div>
                            </div>

                            <input type="hidden" name="current_user_id" id="current_user_id" value="1">
                            <div class="product__bid">
                                {% if object.contribute_end_time > time() %}
                                    <a id="bid_now" type="button" class="btn btn-newcater" style="width: 50%;"><span class="glyphicon glyphicon-hand-right"></span> Bid now</a>
                                {% endif %}
                            </div>

                            <p> 5% Service costs</p>
                            <p style="font-size: 18px;">Current Warriors: <span id="bid_currently">{{ object.current_warrior }}</span></p>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="footer-detail">
            <div class="clearfix">
                <div class="col-sm-8">

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
                                {{ object.description }}
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

                <input type="hidden" class="project_info"
                       data-id="{{ object.id }}"
                >
            </div>
        </div>
    </div>
</div>
<style>
    .content-seller-wrapper {
        padding: 28px 28px 0;
    }

    .flag-body, .flag-img {
        display: table-cell;
        padding-right: 10px;
    }

    .content-information, .count-box .thumbnail-inner, .etsy-icon, .fa-money, .fa-stack, .flag-body, .flag-img, .stars-svg {
        vertical-align: middle;
    }

    .content-seller-wrapper .flag-img img {
        width: 75px;
    }

    .button-fave-container {
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: 3px;
        display: inline-block;
    }

    .data-shop-fave-container .text-gray-lighter {
        color: #777;
        margin-bottom: 0;
        padding-top: 10px;
    }

    .thumbnail-links {
        float: right;
    }

    .thumbnail-links li {
        display: inline-block;
        word-wrap: break-word;
        margin-left: 14px;
    }

    .thumbnail-outer {
        border-radius: 3px;
        border: 1px solid #ececec;
        float: left;
        transition: all .1s;
        -webkit-transition: all .1s;
        -moz-transition: all .1s;
    }

    .thumbnail-inner {
        border: 3px solid #fff;
        background: #fff;
        overflow: hidden;
        transition: all .1s;
        -webkit-transition: all .1s;
        -moz-transition: all .1s;
    }

    .count-box .thumbnail-inner {
        display: table-cell;
        text-align: center;
        height: 69px;
        width: 69px;
        background: #fff;
        transition: all .1s;
        -webkit-transition: all .1s;
        -moz-transition: all .1s;
    }

    .thumbnail-links li span {
        display: block;
    }

    span.count-number {
        display: block;
        font-size: 20px;
        padding-top: 0;
        color: #222;
        text-align: center;
    }

    .content-detail-block {
        padding: 10px 0;
        border-bottom: 1px dashed rgba(0, 0, 0, .09);
    }

    .content-detail-block {
        padding: 10px 0;
        border-bottom: 1px dashed rgba(0, 0, 0, .09);
    }

    .product__price__regular {
        display: inline-block;
        font-weight: 500;
        color: red;
        font-size: 18px;
    }

    .product__view__content .social_shared {
        display: inline-block;
        float: right;
    }

    .social_shared .fa-facebook {
        background: #3b5998;
        color: #fff;
    }

    .social_shared .fa {
        padding: 5px;
        font-size: 0;
        width: 24px;
        text-align: center;
        margin: 5px 2px;
        border-radius: 50%;
        font: normal normal normal 14px/1 FontAwesome;
    }

    .social_shared .fa-google {
        background: #dd4b39;
        color: #fff;
    }

    .name-info-conten {
        margin-bottom: 10px;
    }

    .social_shared .fa-twitter {
        background: #55acee;
        color: #fff;
    }

    .paddingrigt {
        padding-bottom: 15px;
    }

    .btn-newcater {
        background: #04a2bc;
        color: #fff;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }
</style>
<script>
    $(document).ready(function () {
        var date = $("#bid_countdown").attr('data-date');
        countDown(date * 1000, $("#bid_countdown"));
    })
</script>