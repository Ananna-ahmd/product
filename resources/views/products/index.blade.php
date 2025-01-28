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
    <h2 class="text-center">Laptop Shop</h2>

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
        <div class="mb-3">
            <label>Availability</label><br>
            <input type="radio" id="available" name="availability" value="in_stock" checked>
            <label for="available">In Stock</label>
            <input type="radio" id="out_of_stock" name="availability" value="out_of_stock">
            <label for="out_of_stock">Out of Stock</label>
        </div>
    
        <div class="mb-3">
            <input type="checkbox" id="featured" name="featured">
            <label for="featured">Mark as Featured</label>
        </div>
    
       
        <div class="mb-3">
            <label>Product Image</label>
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
    <div class="mb-3 d-flex">
        <input type="text" id="search" class="form-control" placeholder="Search product...">
        <button id="searchBtn" class="btn btn-primary ms-2">Search</button>
    </div>

    <div class="mb-3 d-flex">
        <!-- Filter Availability -->
        <select id="availabilityFilter" class="form-control me-2">
            <option value="">All</option>
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
        </select>
    
        <!-- Sort By -->
        <select id="sortBy" class="form-control me-2">
            <option value="name">Sort by Name</option>
            <option value="price">Sort by Price</option>
        </select>
    
        <!-- Sort Order -->
        <select id="sortOrder" class="form-control me-2">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
        </select>
    
        <button id="filterBtn" class="btn btn-success">Apply</button>
    </div>

    <!-- Product Table -->
    <div id="productTable">
        @include('products.table', ['products' => $products])
    </div>
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
        let method = id ? 'POST' : 'POST'; 
       
        let formData = new FormData(this);
        formData.append('_method', id ? 'PUT' : 'POST'); 
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false,
            success: function (response) {
                alert('Product saved successfully!');
                location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + "\n";
                    });
                    alert("Validation Failed:\n" + errorMessage);
                }
            }
        });
    });

    // Edit Product
    $(document).on('click', '.editProduct', function () {
        let id = $(this).closest('tr').data('id');

        $.get(`/products/${id}/edit`, function (data) {
            $('#product_id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#price').val(data.price);

            $(`input[name="availability"][value="${data.availability}"]`).prop("checked", true);
            
            
            $('#featured').prop('checked', data.featured == 1);
        });
    });

    // Delete Product
    $(document).on('click', '.deleteProduct', function () {
        let id = $(this).closest('tr').data('id');

        $.ajax({
            url: `/products/${id}`,
            type: 'DELETE',
            success: function () {
                alert('Product deleted successfully!');
                location.reload();
            }
        });
    });

// search ,filtering, pagination , sorting

    $(document).ready(function () {
    function fetchProducts(page = 1) {
        let search = $('#search').val();
        let availability = $('#availabilityFilter').val();
        let sortBy = $('#sortBy').val();
        let sortOrder = $('#sortOrder').val();

        console.log("Fetching products with:", { availability, sortBy, sortOrder});
        
        $.ajax({
            url: "/products?page=" + page + "&search=" + search,
            type: "GET",
            data: {
                availability: availability,
                sort_by: sortBy,
                sort_order: sortOrder
            },

            success: function (response) {
                $('#productTable').html(response.products);
                $('.pagination').html(response.pagination);
            }
        });
    }

    // Search 
    $('#searchBtn').click(function () {
        fetchProducts();
    });

    // Search 
    $('#search').keyup(function (e) {
        if (e.key === 'Enter') {
            fetchProducts();
        }
    });

    $('#filterBtn').click(function () {
        fetchProducts();
    });

    // Pagination 
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchProducts(page);
    });
});
});
</script>
