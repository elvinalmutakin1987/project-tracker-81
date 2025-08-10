@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('task_board.index', ['assignee' => 'finance-accounting']) }}">Task
                            Board</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Document Upload</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('task_board.document_invoice_dp.update', $project_invoice_dp->id) }}"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header">
                                Document Upload
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="file_doc_type" class="col-sm-3 col-form-label">Document</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="file_doc_type" name="file_doc_type" required>
                                            <option value="Invoice DP" selected>
                                                Invoice DP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="file_upload" class="col-sm-3 col-form-label">File</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="file" id="file_upload" name="file_upload">
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="file_link" class="col-sm-3 col-form-label">Link</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" id="file_link" name="file_link">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" type="submit">
                                    Save
                                </button>
                                <a class="btn btn-secondary"
                                    href="{{ route('task_board.index', ['assignee' => 'finance-accounting', 'doc_type' => 'invoice-dp']) }}"
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
        $(document).ready(function() {});
    </script>
@endpush
