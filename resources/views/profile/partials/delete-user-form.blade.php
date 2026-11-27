<section>
    <div class="surface-head">
        <h3>Hapus Akun</h3>
    </div>
    <p class="text-secondary">
        Penghapusan akun bersifat permanen. Masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
        Hapus Akun
    </button>

    <div class="modal fade" id="confirm-user-deletion" tabindex="-1"
        aria-labelledby="confirm-user-deletion-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="confirm-user-deletion-title">Konfirmasi Hapus Akun</h2>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p>Masukkan kata sandi untuk menghapus akun Anda secara permanen.</p>
                        <label for="delete-account-password" class="form-label">Kata Sandi</label>
                        <input id="delete-account-password" name="password" type="password" class="form-control"
                            autocomplete="current-password" required>
                        @error('password', 'userDeletion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@if ($errors->userDeletion->isNotEmpty())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById('confirm-user-deletion')
                ).show();
            });
        </script>
    @endpush
@endif
