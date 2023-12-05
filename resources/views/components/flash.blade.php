@if ($message = Session::get('success'))
    <script>
        toastr.success("{{ Session::get('success') }}");
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        toastr.error("{{ Session::get('error') }}");
    </script>
@endif
