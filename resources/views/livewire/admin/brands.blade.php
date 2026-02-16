<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-award fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Brand Management</h2>
                    <p class="text-muted mb-0">Manage product manufacturers and brand partners.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <button wire:click="exportCSV" class="btn btn-secondary shadow-premium">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </button>
                <button wire:click="toggleAddModal" class="btn btn-primary shadow-premium">
                    <i class="bi bi-plus-lg me-2"></i>New Brand
                </button>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="glass-card p-3 mb-4 rounded-xl">
        <div class="row g-3 align-items-center">
            <div class="col-md-12">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-transparent border-0 pe-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-0 bg-transparent py-2" 
                           placeholder="Search brands by name or notes..."
                           wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>
    </div>

    <!-- Brands Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Brand Name</th>
                        <th>Notes / Description</th>
                        <th class="text-center">Added Date</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($brands as $brand)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">#{{ $brand->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-sm rounded-circle bg-primary text-white text-xs fw-bold">
                                    {{ strtoupper(substr($brand->brand_name, 0, 2)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $brand->brand_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-muted">{{ Str::limit($brand->notes, 80) ?: 'No additional notes' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-xs fw-bold text-muted">{{ $brand->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="toggleEditModal({{ $brand->id }})" class="btn btn-sm btn-white text-info" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button wire:click="toggleDeleteModal({{ $brand->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-shield-check"></i></div>
                            <h6 class="text-muted">No brands found in the record.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($brands->hasPages())
        <div class="p-4 border-top bg-light-soft">
            {{ $brands->links('livewire::bootstrap') }}
        </div>
        @endif
    </div>

    <!-- Modals -->

    <!-- Add/Edit Modal -->
    @if($showAddModal || $showEditModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">
                        {{ $showAddModal ? 'Register New Brand' : 'Update Brand Info' }}
                    </h4>
                    <button type="button" class="btn-close" wire:click="$set('{{ $showAddModal ? 'showAddModal' : 'showEditModal' }}', false)"></button>
                </div>
                <form wire:submit.prevent="{{ $showAddModal ? 'save' : 'update' }}">
                    <div class="modal-body p-5">
                        <div class="mb-4">
                            <label class="form-label text-sm fw-bold">Brand Name</label>
                            <input type="text" wire:model="brand_name" class="form-control" placeholder="E.g. Sony, Samsung, Local Hardware">
                            @error('brand_name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-sm fw-bold">Note / Description</label>
                            <textarea wire:model="notes" class="form-control" rows="4" placeholder="Optional notes about this brand..."></textarea>
                            @error('notes') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light-soft p-4">
                        <button type="button" class="btn btn-secondary px-4" wire:click="$set('{{ $showAddModal ? 'showAddModal' : 'showEditModal' }}', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-premium">
                            {{ $showAddModal ? 'Create Brand' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-body p-5 text-center">
                    <div class="icon-shape icon-xl bg-danger-soft text-danger mb-4 mx-auto rounded-circle">
                        <i class="bi bi-trash3 fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Remove Brand?</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete this brand? Products associated with it will remain but without a brand reference. This cannot be undone.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-light-soft px-4" wire:click="$set('showDeleteModal', false)">No, Keep it</button>
                        <button class="btn btn-danger px-4 shadow-danger" wire:click="delete">Yes, Delete now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>