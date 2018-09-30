<ul class="nav nav-tabs">
    <li><a  class="active" data-toggle="tab" href="#home"><i class="fa fa-star" ></i> Phim mới</a></li>
    <li><a data-toggle="tab" href="#menu1">Xem nhiều</a></li>
</ul>

<div class="tab-content bg-white pl-15 pr-15 pt-15 pb-15">
    <div id="home" class="tab-pane in active">
        <ul class="new_film">
            {% for item in list_new_film %}
                <li class="clearfix">
                    <a href="{{ item.getLink() }}" title="{{ item.name~item.name_en }}"><img src="{{ item.avatar }}" class="img-responsive" alt="{{ item.name_en }}"></a>
                    <h3><a href="{{ item.getLink() }}" title="{{ item.name~item.name_en }}">{{ item.name_en }}</a></h3>
                    <p>{{ item.name }}</p>
                    <p class="view"><small>Lượt xem: {{ number_format(item.view) }}</small></p>
                </li>
            {% endfor %}
        </ul>
    </div>
    <div id="menu1" class="tab-pane">
        <ul class="new_film">
            {% for item in list_film_view %}
                <li class="clearfix">
                    <a href="{{ item.getLink() }}" title="{{ item.name~item.name_en }}"><img src="{{ item.avatar }}" class="img-responsive" alt="{{ item.name_en }}"></a>
                    <h3><a href="{{ item.getLink() }}" title="{{ item.name~item.name_en }}">{{ item.name_en }}</a></h3>
                    <p>{{ item.name }}</p>
                    <p class="view"><small>Lượt xem: {{ number_format(item.view) }}</small></p>
                </li>
            {% endfor %}
        </ul>
    </div>

</div>