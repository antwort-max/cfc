@extends('ecommerce.layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8 space-y-6">

                {{-- Encabezado --}}
                <div class="text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                        Iniciar sesión
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Accede a tu cuenta para continuar comprando
                    </p>
                </div>

                {{-- Formulario --}}
                <form method="POST" action="{{ route('customer.login.store') }}" class="space-y-4">
                    @csrf

                    @php
                        $inputClasses = "
                            w-full bg-white border border-gray-300 text-gray-900 placeholder-gray-400
                            rounded-md shadow-sm px-3 py-2
                            focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600
                            dark:bg-gray-800 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-500
                        ";
                    @endphp

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Correo electrónico
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
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
                            autocomplete="current-password"
                            required
                            class="{{ $inputClasses }}"
                        >
                    </div>

                    {{-- Recordarme --}}
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-primary-600 border-gray-300 rounded
                                   focus:ring-primary-600 dark:bg-gray-800 dark:border-gray-600"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Recuérdame
                        </label>
                    </div>

                    {{-- Botón --}}
                     <button
                        type="submit"
                        class="w-full flex justify-center items-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition"
                    >
                        Entrar
                    </button>
                </form>

                {{-- Enlaces auxiliares --}}
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    ¿No tienes cuenta?
                    <a href="{{ route('customer.register') }}"
                       class="font-medium text-primary-600 hover:text-primary-500">
                        Crear cuenta nueva
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

