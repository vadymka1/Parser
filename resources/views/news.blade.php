<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        News
                    </a>
                </div>
            </div>
        </nav>
            <table class="table  text-center" id="data-table">
                <thead>
                <tr>
                    <th class="text-center sort" style="cursor: pointer" >ID</th>
                    <th class="text-center sort" style="cursor: pointer">
                        <a href="{{route ('news.sort',['param' => 'title', 'dir' => request()->input('dir') == 'desc' ? 'asc' : 'desc'])}}"
                            @if(request()->input('dir') == 'asc' && request()->input('param') == 'title') class="glyphicon glyphicon-chevron-down"
                            @else class="glyphicon glyphicon-chevron-up"
                            @endif>
                        </a>Title</th>
                    <th class="text-center sort" style="cursor: pointer">Tags</th>
                    <th class="text-center sort" style="cursor: pointer">
                        <a href="{{route ('news.sort',['param' => 'views', 'dir' => request()->input('dir') == 'desc' ? 'asc' : 'desc'])}}"
                            @if(request()->input('dir') == 'asc' && request()->input('param') == 'views') class="glyphicon glyphicon-chevron-down"
                            @else class="glyphicon glyphicon-chevron-up"
                            @endif>
                        </a>Views</th>
                    <th class="text-center sort" style="cursor: pointer">Date</th>
                </tr>
                </thead>

                <tbody>

                @foreach($news as $new)
                    <tr>
                        <td style="vertical-align: middle">{{$new->id}}</td>
                        <td style="vertical-align: middle"><a href="{{$new->link}}">{{$new->title}}</a></td>
                        <td style="vertical-align: middle">{{$new->tags}}</td>
                        <td style="vertical-align: middle">{{$new->views}}</td>
                        <td style="vertical-align: middle">{{$new->date}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="text-center" data-pagination>
                {{ $news->links() }}
            </div>
        @yield('content')
    </div>
    </body>
</html>
