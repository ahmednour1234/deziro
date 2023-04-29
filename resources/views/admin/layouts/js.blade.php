<!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>



    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="{{ asset('https://buttons.github.io/buttons.js') }}"></script>
    {{-- <script src="jquery-3.6.1.min.js"></script> --}}
    <script src="{{ asset('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js') }}"></script>



{{--
    <script src="{{ asset(' https://code.jquery.com/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js') }}"></script> --}}

    <script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js') }}"></script>

{{-- <script src="{{ asset('cdn.datatables.net/plug-ins/1.13.1/sorting/numeric-comma.js') }}"></script> --}}

<!--select2-->
<script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js') }}"></script>


<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js') }}"></script>

<script src="{{ asset('//cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js') }}"></script>



<script src="{{ asset('https://raw.githack.com/SortableJS/Sortable/master/Sortable.js') }}"></script>
<script src="{{ asset('https://code.jquery.com/ui/1.12.1/jquery-ui.js') }}"></script>


<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js') }}"></script>

<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

@yield('scripts')
