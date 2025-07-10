@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <h1 class="mb-4">Erro page</h1>
            <p>
                {{ isset($th) ? $th->getMessage() : $message }}

                @if (request()->routeIs('task_board.*'))
                    <br>
                    <br>
                    <ul class="list-unstyled ps-0">
                        @if (Auth::user()->hasPermissionTo('task_board.pre_sales'))
                            <li>
                                <a href="{{ route('task_board.index', ['assignee' => 'pre-sales']) }}"
                                    class="align-items-center gap-2 text-decoration-none">
                                    <span>Pre Sales</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->hasPermissionTo('task_board.sales_admin'))
                            <li>
                                <a href="{{ route('task_board.index', ['assignee' => 'sales-admin']) }}"
                                    class="align-items-center gap-2 text-decoration-none">
                                    <span>Sales Admin</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->hasPermissionTo('task_board.operation'))
                            <li>
                                <a href="{{ route('task_board.index', ['assignee' => 'operation']) }}"
                                    class="align-items-center gap-2 text-decoration-none">
                                    <span>Operation</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->hasPermissionTo('task_board.finance_accounting'))
                            <li>
                                <a href="{{ route('task_board.index', ['assignee' => 'finance_accounting']) }}"
                                    class="align-items-center gap-2 text-decoration-none">
                                    <span>Finance & Accounting</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                @endif
            </p>
        </div>
    </div>
@endsection
