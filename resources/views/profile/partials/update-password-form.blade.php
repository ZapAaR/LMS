<section>
    <div class="surface-head">
        <h3>Ubah Kata Sandi</h3>
    </div>
    <p class="text-secondary">Gunakan kata sandi yang kuat untuk menjaga keamanan akun Anda.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="form-control" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-outline-brass">Simpan Kata Sandi</button>
    </form>
</section>
