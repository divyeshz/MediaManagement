@extends('layouts/layoutMaster')

@section('title', 'User List')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />

    <!-- Form Validation -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <!-- Form Validation -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection


@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Home /</span> User list
    </h4>

    <div class="card mb-4">
        <h5 class="card-header">Filter</h5>
        <form class="card-body" action="{{ route('user.list') }}" method="get">
            <div class="row g-3">
                <div class="col-md-5">
                    <select name="gender" id="selectpickerBasic" placeholder="Select Gender" class="selectpicker w-100"
                        data-style="btn-default">
                        <option value="" selected>Select Gender</option>
                        <option {{ request()->input('gender') == 'male' ? 'selected' : '' }} value="male">Male</option>
                        <option {{ request()->input('gender') == 'female' ? 'selected' : '' }} value="female">Female
                        </option>
                    </select>
                </div>
                <div class="col-md-5">
                    <select name="status" id="selectpickerBasic" placeholder="Select Status" class="selectpicker w-100"
                        data-style="btn-default">
                        <option value="" selected>Select Status</option>
                        <option {{ request()->input('status') == '1' ? 'selected' : '' }} value="1">Active</option>
                        <option {{ request()->input('status') == '0' ? 'selected' : '' }} value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary me-1">Apply</button>
                    <a href="{{ route('user.list') }}" type="reset" class="btn btn-label-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <h5 class="card-header">User List</h5>
        <div class="card-body">
            <div class="card-title header-elements">
                <div class="dataTables_length" id="DataTables_Table_0_length">
                    <select name="per_page" id="perPageSelect" aria-controls="DataTables_Table_0" class="form-select">
                        <option {{ request()->input('per_page') == '10' ? 'selected' : '' }} value="10">10</option>
                        <option {{ request()->input('per_page') == '25' ? 'selected' : '' }} value="25">25</option>
                        <option {{ request()->input('per_page') == '50' ? 'selected' : '' }} value="50">50</option>
                        <option {{ request()->input('per_page') == '75' ? 'selected' : '' }} value="75">75</option>
                        <option {{ request()->input('per_page') == '100' ? 'selected' : '' }} value="100">100</option>
                    </select>
                </div>
                <div class="card-title-elements ms-auto">
                    <input type="text" name='search' class="form-control" id="search" placeholder="Search"
                        value="{{ request()->search }}" />
                    <div class="invalid-feedback"> Please enter max 3 Character for search. </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap" id="userList">

            @include('_partials.user_list')

        </div>
    </div>
@endsection

@section('page-script')

    @include('components.flash')

    <script>
        $(document).on("click", ".switch_is_active", function() {
            const id = $(this).attr('data-id');
            let is_active = $(this).prop('checked') ? 1 : 0;
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    is_active: is_active,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                url: "{{ route('user.status') }}",
                success: function(response) {
                    if (response.status == "200") {
                        toastr.success('' + response.message + '');
                    } else {
                        toastr.error('' + response.message + '');
                    }
                }
            });
        });

        $('#perPageSelect').on('change', function() {
            let perPage = $(this).val(); // Get the selected value
            var searchValue = $('#search').val().trim();

            // AJAX call to update the records per page
            $.ajax({
                url: "{{ route('user.list') }}",
                type: "GET",
                data: {
                    per_page: perPage,
                    search: searchValue,
                    is_ajax: true
                }, // Send the perPage value to the backend
                success: function(data) {
                    $('#userList').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(error); // Log any errors for debugging
                }
            });
        });

        $(document).on('keyup', '#search', function() {
            var searchValue = $(this).val().trim();
            let perPage = $('#perPageSelect').val();
            $.ajax({
                url: "{{ route('user.list') }}",
                type: "GET",
                data: {
                    search: searchValue,
                    per_page: perPage,
                    is_ajax: true
                }, // Send the perPage value to the backend
                success: function(data) {
                    $('#userList').html(data);
                },
                error: function(xhr, status, error) {
                    console.error(error); // Log any errors for debugging
                }
            });
        });
    </script>
@endsection
