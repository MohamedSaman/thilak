<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-truck fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Supplier Directory</h2>
                    <p class="text-muted mb-0">Manage your product vendors and supply chain partners.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <button wire:click="createSupplier" class="btn btn-primary shadow-premium">
                    <i class="bi bi-plus-lg me-2"></i>Register Supplier
                </button>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Supplier Detail</th>
                        <th>Contact Info</th>
                        <th>Location</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md rounded-circle bg-primary-soft text-primary fw-bold text-xs">
                                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $supplier->name }}</h6>
                                    <span class="text-xs text-muted">ID: SUP-{{ $supplier->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-sm fw-bold"><i class="bi bi-telephone text-primary me-2"></i>{{ $supplier->contact ?? '-' }}</span>
                                <span class="text-xs text-muted"><i class="bi bi-envelope text-primary me-2"></i>{{ $supplier->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-start gap-2 max-w-xs">
                                <i class="bi bi-geo-alt text-danger mt-1"></i>
                                <span class="text-xs text-muted fw-medium">{{ $supplier->address ?? 'No address listed' }}</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="editSupplier({{ $supplier->id }})" class="btn btn-sm btn-white text-info" title="Edit">
                                    <i class="bi bi-pencil" wire:loading.class="d-none" wire:target="editSupplier({{ $supplier->id }})"></i>
                                    <span wire:loading wire:target="editSupplier({{ $supplier->id }})">
                                        <i class="spinner-border spinner-border-sm"></i>
                                    </span>
                                </button>
                                <button wire:click="confirmDelete({{ $supplier->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-person-badge"></i></div>
                            <h6 class="text-muted">No suppliers registered in the system.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="p-4 border-top bg-light-soft">
            {{ $suppliers->links('livewire::bootstrap') }}
        </div>
        @endif
    </div>

    <!-- Modals -->

    {{-- Create Supplier Modal --}}
    <div wire:ignore.self wire:key="create-supplier-modal" class="modal fade" id="createSupplierModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Register New Supplier</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Supplier Name</label>
                            <input type="text" class="form-control" wire:model.defer="name" placeholder="E.g. ABC Distributors">
                            @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model.defer="contactNumber" placeholder="+94 7X XXX XXXX">
                            @error('contactNumber') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Email Address</label>
                            <input type="email" class="form-control" wire:model.defer="email" placeholder="supplier@example.com">
                            @error('email') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Office Address</label>
                            <input type="text" class="form-control" wire:model.defer="address" placeholder="123 Street, City">
                            @error('address') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" wire:click="resetForm">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="saveSupplier">Create Supplier</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Supplier Modal --}}
    <div wire:ignore.self wire:key="edit-supplier-modal-{{ $editSupplierId ?? 'new' }}" class="modal fade" id="editSupplierModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Edit Supplier Info</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Supplier Name</label>
                            <input type="text" class="form-control" wire:model="editName">
                            @error('editName') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="editContactNumber">
                            @error('editContactNumber') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Email Address</label>
                            <input type="email" class="form-control" wire:model="editEmail">
                            @error('editEmail') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Office Address</label>
                            <input type="text" class="form-control" wire:model="editAddress">
                            @error('editAddress') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" wire:click="resetForm">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="updateSupplier({{ $editSupplierId }})">Save Changes</button>
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
            title: "Delete Supplier?",
            text: "This supplier will be permanently removed. You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#233d7f",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Yes, delete it!",
            customClass: {
                popup: 'rounded-xl border-0 shadow-premium',
                confirmButton: 'btn btn-primary px-4',
                cancelButton: 'btn btn-light-soft px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('confirmDelete');
                Swal.fire({
                    title: "Deleted!",
                    text: "Supplier has been removed from the directory.",
                    icon: "success",
                    confirmButtonColor: "#233d7f",
                    customClass: {
                        popup: 'rounded-xl border-0 shadow-premium',
                        confirmButton: 'btn btn-primary px-4'
                    }
                });
            }
        });
    });

    window.addEventListener('create-supplier', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('createSupplierModal'));
            modal.show();
        }, 100);
    });

    window.addEventListener('edit-supplier', event => {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
            modal.show();
        }, 100);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const createModal = document.getElementById('createSupplierModal');
        const editModal = document.getElementById('editSupplierModal');
        if (createModal) {
            createModal.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('resetForm');
            });
        }
        if (editModal) {
            editModal.addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('resetForm');
            });
        }
    });
</script>
@endpush
