@extends('layouts.default')

@section('content')

    <x-home.hero_section :products="$products" />
    <x-home.trending_section :trending="$brands" />
    <x-home.content_section :productsByCategory="$productsByCategory" :featuredProducts="$featuredProducts" />
    <x-home.blog_section :mostBuy="$mostBuy" :mostView="$mostView" />

@endsection
