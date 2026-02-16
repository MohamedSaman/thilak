<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Product Inventory</h2>
                    <p class="text-muted mb-0">Manage your product catalog and warehouse stock.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <button wire:click="exportToCSV" class="btn btn-secondary shadow-premium">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </button>
                <button wire:click="exportPDF" class="btn btn-secondary shadow-premium">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </button>
                <button wire:click="toggleAddModal" class="btn btn-primary shadow-premium">
                    <i class="bi bi-plus-lg me-2"></i>Add Product
                </button>
            </div>
        </div>
    </div>

    <!-- Action Bar & Search -->
    <div class="glass-card p-3 mb-4 rounded-xl">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-transparent border-0 pe-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control border-0 bg-transparent py-2" 
                           placeholder="Search by code, name, category or brand..."
                           wire:model.live.debounce.300ms="search">
                </div>
            </div>
            <!-- <div class="col-md-2 text-md-end ms-auto">
                 <button class="btn btn-sm btn-light-soft fw-bold text-xs" wire:click="$set('showAddFieldModal', true)">
                    <i class="bi bi-gear me-1"></i>Custom Fields
                </button>
            </div> -->
        </div>
    </div>

    <!-- Products Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Product Info</th>
                        <th>Category/Brand</th>
                        <th>Pricing</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Status</th>
                        @foreach ($fieldKeys as $key)
                        <th class="text-center">{{ $key }}</th>
                        @endforeach
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">#{{ $product->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-md rounded overflow-hidden bg-light">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="" class="img-fluid object-cover h-100 w-100">
                                    @else
                                        <i class="bi bi-box2 text-muted fs-4"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $product->product_name }}</h6>
                                    <span class="text-xs text-muted fw-bold">{{ $product->product_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge badge-primary-soft text-xs mb-1 d-inline-block">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                <span class="text-xs text-muted">{{ $product->brand->brand_name ?? 'Generic' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-primary">Rs.{{ number_format($product->selling_price, 2) }}</span>
                                <span class="text-xs text-muted strike">Rs.{{ number_format($product->mrp_price, 2) }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $total_stock = ($product->stock_quantity ?? 0) + ($product->damage_quantity ?? 0);
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold {{ $total_stock <= 10 ? 'text-danger' : 'text-dark' }}">{{ $total_stock }}</span>
                                <span class="text-xs text-muted">units</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $product->stock_quantity > 0 ? 'badge-success-soft' : 'badge-danger-soft' }} px-3 py-2 rounded-pill">
                                {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </td>
                        @foreach ($fieldKeys as $key)
                        <td class="text-center text-sm font-medium">{{ $product->customer_field[$key] ?? '-' }}</td>
                        @endforeach
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button wire:click="viewProduct({{ $product->id }})" class="btn btn-sm btn-white text-primary" title="View"><i class="bi bi-eye"></i></button>
                                <button wire:click="editProduct({{ $product->id }})" class="btn btn-sm btn-white text-info" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button wire:click="confirmDelete({{ $product->id }})" class="btn btn-sm btn-white text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 7 + count($fieldKeys) }}" class="text-center py-5">
                            <div class="opacity-30 mb-3 fs-1"><i class="bi bi-inbox"></i></div>
                            <h6 class="text-muted">No products found matches your criteria.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="p-4 border-top bg-light-soft">
            {{ $products->links('livewire::bootstrap') }}
        </div>
        @endif
    </div>

    <!-- Modals (Add/Edit/View/Delete) -->
    
    <!-- Add/Edit Modal -->
    @if ($showAddModal || $showEditModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">
                        {{ $showAddModal ? 'Register New Product' : 'Edit Product Details' }}
                    </h4>
                    <button type="button" class="btn-close" wire:click="{{ $showAddModal ? 'toggleAddModal' : 'toggleEditModal' }}"></button>
                </div>
                <form wire:submit.prevent="{{ $showAddModal ? 'save' : 'update' }}">
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <!-- Basic Info Section -->
                        <div class="col-md-12">
                            <h6 class="text-uppercase text-xs fw-bold tracking-wider text-muted mb-4">Basic Information</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-bold">Product Name</label>
                            <input type="text" wire:model="product_name" class="form-control" placeholder="Enter product name">
                            @error('product_name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-bold">Product Code</label>
                            <input type="text" wire:model="product_code" class="form-control" placeholder="SKU-XXXX">
                            @error('product_code') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-bold">Image URL</label>
                            <input type="url" wire:model.live="image_url" class="form-control" placeholder="https://...">
                            @if($image_url)
                                <div class="mt-2 text-center bg-light p-2 rounded">
                                    <img src="{{ $image_url }}" alt="Preview" style="max-height: 80px;" class="rounded shadow-sm">
                                </div>
                            @endif
                        </div>

                        <!-- Categorization Section -->
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Category</label>
                            <select wire:model="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-sm fw-bold">Brand</label>
                            <select wire:model="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Pricing & Stock Section -->
                        <div class="col-md-12 mt-5">
                            <h6 class="text-uppercase text-xs fw-bold tracking-wider text-muted mb-4">Pricing & Inventory</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-bold">Supplier Price (Rs.)</label>
                            <input type="number" wire:model="supplier_price" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-bold">MRP Price (Rs.)</label>
                            <input type="number" wire:model="mrp_price" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-bold">Selling Price (Rs.)</label>
                            <input type="number" wire:model="selling_price" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-sm fw-bold">Starting Stock</label>
                            <input type="number" wire:model="stock_quantity" class="form-control">
                        </div>

                        <!-- Special Fields Section -->
                        @if(count($fieldKeys) > 0)
                        <div class="col-md-12 mt-5">
                            <h6 class="text-uppercase text-xs fw-bold tracking-wider text-muted mb-4">Additional Details</h6>
                        </div>
                        @foreach ($fieldKeys as $index => $key)
                        <div class="col-md-4">
                            <label class="form-label text-sm fw-bold">{{ $key }}</label>
                            <input type="text" wire:model="customer_fields.{{ $index }}.value" class="form-control" placeholder="Enter {{ $key }}">
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" wire:click="{{ $showAddModal ? 'toggleAddModal' : 'toggleEditModal' }}">Discard</button>
                    <button type="submit" class="btn btn-primary px-5 shadow-premium">
                        {{ $showAddModal ? 'Create Product' : 'Save Changes' }}
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-body p-5 text-center">
                    <div class="icon-shape icon-xl bg-danger-soft text-danger mb-4 mx-auto rounded-circle">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Delete Product?</h4>
                    <p class="text-muted mb-4">Are you sure you want to delete <strong>{{ $deletingProductName }}</strong>? This action will permanently remove the product and all associated data records.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-light-soft px-4" wire:click="toggleDeleteModal">No, Keep it</button>
                        <button class="btn btn-danger px-4 shadow-danger" wire:click="delete">Yes, Delete now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- View Product Modal -->
    @if ($showViewModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(10px);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Product Snapshot</h4>
                    <button type="button" class="btn-close" wire:click="$set('showViewModal', false)"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-5">
                        <div class="col-md-5">
                            <div class="card bg-light border-0 rounded-xl overflow-hidden shadow-sm aspect-square d-flex align-items-center justify-content-center">
                                @if($viewProductCode && ($prod = \App\Models\ProductDetail::where('product_code', $viewProductCode)->first()) && ($prod->image_url))
                                    <img src="{{ $prod->image_url }}" alt="" class="img-fluid object-cover w-100 h-100">
                                @else
                                    <i class="bi bi-box-seam fs-1 text-muted opacity-30"></i>
                                @endif
                            </div>
                            <div class="mt-4 text-center">
                                <span class="badge {{ $viewStatus == 'Available' ? 'badge-success-soft' : 'badge-danger-soft' }} fs-6 px-4 py-2 rounded-pill">
                                    {{ $viewStatus }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h3 class="fw-bold mb-1">{{ $viewProductName }}</h3>
                            <p class="text-primary fw-bold text-sm mb-4">{{ $viewProductCode }}</p>
                            
                            <div class="row g-3">
                                <div class="col-6 border-bottom pb-2">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Category</label>
                                    <span class="text-sm fw-bold text-dark">{{ $viewCategoryName }}</span>
                                </div>
                                <div class="col-6 border-bottom pb-2">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Brand</label>
                                    <span class="text-sm fw-bold text-dark">{{ $viewBrandName }}</span>
                                </div>
                                <div class="col-6 border-bottom pb-2">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Selling Price</label>
                                    <span class="text-sm fw-bold text-primary">Rs.{{ number_format($viewSellingPrice, 2) }}</span>
                                </div>
                                <div class="col-6 border-bottom pb-2">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">MRP Price</label>
                                    <span class="text-sm fw-bold text-muted strike">Rs.{{ number_format($viewMrpPrice, 2) }}</span>
                                </div>
                                <div class="col-4">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Stock</label>
                                    <span class="text-sm fw-bold {{ $viewStockQuantity <= 10 ? 'text-danger' : 'text-success' }}">{{ $viewStockQuantity }}</span>
                                </div>
                                <div class="col-4">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Sold</label>
                                    <span class="text-sm fw-bold text-dark">{{ $viewSoldQuantity }}</span>
                                </div>
                                <div class="col-4">
                                    <label class="text-xs text-muted text-uppercase fw-bold d-block">Damage</label>
                                    <span class="text-sm fw-bold text-danger">{{ $viewDamageQuantity }}</span>
                                </div>
                            </div>

                            @if(!empty($viewCustomerFields))
                            <div class="mt-4 pt-4 border-top">
                                <h6 class="text-xs fw-bold text-muted text-uppercase mb-3">Specification Details</h6>
                                <div class="row g-2">
                                    @foreach($viewCustomerFields as $key => $val)
                                    <div class="col-6">
                                        <div class="bg-light p-2 rounded text-sm text-truncate" title="{{ $key }}: {{ $val }}">
                                            <span class="text-muted">{{ $key }}:</span> <span class="fw-bold">{{ $val }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button class="btn btn-primary w-100 py-3 rounded-xl shadow-premium" wire:click="$set('showViewModal', false)">Close Overview</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom Fields Management Modal -->
    @if ($showAddFieldModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl">
                <div class="modal-header border-0 bg-light-soft py-4 px-5">
                    <h5 class="fw-bold mb-0">Manage Custom Fields</h5>
                    <button type="button" class="btn-close" wire:click="$set('showAddFieldModal', false)"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="input-group mb-4">
                        <input type="text" wire:model="newFieldKey" class="form-control" placeholder="E.g. Size, Color, Weight">
                        <button class="btn btn-primary" wire:click="manageField('add')">Add Field</button>
                    </div>
                    @error('newFieldKey') <p class="text-danger text-xs mt-n3 mb-3">{{ $message }}</p> @enderror

                    <label class="text-xs fw-bold text-muted text-uppercase mb-2 d-block">Current Active Fields</label>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse ($fieldKeys as $field)
                        <div class="badge badge-primary-soft d-flex align-items-center gap-2 px-3 py-2 rounded-lg">
                            <span class="fw-bold">{{ $field }}</span>
                            <button class="btn-close text-xs" style="width: 8px; height: 8px;" wire:click="manageField('delete', '{{ $field }}')"></button>
                        </div>
                        @empty
                        <p class="text-sm text-muted">No custom fields defined yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>