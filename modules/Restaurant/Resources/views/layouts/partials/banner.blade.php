{{--
    - vista slider promociones
    - var items definida en Modules\Ecommerce\Http\ViewComposers\PromotionsViewComposer
--}}

<div class="home-slider owl-carousel owl-carousel-lazy owl-theme owl-theme-light">

    @foreach ($items as $item)

    <div class="home-slide">
        <div class="owl-lazy slide-bg" data-src="{{ asset('storage/uploads/promotions/restaurant/'.$item->image) }}"></div>
        <div class="home-slide-content text-white">

            {{-- <h1>{{$item->name}}</h1> --}}
            {{-- <p>{{$item->description}}</p> --}}
            <a href="/ecommerce/item/{{ $item->item_id }}/{{ $item->id }}" class="btn btn-dark">Comprar Ahora!</a>
            {{-- <a href="/ecommerce/item/{{ $item->item_id }}" class="btn btn-dark">Comprar Ahora!</a> --}}
        </div>
    </div>

    @endforeach

</div>
