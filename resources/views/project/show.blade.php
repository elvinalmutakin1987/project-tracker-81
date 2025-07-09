@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Project</a></li>
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
                                        <td class="fw-bold" style="width: 20%">
                                            Project ID</td>
                                        <td>{{ $project->proj_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Project Name</td>
                                        <td>{{ $project->proj_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Customer</td>
                                        <td>{{ $project->proj_customer }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Work Type</td>
                                        <td>{{ $project->proj_work_type }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Project Leader</td>
                                        <td>{{ $project->proj_leader }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Start Date</td>
                                        <td>{{ $project->proj_start_date ? \Carbon\Carbon::parse($project->proj_start_date)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Finished Date</td>
                                        <td>{{ $project->proj_finished_date ? \Carbon\Carbon::parse($project->proj_finished_date)->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Notes</td>
                                        <td>{!! $project->proj_notes !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Document</td>
                                        <td></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a class="btn btn-secondary" href="{{ route('project.index') }}" role="button">Back</a>
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
