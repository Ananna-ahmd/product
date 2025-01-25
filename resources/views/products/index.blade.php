<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Our Product</h2>

    <form id="productForm">
        <input type="hidden" id="product_id" name="product_id">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" id="name" name="name" class="form-control">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea id="description" name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Price</label>
            <input type="number" id="price" name="price" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
    

    <!-- Product Table -->
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTable">
            @foreach($products as $product)
                <tr data-id="{{ $product->id }}">
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>${{ $product->price }}</td>

                    <td>
                        <button class="btn btn-warning editProduct">Edit</button>
                        <button class="btn btn-danger deleteProduct">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Create or Update Product
    $('#productForm').submit(function (e) {
        e.preventDefault();
        let id = $('#product_id').val();
        let url = id ? `/products/${id}` : '/products';
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), 
                 _method: method, 
                name: $('#name').val(),
                description: $('#description').val(),
                price: $('#price').val(),
                
            },
            success: function (response) {
                location.reload();
            }
        });
    });

    // Edit Product
    $('.editProduct').click(function () {
        let id = $(this).closest('tr').data('id');

        $.get(`/products/${id}/edit`, function (data) {
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#price').val(data.price);
           
        });
    });

    // Delete Product
    $('.deleteProduct').click(function () {
        let id = $(this).closest('tr').data('id');

        $.ajax({
            url: `/products/${id}`,
            type: 'DELETE',
            success: function () {
                location.reload();
            }
        });
    });
});
</script>
</body>
</html>
