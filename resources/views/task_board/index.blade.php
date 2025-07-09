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
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Pre Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sales Admin</a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col">
                            <table id="table1" class="table table-striped mt-3 table-sm">
                                <thead class="table-group-divider">
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-end" style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                </tbody>
                            </table>
                            <nav>
                                {{-- {{ $project_->links('pagination::bootstrap-5') }} --}}
                            </nav>
                        </div>
                    </div>
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
            $("#show").on('change', function() {
                search()
            })
        });

        function search() {
            var url =
                `{!! route('task_board.index', [
                    'search' => '_search',
                    'show' => '_show',
                ]) !!}`
            url = url.replace('_search', $("#search").val())
            url = url.replace('_show', $("#show").val())
            window.open(url, '_self')
        }

        $(document).keyup(function(e) {
            if ($("#search").is(":focus") && (e.keyCode == 13)) {
                search()
            }
        });

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
    </script>
@endpush
