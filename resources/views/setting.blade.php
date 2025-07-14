@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Setting</li>
                </ol>
            </nav>

            <hr class="col-12 ">

            <div class="col-lg-12 mt-3">
                <main>
                    <div class="row g-5">
                        <div class="col">
                            <ul class="list-unstyled ps-0">
                                @if (Auth::user()->hasPermissionTo('setting.brand'))
                                    <li>
                                        <a href="{{ route('brand.index') }}"
                                            class="align-items-center gap-2 text-decoration-none">
                                            <span>Brand</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->hasPermissionTo('setting.customer'))
                                    <li>
                                        <a href="{{ route('customer.index') }}"
                                            class="align-items-center gap-2 text-decoration-none">
                                            <span>Customer</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->hasPermissionTo('setting.work_type'))
                                    <li>
                                        <a href="{{ route('work_type.index') }}"
                                            class="align-items-center gap-2 text-decoration-none">
                                            <span>Work Type</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->hasPermissionTo('setting.role'))
                                    <li>
                                        <a href="{{ route('role.index') }}"
                                            class="align-items-center gap-2 text-decoration-none">
                                            <span>Role</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->hasPermissionTo('setting.user'))
                                    <li>
                                        <a href="{{ route('project.index') }}"
                                            class="align-items-center gap-2 text-decoration-none">
                                            <span>User</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
