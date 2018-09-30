{#<div class="paging text-right">
    {% if paging_info['rowcount'] > 0 %}
        <ul class="pagination">
            <li><a class="prev" href="{{ paging_info['currentlink'] }}&p={{ paging_info['page']-1<=1?1:paging_info['page']-1 }}"><span>&laquo </span></a></li>
            {% for index,item in paging_info['rangepage'] %}
                <li class="{{ item==paging_info['page']?"active":"" }}"><a href="{{ paging_info['currentlink'] }}&p={{ item }}">{{ item }}</a></li>
            {% endfor %}
            <li><a class="next" href="{{ paging_info['currentlink'] }}&p={{ paging_info['page']+1>=paging_info['totalpage']? paging_info['totalpage']:paging_info['page']+1 }}"><span>»</span></a></li>
        </ul>
    {% else %}
        <p class="text-success m-t-10 text-center">Không tìm thấy kết quả nào</p>
    {% endif %}
</div>#}

{% if paging_info['rowcount'] > 0 %}

    <nav aria-label="Page navigation example">
        {#<span>{{ from }}-{{ to }} of {{ paging_info['rowcount'] }}</span>#}
        <ul class="pagination mb-0">
            <li class="page-item">
                <a href="{{ paging_info['currentlink'] }}&p={{ paging_info['page'] -1 <= 1 ? 1 : paging_info['page'] - 1 }}"><span>&laquo </span></a>
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
                    <span>»</span>
                </a>
            </li>
        </ul>
    </nav>

{% endif %}