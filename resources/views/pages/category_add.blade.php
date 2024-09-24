@extends('layouts.default')
@section('title', 'Product Add')
@section('content')


    <h1 class="text-center">Category Form</h1>
    <form method="post" id="CategoryFormData" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="exampleInputCatNmae1" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="categoryname" name="categoryname" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Category Image</label>
            <input type="file" class="form-control" id="categoryimage" name="categoryimage" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Category Image</label>
            <select class="form-control" id="categorystatus" name="categorystatus">
                <option value="">--select--</option>
                <option value="101">Active</option>
                <option value="102">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <h1 class="text-center mt-5 mt-3">Category List</h1>
    <div class="form-control">
        <button class="form-control btn-primary" id="bulkUploadBtn">Bulk Upload Data</button>
        <button class="form-control btn-secondary mt-3" id="bulkImageBtn">Bulk Image Data</button>
    </div>


    <table class="table table-bordered table-sm mt-2">
        <thead>
            <tr>
                <th>No</th>
                <th>Category Name</th>
                <th>Category Image</th>
                <th>Category Status</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody id="category_list">

        </tbody>
    </table>

    <div class="modal fade" id="editModalImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                    <div class="form-group">
                        <label for="ExcelCategory">Bulk Image Upload</label>
                        <input type="file" class="form-control"  id="BulkImage">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="Submit_Excel_btn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editModalExcel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                    <div class="form-group">
                        <label for="ExcelCategory">Excel File</label>
                        <input type="file" class="form-control"  id="ExcelCategory">
                    </div>

                    <div class="form-group mt-3">
                        <a href="#" class="btn btn-secondary" data-bs-dismiss="modal">Excel Format Download</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="Submit_Excel_btn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <input type="hidden" name="_token" id="csrf" value="{{ Session::token() }}">
                    <div class="form-group">
                        <label for="edit_category">Category Name</label>
                        <input type="text" class="form-control" id="edit_category">
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" class="form-control">
                            <option value="101">Active</option>
                            <option value="102">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Image</label>
                        <input type="file" class="form-control" id="edit_image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="update_category_btn" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#CategoryFormData').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('category.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response.success);
                        loadCategories();
                        $('#CategoryFormData')[0].reset();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            let errorMessage = '';
                            $.each(errors, function(key, value) {
                                errorMessage += value + '\n';
                            });
                            alert(errorMessage);
                        }
                    }
                });
            });

            function loadCategories() {
                $.ajax({
                    url: "{{ route('category.fetch') }}",
                    type: "GET",
                    success: function(response) {
                        var html = '';
                        response.data.forEach(function(category) {
                            html += '<tr>';
                            html += '<td>' + category.id + '</td>';
                            html += '<td>' + category.name + '</td>';
                            html += '<td>' + (category.status == '101' ? 'Active' :
                                'Inactive') + '</td>';

                            if (category.image) {

                                let imageUrl = "{{ asset('categoryimage/') }}/" + category
                                    .image;
                                html += '<td><img src="' + imageUrl +
                                    '" width="100" alt="Category Image"></td>';
                            } else {
                                html += '<td>No Image</td>';
                            }

                            html += '<td>';
                            html += '<button class="btn btn-success edit_btn" data-id="' +
                                category.id + '">Edit</button> ';
                            html += '<button class="btn btn-danger delete_btn" data-id="' +
                                category.id + '">Delete</button>';
                            html += '</td>';
                            html += '</tr>';
                        });
                        $('#category_list').html(html);
                    }
                });
            }

            loadCategories();
            $(document).on('click', '.edit_btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ route('category.edit') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#edit_id').val(response.id);
                        $('#edit_category').val(response.name);
                        $('#edit_status').val(response.status);
                        $('#editModal').modal('show');
                    }
                });
            });

            $('#update_category_btn').on('click', function() {
                var id = $('#edit_id').val();
                var category = $('#edit_category').val();
                var status = $('#edit_status').val();
                var image = $('#edit_image')[0].files[0];

                var formData = new FormData();
                formData.append("_token", $("#csrf").val());
                formData.append("category", category);
                formData.append("status", status);
                formData.append("image", image);
                formData.append("id", id);

                $.ajax({
                    url: "{{ route('category.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.statusCode == 200) {
                            loadCategories();
                            $('#editModal').modal('hide');
                            alert(response.message);
                        } else {
                            alert("Error occurred while updating!");
                        }
                    }
                });
            });

            $('#Submit_Excel_btn').on('click', function() {
                var formData = new FormData();
                var ExcelCategory = $('#ExcelCategory')[0].files[0];

                if (ExcelCategory) {
                    formData.append('ExcelCategory', ExcelCategory);
                }
                formData.append('_token', $('#csrf').val());

                $.ajax({
                    url: "{{ route('excel.category') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.statusCode === 200) {
                            alert(response.message || response.data);
                            loadCategories(); // Function to refresh categories list
                            $('#editModalExcel').modal('hide');
                        } else {
                            alert('Unexpected response status: ' + response.statusCode);
                        }
                    },
                    error: function(xhr) {
                        // Handle general AJAX errors
                        let errorMessage = 'An error occurred: ' + xhr.status + ' ' + xhr
                            .statusText;

                        if (xhr.responseJSON && xhr.responseJSON.data) {
                            errorMessage = xhr.responseJSON.data;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += value + '\n';
                            });
                        }

                        alert(errorMessage);
                    }
                });
            });


            $(document).on('click', '.delete_btn', function() {
                var id = $(this).data('id');

                if (confirm("Are you sure you want to delete this category?")) {
                    $.ajax({
                        url: "{{ route('category.delete') }}",
                        type: "POST",
                        data: {
                            _token: $("#csrf").val(),
                            id: id
                        },
                        success: function(response) {
                            if (response.statusCode == 200) {
                                loadCategories();
                            } else {
                                alert("Error occurred while deleting!");
                            }
                        }
                    });
                }
            });
            $('#bulkUploadBtn').click(function() {
                var myModal = new bootstrap.Modal($('#editModalExcel'));
                myModal.show();
            });
            $('#bulkImageBtn').click(function() {
                var myModal = new bootstrap.Modal($('#editModalImage'));
                myModal.show();
            });
        });
    </script>


@stop
