@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <div class="row g-25 mb-2">
                        <div class="d-flex flex-row gap-2">
                            <div class="flex-fill w-100">
                                <a class="btn btn-success"href="{{ route('project.create') }}" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16"
                                        height="16" fill="currentColor" aria-hidden="true">
                                        <!-- Font Awesome Free 6.7.2: Plus Icon -->
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32v144H48c-17.7 0-32 14.3-32 32s14.3 32 32 32h144v144c0 17.7 14.3 32 32 32s32-14.3 32-32V288h144c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Create New Project
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5">
                        <div class="d-flex flex-row gap-2">
                            <div class="flex-fill w-100">
                                <label for="work_type" class="form-label">Work Type</label>
                                <select class="form-select" id="work_type" name="work_type">
                                    <option value="All" {{ request()->get('work_type') == 'All' ? 'selected' : '' }}>
                                        All</option>
                                    @foreach ($work_type as $d)
                                        <option value="{{ $d->work_name }}"
                                            {{ request()->get('work_type') == $d->work_name ? 'selected' : '' }}>
                                            {{ $d->work_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-fill w-100">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select flex-fill" id="status" name="status">
                                    <option value="All" {{ request()->get('status') == 'All' ? 'selected' : '' }}>
                                        All</option>
                                    <option value="Draft" {{ request()->get('status') == 'Draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="Pra-tender"
                                        {{ request()->get('status') == 'Pra-tender' ? 'selected' : '' }}>
                                        Pra-tender</option>
                                    <option value="Submitted"
                                        {{ request()->get('status') == 'Submitted' ? 'selected' : '' }}>
                                        Submitted</option>
                                    <option value="Under Review"
                                        {{ request()->get('status') == 'Under Review' ? 'selected' : '' }}>
                                        Under Review</option>
                                    <option value="Shortlisted"
                                        {{ request()->get('status') == 'Shortlisted' ? 'selected' : '' }}>
                                        Shortlisted</option>
                                    <option value="Negotiation"
                                        {{ request()->get('status') == 'Negotiation' ? 'selected' : '' }}>
                                        Negotiation</option>
                                    <option value="Awarded" {{ request()->get('status') == 'Awarded' ? 'selected' : '' }}>
                                        Awarded</option>
                                    <option value="Contract Signed"
                                        {{ request()->get('status') == 'Contract Signed' ? 'selected' : '' }}>
                                        Contract Signed</option>
                                    <option value="Planning"
                                        {{ request()->get('status') == 'Planning' ? 'selected' : '' }}>
                                        Planning</option>
                                    <option value="In Progress"
                                        {{ request()->get('status') == 'In Progress' ? 'selected' : '' }}>
                                        In Progress</option>
                                    <option value="On Hold" {{ request()->get('status') == 'On Hold' ? 'selected' : '' }}>
                                        On Hold</option>
                                    <option value="Delayed" {{ request()->get('status') == 'Delayed' ? 'selected' : '' }}>
                                        Delayed</option>
                                    <option value="Cancelled"
                                        {{ request()->get('status') == 'Cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="Completed"
                                        {{ request()->get('status') == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="Closed" {{ request()->get('status') == 'Closed' ? 'selected' : '' }}>
                                        Closed</option>
                                </select>
                            </div>
                            <div class="flex-fill w-100">
                                <label for="search" class="form-label">Project ID</label>
                                <input type="text" id="search" name="search" class="form-control" placeholder=""
                                    value="{{ request()->get('search') }}">
                            </div>
                            <div class="flex-fill" style="width: 22%">
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
                                        <th style="width: 11%">Project ID</th>
                                        <th>Project Name</th>
                                        <th>Customer</th>
                                        <th>Work Type</th>
                                        <th>Start At</th>
                                        <th>Finished At</th>
                                        <th style="width: 10%">Status</th>
                                        <th class="text-end" style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @if ($project->count() == 0)
                                        <tr>
                                            <td colspan="8" class="text-center">No data displayed</td>
                                        </tr>
                                    @else
                                        @foreach ($project as $d)
                                            <tr>
                                                <td>
                                                    <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                                        href="{{ route('project.show', $d->id) }}">{{ $d->proj_number }}</a>
                                                </td>
                                                <td>{{ $d->proj_name }}</td>
                                                <td>{{ $d->proj_customer }}</td>
                                                <td>{{ $d->work_type->work_name }}</td>
                                                <td>{{ $d->proj_start_date ? \Carbon\Carbon::parse($d->proj_start_date)->format('d M Y') : '-' }}
                                                </td>
                                                <td>{{ $d->proj_finished_date ? \Carbon\Carbon::parse($d->proj_finished_date)->format('d M Y') : '-' }}
                                                </td>
                                                <td>{{ $d->proj_status }}
                                                    {{ $d->proj_status == 'Pra-tender' && $d->project_survey->projsur_status == 'Done' ? ' - Done' : '' }}
                                                </td>
                                                <td class="text-end">
                                                    @if (!in_array($d->proj_status, ['Cancelled', 'Closed']))
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
                                                                            href="{{ route('project.edit', $d->id) }}">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                    @if ($d->proj_status == 'Draft')
                                                                        <li>
                                                                            <form class="d-inline"
                                                                                action="{{ route('project.update.status', $d->id) }}"
                                                                                method="POST"
                                                                                id="form-request{{ $d->id }}">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <input type="hidden"
                                                                                    id="proj_status{{ $d->id }}"
                                                                                    name="proj_status">
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-id="{{ $d->id }}"
                                                                                    onclick="update_status('Request Survey', {{ $d->id }}); return false;">
                                                                                    Request Survey
                                                                                </a>
                                                                            </form>
                                                                        </li>
                                                                        <li>
                                                                            <form class="d-inline"
                                                                                action="{{ route('project.destroy', $d->id) }}"
                                                                                method="POST"
                                                                                id="form-delete{{ $d->id }}">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-id="{{ $d->id }}"
                                                                                    onclick="delete_data({{ $d->id }}); return false;">
                                                                                    Delete
                                                                                </a>
                                                                            </form>
                                                                        </li>
                                                                    @elseif($d->proj_status == 'Pra-tender')
                                                                        @if ($d->project_survey->projsur_status == 'Done')
                                                                            <li>
                                                                                <form class="d-inline"
                                                                                    action="{{ route('project.update.status', $d->id) }}"
                                                                                    method="POST"
                                                                                    id="form-request{{ $d->id }}">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <input type="hidden"
                                                                                        id="proj_status{{ $d->id }}"
                                                                                        name="proj_status">
                                                                                    <a class="dropdown-item"
                                                                                        href="#"
                                                                                        data-id="{{ $d->id }}"
                                                                                        onclick="update_status('Request Offer Letter', {{ $d->id }}); return false;">
                                                                                        Request Offer Letter
                                                                                    </a>
                                                                                </form>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    <li>
                                                                        <form class="d-inline"
                                                                            action="{{ route('project.cancel', $d->id) }}"
                                                                            method="POST"
                                                                            id="form-cancel{{ $d->id }}">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <input type="hidden"
                                                                                id="cancel-message{{ $d->id }}"
                                                                                name="message">
                                                                            <a class="dropdown-item" href="#"
                                                                                data-id="{{ $d->id }}"
                                                                                onclick="cancel({{ $d->id }}); return false;">
                                                                                Cancel
                                                                            </a>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <nav>
                                {{ $project->links('pagination::bootstrap-5') }}
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
            $('#work_type').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            }).on('change', function() {
                search();
            });

            $('#status').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
            }).on('change', function() {
                search();
            });

            $("#show").on('change', function() {
                search()
            })
        });

        function search() {
            var url =
                `{!! route('project.index', [
                    'work_type' => '_work_type',
                    'status' => '_status',
                    'search' => '_search',
                    'show' => '_show',
                ]) !!}`
            url = url.replace('_work_type', $("#work_type").val())
            url = url.replace('_status', $("#status").val())
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

        function update_status(status, id) {
            Swal.fire({
                title: "Are you sure?",
                text: "The process will enter " + status + "!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('proj_status' + id).value = status;
                    document.getElementById('form-request' + id).submit();
                }
            });
        }

        function cancel(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "The process will be cancelled",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, cancel it!",
                input: "textarea",
                inputPlaceholder: "Type your message here...",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-message' + id).value = result.value;
                    document.getElementById('form-cancel' + id).submit();
                }
            });
        }
    </script>
@endpush
