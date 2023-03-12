<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <style>
            .container {
                width: 700px;
                margin: 0 auto;
            }

            .chart {
                width: 100%;
                height: 300px;
            }

            .state-disabled {
                pointer-events: none;
            }

            .errors {
                color: #900a0a;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            <canvas id="chart" class="chart"></canvas>
            <form id="subscribe-form">
                <div class="wrapper">
                    <input type="email" id="email" name="email" placeholder="Enter your email">
                    <input type="number" id="price" name="price" placeholder="Enter the price">
                    <button type="submit">Submit</button>
                </div>
                <div class="errors" id="errors">

                </div>
            </form>
        </div>
        <script src="{{ asset('app.js') }}" defer></script>
    </body>
</html>
