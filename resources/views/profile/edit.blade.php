@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('content')
    <div class="page-head mb-3">
        <h2>Pengaturan Profil</h2>
        <p>Perbarui informasi akun, kata sandi, atau hapus akun Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success" role="status">Informasi profil berhasil diperbarui.</div>
    @elseif (session('status') === 'password-updated')
        <div class="alert alert-success" role="status">Kata sandi berhasil diperbarui.</div>
    @endif

    <div class="surface mb-4">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="surface mb-4">
        @include('profile.partials.update-password-form')
    </div>

    <div class="surface">
        @include('profile.partials.delete-user-form')
    </div>
@endsection
