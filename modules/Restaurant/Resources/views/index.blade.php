@extends('restaurant::layouts.master')

@section('content')
@php
    use Illuminate\Support\Str;
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[0] = ($path[0] === '')?'menu':$path[0];
@endphp
<div class="container">
    <div class="row">
        <nav class="main-nav flex-grow-1">
            <ul class="menu sf-arrows sf-js-enabled" style="touch-action: pan-y;">
                <li>
                    <a href="{{ route('tenant.restaurant.menu') }}" class="{{ $path[1] == '' ? 'bg-success text-light' : '' }}">Todos</a>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('tenant.restaurant.menu', ['name' => Str::slug($category->name, '-')]) }}"  class="{{ $path[1] == $category->name ? 'bg-success text-light' : '' }}">{{$category->name}}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="col-lg-12">
            @php
                $tagid = Request::segment(3);
            @endphp
            @if(!$tagid)
                @include('restaurant::layouts.partials.banner')
            @endif
            <div class="my-3"></div><!-- margin -->
            <div class="row row-sm mt-4">
                @include('restaurant::layouts.partials.list_products')
            </div>
            <div class="row float-right">
              <div class="col-md-12 col-lg-12">
                {{ $dataPaginate->links() }}
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
