<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #19202c;
                color: #c5c7ca;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
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
                width: 100%;
            }

            .title {
                font-size: 84px;
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

            .intro {
                border-bottom: 1px solid #636b6f;
                margin-bottom: 5vh;
            }

            .block_error {

            }

            .block_error .code {
                font-size: 4rem;
                color: red;
                border: 2px solid;
                padding: 0 2rem 0.5rem 2rem;
            }

            .block_error .message {
                padding: 0 5%;
                font-size: 1.5rem;
            }

            .block_error .details {
                padding: 0 5%;
                font-size: 1.2rem;
                color: #999;
            }
        </style>
    </head>
    <body>

        <div class="flex-center position-ref full-height">

            <div class="content">

                <div class="intro">

                    <div class="links">
                        <a href="{{ config('app.org_url') }}" target="_blank">{{ config('app.org_name') }}</a>
                    </div>

                    <div class="title m-b-md">
                        {{ config('app.name') }}
                    </div>

                </div>

                @isset ($code)

                    <div class="block_error">
                        @isset ($code)
                        <div class="flex-center">
                            <div class="code">{{ $code }}</div>
                        </div>
                        @endif

                        @isset ($message)
                        <p class="message">{{ $message }}</p>
                        @endif

                        @isset ($details)
                        <p class="details">{{ $details }}</p>
                        @endif
                    </div>

                @endif

            </div>
        </div>
    </body>
</html>
