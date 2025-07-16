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
                    <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <form action="{{ route('role.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('post')
                        <input type="hidden" id="guard_name" name="guard_name" value="web">
                        <div class="card">
                            <div class="card-header">
                                Role
                            </div>
                            <div class="card-body">
                                <div class="mb-2 row">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label for="name" class="col-sm-3 col-form-label">Access</label>
                                    <div class="col-sm-9">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td class="w-25">Task Board</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="task_board_pre_sales" name="task_board_pre_sales"
                                                            value="task_board.pre_sales">
                                                        <label class="form-check-label" for="task_board_pre_sales">
                                                            Pre Sales
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="task_board_sales_admin" name="task_board_sales_admin"
                                                            value="task_board.sales_admin">
                                                        <label class="form-check-label" for="task_board_sales_admin">
                                                            Sales Admin
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="task_board_finance_accounting"
                                                            name="task_board_finance_accounting"
                                                            value="task_board.finance_accounting">
                                                        <label class="form-check-label" for="task_board_finance_accounting">
                                                            Finance & Accounting
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="task_board_operation" name="task_board_operation"
                                                            value="task_board.operation">
                                                        <label class="form-check-label" for="task_board_operation">
                                                            Operation
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Project</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="project"
                                                            name="project" value="project">
                                                        <label class="form-check-label" for="project">
                                                            All Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Work Order</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="work_order"
                                                            name="work_order" value="work_order">
                                                        <label class="form-check-label" for="work_order">
                                                            All Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Assignment</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="assignment"
                                                            name="assignment" value="assignment">
                                                        <label class="form-check-label" for="assignment">
                                                            All Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Invoice</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="invoice"
                                                            name="invoice" value="invoice">
                                                        <label class="form-check-label" for="invoice">
                                                            All Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tool Kit</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="tool_kit.loan" name="tool_kit.loan"
                                                            value="tool_kit.loan">
                                                        <label class="form-check-label" for="tool_kit.loan">
                                                            Loan
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="tool_kit.return" name="tool_kit.return"
                                                            value="tool_kit.return">
                                                        <label class="form-check-label" for="tool_kit.return">
                                                            Return
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Report</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="report"
                                                            name="report" value="report">
                                                        <label class="form-check-label" for="report">
                                                            All Access
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Setting</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="setting_brand" name="setting_brand"
                                                            value="setting.brand">
                                                        <label class="form-check-label" for="setting_brand">
                                                            Brand
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="setting_customer" name="setting_customer"
                                                            value="setting.customer">
                                                        <label class="form-check-label" for="setting_customer">
                                                            Customer
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="setting_work_type" name="setting_work_type"
                                                            value="setting.work_type">
                                                        <label class="form-check-label" for="setting_work_type">
                                                            Work Type
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="setting_role"
                                                            name="setting_role" value="setting.role">
                                                        <label class="form-check-label" for="setting_role">
                                                            Role
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="setting_user"
                                                            name="setting_user" value="setting.user">
                                                        <label class="form-check-label" for="setting_user">
                                                            User
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success" type="submit">
                                    Save
                                </button>
                                <a class="btn btn-secondary" href="{{ route('role.index') }}" role="button">Back</a>
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

        });
    </script>
@endpush
