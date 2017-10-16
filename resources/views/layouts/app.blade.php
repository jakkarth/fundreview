<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FundReview</title>
      <link rel="stylesheet" href="{!! asset('css/app.css') !!}"></script>
<script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
      <script src="https://unpkg.com/vue-star-rating/dist/star-rating.min.js"></script>
      <script type="text/javascript">Vue.component('star-rating', VueStarRating.default);</script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000;
                font-family: 'Raleway', sans-serif;
                font-weight: bold;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

.subtitle, .fundname {
          font-size: 40px;
       }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
.list-group {
              text-align: left;
    max-width: 800px;
 }

.starz {
              display: inline-block !important;
              }
        </style>
    </head>
    <body>
        <div class="flex-center">
            <div class="content">
                      <div class="title m-b-md">
                    FundReview
                </div>
      <div class="subtitle m-b-md">Where people review fundraising platforms!</div>

      @yield('content')
      </div>

        </div>
    </body>
</html>
