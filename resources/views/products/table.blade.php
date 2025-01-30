<table class="table mt-3">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Category</th>
            <th>Product Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="productTable">
        @foreach($products as $product)
            <tr data-id="{{ $product->id }}">
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->availability }}</td>
                <td>{{ $product->category->name ?? 'No Category' }}</td>
                <td>
                    
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="100" height="100" alt="Product Image">
                    @else
                        No Image
                    @endif
                </td>
                

                <td>
                    <button class="btn btn-warning editProduct">Edit</button>
                    <button class="btn btn-danger deleteProduct">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

  <div class="pagination">
    {!! $products->links('pagination::bootstrap-4') !!}
</div> 
