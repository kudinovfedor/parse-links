@extends('layouts.layout')

@section('content')
    <div class="container py-4">
        <div class="row mb-4 align-items-end">
            <div class="col-md-8">
                <h1 class="mb-0">Site - <span class="h4 text">{{ $site->url }} ({{ $links_count = $site->links()->count() }})</span></h1>
            </div>
            @if($count = $site->notProcessed()->count())
                <div class="col-md-4 text-right">
                    <form action="{{ action('FrontController@processing', ['id' => $site->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">Processing ({{ $count }})</button>
                    </form>
                </div>
            @endif
        </div>
        @if($links_count)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <caption>List of site links</caption>
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        <th class="text-center">Links qlt.</th>
                        <th class="text-center">Processed</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($site->links()->paginate(30) as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            {{--<td>
                                @if(strlen($link->path) >= 60)
                                    <span class="ellipsis">{{ $link->path }}</span>
                                @else
                                    {{ $link->path }}
                                @endif
                            </td>--}}
                            <td>
                                @if(strlen($link->url) > 60)
                                    <span class="ellipsis">{{ $link->url }}</span>
                                @else
                                    {{ $link->url }}
                                @endif
                            </td>
                            <td class="text-center">{{ $link->qlt_links }}</td>
                            <td class="text-center">{{ $link->processed ? 'Yes' : 'No' }}</td>
                            <td>{{ $link->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    @if($links_count > 20)
                        <tfoot class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th class="text-center">Links qlt.</th>
                            <th class="text-center">Processed</th>
                            <th>Created</th>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif
    </div>
@endsection
