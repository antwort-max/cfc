// resources/views/ecommerce/customer/profile.blade.php
@extends('ecommerce.layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto my-12">
    <h1 class="text-2xl font-bold mb-6">Mi Perfil</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <p><strong>Nombre:</strong> {{ $customer->first_name }} {{ $customer->last_name }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Último ingreso:</strong> {{ optional($customer->last_login_at)->format('d/m/Y H:i') }}</p>
        <p><strong>Registrado:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
    </div>
</div>
@endsection



