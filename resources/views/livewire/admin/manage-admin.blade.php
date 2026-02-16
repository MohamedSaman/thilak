<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Administrator Access</h2>
                    <p class="text-muted mb-0">High-level system management and security control.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="createAdmin" class="btn btn-primary shadow-premium">
                <i class="bi bi-person-plus-fill me-2"></i>New Admin Account
            </button>
        </div>
    </div>

    <!-- Admin Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft text-xs">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Administrator</th>
                        <th>Secure Contact</th>
                        <th class="text-center">System Role</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $admin->name }}</h6>
                                    <span class="text-xs text-muted">ID: ADM-{{ $admin->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column text-xs">
                                <span class="fw-bold mb-1"><i class="bi bi-telephone text-primary me-2"></i>{{ $admin->contact ?? '-' }}</span>
                                <span class="text-muted"><i class="bi bi-envelope text-primary me-2"></i>{{ $admin->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-dark px-3 py-2 rounded-pill text-xs">
                                {{ strtoupper($admin->role ?: 'ADMIN') }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="editAdmin({{ $admin->id }})" class="btn btn-sm btn-white text-info" title="Edit">
                                    <i class="bi bi-pencil" wire:loading.class="d-none" wire:target="editAdmin({{ $admin->id }})"></i>
                                    <span wire:loading wire:target="editAdmin({{ $admin->id }})">
                                        <i class="spinner-border spinner-border-sm"></i>
                                    </span>
                                </button>
                                <button wire:click="confirmDelete({{ $admin->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-person-x"></i></div>
                            <h6 class="text-muted">No administrator accounts discovered.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->

    {{-- Create Admin Modal --}}
    <div wire:ignore.self class="modal fade" id="createAdminModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Provision Admin Account</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Admin Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name" placeholder="E.g. System Master">
                            @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="contactNumber" placeholder="+94 ...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-sm fw-bold">Login Email</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="admin@hardware.com">
                            @error('email') <span class="text-danger text-xs text-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Secure Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="Password" wire:model="password">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('Password')">
                                    <i class="bi bi-eye" id="PasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('password') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Verify Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="ConfirmPassword" wire:model="confirmPassword">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('ConfirmPassword')">
                                    <i class="bi bi-eye" id="ConfirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="saveAdmin">Provision Account</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Admin Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editAdminId ?? 'new' }}" class="modal fade" id="editAdminModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Alter Admin Permissions</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Admin Name</label>
                            <input type="text" class="form-control" wire:model="editName">
                            @error('editName') <span class="text-danger text-xs text-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="editContactNumber">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-sm fw-bold">Email Address</label>
                            <input type="email" class="form-control" wire:model="editEmail">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Reset Password (Optional)</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="editPassword" wire:model="editPassword">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('editPassword')">
                                    <i class="bi bi-eye" id="editPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label text-sm fw-bold">Confirm Reset</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="editConfirmPassword" wire:model="editConfirmPassword">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('editConfirmPassword')">
                                    <i class="bi bi-eye" id="editConfirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="updateAdmin({{$editAdminId}})">Authorize Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('confirm-delete', event => {
        Swal.fire({
            title: "Revoke Administrator Access?",
            text: "This admin will lose all system privileges immediately. This is a critical security action.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#233d7f",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Yes, revoke access",
            customClass: {
                popup: 'rounded-xl border-0 shadow-premium',
                confirmButton: 'btn btn-primary px-4',
                cancelButton: 'btn btn-light-soft px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('confirmDelete');
                Swal.fire({
                    title: "Access Revoked!",
                    text: "Administrator account has been permanently disabled.",
                    icon: "success",
                    confirmButtonColor: "#233d7f"
                });
            }
        });
    });

    window.addEventListener('edit-admin-modal', event => {
        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('editAdminModal')).show();
        }, 100);
    });

    function togglePasswordVisibility(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(inputId + 'ToggleIcon');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("bi-eye");
            toggleIcon.classList.add("bi-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("bi-eye-slash");
            toggleIcon.classList.add("bi-eye");
        }
    }
</script>
@endpush
