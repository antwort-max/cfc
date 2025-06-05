<h1>¡Gracias por tu compra!</h1>

@if(session('checkout_email') && ! auth('customer')->check())
    <p>
        ¿Quieres crear una cuenta para gestionar tus pedidos y obtener descuentos?
    </p>
    <form method="POST" action="{{ route('customer.register.fromGuest') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('checkout_email') }}">
        <button class="btn btn-primary">Crear mi cuenta</button>
    </form>
@endif
