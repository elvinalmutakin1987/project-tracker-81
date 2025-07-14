@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('setting') }}">Setting</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brand</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <div class="row g-25 mb-2">
                        <div class="d-flex flex-row gap-2">
                            <div class="flex-fill w-100">
                                <a class="btn btn-success"href="{{ route('brand.create') }}" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16"
                                        height="16" fill="currentColor" aria-hidden="true">
                                        <!-- Font Awesome Free 6.7.2: Plus Icon -->
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32v144H48c-17.7 0-32 14.3-32 32s14.3 32 32 32h144v144c0 17.7 14.3 32 32 32s32-14.3 32-32V288h144c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Create New Brand
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5">
                        <div class="d-flex flex-row gap-2">
                            <div class="flex-fill w-100">
                                <label for="search" class="form-label">Name</label>
                                <input type="text" id="search" name="search" class="form-control" placeholder=""
                                    value="{{ request()->get('search') }}">
                            </div>
                            <div class="flex-fill" style="width: 8%">
                                <label for="show" class="form-label">Show</label>
                                <select class="form-select" id="show" name="show">
                                    {{-- <option value="5" {{ request()->get('show') == '5' ? 'selected' : '' }}>
                                        5
                                    </option> --}}
                                    <option value="10" {{ request()->get('show') == '10' ? 'selected' : '' }}>
                                        10
                                    </option>
                                    <option value="25" {{ request()->get('show') == '25' ? 'selected' : '' }}>
                                        25
                                    </option>
                                    <option value="50" {{ request()->get('show') == '50' ? 'selected' : '' }}>
                                        50
                                    </option>
                                    <option value="100" {{ request()->get('show') == '100' ? 'selected' : '' }}>
                                        100
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
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
                                    @if ($brand->count() == 0)
                                        <tr>
                                            <td colspan="100%" class="text-center">No data displayed</td>
                                        </tr>
                                    @else
                                        @foreach ($brand as $d)
                                            <tr>
                                                <td>{{ $d->brand_name }}</td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group"
                                                        aria-label="Button group with nested dropdown">
                                                        <div class="btn-group" role="group">
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('brand.edit', $d->id) }}">
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form class="d-inline"
                                                                        action="{{ route('brand.destroy', $d->id) }}"
                                                                        method="POST" id="form-delete{{ $d->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a class="dropdown-item" href="#"
                                                                            data-id="{{ $d->id }}"
                                                                            onclick="delete_data({{ $d->id }}); return false;">
                                                                            Delete
                                                                        </a>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <nav>
                                {{ $brand->links('pagination::bootstrap-5') }}
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
                `{!! route('brand.index', [
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
