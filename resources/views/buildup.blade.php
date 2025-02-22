@extends('layouts.layouts_landing_page')

@section('content-lp')
    @include('screen_lp.hero')

    @include('screen_lp.visi_misi')

    @include('screen_lp.berita')

    @include('screen_lp.data_info')

    @include('screen_lp.jadwal')

    @include('screen_lp.layanan')

    @include('screen_lp.kontak')
@endsection
