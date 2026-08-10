@extends('layouts.app')

@section('title', 'ProductosCrear')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-800 overflow-hidden">

        {{-- Encabezado --}}
        <div class="px-8 py-6 border-b border-zinc-800">

            <h2 class="text-2xl font-bold text-white">
                Editar producto
            </h2>

            <p class="mt-1 text-sm text-zinc-400">
                Modificá los datos del producto.
            </p>

        </div>


        {{-- Formulario --}}
        <form
            action="{{ route('productos.actualizar', $producto) }}"
            method="POST"
            class="p-8 space-y-6">

            @csrf
            @method('PUT')


            {{-- Categoría --}}
            <div>

                <label
                    for="categoria_id"
                    class="block text-sm font-semibold text-zinc-300 mb-2">
                    Categoría
                </label>

                <select
                    name="categoria_id"
                    id="categoria_id"
                    required
                    class="w-full rounded-lg border border-zinc-700
                           bg-zinc-800 px-4 py-3 text-zinc-200
                           outline-none transition
                           focus:border-indigo-500
                           focus:ring-2 focus:ring-indigo-500/20">

                    <option value="">
                        Seleccione una categoría
                    </option>

                    @foreach($categorias as $categoria)

                    <option
                        value="{{ $categoria->id }}"
                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>

                    @endforeach

                </select>

                @error('categoria_id')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Nombre --}}
            <div>

                <label
                    for="nombre"
                    class="block text-sm font-semibold text-zinc-300 mb-2">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    value="{{ old('nombre', $producto->nombre) }}"
                    required
                    class="w-full rounded-lg border border-zinc-700
                           bg-zinc-800 px-4 py-3 text-zinc-200
                           outline-none transition
                           placeholder:text-zinc-500
                           focus:border-indigo-500
                           focus:ring-2 focus:ring-indigo-500/20">

                @error('nombre')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Descripción --}}
            <div>

                <label
                    for="descripcion"
                    class="block text-sm font-semibold text-zinc-300 mb-2">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    id="descripcion"
                    rows="4"
                    class="w-full rounded-lg border border-zinc-700
                           bg-zinc-800 px-4 py-3 text-zinc-200
                           outline-none transition resize-none
                           placeholder:text-zinc-500
                           focus:border-indigo-500
                           focus:ring-2 focus:ring-indigo-500/20">{{ old('descripcion', $producto->descripcion) }}</textarea>

                @error('descripcion')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
                @enderror

            </div>


            {{-- Precio y Stock --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Precio --}}
                <div>

                    <label
                        for="precio"
                        class="block text-sm font-semibold text-zinc-300 mb-2">
                        Precio
                    </label>

                    <input
                        type="number"
                        name="precio"
                        id="precio"
                        value="{{ old('precio', $producto->precio) }}"
                        step="0.01"
                        min="0"
                        required
                        class="w-full rounded-lg border border-zinc-700
                               bg-zinc-800 px-4 py-3 text-zinc-200
                               outline-none transition
                               focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-500/20">

                    @error('precio')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                    @enderror

                </div>


                {{-- Stock --}}
                <div>

                    <label
                        for="stock"
                        class="block text-sm font-semibold text-zinc-300 mb-2">
                        Stock
                    </label>

                    <input
                        type="number"
                        name="stock"
                        id="stock"
                        value="{{ old('stock', $producto->stock) }}"
                        min="0"
                        required
                        class="w-full rounded-lg border border-zinc-700
                               bg-zinc-800 px-4 py-3 text-zinc-200
                               outline-none transition
                               focus:border-indigo-500
                               focus:ring-2 focus:ring-indigo-500/20">

                    @error('stock')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                    @enderror

                </div>

            </div>


            {{-- Botón --}}
            <div class="border-t border-zinc-800 pt-6">

                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600
                           px-6 py-3.5 text-sm font-semibold text-white
                           shadow-lg shadow-indigo-600/10
                           transition duration-200
                           hover:bg-indigo-500
                           focus:outline-none
                           focus:ring-2 focus:ring-indigo-500
                           focus:ring-offset-2
                           focus:ring-offset-zinc-900">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>
@endsection