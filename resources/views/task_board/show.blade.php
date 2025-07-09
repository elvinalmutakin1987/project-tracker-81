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
                    <li class="breadcrumb-item"><a href="{{ route('task_board.index', ['assignee' => $assignee]) }}">Task
                            Board</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <div class="card">
                        <div class="card-header">
                            Project Detail
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold table-light" style="width: 20%">
                                            Project ID</td>
                                        <td>{{ $project->proj_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Project Name</td>
                                        <td>{{ $project->proj_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Customer</td>
                                        <td>{{ $project->proj_customer }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Work Type</td>
                                        <td>{{ $project->proj_work_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Project Leader</td>
                                        <td>{{ $project->proj_leader }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Start Date</td>
                                        <td>{{ $project->proj_start_date ? \Carbon\Carbon::parse($project->proj_start_date)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Finished Date</td>
                                        <td>{{ $project->proj_finished_date ? \Carbon\Carbon::parse($project->proj_finished_date)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Notes</td>
                                        <td>{!! $project->proj_notes !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Document</td>
                                        <td style="padding:0">
                                            <table class="table table-bordered" style="margin:0;">
                                                <tr>
                                                    <th class="table-light" style="width:25%">Type</th>
                                                    <th class="table-light" style="width:12%">Status</th>
                                                    <th class="table-light">Link</th>
                                                </tr>
                                                <tr>
                                                    <td>Denah</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_denah == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-check"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where('file_doc_type', 'Denah')
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Shop Drawing</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_shop == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-check"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where(
                                                                'file_doc_type',
                                                                'Shop Drawing',
                                                            )
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>SLD/Topology</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_sld == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-check"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where(
                                                                'file_doc_type',
                                                                'SLD/Topology',
                                                            )
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>RAB/BOQ/Budget</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_rab == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-check"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where(
                                                                'file_doc_type',
                                                                'RAB/BOQ/Budger',
                                                            )
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Personil</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_personil == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    class="bi bi-check" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where(
                                                                'file_doc_type',
                                                                'Peronil',
                                                            )
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Schedule</td>
                                                    <td>
                                                        @if ($project->project_survey->projsur_schedule == '1')
                                                            <span class="badge text-bg-success rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    class="bi bi-check" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-danger rounded-pill">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $file_upload = File_upload::where(
                                                                'file_doc_type',
                                                                'Schedule',
                                                            )
                                                                ->where('file_table', 'project_survey')
                                                                ->where('file_table_id', $project->project_survey->id)
                                                                ->get();
                                                        @endphp
                                                        <table class="w-100">
                                                            @foreach ($file_upload as $d)
                                                                <tr>
                                                                    <td>
                                                                        <a href="">{{ $d->file_real_name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-secondary"
                                href="{{ route('task_board.index', ['assignee' => $assignee]) }}" role="button">Back</a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {
            $('#proj_work_type').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });
        });
    </script>
@endpush
