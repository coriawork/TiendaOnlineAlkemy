<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @fonts

    <!-- Importa tailwind pero necesito hacer npm run dev -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @if (isset($message) )
        <div id="message" class="mb-4 rounded-lg {{ $message['type'] === 'success' ? 'bg-green-500' : 'bg-red-500' }} px-4 py-2 h-10 flex items-center justify-center fixed bottom-10 right-10 text-sm font-semibold text-black shadow-lg shadow-black/20 transition duration-300">
            {{ $message['message'] }}
        </div>  
    @endif
    <nav class=" flex items-center justify-between px-5 h-[10vh]  border-white/10 bg-slate-900/80 shadow-lg shadow-black/20  hover:border-cyan-400/30 ">
        <div>
            <h1 class="text-2xl font-bold text-white">Mi Carrito</h1>
        </div>
        <div class="flex items-center gap-4 ">
            <a href="{{ route('productos') }}" class="text-sm border-b-2 font-semibold text-cyan-200/80 cursor-pointer">Productos</a>
        </div>
        <div>
            <button class="rounded-lg text-sm font-bold  uppercase bg-gray-900 text-white p-2 cursor-pointer">
                Mi carrito
            </button>
        </div>
    </nav>
    <main class="h-full px-4 py-10 sm:px-6 lg:px-8 bg-slate-900/80 bg-gradient-to-br from-cyan-500/20 via-slate-900 to-emerald-500/20">
        @yield('content')
    </main>
</body>
<script>
    setTimeout(() => {
        const message = document.getElementById('message');

        message.classList.add('opacity-0');

        setTimeout(() => {
            message.remove();
        }, 500);

    }, 3000);
</script>
</html>