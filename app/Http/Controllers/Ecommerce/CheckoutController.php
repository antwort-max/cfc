<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\EcoCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuoteMail;
use Illuminate\Support\Str;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Helpers\ActivityLogger; 

class CheckoutController extends BaseEcommerceController
{
    protected $themeOptions;
    protected $menus;
    protected CartService $cartService;


    public function show(Request $request)
    {
        if (! $request->session()->has('guest_token')) {
            $request->session()->put('guest_token', Str::uuid()->toString());
        }

        $currentCart = $this->cartService->current()->load('items.product');

        return view('ecommerce.checkout.form', compact('currentCart'));
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'send_method'     => 'required|in:email,printed,whatsapp',
            'explanation'     => 'nullable|string',
            'recipient_email' => 'required_if:send_method,email|email',
        ]);

        $currentCart = $this->cartService->current()->load('items.product');

        switch ($data['send_method']) {
            case 'email':
                $mailData = [
                    'customerName' => auth()->user()?->name 
                                      ?? $currentCart->customer?->name 
                                      ?? 'Cliente',
                    'items'        => $currentCart->items,
                    'taxes'        => $currentCart->taxes,
                    'amount'       => $currentCart->amount,
                    'explanation'  => $data['explanation'] ?? null,
                ];

                Mail::to($data['recipient_email'])
                    ->send(new QuoteMail($mailData));
                break;

            case 'whatsapp':
                // aquí podrías inyectar un WhatsappService y hacer:
                // app(WhatsappService::class)
                //     ->to($data['recipient_phone'])
                //     ->sendQuote($currentCart, $data['explanation']);
                break;

            case 'printed':

                $pdf = Pdf::loadView('ecommerce.checkout.print', array_merge($shared, [
                    'items'  => $currentCart->items,
                    'taxes'  => $currentCart->taxes,
                    'amount' => $currentCart->amount,
                ]));
                return $pdf->stream("cotizacion_{$currentCart->id}.pdf");
                dd($pdf);
        }

        $currentCart->update([
            'status'      => EcoCart::STATUS_CONVERTED,
            'send_method' => $data['send_method'],
            'explanation' => $data['explanation'] ?? null,
            'amount'      => $currentCart->amount,
            'taxes'       => $currentCart->taxes,
        ]);

        $request->session()->forget('guest_token');

        return redirect()->route('checkout.thanks')->with('success', 'Tu cotización ha sido procesada correctamente.');
    }

    public function thanks()
    {
        return view('ecommerce.checkout.thanks');
    }
}
