{% set item = object['data'] %}
<div class="row order_product_item pt-3" data-id="{{ item.id }}">
    <div class="col-sm-4 col-md-4">
        {{ item.name }}
        <input type="hidden" name="order_product[{{ item.id }}][product_id]" value="{{ item.id }}">
    </div>
    <div class="col-sm-4 col-md-4">
        <input type="text" name="order_product[{{ item.id }}][quantity]" placeholder="Số lượng" class="form-control" value="{{ item.quantity }}">
    </div>
    <div class="col-sm-3 col-md-3">
        <input type="text" name="order_product[{{ item.id }}][price]" placeholder="Giá" class="form-control" value="{{ item.price }}">
    </div>
    <div class="col-sm-1 col-md-1">
        <button class="btn btn-sm btn-danger waves-effect waves-light btn_delete_order_product"><i class="fa fa-trash-o"> Xóa</i></button>
    </div>
</div>