@extends('partials.main')

@section('content')
    <!-- Main Content -->
    <div class="content" id="mainContent" style="margin-left:250px;">
        <div class="container-fluid">
            <h1 class="mb-4">Erro page</h1>
            <p>
                {{ $th->getMessage() }}
            </p>
        </div>
    </div>
@endsection
