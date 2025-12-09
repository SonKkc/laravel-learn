@extends('layouts.default')

@section('content')

    <x-home.hero_section />
    <x-home.trending_section />
    <x-home.content_section />
    {{-- <x-home.featured_section /> --}}
    <x-home.blog_section />

@endsection
