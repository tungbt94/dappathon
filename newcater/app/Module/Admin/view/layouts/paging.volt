{% if paging_info['rowcount'] > 0 %}
    <div class="text-center">

        <ul class="pagination mb-0">
            <li class="page-item">
                <a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ paging_info['page'] -1 <= 1 ? 1 : paging_info['page'] - 1 }}"><span class="ti-arrow-left"></span></a>
            </li>

            {% set range_length = paging_info['rangepage']|length %}
            {% set first_item = paging_info['rangepage'][0] %}
            {% set last_item = paging_info['rangepage'][range_length-1] %}


            {% if last_item - range_length > 1 %}
                <li class="page-item">
                    <a class="page-link" href="{{ paging_info['currentlink'] }}&p=1"><span>1</span></a>
                </li>
            {% endif %}


            {% if first_item > 2 %}
                <li class="page-item">
                    <a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ first_item - 1 }}"><span>...</span></a>
                </li>
            {% endif %}

            {% for index,item in paging_info['rangepage'] %}
                <li class="page-item {{ item == paging_info['page'] ? "active" : "" }}">
                    <a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ item }}">{{ item }}</a>
                </li>
            {% endfor %}


            {% if paging_info['totalpage'] - 1 > last_item %}
                <li class="page-item"><a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ last_item + 1 }}">...</a></li>
            {% endif %}

            {% if last_item < paging_info['totalpage'] %}
                <li class="page-item">
                    <a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ paging_info['totalpage'] }}"><span>{{ paging_info['totalpage'] }}</span></a>
                </li>
            {% endif %}


            <li class="page-item">
                <a class="page-link" href="{{ paging_info['currentlink'] }}&p={{ paging_info['page'] + 1 >= paging_info['totalpage'] ? paging_info['totalpage'] : paging_info['page'] + 1 }}">
                    <span class="ti-arrow-right"></span>
                </a>
            </li>
        </ul>
    </div>
{% else %}
    <div class="text-center"><p class="text-success m-t-10">Không có kết quả nào</p></div>
{% endif %}