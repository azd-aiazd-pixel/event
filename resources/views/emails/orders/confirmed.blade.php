<x-mail::message>
# Merci pour votre commande ! 

Votre commande **#{{ $order->id }}** à la boutique **{{ $order->store->name ?? 'du festival' }}** a bien été validée 

### Récapitulatif de vos achats :

<x-mail::table>
| Produit       | Quantité | Prix Unitaire |
|:--------------|:--------:|--------------:|
@foreach($order->items as $item)
| {{ $item->product->name }} | x{{ $item->quantity }} | {{ $item->unit_price }} Pts |
@endforeach
</x-mail::table>

---

**Montant  : {{ $order->total_points }} Pts**


Merci et bon festival !<br>
L'équipe {{ config('app.name') }}
</x-mail::message>