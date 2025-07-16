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
                                        <td>{{ $project->customer->cust_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Email</td>
                                        <td>{{ $project->proj_email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Phone</td>
                                        <td>{{ $project->proj_phone }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">PIC Sales</td>
                                        <td>{{ $project->proj_pic }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Work Type</td>
                                        <td>{{ $project->work_type->work_name }}</td>
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
                                        <td class="fw-bold table-light">Pre Sales</td>
                                        <td style="padding:0">
                                            <table class="table table-borderless" style="margin:0;">
                                                <tr>
                                                    <th class="table-light" style="width: 15%">Document</th>
                                                    <th class="table-light" style="width: 10%">Required</th>
                                                    <th class="table-light" style="width: 10%">Status</th>
                                                    <th class="table-light" style="width: 25%">File</th>
                                                    <th class="table-light" style="width: 50%">Link</th>
                                                </tr>
                                                @php
                                                    $doc_type = [
                                                        'Denah',
                                                        'Shop Drawing',
                                                        'SLD/Topology',
                                                        'RAB/BOQ/Budget',
                                                        'Personil',
                                                        'Schedule',
                                                    ];
                                                @endphp
                                                @foreach ($doc_type as $type)
                                                    @php
                                                        $required = 0;
                                                    @endphp
                                                    <tr>
                                                        <td style="vertical-align: top">
                                                            {{ $type }}
                                                        </td>
                                                        <td style="vertical-align: top">
                                                            @if ($type == 'Denah' && $project->proj_denah == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @elseif($type == 'Shop Drawing' && $project->proj_shop == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @elseif($type == 'SLD/Topology' && $project->proj_sld == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @elseif($type == 'RAB/BOQ/Budget' && $project->proj_rab == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @elseif($type == 'Personil' && $project->proj_personil == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @elseif($type == 'Schedule' && $project->proj_schedule == 1)
                                                                <span class="badge text-bg-success rounded-pill">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" fill="currentColor"
                                                                        class="bi bi-check" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                    </svg>
                                                                </span>
                                                                @php $required = 1 @endphp
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: top">
                                                            @php
                                                                $require = 0;
                                                                $doc_status = 0;
                                                                if (
                                                                    $type == 'Denah' &&
                                                                    $project->project_survey->projsur_denah == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                } elseif (
                                                                    $type == 'Shop Drawing' &&
                                                                    $project->project_survey->projsur_shop == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                } elseif (
                                                                    $type == 'SLD/Topology' &&
                                                                    $project->project_survey->projsur_sld == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                } elseif (
                                                                    $type == 'RAB/BOQ/Budget' &&
                                                                    $project->project_survey->projsur_rab == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                } elseif (
                                                                    $type == 'Personil' &&
                                                                    $project->project_survey->projsur_personil == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                } elseif (
                                                                    $type == 'Schedule' &&
                                                                    $project->project_survey->projsur_schedule == '1'
                                                                ) {
                                                                    $doc_status = 1;
                                                                }
                                                            @endphp
                                                            @if ($required == 1)
                                                                @if ($doc_status == 1)
                                                                    <span class="badge text-bg-success rounded-pill">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            fill="currentColor" class="bi bi-check"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                                        </svg>
                                                                    </span>
                                                                @else
                                                                    <span class="badge text-bg-danger rounded-pill">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="16" height="16"
                                                                            fill="currentColor" class="bi bi-x"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: top">
                                                            @if ($project->project_survey)
                                                                @php
                                                                    $file_upload = File_upload::where(
                                                                        'file_doc_type',
                                                                        $type,
                                                                    )
                                                                        ->where('file_table', 'project_survey')
                                                                        ->where(
                                                                            'file_table_id',
                                                                            $project->project_survey->id,
                                                                        )
                                                                        ->get();
                                                                @endphp
                                                                <table class="w-100">
                                                                    @if ($file_upload)
                                                                        @foreach ($file_upload as $d)
                                                                            @if ($d->file_real_name)
                                                                                <tr>
                                                                                    <td>
                                                                                        <table class="w-100">
                                                                                            <tr>
                                                                                                <td class="align-top"
                                                                                                    style="width: 25px">
                                                                                                    @if ($project->project_survey->projsur_status != 'Done' && $project->project_survey->user_id == auth()->user()->id)
                                                                                                        <form
                                                                                                            class="d-inline"
                                                                                                            action="{{ route('task_board.document_remove', $d->id) }}"
                                                                                                            method="POST"
                                                                                                            id="form-delete{{ $d->id }}">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <input
                                                                                                                type="hidden"
                                                                                                                name="assignee"
                                                                                                                value="pre-sales">
                                                                                                            <a href=""
                                                                                                                onclick="document_remove({{ $d->id }}); return false;">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                                    width="16"
                                                                                                                    height="16"
                                                                                                                    fill="currentColor"
                                                                                                                    class="bi bi-trash"
                                                                                                                    viewBox="0 0 16 16"
                                                                                                                    style="color:red">
                                                                                                                    <path
                                                                                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                                                                    <path
                                                                                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                                                                </svg>
                                                                                                            </a>
                                                                                                        </form>
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td class="align-top">
                                                                                                    <a href="{{ route('task_board.document_download', $d->id) }}"
                                                                                                        target="_blank">{{ $d->file_real_name }}</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </table>
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: top">
                                                            @if ($project->project_survey)
                                                                @php
                                                                    $file_upload = File_upload::where(
                                                                        'file_doc_type',
                                                                        $type,
                                                                    )
                                                                        ->where('file_table', 'project_survey')
                                                                        ->where(
                                                                            'file_table_id',
                                                                            $project->project_survey->id,
                                                                        )
                                                                        ->get();
                                                                @endphp
                                                                <table class="w-100">
                                                                    @foreach ($file_upload as $d)
                                                                        @if ($d->file_link)
                                                                            <tr>
                                                                                <td>
                                                                                    <table class="w-100">
                                                                                        <tr>
                                                                                            <td class="align-top"
                                                                                                style="width:25px">
                                                                                                @if ($project->project_survey->projsur_status != 'Done' && $project->project_survey->user_id == auth()->user()->id)
                                                                                                    <form class="d-inline"
                                                                                                        action="{{ route('task_board.link_remove', $d->id) }}"
                                                                                                        method="POST"
                                                                                                        id="form-delete{{ $d->id }}link">
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            name="assignee"
                                                                                                            value="pre-sales">
                                                                                                        <a href=""
                                                                                                            onclick="link_remove({{ $d->id }}); return false;">
                                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                                width="16"
                                                                                                                height="16"
                                                                                                                fill="currentColor"
                                                                                                                class="bi bi-trash"
                                                                                                                viewBox="0 0 16 16"
                                                                                                                style="color:red">
                                                                                                                <path
                                                                                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                                                                <path
                                                                                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                                                            </svg>
                                                                                                        </a>
                                                                                                    </form>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                <a href="{{ $d->file_link }}"
                                                                                                    target="_blank">{{ $d->file_link }}</a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </table>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold table-light">Sales Admin</td>
                                        <td style="padding:0">
                                            <table class="table table-borderless" style="margin:0;">
                                                <tr>
                                                    <th class="table-light" style="width: 15%">Document</th>
                                                    <th class="table-light" style="width: 10%">Required</th>
                                                    <th class="table-light" style="width: 10%">Status</th>
                                                    <th class="table-light" style="width: 25%">File</th>
                                                    <th class="table-light" style="width: 50%">Link</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Quotation
                                                    </td>
                                                    <td><span class="badge text-bg-success rounded-pill">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-check"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                            </svg>
                                                        </span></td>
                                                    <td>
                                                        @if (isset($project->project_offer->projoff_offer_number))
                                                            {{ $project->project_offer->projoff_offer_number }}
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
                                                        @if ($project->project_offer)
                                                            @php
                                                                $file_upload = File_upload::where(
                                                                    'file_doc_type',
                                                                    'Sales Quotation',
                                                                )
                                                                    ->where('file_table', 'project_offer')
                                                                    ->where(
                                                                        'file_table_id',
                                                                        $project->project_offer->id,
                                                                    )
                                                                    ->get();
                                                            @endphp
                                                            @if ($file_upload)
                                                                @foreach ($file_upload as $d)
                                                                    @if ($d->file_real_name)
                                                                        <table class="w-100">
                                                                            <tr>
                                                                                <td class="align-top" style="width: 25px">
                                                                                    @if ($project->project_offer->projoff_status != 'Done' && $project->project_offer->user_id == auth()->user()->id)
                                                                                        <form class="d-inline"
                                                                                            action="{{ route('task_board.document_remove', $d->id) }}"
                                                                                            method="POST"
                                                                                            id="form-delete{{ $d->id }}">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <input type="hidden"
                                                                                                name="assignee"
                                                                                                value="pre-sales">
                                                                                            <a href=""
                                                                                                onclick="document_remove({{ $d->id }}); return false;">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                    width="16"
                                                                                                    height="16"
                                                                                                    fill="currentColor"
                                                                                                    class="bi bi-trash"
                                                                                                    viewBox="0 0 16 16"
                                                                                                    style="color:red">
                                                                                                    <path
                                                                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                                                    <path
                                                                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                                                </svg>
                                                                                            </a>
                                                                                        </form>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="align-top">
                                                                                    <a href="{{ route('task_board.document_download', $d->id) }}"
                                                                                        target="_blank">{{ $d->file_real_name }}</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($file_upload)
                                                            @foreach ($file_upload as $d)
                                                                @if ($d->file_link)
                                                                    <table class="w-100">
                                                                        <tr>
                                                                            <td class="align-top" style="width: 25px">
                                                                                @if ($project->project_offer->projoff_status != 'Done' && $project->project_offer->user_id == auth()->user()->id)
                                                                                    <form class="d-inline"
                                                                                        action="{{ route('task_board.link_remove', $d->id) }}"
                                                                                        method="POST"
                                                                                        id="form-delete{{ $d->id }}link">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <input type="hidden"
                                                                                            name="assignee"
                                                                                            value="pre-sales">
                                                                                        <a href=""
                                                                                            onclick="link_remove({{ $d->id }}); return false;">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                width="16"
                                                                                                height="16"
                                                                                                fill="currentColor"
                                                                                                class="bi bi-trash"
                                                                                                viewBox="0 0 16 16"
                                                                                                style="color:red">
                                                                                                <path
                                                                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                                                                <path
                                                                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                                                            </svg>
                                                                                        </a>
                                                                                    </form>
                                                                                @endif
                                                                            </td>
                                                                            <td class="align-top">
                                                                                <a href="{{ $d->file_link }}"
                                                                                    target="_blank">{{ $d->file_link }}</a>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Sales Order
                                                    </td>
                                                    <td><span class="badge text-bg-success rounded-pill">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-check"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                            </svg>
                                                        </span></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Work Order (SPK)
                                                    </td>
                                                    <td><span class="badge text-bg-success rounded-pill">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-check"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                                            </svg>
                                                        </span></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
            $('#proj_work_type').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });
        });

        function document_remove(id) {
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

        function link_remove(id) {
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
                    document.getElementById('form-delete' + id + 'link').submit();
                }
            });
        }
    </script>
@endpush
