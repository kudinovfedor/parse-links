@extends('layouts.layout')

@section('content')
    <div class="container h-100 py-4 d-flex">
        <form class="col-12 col-md-10 col-lg-8 m-auto" action="{{ route('front') }}" method="post">
            @csrf
            <div class="input-group input-group-lg">
                <input type="url" class="form-control" name="url" placeholder="Enter site url..." required>
                <div class="input-group-append">
                    <button class="btn btn-success font-weight-bold" type="submit">Send</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('css')

@endpush

@push('js')

@endpush