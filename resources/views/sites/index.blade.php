@extends('layouts.layout')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Parse sites</h1>
        @if(count($sites))
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <caption>List of parse sites</caption>
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        {{--<th>URL</th>--}}
                        <th>Domain</th>
                        <th>Created</th>
                        <th>Links qlt.</th>
                        <th class="text-center">Show</th>
                        <th class="text-center">Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sites as $site)
                        <tr>
                            <td>{{ $site->id }}</td>
                            {{--<td>{{ $site->url }}</td>--}}
                            <td>{{ $site->domain }}</td>
                            <td>{{ $site->created_at }}</td>
                            <td>{{ count($site->links) }}</td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm"
                                   href="{{ action('ParseSitesController@show', ['id' => $site->id]) }}">
                                    View
                                </a>
                            </td>
                            <td class="text-center">
                                <form action="{{ action('ParseSitesController@destroy', ['id' => $site->id]) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="far fa-trash-alt" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    @if(count($sites) > 20)
                        <tfoot class="thead-dark">
                        <tr>
                            <th>ID</th>
                            {{--<th>URL</th>--}}
                            <th>Domain</th>
                            <th>Created</th>
                            <th>Links qlt.</th>
                            <th class="text-center">Show</th>
                            <th class="text-center">Remove</th>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif
    </div>
@endsection
