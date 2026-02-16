<div class="container-fluid py-2">

    <!-- Success Message -->
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('message') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Page Card -->
    <div class="card shadow-lg border-0 fade-in-up">
        <!-- Header Section -->
        <div class="card-header text-white p-4 d-flex align-items-center">
            <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3">
                <i class="bi bi-collection fs-4 text-white" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="mb-1 fw-bold tracking-tight text-white">Product Category Details</h3>
                <p class="text-white opacity-80 mb-0 text-sm">Monitor and manage your product categories</p>
            </div>
        </div>

        <!-- Search & Actions Bar -->
        <div class="card-header bg-transparent pb-4 mt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 border-bottom">
            <!-- Search Bar -->
            <div class="flex-grow-1 d-flex justify-content-lg">
                <div class="input-group" style="max-width: 600px;">
                    <span class="input-group-text bg-gray-100 border-0 px-3">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text"
                        class="form-control"
                        placeholder="Search category..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 flex-shrink-0 justify-content-lg-end">
                <button
                    class="btn btn-primary rounded-pill px-4 fw-medium"
                    wire:click="toggleAddModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Category
                </button>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card-body p-4 bg-transparent">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td class="text-center">{{ $category->id }}</td>
                            <td class="text-center fw-medium">{{ $category->name }}</td>
                            <td class="text-center">{{ $category->description }}</td>
                            <td class="text-center">{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button
                                        class="btn btn-sm btn-light"
                                        wire:click="toggleEditModal({{ $category->id }})"
                                        title="Edit">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <button
                                        class="btn btn-sm btn-light text-danger"
                                        wire:click="toggleDeleteModal({{ $category->id }})"
                                        title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-exclamation-circle me-2"></i>No categories found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $categories->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    @if($showAddModal)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Add New Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showAddModal', false)" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" wire:model="name" class="form-control" id="name" placeholder="Enter category name">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea wire:model="description" class="form-control" id="description" rows="4" placeholder="Enter description"></textarea>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" wire:click="$set('showAddModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Category Modal -->
    @if($showEditModal)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil me-2"></i>Edit Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showEditModal', false))" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="edit-name" class="form-label">Category Name</label>
                            <input type="text" wire:model="name" class="form-control" id="edit-name" placeholder="Enter category name">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea wire:model="description" class="form-control" id="edit-description" rows="4" placeholder="Enter description"></textarea>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" wire:click="$set('showEditModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="modal fade show d-block" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showDeleteModal', false)" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this category? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" wire:click="$set('showDeleteModal', false)">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" wire:click="delete">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
    @include('components.admin-styles')
@endpush