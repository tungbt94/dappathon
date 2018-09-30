{% set item = object['data'] %}

<tr class="home_category_item" data-id="{{ item.id }}">
    <th scope="row">{{ item.id }}</th>
    <td>{{ item.name }}</td>
    <td class="sort">{{ item.sort ? item.sort : 1 }}</td>
    <td>
        <button type="button" class="btn btn-sm btn-{{ item.status == 1 ? 'danger' : 'success' }}">{{ item.status == 1 ? 'Không hoạt động' : 'Hoạt động' }}</button>
    </td>
    <td>{{ date('d/m/Y', item.datecreate) }}</td>
    <td>{{ item.Usercreate.fullname }}</td>
    <td>
        <button class="btn btn-danger btn-sm delete_home_category_item" type="button">Xóa</button>
    </td>
    <input type="hidden" name="category_id[]" value="{{ item.id }}">
    <input type="hidden" name="sort[]" value="{{ item.sort ? item.sort : 1 }}" class="input_sort">
</tr>