<div class="content">
    {% include "layouts/slider_index.volt" %}
    <section id="page_animation">
        <div class="container home-difference-connect" data-is="active">
            <div class="home-difference-content -left">
                <h3 class="home-difference-heading">Management solution</h3>

                <p class="home-difference-lede">Decentralized Community Fund</p>

                <p>
                    CaterFund is a decentralized community fund management platform applying Blockchain technology and Smart contracts in community fundraising and management to improve the transparency, safety and efficiency of community financial
                    activitities.
                </p>
            </div>
            <div class="differences-solar-system hidden-xs" data-is="active">
                <span class="differences-earth differences-stat">
                  <p>
                    <strong></strong>
                  </p>
                </span>

                <span class="differences-person differences-person-1 hidden-xs"></span>
                <span class="differences-person differences-person-2 hidden-xs"></span>
                <span class="differences-person differences-person-3 hidden-xs"></span>
                <span class="differences-person differences-person-4 hidden-xs"></span>
                <span class="differences-person differences-person-5 hidden-xs"></span>
                <span class="differences-person differences-person-6 hidden-xs"></span>
                <span class="differences-person differences-person-7 hidden-xs"></span>
                <span class="differences-person differences-person-8 hidden-xs"></span>
                <span class="differences-person differences-person-9 hidden-xs"></span>
            </div>
            <span class="differences-satelites hidden-xs" data-is="active">
                <span class="differences-satelite-1"></span>
                <!--span class="differences-satelite-2"></span-->

                <span class="differences-ring-1"></span>
                <span class="differences-ring-2"></span>
            </span>
            <span class="differences-network hidden-xs" data-is="active"></span>
        </div>

    </section>

    {#<section>
        <div class="container">
            <h3 class="title">Charity
                <span>
                Category
                </span>
            </h3>
            <div class="row">
                {% for k,item in list_category %}
                    <div class="col-sm-4 col-xs-6 box-item-cate">
                        <div class="box_cate class_{{ k }}">
                            <a href="{{ item.getLink() }}">
                                <img class="mb-0 image_category" src="{{ item.getAvatar() }}" alt="{{ item.name }}"></a>
                            <div class="inner_cate">
                                <a href="{{ item.getLink() }}" title="{{ item.name }}">
                                    <p>{{ item.name }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    {% if((k+1)%3 == 0) %}
                        <div class="clearfix"></div>
                    {% endif %}
                {% endfor %}
            </div>
        </div>
    </section>#}
    {#<section  class="sec-our-causes bg_ef">#}

        {#<div class="container">#}
            {#<h3 class="title">Project#}
                {#<span>#}
                {#Fundraising#}
                {#</span>#}
            {#</h3>#}
            {#<div class="row">#}
                {#{% for k,item in list_category %}#}
                    {#<div class="col-sm-4 col-xs-6 box-item-cate">#}
                        {#<div class="box_cate class_{{ k }}">#}
                            {#<a href="{{ item.getLink() }}">#}
                                {#<img class="mb-0 image_category" src="{{ item.getAvatar() }}" alt="{{ item.name }}"></a>#}
                            {#<div class="inner_cate">#}
                                {#<a href="{{ item.getLink() }}" title="{{ item.name }}">#}
                                    {#<p>{{ item.name }}</p>#}
                                {#</a>#}
                            {#</div>#}
                        {#</div>#}
                    {#</div>#}
                    {#{% if((k+1)%3 == 0) %}#}
                        {#<div class="clearfix"></div>#}
                    {#{% endif %}#}
                {#{% endfor %}#}
            {#</div>#}

        {#</div>#}
    {#</section>#}

    {#<section id="category" class="sec-our-causes ">

        <div class="container">
            <h3 class="title">Fundraising
                <span>
                Categories
                </span>
            </h3>
            <div class="row">

                {% for item in list_running %}
                    <div class="col-sm-4 box-item-cate">
                        <div class="image-our-causes">
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}"><img src="{{ item is not empty ? item.getAvatar() : '' }}" title="{{ item.name }}"></a>
                        </div>

                        <div class="content-img">
                            <h3 class="title"><a href="{{ item is not empty ? item.getLink() : 'javascript:' }}">{{ item.name }}</a></h3>
                            <span class="fa fa-flag title-text goal">TARGET : <em> {{ number_format(item.target, 2) }} ETH</em></span>
                            <span class="title-text raised">RAISED : <em> {{ number_format(item.raised, 2) }} ETH</em></span>
                            <p>{{ item.description }}</p>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-blue">Read More</a>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-donate pull-right">Donate</a>
                        </div>
                    </div>
                {% endfor %}

                #}{#<div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_0">
                        <a   href="https://caterfund.com/poverty-reduction-c6">
                            <img src="/client_resource/assets/img/cate/1.jpg" class="mb-0 image_category" alt=" Poverty reduction"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/poverty-reduction-c6" title=" Poverty reduction">
                                <p> Eco-friendly products</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_1">
                        <a   href="https://caterfund.com/medical-health-c5">
                            <img class="mb-0 image_category" src="/client_resource/assets/img/cate/2.jpg" alt=" Medical-Health"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/medical-health-c5" title=" Medical-Health">
                                <p> Technology</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_2">
                        <a  href="https://caterfund.com/education-c4">
                            <img class="mb-0 image_category" src="/client_resource/assets/img/cate/3.jpg" alt=" Education"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/education-c4" title=" Education">
                                <p> Crafts</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_3">
                        <a   href="https://caterfund.com/conserve-c3">
                            <img class="mb-0 image_category" src="/client_resource/assets/img/cate/4.jpg" alt=" Conserve"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/conserve-c3" title=" Conserve">
                                <p> Art</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_4">
                        <a href="https://caterfund.com/environment-c2">
                            <img class="mb-0 image_category" src="/client_resource/assets/img/cate/7.jpg" alt=" Environment"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/environment-c2" title=" Environment">
                                <p> Game</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-6 box-item-cate">
                    <div class="box_cate class_5">
                        <a href="https://caterfund.com/rescue--aid-c1">
                            <img class="mb-0 image_category" src="/client_resource/assets/img/cate/6.jpg" alt="Rescue &amp; Aid"></a>
                        <div class="inner_cate">
                            <a href="https://caterfund.com/rescue--aid-c1" title="Rescue &amp; Aid">
                                <p>Others</p>
                            </a>
                        </div>
                    </div>
                </div>#}{#
                <div class="clearfix"></div>
            </div>

        </div>
    </section>#}

    <section class="sec-our-causes bg_ef">

        <div class="container">
            <h3 class="title">Collectible
                <p style="font-size: 12px;">Support by Newcater.com</p>
            </h3>
            <div class="row">
                {% for item in list_collect %}
                    <div class="col-sm-4 box-item-cate">
                        <div class="image-our-causes">
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}"><img src="{{ item is not empty ? item.getAvatar() : '' }}" title="{{ item.name }}"></a>
                        </div>

                        <div class="content-img">
                            <h3 class="title"><a href="{{ item is not empty ? item.getLink() : 'javascript:' }}">{{ item.name }}</a></h3>
                            <span class="fa fa-flag title-text goal">Token Name : <em>{{ item.token_name }} ({{ item.symbol }})</em></span>
                            <span class="fa fa-flag title-text goal">Total Supply : <em>{{ item.total_supply }} {{ item.symbol }}</em></span>
                            <p>{{ item.description }}</p>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-blue">Read More</a>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-donate pull-right">Buy</a>

                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    </section>

    <section class="sec-our-causes bg_ef">

        <div class="container">
            <h3 class="title">Campaigns
                <span> Upcoming </span>
            </h3>
            <div class="row">
                {% for item in list_upcoming %}
                    <div class="col-sm-4 box-item-cate">
                        <div class="image-our-causes">
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}"><img src="{{ item is not empty ? item.getAvatar() : '' }}" title="{{ item.name }}"></a>
                        </div>

                        <div class="content-img">
                            <h3 class="title"><a href="{{ item is not empty ? item.getLink() : 'javascript:' }}">{{ item.name }}</a></h3>
                            <span class="fa fa-flag title-text goal">TARGET : <em> {{ number_format(item.target, 2) }} ETH</em></span>
                            {#<span class="title-text raised">RAISED : <em> 78000 $</em></span>#}
                            <p>{{ item.description }}</p>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-blue">Read More</a>
                            <a href="{{ item is not empty ? item.getLink() : 'javascript:' }}" class="background-our-causes bg-donate pull-right">Donate</a>

                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    </section>

    <section class="sec-our-causes">

        <div class="container">
            <h3 class="title">Charity
                <span>
                 Auction
                </span>
                <p style="font-size: 12px;">Support by Newcater.com</p>
            </h3>
            <div class="row" id="bid">
                {% for item in list_auction %}
                    <div class="col-sm-3 box-item-cate" data-date="{{ item.contribute_end_time }}">
                        <div class="item">
                            <div class="item-product" >
                                <a href="{{ item.getLink() }}"><img src="{{ item.getAvatar() }}" class="img-responsive"></a>
                                <h4><a href="{{ item.getLink() }}">{{ item.name }}</a></h4>
                                {#<p class="mb-0">Arminho</p>#}
                                <p class="mb-0"><b>Bids: {{ item.step_value }} ETH</b></p>
                                <p class="mb-0 clearfix">
                                    <span class="pull-left"><i class="fa fa-clock-o"></i> <span class="time_countdown">{{ date('m/d/Y H:i', item.contribute_end_time) }}</span></span>
                                    <span class="pull-right"><b>{{ item.min_value }} ETH</b></span>
                                </p>
                                <p class="n-listing-card__price  strong text-center btn-bids">
                                    <a href="{{ item.getLink() }}" class="text-white"><i class="fa fa-gavel"></i> Make Offer</a>
                                </p>
                            </div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    </section>

    {#<section class="sec-our-causes sec-our-block bg_ef">#}

        {#<div class="container">#}
            {#<h3 class="title">#}
                {#<span>#}
               {#Blog#}
                {#</span>#}
            {#</h3>#}
            {#<div class="row">#}
                {#<div class="col-sm-4 box-item-cate">#}
                    {#<div class="image-our-causes">#}
                        {#<img src="/client_resource/assets/img/blog/2.jpg">#}

                    {#</div>#}

                    {#<div class="content-img">#}
                        {#<h3 class="title">Artefacts of UNESCO-listed My Son Sanctuary go digital</h3>#}
                        {#<p>NDO - More than one thousand artefacts discovered during the excavation and renovation of towers in the My Son Sanctuary...</p>#}
                        {#<a href="https://dozilla.com.ng/2017/10/19/poverty-still-major-nigerian-issue-poll/" class="text-blue text-uppercase readmore">Read More</a>#}

                    {#</div>#}
                {#</div>#}
                {#<div class="col-sm-4 box-item-cate">#}
                    {#<div class="image-our-causes hidden-sm-up">#}
                        {#<img src="/client_resource/assets/img/blog/3.jpg">#}
                    {#</div>#}
                    {#<div class="content-img" style="border-top: solid 1px #e5e5e5">#}
                        {#<h3 class="title">POVERTY STILL MAJOR NIGERIAN ISSUE – POLL</h3>#}
                        {#<p>As the world celebrated the United Nations (UN) International Day for the eradication of poverty Tuesday largest bust to date.</p>#}
                        {#<a href="https://dozilla.com.ng/2017/10/19/poverty-still-major-nigerian-issue-poll/" class="text-yellow text-uppercase readmore">Read More</a>#}

                    {#</div>#}
                    {#<div class="image-our-causes hidden-sm-down">#}
                        {#<img src="/client_resource/assets/img/blog/3.jpg">#}
                    {#</div>#}
                {#</div>#}
                {#<div class="col-sm-4 box-item-cate">#}
                    {#<div class="image-our-causes">#}
                        {#<img src="/client_resource/assets/img/blog/1.jpg">#}

                    {#</div>#}

                    {#<div class="content-img">#}
                        {#<h3 class="title">Big Bust Leaves a Hole in SAs Illegal Rhino Horn</h3>#}
                        {#<p>At the end of March this year, SANParks ECI and the South African Police Service (SAPS) made their largest bust to date.</p>#}
                        {#<a href="http://www.krugerpark.co.za/krugerpark-times-5-9-big-bust-illegal-rhino-horn-24966.html" class="text-red text-uppercase readmore">Read More</a>#}

                    {#</div>#}
                {#</div>#}
            {#</div>#}
        {#</div>#}
    {#</section>#}

    {#<section class="banner-footer" style="background: #f9f5f5;">
        <div class="container">
            <img class="w-100" src="/client_resource/assets/img/banner-01-01.jpg"/>
        </div>

    </section>#}
</div>
