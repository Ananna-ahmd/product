<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<div class="container">
    <h2>Categories</h2>

  
    <div class="mb-3">
        <input type="text" id="category_name" class="form-control" placeholder="Enter category name">
        <button id="addCategory" class="btn btn-primary mt-2">Add Category</button>
    </div>

    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoryTable">
            
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        fetchCategories();

        function fetchCategories() {
        $.ajax({
            url: "/categories",
            type: "GET",
            success: function (response) {
                let rows = "";
                $.each(response.categories, function (index, category) {
                    rows += `<tr data-id="${category.id}">
                                <td>${category.id}</td>
                                <td>${category.name}</td>
                                <td><button class="btn btn-danger deleteCategory">Delete</button></td>
                             </tr>`;
                });
                $("#categoryTable").html(rows);
            }
        });
    }

    // Add Category
    $("#addCategory").click(function () {
        let categoryName = $("#category_name").val();
        if (categoryName === "") {
            alert("Category name is required!");
            return;
        }

        $.ajax({
            url: "/categories",
            type: "POST",
            data: { name: categoryName, _token: "{{ csrf_token() }}" },
            success: function (response) {
                alert(response.message);
                $("#category_name").val("");
                fetchCategories();
            }
        });
    });

    // Delete Category
    $(document).on("click", ".deleteCategory", function () {
        let id = $(this).closest("tr").data("id");
        if (!confirm("Are you sure you want to delete this category?")) return;

        $.ajax({
            url: "/categories/" + id,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                alert(response.message);
                fetchCategories();
            }
        });
    });
});
</script>

