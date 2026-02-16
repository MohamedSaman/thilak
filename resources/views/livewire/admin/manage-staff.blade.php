<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-person-badge fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Staff Management</h2>
                    <p class="text-muted mb-0">Manage system users, roles, and access permissions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button wire:click="createStaff" class="btn btn-primary shadow-premium">
                <i class="bi bi-person-plus me-2"></i>Add Staff Member
            </button>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft text-xs">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Member Details</th>
                        <th>Contact info</th>
                        <th class="text-center">Role / Access</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffs as $staff)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $staff->name }}</h6>
                                    <span class="text-xs text-muted">Member since {{ $staff->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column text-xs">
                                <span class="fw-bold mb-1"><i class="bi bi-telephone text-primary me-2"></i>{{ $staff->contact ?? '-' }}</span>
                                <span class="text-muted"><i class="bi bi-envelope text-primary me-2"></i>{{ $staff->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary-soft px-3 py-2 rounded-pill text-xs">
                                {{ strtoupper($staff->role ?: 'STAFF') }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="editStaff({{ $staff->id }})" class="btn btn-sm btn-white text-info" title="Edit">
                                    <i class="bi bi-pencil" wire:loading.class="d-none" wire:target="editStaff({{ $staff->id }})"></i>
                                    <span wire:loading wire:target="editStaff({{ $staff->id }})">
                                        <i class="spinner-border spinner-border-sm"></i>
                                    </span>
                                </button>
                                <button wire:click="confirmDelete({{ $staff->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-shield-lock"></i></div>
                            <h6 class="text-muted">No staff records found.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->

    {{-- Create Staff Modal --}}
    <div wire:ignore.self class="modal fade" id="createStaffModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Add New Team Member</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Staff Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name" placeholder="E.g. Kamal Perera">
                            @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="contactNumber" placeholder="+94 ...">
                            @error('contactNumber') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-sm fw-bold">Email Address (Username)</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="staff@hardware.com">
                            @error('email') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Access Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="Password" wire:model="password">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('Password')">
                                    <i class="bi bi-eye" id="PasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('password') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="ConfirmPassword" wire:model="confirmPassword">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('ConfirmPassword')">
                                    <i class="bi bi-eye" id="ConfirmPasswordToggleIcon"></i>
                                </button>
                            </div>
                            @error('confirmPassword') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="saveStaff">Create Account</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Staff Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editStaffId ?? 'new' }}" class="modal fade" id="editStaffModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Edit Staff Member Profile</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Staff Name</label>
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
                            <label class="form-label text-sm fw-bold">New Password (Optional)</label>
                            <div class="input-group">
                                <input type="password" class="form-control border-end-0 bg-light-soft" id="editPassword" wire:model="editPassword">
                                <button class="btn btn-light-soft border-start-0" type="button" onclick="togglePasswordVisibility('editPassword')">
                                    <i class="bi bi-eye" id="editPasswordToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Confirm New Password</label>
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
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="updateStaff({{$editStaffId}})">Save Profile</button>
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
            title: "Remove Staff Member?",
            text: "This will disable their system access immediately. This cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#233d7f",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Yes, remove member",
            customClass: {
                popup: 'rounded-xl border-0 shadow-premium',
                confirmButton: 'btn btn-primary px-4',
                cancelButton: 'btn btn-light-soft px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('confirmDelete');
                Swal.fire({
                    title: "Removed!",
                    text: "Staff member has been removed from the directory.",
                    icon: "success",
                    confirmButtonColor: "#233d7f"
                });
            }
        });
    });

    window.addEventListener('edit-staff-modal', event => {
        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('editStaffModal')).show();
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
