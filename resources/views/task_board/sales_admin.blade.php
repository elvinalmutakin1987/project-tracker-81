@php
    use App\Models\File_upload;
@endphp

@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Task Board</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    {!! $tab !!}

                    {!! $table !!}
                </main>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        @if (session('message'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('message') }}",
                    icon: "success"
                });
            });
        @endif

        $(document).ready(function() {
            // $('#status').select2({
            //     theme: "bootstrap-5",
            //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
            //         'style',
            //     placeholder: $(this).data('placeholder'),
            // }).on('change', function() {
            //     search();
            // });

            // $('#doc_type').select2({
            //     theme: "bootstrap-5",
            //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
            //         'style',
            //     placeholder: $(this).data('placeholder'),
            // }).on('change', function() {
            //     search();
            // });

            $("#status").on('change', function() {
                search()
            })

            $("#doc_type").on('change', function() {
                search()
            })

            $("#show").on('change', function() {
                search()
            })

            $("#taker").on('change', function() {
                search()
            })
        });

        function search() {
            var url =
                `{!! route('task_board.index', [
                    'search' => '_search',
                    'status' => '_status',
                    'show' => '_show',
                    'taker' => '_taker',
                    'assignee' => 'sales-admin',
                    'doc_type' => '_doc_type',
                ]) !!}`
            url = url.replace('_search', $("#search").val())
            url = url.replace('_status', $("#status").val())
            url = url.replace('_show', $("#show").val())
            url = url.replace('_taker', $("#taker").val())
            url = url.replace('_doc_type', $("#doc_type").val())
            window.open(url, '_self')
        }

        $(document).keyup(function(e) {
            if ($("#search").is(":focus") && (e.keyCode == 13)) {
                search()
            }
        });

        function take_task(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Taking this task will trigger the timer!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, take it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-take' + id).submit();
                }
            });
        }

        function hold(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Timer continues even when on hold!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!",
                input: "textarea",
                inputPlaceholder: "Type your message here...",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('hold-message' + id).value = result.value;
                    document.getElementById('form-hold' + id).submit();
                }
            });
        }

        function approval(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Great job!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hold' + id + 'approval').submit();
                }
            });
        }

        function finish(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Make sure all the requested documents are uploaded!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!",
                input: "text",
                @if ($doc_type == 'quotation')
                    inputPlaceholder: "Type offer number here...",
                @elseif ($doc_type == 'sales-order')
                    inputPlaceholder: "Type sales order number here...",
                @elseif ($doc_type == 'work-order')
                    inputPlaceholder: "Type work order number here...",
                @endif
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value == '') {
                        Swal.fire({
                            title: "Required Field!",
                            text: "Please fill out this field",
                            icon: "warning"
                        });
                        return false;
                    }
                    document.getElementById('finish-message' + id).value = result.value;
                    document.getElementById('form-finish' + id).submit();
                }
            });
        }

        function continue_(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Good job!",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-continue' + id).submit();
                }
            });
        }

        @if (Auth::user()->hasRole('superadmin'))
            function cancel(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "The process will be cancelled",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, cancel it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-cancel' + id).submit();
                    }
                });
            }

            function delete_data(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Once deleted, you won't be able to recover this record!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-delete' + id).submit();
                    }
                });
            }
        @endif
    </script>
@endpush
