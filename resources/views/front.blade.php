@extends('layouts.layout')

@section('content')
    <main class="container py-4 d-flex">
        <form class="col-12 col-md-10 col-lg-8 m-auto" action="{{ route('front') }}" method="post">
            @csrf
            <div class="input-group input-group-lg">
                <input type="url" class="form-control" name="url" id="url" placeholder="Enter site url..." required>
                <label for="url" class="sr-only">Site URL:</label>
                <div class="input-group-append">
                    <button class="btn btn-success font-weight-bold" type="submit">Send</button>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('css')

@endpush

@push('js')

@endpush