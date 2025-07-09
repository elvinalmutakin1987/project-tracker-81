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
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('project.update', $project->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header" style="font-weight: bold">
                                {{ $project->proj_number }}
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="proj_name" class="col-sm-3 col-form-label">Project Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('proj_name') is-invalid @enderror"
                                            name="proj_name" value="{{ old('proj_name') ?? $project->proj_name }}" required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_customer" class="col-sm-3 col-form-label">Customer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proj_customer" required
                                            value="{{ old('prod_customer') ?? $project->proj_customer }}">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="work_type_id" class="col-sm-3 col-form-label">Work Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="work_type_id" name="work_type_id" required>
                                            @foreach ($work_type as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('work_type_id') == $d->id || $project->work_type_id == $d->id ? 'selected' : '' }}>
                                                    {{ $d->work_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_leader" class="col-sm-3 col-form-label">Project Leader</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="proj_leader"
                                            value="{{ old('proj_leader') ?? $project->proj_leader }}">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_start_date" class="col-sm-3 col-form-label">Start Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="proj_start_date"
                                            value="{{ old('proj_start_date') ?? $project->proj_start_date }}">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_notes" class="col-sm-3 col-form-label">Notes</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="proj_notes" rows="3">{!! old('proj_notes') ?? $project->proj_notes !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Save
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><button class="dropdown-item" type="submit" name="proj_status"
                                                value="Draft">As
                                                Draft</button></li>
                                        <li><button class="dropdown-item" type="submit" name="proj_status"
                                                value="Pra-tender">Save
                                                To Request Survey</button></li>
                                    </ul>
                                </div>
                                <a class="btn btn-secondary" href="{{ route('project.index') }}" role="button">Back</a>
                            </div>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>
@endsection


@push('javascript')
    <script>
        $(document).ready(function() {
            $('#work_type_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });
        });
    </script>
@endpush
