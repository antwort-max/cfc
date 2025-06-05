@extends('ecommerce.layouts.app')

@section('title', 'Registro de Clientes')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8 space-y-6">
                
                {{-- Encabezado --}}
                <div class="text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                        Crear cuenta
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Ingresa tus datos para registrarte y comenzar a comprar
                    </p>
                </div>

                {{-- Formulario --}}
                <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
                    @csrf

                    @php
                        $inputClasses = "
                            w-full bg-white border border-gray-300 text-gray-900 placeholder-gray-400
                            rounded-md shadow-sm px-3 py-2
                            focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600
                            dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-500
                        ";
                    @endphp

                    {{-- Nombre --}}
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre
                        </label>
                        <input
                            id="first_name"
                            name="first_name"
                            type="text"
                            autocomplete="given-name"
                            required
                            value="{{ old('first_name') }}"
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Apellido --}}
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Apellido
                        </label>
                        <input
                            id="last_name"
                            name="last_name"
                            type="text"
                            autocomplete="family-name"
                            required
                            value="{{ old('last_name') }}"
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Correo electrónico
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Contraseña
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Confirmar contraseña
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Botón --}}
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition"
                    >
                        Registrarme
                    </button>
                </form>

                {{-- Enlace a login --}}
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('customer.login') }}"
                       class="font-medium text-primary-600 hover:text-primary-500">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

