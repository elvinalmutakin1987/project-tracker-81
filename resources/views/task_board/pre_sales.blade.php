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
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="{{ route('task_board.index', ['assignee' => 'pre-sales']) }}">Pre Sales
                                @if ($project_survey->count() > 0)
                                    <span class="badge text-bg-warning rounded-pill">{{ $project_survey->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('task_board.index', ['assignee' => 'sales-admin']) }}">Sales
                                Admin
                                @if ($project_offer->count() > 0)
                                    <span class="badge text-bg-primary rounded-pill">{{ $project_offer->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('task_board.index', ['assignee' => 'operation']) }}">Operation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('task_board.index', ['finance-accounting' => 'operation']) }}">Finance
                                & Accounting</a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col">
                            <table id="table1" class="table table-striped mt-3 table-sm">
                                <thead class="table-group-divider">
                                    <tr>
                                        <th>Project ID</th>
                                        <th>Project Name</th>
                                        <th>Customer</th>
                                        <th>Taken By</th>
                                        <th>Start At</th>
                                        <th>Status</th>
                                        <th>Aging</th>
                                        <th class="text-end" style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @if ($project_survey->count() == 0)
                                        <tr>
                                            <td colspan="8" class="text-center">No data displayed</td>
                                        </tr>
                                    @else
                                        @foreach ($project_survey as $d)
                                            <tr>
                                                <td><a
                                                        href="{{ route('task_board.show', $d->project_id) }}">{{ $d->project->proj_number }}</a>
                                                </td>
                                                <td>{{ $d->project->proj_name }}</td>
                                                <td>{{ $d->project->proj_customer }}</td>
                                                <td>{{ $d->user->username ?? '-' }}</td>
                                                <td>{{ $d->projsur_started_at ?? '-' }}</td>
                                                <td>{{ $d->projsur_status }}</td>
                                                <td>
                                                    @if (in_array($d->projsur_status, ['Started', 'Hold']))
                                                        @php
                                                            $now = \Carbon\Carbon::now();
                                                            $started_at = \Carbon\Carbon::parse($d->projsur_started_at);
                                                            $aging = '-';
                                                            if ($d->projsur_started_at) {
                                                                $diffInSeconds = $started_at->diffInSeconds($now);
                                                                $hours = floor($diffInSeconds / 3600);
                                                                $minutes = floor(($diffInSeconds % 3600) / 60);
                                                                $aging = sprintf('%02d:%02d', $hours, $minutes);
                                                            }
                                                        @endphp
                                                        {{ $aging }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if ($d->projsur_status == 'Open')
                                                        <form class="d-inline"
                                                            action="{{ route('task_board.take_survey', $d->id) }}"
                                                            method="POST" id="form-take{{ $d->id }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <a class="btn btn-warning btn-sm" href="#" role="button"
                                                                onclick="take_task({{ $d->id }}); return false;">Pick
                                                                Up</a>
                                                        </form>
                                                    @else
                                                        @if ($d->user_id == auth()->user()->id)
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
                                                                                href="{{ route('task_board.document_survey', $d->id) }}">
                                                                                Document Upload
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <form class="d-inline"
                                                                                action="{{ route('task_board.hold_survey', $d->id) }}"
                                                                                method="POST"
                                                                                id="form-hold{{ $d->id }}">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <input type="hidden"
                                                                                    id="hold-message{{ $d->id }}"
                                                                                    name="message">
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-id="{{ $d->id }}"
                                                                                    onclick="hold({{ $d->id }}); return false;">
                                                                                    Hold
                                                                                </a>
                                                                            </form>

                                                                            <form class="d-inline"
                                                                                action="{{ route('task_board.finish_survey', $d->id) }}"
                                                                                method="POST"
                                                                                id="form-finish{{ $d->id }}">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <input type="hidden"
                                                                                    id="finih-message{{ $d->id }}"
                                                                                    name="message">
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-id="{{ $d->id }}"
                                                                                    onclick="finish({{ $d->id }}); return false;">
                                                                                    Finish
                                                                                </a>
                                                                            </form>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        @else
                                                            Already Taken
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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

        function take_task(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Taking this task will trigger the timer!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
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
                confirmButtonColor: "#d33",
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

        function finish(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Make sure all the requested documents are uploaded!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, do it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-finish' + id).submit();
                }
            });
        }
    </script>
@endpush
