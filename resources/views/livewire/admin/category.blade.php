<div class="container-fluid py-2">

    <!-- Success Message -->
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show mb-5 rounded-3 shadow-sm" role="alert" style="border-left: 5px solid #28a745; color: #233D7F; background: #e6f4ea;">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            {{ session('message') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    <!-- Header Section -->
    <div class="card-header text-white p-5  d-flex align-items-center"
        style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); border-radius: 20px 20px 0 0;">
        <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
            <i class="bi bi-collection fs-4 text-white" aria-hidden="true"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold tracking-tight text-white">Product Category Details</h3>
            <p class="text-white opacity-80 mb-0 text-sm">Monitor and manage your Product Category Details</p>
        </div>
    </div>
    <div class="card-header bg-transparent pb-4 mt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 border-bottom" style="border-color: #233D7F;">

        <!-- Middle: Search Bar -->
        <div class="flex-grow-1 d-flex justify-content-lg">
            <div class="input-group" style="max-width: 600px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
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

        <!-- Right: Buttons -->
        <div class="d-flex gap-2 flex-shrink-0 justify-content-lg-end">
            <button
                class="btn btn-primary rounded-full px-4 fw-medium transition-all hover:shadow w-100"
                wire:click="toggleAddModal"
                style="background-color: #233D7F; border-color: #233D7F; color: white;transition: all 0.3s ease; hover: transform: scale(1.05)">
                <i class="bi bi-plus-circle me-2"></i>Add Category
            </button>

        </div>
    </div>

    <!-- Categories Table -->
    <div class="card-body p-1  pt-5 bg-transparent">
        <div class="table-responsive shadow-sm rounded-2 overflow-hidden">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-center py-3 ps-4">ID</th>
                        <th class="text-center py-3">Name</th>
                        <th class="text-center py-3">Description</th>
                        <th class="text-center py-3">Created At</th>
                        <th class="text-center py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr class="transition-all hover:bg-gray-50">
                        <td class="text-sm text-center ps-4 py-3">{{ $category->id }}</td>
                        <td class="text-sm text-center py-3">{{ $category->name }}</td>
                        <td class="text-sm text-center py-3">{{ $category->description }}</td>
                        <td class="text-sm text-center py-3">{{ $category->created_at->format('d/m/Y') }}</td>
                        <td class="text-center py-3">
                            <div class="d-flex justify-content-center gap-2">
                                <button
                                    class="btn btn-sm"
                                    wire:click="toggleEditModal({{ $category->id }})"
                                    style="color: #00C8FF;"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button
                                    class="btn btn-sm text-danger"
                                    wire:click="toggleDeleteModal({{ $category->id }})"
                                    title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4" style="color: #233D7F;">
                            <i class="bi bi-exclamation-circle me-2"></i>No categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 mx-2">
                {{ $categories->links('livewire::bootstrap') }}

            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    @if($showAddModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden" style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Add New Category</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100" wire:click="$set('showAddModal', false)" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-5">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium" style="color: #233D7F;">Category Name</label>
                            <input type="text" wire:model="name" class="form-control border-2 shadow-sm" style="border-color: #233D7F; color: #233D7F;">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium" style="color: #233D7F;">Description</label>
                            <textarea wire:model="description" class="form-control border-2 shadow-sm" rows="4" style="border-color: #233D7F; color: #233D7F;"></textarea>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow" wire:click="$set('showAddModal', false)" style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium transition-all hover:shadow" style="background-color: #00C8FF; border-color: #00C8FF; color: white;" onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';" onmouseout="this.style.backgroundColor='#00C8FF'; this.style.borderColor='#00C8FF';">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Category Modal -->
    @if($showEditModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden" style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Edit Category</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100" wire:click="$set('showEditModal', false)" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body p-5">
                        <div class="mb-4">
                            <label for="edit-name" class="form-label fw-medium" style="color: #233D7F;">Category Name</label>
                            <input type="text" wire:model="name" class="form-control border-2 shadow-sm" style="border-color: #233D7F; color: #233D7F;">
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="edit-description" class="form-label fw-medium" style="color: #233D7F;">Description</label>
                            <textarea wire:model="description" class="form-control border-2 shadow-sm" rows="4" style="border-color: #233D7F; color: #233D7F;"></textarea>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow" wire:click="$set('showEditModal', false)" style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium transition-all hover:shadow" style="background-color: #00C8FF; border-color: #00C8FF; color: white;" onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';" onmouseout="this.style.backgroundColor='#00C8FF'; this.style.borderColor='#00C8FF';">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden" style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #EF4444; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100" wire:click="$set('showDeleteModal', false)" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5" style="color: #233D7F;">
                    <p class="mb-0">Are you sure you want to delete this category? This action cannot be undone.</p>
                </div>
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow" wire:click="$set('showDeleteModal', false)" style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-medium transition-all hover:shadow" wire:click="delete" style="background-color: #EF4444; border-color: #EF4444; color: white;" onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';" onmouseout="this.style.backgroundColor='#EF4444'; this.style.borderColor='#EF4444';">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
    }

    .container-fluid {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    .card-header {
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(35, 61, 127, 0.1);
    }

    .icon-shape {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-shape.icon-lg {
        width: 3rem;
        height: 3rem;
    }

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }

    .hover\:shadow:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    /* Table Styling */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, #eff6ff 0%, #f0f4ff 100%);
        color: #233d7f;
        font-weight: 700;
        border: 1px solid #e5e7eb;
        padding: 1rem 0.75rem !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .table tbody tr:hover {
        background-color: #eff6ff;
        box-shadow: 0 4px 12px rgba(35, 61, 127, 0.08);
    }

    .table tbody td {
        padding: 1rem 0.75rem !important;
        color: #4b5563;
        vertical-align: middle;
        border: 1px solid #e5e7eb;
    }

    /* Button Styling */
    .btn {
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary,
    .btn-primary:hover {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(35, 61, 127, 0.3);
    }

    .btn-secondary {
        background-color: #6b7280;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    /* Modal Styling */
    .modal-content {
        border: 2px solid #233d7f;
        border-radius: 1rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        color: white;
        border-bottom: none;
        border-radius: 1rem 1rem 0 0;
    }

    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e5e7eb;
    }

    /* Form Control Styling */
    .form-control,
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #233d7f;
        box-shadow: 0 0 0 0.2rem rgba(35, 61, 127, 0.15);
        background-color: #ffffff;
    }

    .form-label {
        color: #233d7f;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /* Alert Styling */
    .alert {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(35, 61, 127, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    /* Input Group Styling */
    .input-group {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .input-group-text {
        background-color: #f3f4f6 !important;
        border: 1px solid #e5e7eb !important;
        color: #233d7f;
    }

    .input-group .form-control {
        border: 1px solid #e5e7eb !important;
    }

    .input-group .form-control:focus {
        border-color: #233d7f !important;
    }

    /* Rounded Button */
    .rounded-full {
        border-radius: 9999px;
    }

    .rounded-pill {
        border-radius: 9999px;
    }

    /* Text Styles */
    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .fw-medium {
        font-weight: 500;
    }

    /* Pagination Styling */
    .pagination {
        margin-top: 1.5rem;
    }

    .page-link {
        color: #233d7f;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: #eff6ff;
        border-color: #233d7f;
        color: #1e3a8a;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%);
        border-color: #233d7f;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem 0.5rem;
        }

        .card-header {
            flex-direction: column !important;
            text-align: center;
        }

        .table {
            font-size: 0.875rem;
        }

        .table thead th {
            padding: 0.75rem 0.5rem !important;
        }

        .table tbody td {
            padding: 0.75rem 0.5rem !important;
        }
    }
</style>
@endpush

@push('script')
<!-- Include Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function() {
                alert.classList.add('show');
            }, 100);
        }
    });
</script>
@endpush