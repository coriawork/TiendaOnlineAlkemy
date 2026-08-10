@extends('layouts.app')

@section('title', 'Productos')

@section('content')


@if ($productos->isEmpty())
<div class="rounded-3xl border border-dashed border-white/15 bg-white/5 p-10 text-center text-slate-300">
    No hay productos registrados todavía.
</div>
@else
<a href="{{ route('productos.crear') }}">
    <button class="mb-6 rounded-lg bg-emerald-600/80 px-4 py-2 text-sm font-semibold  shadow-lg shadow-black/20 transition duration-300 hover:bg-emerald-500/80 text-white hover:shadow-cyan-950/30 cursor-pointer">
        agregar nuevo producto
    </button>
</a>
<section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ($productos as $producto)
    <article class="group overflow-hidden rounded-3xl border border-white/10 bg-slate-900/80 shadow-lg shadow-black/20 transition duration-300 hover:border-cyan-400/30 hover:shadow-cyan-950/30">
        <div class="flex h-44 items-center justify-center bg-gradient-to-br from-cyan-500/20 via-slate-900 to-emerald-500/20 px-6 text-center rounded-3xl">
            <div>
                <h2 class="mt-3 text-2xl font-semibold text-white">{{ $producto->nombre }}</h2>
            </div>
        </div>

        <div class="space-y-4 p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200/80">
                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
            </p>
            <p class="text-sm leading-6 text-slate-300">
                {{ $producto->descripcion ?: 'Sin descripción disponible.' }}
            </p>

            <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Precio</p>
                    <p class="text-2xl font-semibold text-emerald-300">${{ number_format($producto->precio, 2, ',', '.') }}</p>
                </div>

                <div class="text-right">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Stock</p>
                    <p class="text-lg font-semibold text-white">{{ $producto->stock }}</p>
                </div>
            </div>

            <a href="{{ route('productos.editar', $producto) }}">
                <button class="my-4 w-full rounded-lg bg-orange-400/60 px-4 py-2 text-sm font-semibold shadow-lg shadow-black/20 transition duration-300 hover:bg-orange-400/80 text-white hover:shadow-cyan-950/30 cursor-pointer">
                    Editar producto
                </button>
            </a>

            <form action="{{ route('productos.eliminar', $producto->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class=" w-full rounded-lg bg-red-400/60 px-4 py-2 text-sm font-semibold shadow-lg shadow-black/20 transition duration-300 hover:bg-red-400/80 text-white hover:shadow-cyan-950/30 cursor-pointer">
                    Eliminar producto
                </button>
            </form>
    </article>
    @endforeach
</section>
@endif
@endsection