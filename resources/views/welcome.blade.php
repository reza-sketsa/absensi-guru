<!DOCTYPE html>
<html lang="en">

<head>
    @include('components.head')
</head>

<body>
    @include('components.navbar')

    <main class="container mt-4">
        @yield('content')
    </main>

    @include('components.scripts')
</body>

</html>
