@extends('ecommerce.layouts.app')

@section('title', 'Mi cuenta')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-8 space-y-6">

                {{-- Encabezado --}}
                <div class="text-center space-y-1">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                        Hola, {{ auth('customer')->user()->first_name }}!
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Bienvenido a tu cuenta
                    </p>
                </div>

                {{-- Acciones --}}
                <form method="POST" action="{{ route('customer.logout') }}" class="space-y-4">
                    @csrf

                     <button
                        type="submit"
                        class="w-full flex justify-center items-center px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition"
                    >
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
