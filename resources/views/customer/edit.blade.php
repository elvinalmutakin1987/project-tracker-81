@php
    use App\Models\File_upload;
    use Illuminate\Support\Facades\Storage;
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
                    <li class="breadcrumb-item"><a href="{{ route('setting') }}">Setting</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('customer.update', $customer->id) }}" enctype="multipart/form-data"
                        method="POST">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                                Customer
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="cust_name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('cust_name') is-invalid @enderror"
                                            name="cust_name" value="{{ old('cust_name') ?? $customer->cust_name }}"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_address" class="col-sm-3 col-form-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('cust_address') is-invalid @enderror"
                                            name="cust_address" value="{{ old('cust_address') ?? $customer->cust_address }}"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_director_name" class="col-sm-3 col-form-label">Director Name</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                            class="form-control @error('cust_director_name') is-invalid @enderror"
                                            name="cust_director_name"
                                            value="{{ old('cust_director_name') ?? $customer->cust_director_name }}"
                                            required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="cust_email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('cust_email') is-invalid @enderror"
                                            name="cust_email" value="{{ old('cust_email') ?? $customer->cust_email }}"
                                            required>
                                    </div>
                                </div>
                                @if (Auth::user()->hasPermissionTo('setting.customer.partnership'))
                                    <div class="mb-2 row">
                                        <label for="cust_type" class="col-sm-3 col-form-label">Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" id="cust_type" name="cust_type" required>
                                                <option value="EU"
                                                    {{ old('cust_type') == 'EU' || $customer->cust_type == 'EU' ? 'selected' : '' }}>
                                                    EU</option>
                                                <option value="KT"
                                                    {{ old('cust_type') == 'KT' || $customer->cust_type == 'KT' ? 'selected' : '' }}>
                                                    KT</option>
                                                <option value="ME"
                                                    {{ old('cust_type') == 'ME' || $customer->cust_type == 'ME' ? 'selected' : '' }}>
                                                    ME</option>
                                                <option value="SI"
                                                    {{ old('cust_type') == 'SI' || $customer->cust_type == 'SI' ? 'selected' : '' }}>
                                                    SI</option>
                                                <option value="R"
                                                    {{ old('cust_type') == 'R' || $customer->cust_type == 'R' ? 'selected' : '' }}>
                                                    R</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label for="file_ktp" class="col-sm-3 col-form-label">KTP</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" id="file_ktp" name="file_ktp">
                                        </div>
                                    </div>
                                    @php
                                        $file_upload = File_upload::where('file_table', 'customer')
                                            ->where('file_table_id', $customer->id)
                                            ->where('file_doc_type', 'KTP')
                                            ->first();
                                    @endphp
                                    @if ($file_upload)
                                        <div class="row mb-2">
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-9">

                                                <a href="{{ route('customer.file_download', $file_upload->id) }}"
                                                    target="_blank">{{ $file_upload->file_real_name }}</a>
                                                <a href=""
                                                    onclick="file_remove({{ $file_upload->id }}); return false;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"
                                                        style="color:red">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mb-2 row">
                                        <label for="file_nib" class="col-sm-3 col-form-label">NIB</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" id="file_nib" name="file_nib">
                                        </div>
                                    </div>
                                    @php
                                        $file_upload = File_upload::where('file_table', 'customer')
                                            ->where('file_table_id', $customer->id)
                                            ->where('file_doc_type', 'NIB')
                                            ->first();
                                    @endphp
                                    @if ($file_upload)
                                        <div class="row mb-2">
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-9">
                                                <a href="{{ route('customer.file_download', $file_upload->id) }}"
                                                    target="_blank">{{ $file_upload->file_real_name }}</a>
                                                <a href=""
                                                    onclick="file_remove({{ $file_upload->id }}); return false;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"
                                                        style="color:red">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mb-2 row">
                                        <label for="file_npwp" class="col-sm-3 col-form-label">NPWP</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="file" id="file_npwp" name="file_npwp">
                                        </div>
                                    </div>
                                    @php
                                        $file_upload = File_upload::where('file_table', 'customer')
                                            ->where('file_table_id', $customer->id)
                                            ->where('file_doc_type', 'NPWP')
                                            ->first();
                                    @endphp
                                    @if ($file_upload)
                                        <div class="row mb-2">
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-9">
                                                <a href="{{ route('customer.file_download', $file_upload->id) }}"
                                                    target="_blank">{{ $file_upload->file_real_name }}</a>
                                                <a href=""
                                                    onclick="file_remove({{ $file_upload->id }}); return false;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"
                                                        style="color:red">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
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

    <form class="d-inline" method="POST" id="form-delete">
        @csrf
        @method('DELETE')

    </form>
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

        function file_remove(id) {
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
                    var url = "{!! route('customer.file_remove', '_id') !!}"
                    url = url.replace('_id', id)
                    $("#form-delete").attr("action", url);
                    document.getElementById('form-delete').submit();
                }
            });
        }
    </script>
@endpush
