@extends('layouts.base')

@section('title', 'Admin')

@section('navbar')
    @include('partials.navbar-admin')
@endsection

@section('modals')
    @include('components.confirm-delete-modal')
@endsection
