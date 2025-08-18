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
                    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('customer.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('post')
                        <div class="card">
                            <div class="card-header">
                                Customer
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="cust_name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('cust_name') is-invalid @enderror"
                                            name="cust_name" value="{{ old('cust_name') }}" required>
                                        @error('cust_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_address" class="col-sm-3 col-form-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('cust_address') is-invalid @enderror"
                                            name="cust_address" value="{{ old('cust_address') }}" required>
                                        @error('cust_address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_director_name" class="col-sm-3 col-form-label">Director Name</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('cust_director_name') is-invalid @enderror"
                                            name="cust_director_name" value="{{ old('cust_director_name') }}" required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_contact_number" class="col-sm-3 col-form-label">Contact Number</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('cust_contact_number') is-invalid @enderror"
                                            name="cust_contact_number" value="{{ old('cust_contact_number') }}" required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('cust_email') is-invalid @enderror"
                                            name="cust_email" value="{{ old('cust_email') }}" required>
                                    </div>
                                </div>
                                @if (Auth::user()->hasPermissionTo('setting.customer.partnership'))
                                    <div class="mb-2 row">
                                        <label for="cust_type" class="col-sm-3 col-form-label">Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-select @error('cust_type') is-invalid @enderror"
                                                id="cust_type" name="cust_type" required>
                                                <option value="EU" {{ old('cust_type') == 'EU' ? 'selected' : '' }}>
                                                    EU</option>
                                                <option value="KT" {{ old('cust_type') == 'KT' ? 'selected' : '' }}>
                                                    KT</option>
                                                <option value="ME" {{ old('cust_type') == 'ME' ? 'selected' : '' }}>
                                                    ME</option>
                                                <option value="SI" {{ old('cust_type') == 'SI' ? 'selected' : '' }}>
                                                    SI</option>
                                                <option value="R" {{ old('cust_type') == 'R' ? 'selected' : '' }}>
                                                    R</option>
                                            </select>
                                            @error('cust_type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label for="file_ktp" class="col-sm-3 col-form-label">KTP</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('file_ktp') is-invalid @enderror"
                                                type="file" id="file_ktp" name="file_ktp">
                                            @error('fiel_ktp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label for="file_nib" class="col-sm-3 col-form-label">NIB</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('file_nib') is-invalid @enderror"
                                                type="file" id="file_nib" name="file_nib">
                                            @error('file_nib')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label for="file_npwp" class="col-sm-3 col-form-label">NPWP</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('file_npwp') is-invalid @enderror"
                                                type="file" id="file_npwp" name="file_npwp">
                                            @error('file_npwp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" type="submit">
                                    Save
                                </button>
                                <a class="btn btn-secondary" href="{{ route('customer.index') }}"
                                    role="button">Back</a>
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
            $('#cust_type').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
            });
        });
    </script>
@endpush
