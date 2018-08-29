@extends('layouts.layout')

@section('content')
    <div class="container py-4">
        <div class="row mb-4 align-items-end">
            <div class="col-md-8">
                <h1 class="mb-0">Site - <span class="h4 text">{{ $site->url }}</span></h1>
            </div>
            @if($count = $site->links()->where('processed', false)->count())
                <div class="col-md-4 text-right">
                    <form action="{{ action('FrontController@processing', ['id' => $site->id]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-info btn-sm">Processing ({{ $count }})</button>
                    </form>
                </div>
            @endif
        </div>
        @if(count($site->links))
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <caption>List of site links</caption>
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        {{--<th>Parent ID</th>
                        <th>Children ID</th>
                        <th class="text-center">External</th>
                        <th class="text-center">Status</th>--}}
                        <th class="text-center">Processed</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($site->links()->paginate(10) as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>
                                @if(strlen($link->path) >= 60)
                                    <span class="ellipsis">{{ $link->path }}</span>
                                @else
                                    {{ $link->path }}
                                @endif
                            </td>
                            {{--<td>
                                @if(strlen($link->url) >= 60)
                                    <span class="ellipsis2">{{ $link->url }}</span>
                                @else
                                    {{ $link->url }}
                                @endif
                            </td>--}}
                            {{--<td>{{ $link->parent_id }}</td>
                            <td>{{ $link->children_id }}</td>
                            <td class="text-center">
                                @if($link->external)
                                    <i class="fas fa-check text-warning" aria-hidden="true"></i>
                                @else
                                    <i class="fas fa-times text-muted" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td class="text-center">{{ $link->status }}</td>--}}
                            <td class="text-center">{{ $link->processed ? 'Yes' : 'No' }}</td>
                            <td>{{ $link->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    @if(count($site->links) > 20)
                        <tfoot class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            {{--<th>Parent ID</th>
                            <th>Children ID</th>
                            <th class="text-center">External</th>
                            <th class="text-center">Status</th>--}}
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
