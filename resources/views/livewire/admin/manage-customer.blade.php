<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Customer Relationship</h2>
                    <p class="text-muted mb-0">Manage your retail and wholesale customer base.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <button wire:click="importCustomers" class="btn btn-secondary shadow-premium">
                    <i class="bi bi-upload me-2"></i>Import
                </button>
                <button wire:click="exportCustomers" class="btn btn-secondary shadow-premium">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export
                </button>
                <button wire:click="createCustomer" class="btn btn-primary shadow-premium">
                    <i class="bi bi-person-plus me-2"></i>New Customer
                </button>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="glass-card p-3 mb-4 rounded-xl shadow-premium">
        <div class="row g-3 align-items-center">
            <div class="col-md-12">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-transparent border-0 pe-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-0 bg-transparent py-2" 
                           placeholder="Search by name, business, phone or email..."
                           wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium text-xs">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Customer / Business</th>
                        <th>Contact info</th>
                        <th class="text-center">Account Type</th>
                        <th>Address</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted">#{{ $customer->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md rounded-circle bg-primary-soft text-primary fw-bold">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $customer->name }}</h6>
                                    <span class="text-xs text-muted fw-bold">{{ $customer->business_name ?: 'Individual' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="fw-bold"><i class="bi bi-telephone text-primary me-2"></i>{{ $customer->phone ?? '-' }}</span>
                                <span class="text-muted"><i class="bi bi-envelope text-primary me-2"></i>{{ $customer->email ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $customer->type == 'wholesale' ? 'badge-info-soft' : 'badge-primary-soft' }} px-3 py-2 rounded-pill">
                                {{ strtoupper($customer->type) }}
                            </span>
                        </td>
                        <td>
                             <div class="d-flex align-items-start gap-2 max-w-xs">
                                <i class="bi bi-geo-alt text-danger mt-1"></i>
                                <span class="text-xs text-muted fw-medium">{{ $customer->address ?: 'Not provided' }}</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="editCustomer({{ $customer->id }})" class="btn btn-sm btn-white text-info" title="Edit">
                                    <i class="bi bi-pencil" wire:loading.class="d-none" wire:target="editCustomer({{ $customer->id }})"></i>
                                    <span wire:loading wire:target="editCustomer({{ $customer->id }})">
                                        <i class="spinner-border spinner-border-sm"></i>
                                    </span>
                                </button>
                                <button wire:click="confirmDelete({{ $customer->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-people"></i></div>
                            <h6 class="text-muted">No customers found matching your search.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="p-4 border-top bg-light-soft">
            {{ $customers->links('livewire::bootstrap') }}
        </div>
        @endif
    </div>

    <!-- Modals -->

    {{-- Create Customer Modal --}}
    <div wire:ignore.self class="modal fade" id="createCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">New Customer Profile</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name" placeholder="E.g. John Doe">
                            @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Business Name</label>
                            <input type="text" class="form-control" wire:model="bussinessName" placeholder="E.g. XYZ Hardware">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="contactNumber" placeholder="+94 ...">
                            @error('contactNumber') <span class="text-danger text-xs text-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Email Address</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="john@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Customer Type</label>
                            <select class="form-select" wire:model="customerType">
                                <option value="">Select Type</option>
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Primary Address</label>
                            <input type="text" class="form-control" wire:model="address" placeholder="123 Main St, City">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="saveCustomer">Register Profile</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Customer Modal --}}
    <div wire:ignore.self wire:key="edit-modal-{{ $editCustomerId ?? 'new' }}" class="modal fade" id="editCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Edit Customer Identity</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Full Name</label>
                            <input type="text" class="form-control" wire:model="editName">
                            @error('editName') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                         <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Business Name</label>
                            <input type="text" class="form-control" wire:model="editBussinessName">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Contact Number</label>
                            <input type="text" class="form-control" wire:model="editContactNumber">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Email Address</label>
                            <input type="email" class="form-control" wire:model="editEmail">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Customer Type</label>
                            <select class="form-select" wire:model="editCustomerType">
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Primary Address</label>
                            <input type="text" class="form-control" wire:model="editAddress">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="updateCustomer({{ $editCustomerId }})">Save Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div wire:ignore.self class="modal fade" id="importCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-light-soft py-4 px-5 fw-bold">
                    Import Database
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="handleImport">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark-arrow-up fs-1 text-primary opacity-50"></i>
                    </div>
                    <p class="text-muted mb-4">Upload a CSV or Excel file containing customer data. Ensure columns match our template.</p>
                    <input type="file" class="form-control border-dashed p-4" wire:model="importFile" accept=".csv, .xlsx, .xls">
                    @error('importFile') <span class="text-danger text-xs mt-2 d-block">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Start Import</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('confirm-delete', event => {
        Swal.fire({
            title: "Delete Profile?",
            text: "This will remove the customer and their history. This action is irreversible!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#233d7f",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Yes, delete profile",
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
                    text: "Customer profile has been permanently removed.",
                    icon: "success",
                    confirmButtonColor: "#233d7f"
                });
            }
        });
    });

    window.addEventListener('open-edit-modal', event => {
        setTimeout(() => {
            new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
        }, 100);
    });
</script>
@endpush