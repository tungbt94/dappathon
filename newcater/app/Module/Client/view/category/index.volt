<div class="content" style="padding-top: 100px">
    <section id="category" class="sec-our-causes">
        <div class="container">
            <h3 class="title">Category
                <span> {{ object.name }} </span>
            </h3>
            <div class="row">
                {% for item in list_data %}
                    <div class="col-sm-4 box-item-cate">
                        <div class="image-our-causes">
                            <a href="{{ item.getLink() }}" title="{{ item.name }}"><img src="{{ item.getAvatar() }}" title="{{ item.name }}"></a>
                        </div>

                        <div class="content-img">
                            <h3 class="title"><a href="{{ item.getLink() }}" title="{{ item.name }}">{{ item.name }}</a></h3>
                            <span class="fa fa-line-chart title-text goal">TARGET : <em> {{ number_format(item.target, 2) }} ETH</em></span>
                            <span class="title-text raised">RAISED : <em> {{ number_format(item.raised, 2) }} ETH</em></span>
                            <p>{{ item.description }}</p>
                            <a href="{{ item.getLink() }}" title="{{ item.name }}" class="background-our-causes bg-blue">Read More</a>
                            <a href="{{ item.getLink() }}" title="{{ item.name }}" class="background-our-causes bg-donate pull-right">Donate</a>
                        </div>
                    </div>
                {% endfor %}

            </div>

            {% include 'layouts/paging.volt' %}

        </div>
    </section>
</div>
