@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <h1 class="mb-4">Welcome to Admin Dashboard</h1>
            <p>Ready to go beyond the starter template? Check out these open source projects that you can
                quickly duplicate to a new GitHub repository.</p>
            <ul class="list-unstyled ps-0">
                <li> <a class="icon-link mb-1" href="https://github.com/twbs/examples/tree/main/icons-font" rel="noopener"
                        target="_blank"> <svg class="bi" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#arrow-right-circle"></use>
                        </svg>
                        Bootstrap npm starter
                    </a> </li>
                <li> <a class="icon-link mb-1" href="https://github.com/twbs/examples/tree/main/parcel" rel="noopener"
                        target="_blank"> <svg class="bi" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#arrow-right-circle"></use>
                        </svg>
                        Bootstrap Parcel starter
                    </a> </li>
                <li> <a class="icon-link mb-1" href="https://github.com/twbs/examples/tree/main/vite" rel="noopener"
                        target="_blank"> <svg class="bi" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#arrow-right-circle"></use>
                        </svg>
                        Bootstrap Vite starter
                    </a> </li>
                <li> <a class="icon-link mb-1" href="https://github.com/twbs/examples/tree/main/webpack" rel="noopener"
                        target="_blank"> <svg class="bi" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#arrow-right-circle"></use>
                        </svg>
                        Bootstrap Webpack starter
                    </a> </li>
            </ul>
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
    </script>
@endpush
