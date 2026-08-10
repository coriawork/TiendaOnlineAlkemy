@extends('layouts.app')

@section('title', 'ProductosCrear')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="bg-zinc-900 rounded-2xl shadow-2xl border border-zinc-800 overflow-hidden">

        {{-- Encabezado --}}
        <div class="px-8 py-6 border-b border-zinc-800">
            <h2 class="text-2xl font-bold text-white">
                Crear producto
            </h2>

            <p class="mt-1 text-sm text-zinc-400">
                Completá los datos del nuevo producto.
            </p>
        </div>

        {{-- Formulario --}}
        <form
            action="{{ route('productos') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-8 space-y-6"
        >
            @csrf

            {{-- Categoría --}}
            <div>
                <label
                    for="categoria_id"
                    class="block text-sm font-semibold text-zinc-300 mb-2"
                >
                    Categoría
                </label>

                <select
                    name="categoria_id"
                    id="categoria_id"
                    required
                    class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-3
                           text-zinc-200 shadow-sm outline-none transition
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                           hover:border-zinc-600"
                >
                    <option value="" class="bg-zinc-800">
                        Seleccione una categoría
                    </option>

                    @foreach($categorias as $categoria)
                        <option
                            value="{{ $categoria->id }}"
                            class="bg-zinc-800"
                        >
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
                    class="block text-sm font-semibold text-zinc-300 mb-2"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    value="{{ old('nombre') }}"
                    placeholder="Ej: Auriculares inalámbricos"
                    required
                    class="w-full rounded-lg border border-zinc-700 bg-zinc-800
                           px-4 py-3 text-zinc-200 shadow-sm outline-none transition
                           placeholder:text-zinc-500
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                           hover:border-zinc-600"
                >

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
                    class="block text-sm font-semibold text-zinc-300 mb-2"
                >
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    id="descripcion"
                    rows="4"
                    placeholder="Describí las características del producto..."
                    class="w-full rounded-lg border border-zinc-700 bg-zinc-800
                           px-4 py-3 text-zinc-200 shadow-sm outline-none transition
                           placeholder:text-zinc-500 resize-none
                           focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20
                           hover:border-zinc-600"
                >{{ old('descripcion') }}</textarea>

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
                        class="block text-sm font-semibold text-zinc-300 mb-2"
                    >
                        Precio
                    </label>

                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2
                                   text-zinc-500"
                        >
                            $
                        </span>

                        <input
                            type="number"
                            name="precio"
                            id="precio"
                            value="{{ old('precio') }}"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            required
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800
                                   pl-8 pr-4 py-3 text-zinc-200 shadow-sm outline-none
                                   transition placeholder:text-zinc-500
                                   focus:border-indigo-500 focus:ring-2
                                   focus:ring-indigo-500/20
                                   hover:border-zinc-600"
                        >
                    </div>

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
                        class="block text-sm font-semibold text-zinc-300 mb-2"
                    >
                        Stock
                    </label>

                    <input
                        type="number"
                        name="stock"
                        id="stock"
                        value="{{ old('stock') }}"
                        min="0"
                        placeholder="0"
                        required
                        class="w-full rounded-lg border border-zinc-700 bg-zinc-800
                               px-4 py-3 text-zinc-200 shadow-sm outline-none transition
                               placeholder:text-zinc-500
                               focus:border-indigo-500 focus:ring-2
                               focus:ring-indigo-500/20
                               hover:border-zinc-600"
                    >

                    @error('stock')
                        <p class="mt-2 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            {{-- Separador --}}
            <div class="border-t border-zinc-800 pt-6">

                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-6 py-3.5
                           text-sm font-semibold text-white
                           shadow-lg shadow-indigo-600/10
                           transition duration-200
                           hover:bg-indigo-500
                           hover:shadow-indigo-500/20
                           focus:outline-none focus:ring-2
                           focus:ring-indigo-500 focus:ring-offset-2
                           focus:ring-offset-zinc-900
                           active:scale-[0.98]"
                >
                    Crear producto
                </button>

            </div>

        </form>
    </div>
</div>

@endsection