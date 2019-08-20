@extends('layouts.app')

@section('title', 'home')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{-- {{ Breadcrumbs::render('product', $product) }} --}}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Dashboard <?php echo Auth::user()->roles->first()->name; ?></h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">

            <!--div class="card">
                <div class="card-header">Dashboard <?php echo Auth::user()->roles->first()->name; ?></div-->

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- You are logged in! --}}
                    @role('user')
                        <p>You are logged in!</p>
                    @endrole

                {{-- </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
