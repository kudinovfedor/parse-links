@extends('layouts.layout')

@section('content')
    <div class="container py-4">
        {{--{{ dump($site) }}
        {{ dump($links) }}--}}
        <h1 class="mb-4">Site - <span class="h4 text">{{ $site->url }}</span></h1>
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
                    @foreach($site->links as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>
                                @if(strlen($link->path) >= 60)
                                    <span class="ellipsis">{{ $link->path }}</span>
                                @else
                                    {{ $link->path }}
                                @endif
                            </td>
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
