<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Control Plane - ShamHosts</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            dark: {
                                900: '#000000',
                                800: '#111111',
                                700: '#333333',
                            },
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
        </style>
    </head>
    <body class="antialiased min-h-screen">
        @yield('content')
    </body>
</html>
