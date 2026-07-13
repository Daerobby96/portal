@extends('layouts.app')

@section('custom_sidebar')
    @include('manajemenaset::layouts.sidebar')
    
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>
@endsection
