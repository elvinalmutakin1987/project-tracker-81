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
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('project.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('post')
                        <div class="card">
                            <div class="card-header">
                                Project Detail
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="proj_number" class="col-sm-3 col-form-label">Project ID</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('proj_number') is-invalid @enderror"
                                            name="proj_number" value="{{ old('proj_number') }}"
                                            placeholder="Leave blank to auto generate">
                                        @error('proj_number')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_name" class="col-sm-3 col-form-label">Project Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('proj_name') is-invalid @enderror"
                                            name="proj_name" value="{{ old('proj_name') }}" required>
                                        @error('proj_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_customer" class="col-sm-3 col-form-label">Customer</label>
                                    <div class="col-sm-9">
                                        <select class="form-select @error('proj_name') is-invalid @enderror"
                                            id="customer_id" name="customer_id" required>
                                            @foreach ($customer as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('customer_id') == $d->id ? 'selected' : '' }}>
                                                    {{ $d->cust_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_phone" class="col-sm-3 col-form-label">Phone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('proj_phone') is-invalid @enderror"
                                            name="proj_phone" required value="{{ old('proj_phone') }}">
                                        @error('proj_phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('proj_email') is-invalid @enderror"
                                            name="proj_email" required value="{{ old('proj_email') }}">
                                        @error('proj_email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_pic" class="col-sm-3 col-form-label">PIC Sales</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('proj_pic') is-invalid @enderror"
                                            name="proj_pic" required value="{{ old('proj_pic') }}">
                                        @error('proj_pic')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="work_type_id" class="col-sm-3 col-form-label">Work Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="work_type_id" name="work_type_id" required>
                                            @foreach ($work_type as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ old('work_type_id') == $d->id ? 'selected' : '' }}>
                                                    {{ $d->work_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_start_date" class="col-sm-3 col-form-label">Start Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="proj_start_date"
                                            value="{{ old('proj_start_date') }}">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="brand_id" class="col-sm-3 col-form-label">Brand</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="brand_id" name="brand_id[]" multiple>
                                            @foreach ($brand as $d)
                                                <option value="{{ $d->id }}">
                                                    {{ $d->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_notes" class="col-sm-3 col-form-label">Document Requirement</label>
                                    <div class="col-sm-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_denah"
                                                name="proj_denah">
                                            <label class="form-check-label" for="proj_denah">
                                                Denah
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_shop"
                                                name="proj_shop">
                                            <label class="form-check-label" for="proj_shop">
                                                Shop Drawing
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_sld"
                                                name="proj_sld">
                                            <label class="form-check-label" for="proj_sld">
                                                SLD/Topology
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_rab"
                                                name="proj_rab">
                                            <label class="form-check-label" for="proj_rab">
                                                RAB/BOQ/Budget
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_personil"
                                                name="proj_personil">
                                            <label class="form-check-label" for="proj_personil">
                                                Personil
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proj_schedule"
                                                name="proj_schedule">
                                            <label class="form-check-label" for="proj_schedule">
                                                Schedule
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="proj_notes" class="col-sm-3 col-form-label">Notes</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="proj_notes" rows="3">{!! old('proj_notes') !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-success dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Save
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><button class="dropdown-item" type="submit" name="proj_status"
                                                value="Draft">As
                                                Draft</button></li>
                                        <li><button class="dropdown-item" type="submit" name="proj_status"
                                                value="Pre Sales">Pre sales</button></li>
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

            $('#customer_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });

            $('#brand_id').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });
        });
    </script>
@endpush
