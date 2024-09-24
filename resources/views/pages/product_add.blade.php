@extends('layouts.default')
@section('title','Product Add')
@section('content')


<div class="container mt-4">
    <div id="alert-success-Product-submit" class="alert alert-success d-none">
    </div>
    <div class="form-group">
        <input type="hidden" name="_token" id="csrf" value="{{ csrf_token() }}">
        <label for="productname">Product Name</label>
        <input type="text" class="form-control" id="productname" name="productname" placeholder="Enter Product Name">
    </div>
    <div class="form-group">
        <label for="productprice">Product Price</label>
        <input type="text" class="form-control" id="productprice" name="productprice" placeholder="Enter Product Price">
    </div>
    <div class="form-group">
        <label for="productsku">Product SKU</label>
        <input type="text" class="form-control" id="productsku" name="productsku" placeholder="Enter Product SKU">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <select name="category" id="category" class="form-control">
            <option value="">Select</option>
            @foreach ($category as $item)
            <option value="{{$item->id}}">{{$item->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" class="form-control" name="image" id="image">
    </div>
    <button type="submit" id="product_submit_btn" class="btn btn-primary mt-2">Submit</button>

    <div class="mt-5">
        <div id="alert-success" class="alert alert-success d-none">
        </div>
        <form id="excelForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Choose Product Excel File</label>
                <input type="file" name="file" id="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Import</button>
            <a href="{{asset('user/Excel/product.xlsx')}}" class="btn btn-success mt-3" download>Product Excel Format</a>
        </form>
        <form id="bulkImage" class="mt-5" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Choose Product Image File</label>
                <input type="file" name="image_file" id="image_file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Upload</button>

        </form>
    </div>


    <h3 class="mt-5">Product List</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>SKU</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="product_list"></tbody>
    </table>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                        <label for="edit_productname">Product Name</label>
                        <input type="text" class="form-control" id="edit_productname">
                    </div>
                    <div class="form-group">
                        <label for="edit_productprice">Product Price</label>
                        <input type="text" class="form-control" id="edit_productprice">
                    </div>
                    <div class="form-group">
                        <label for="edit_productsku">Product SKU</label>
                        <input type="text" class="form-control" id="edit_productsku">
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <input type="text" class="form-control" id="edit_description">
                    </div>
                    <div class="form-group">
                        <label for="edit_category">Category</label>
                        <select id="edit_category" class="form-control">
                            @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->categoryname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Image</label>
                        <input type="file" class="form-control" id="edit_image" required>
                    </div>
                    <img id="edit_image_preview" src="" width="100" class="mt-2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="update_product_btn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

<script>
    $(document).ready(function () {
        function loadProducts() {
            $.ajax({
                url: "{{ route('product.fetch') }}",
                type: "GET",
                success: function (response) {
                console.log(response);

                    var html = '';
                    response.forEach(function (product) {
                        html += '<tr>';
                        html += '<td>' + product.id + '</td>';
                        html += '<td>' + product.productname + '</td>';
                        html += '<td>' + product.categoryid + '</td>';
                        html += '<td>' + product.productprice + '</td>';
                        html += '<td>' + product.productsku + '</td>';
                        html += '<td>' + product.description + '</td>';
                        html += '<td><img src="/productimages/' + product.image + '" width="100"></td>';
                        html += '<td>';
                        html += '<button class="btn btn-success edit_btn" data-id="' + product.id + '">Edit</button> ';
                        html += '<button class="btn btn-danger delete_btn" data-id="' + product.id + '">Delete</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#product_list').html(html);
                }
            });
        }

        loadProducts();

        $('#product_submit_btn').on('click', function (e) {
            e.preventDefault();

            var productname = $('#productname').val();
            var productprice = $('#productprice').val();
            var productsku = $('#productsku').val();
            var description = $('#description').val();
            var category = $('#category').val();
            var image = $('#image')[0].files[0];

            if (productname && productprice && productsku && description && category && image) {
                var formData = new FormData();
                formData.append("_token", $("#csrf").val());
                formData.append("productname", productname);
                formData.append("productprice", productprice);
                formData.append("productsku", productsku);
                formData.append("description", description);
                formData.append("category", category);
                formData.append("image", image);

                $.ajax({
                    url: "{{ route('product.submit') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#alert-success-Product-submit').text(response.success).removeClass('d-none');
                            loadProducts();
                        } else {
                            alert("Error occurred!");
                        }
                    },
                    error: function () {
                        alert("Failed to submit form");
                    }
                });
            } else {
                alert('Please fill all the fields!');
            }
        });

        $(document).on('click', '.edit_btn', function () {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('product.edit') }}",
                type: "GET",
                data: { id: id },
                success: function (response) {
                    console.log(response);

                    $('#edit_id').val(response.id);
                    $('#edit_productname').val(response.productname);
                    $('#edit_productprice').val(response.productprice);
                    $('#edit_productsku').val(response.productsku);
                    $('#edit_description').val(response.description);
                    $('#edit_category').val(response.categoryid);
                    $('#edit_image_preview').attr('src', '/productimages/' + response.image);
                    $('#editModal').modal('show');
                }
            });
        });

        $('#update_product_btn').on('click', function () {
            var id = $('#edit_id').val();
            var productname = $('#edit_productname').val();
            var productprice = $('#edit_productprice').val();
            var productsku = $('#edit_productsku').val();
            var description = $('#edit_description').val();
            var category = $('#edit_category').val();
            var image = $('#edit_image')[0].files[0];

            var formData = new FormData();
            formData.append("_token", $("#csrf").val());
            formData.append("productname", productname);
            formData.append("productprice", productprice);
            formData.append("productsku", productsku);
            formData.append("description", description);
            formData.append("category", category);
            formData.append("image", image);
            formData.append("id", id);

            $.ajax({
                url: "{{ route('product.update') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.statusCode == 200) {
                        loadProducts();
                        $('#editModal').modal('hide');
                    } else {
                        alert("Error occurred while updating!");
                    }
                }
            });
        });

        $(document).on('click', '.delete_btn', function () {
            var id = $(this).data('id');

            if (confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    url: "{{ route('product.delete') }}",
                    type: "POST",
                    data: {
                        _token: $("#csrf").val(),
                        id: id
                    },
                    success: function (response) {
                        if (response.statusCode == 200) {
                            loadProducts();
                        } else {
                            alert("Error occurred while deleting!");
                        }
                    }
                });
            }
        });

        $('#excelForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('import.product') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#alert-success').text(response.success).removeClass('d-none');
                        loadProducts();
                    } else {
                        alert('Something went wrong!');
                    }
                },
                error: function(xhr) {
                    console.error('Upload failed:', xhr);
                    alert('An error occurred during file upload.');
                }
            });
        });

        $('#bulkImage').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "#",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#alert-success').text(response.success).removeClass('d-none');
                        loadProducts();
                    } else {
                        alert('Something went wrong!');
                    }
                },
                error: function(xhr) {
                    console.error('Upload failed:', xhr);
                    alert('An error occurred during file upload.');
                }
            });
        });


    });
</script>


@stop
