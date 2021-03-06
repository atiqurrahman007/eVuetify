<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
        
    </head>
    <body>
        <v-app id="app">
            <main-app></main-app>
            <vue-snotify></vue-snotify>
            <vue-progress-bar></vue-progress-bar>
        </v-app>
        <script type="text/javascript"  src="{{asset('js/app.js')}}"></script>
    </body>
</html>
