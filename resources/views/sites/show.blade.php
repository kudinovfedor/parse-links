@extends('layouts.layout')

@section('content')
    <div class="container py-4">
        {{--{{ dump($site) }}
        {{ dump($links) }}--}}
        <h1 class="mb-4">Site - <span class="h4">{{ $site->url }}</span></h1>
        @if(count($links))
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <caption>List of site links</caption>
                    <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>URL</th>
                        <th class="text-center">External</th>
                        <th>Created</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($links as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td>
                                @if(strlen($link->url) >= 60)
                                    <span class="ellipsis">{{ $link->url }}</span>
                                @else
                                    {{ $link->url }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($link->external)
                                    <i class="fas fa-check text-warning" aria-hidden="true"></i>
                                @else
                                    <i class="fas fa-times text-muted" aria-hidden="true"></i>
                                @endif
                            </td>
                            <td>{{ $link->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    @if(count($links) > 20)
                        <tfoot class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th class="text-center">External</th>
                            <th>Created</th>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif
    </div>
@endsection
