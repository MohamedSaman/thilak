<div class="container-fluid py-1">

    <!-- Header Section -->
    <div class="card-header text-white p-5  d-flex align-items-center"
        style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); border-radius: 1rem; box-shadow: 0 4px 15px rgba(35, 61, 127, 0.1);">
        <div
            class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
            <i class="bi bi-box-seam fs-4 text-white" aria-hidden="true"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold tracking-tight text-white">Product Details</h3>
            <p class="text-white opacity-80 mb-0 text-sm">Monitor and manage your Product Details</p>
        </div>
    </div>
    <div class="card-header bg-transparent pb-4 mt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 border-bottom"
        style="border-color: #233D7F;">
        <!-- Middle: Search Bar -->
        <div class="flex-grow-1 d-flex justify-content-lg">
            <div class="input-group" style="max-width: 600px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border-radius: 0.5rem; overflow: hidden;">
                <span class="input-group-text bg-gray-100 border-0 px-3" style="background-color: #f3f4f6 !important;">
                    <i class="bi bi-search text-primary"></i>
                </span>
                <input type="text" class="form-control" placeholder="Search products..."
                    wire:model.live.debounce.300ms="search" autocomplete="off" style="border: 1px solid #e5e7eb;">
            </div>
        </div>

        <!-- Right: Buttons -->
        <div class="d-flex gap-2 flex-shrink-0 justify-content-lg-end">
            <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-all"
                wire:click="toggleAddModal"
                style="color: #fff; background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%); border: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="bi bi-plus-circle me-2"></i> Add Product
            </button>
            <button wire:click="exportToCSV"
                class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-all"
                aria-label="Export stock details to CSV"
                style="color: #fff; background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%); border: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="bi bi-download me-1" aria-hidden="true"></i> Export CSV
            </button>

        </div>
    </div>
    <div class="d-flex  align-items-center justify-content-between gap-4">
        <p class="ms-4">You can create custom field here <i class="bi bi-arrow-right ms-4"></i></p>
        <button class="btn btn-primary rounded-full mt-2 px-4 mb-4 fw-medium transition-all"
            wire:click="$set('showAddFieldModal', true)"
            style="background: linear-gradient(135deg, #233d7f 0%, #1e3a8a 100%); border: none; color: white;">
            <i class="bi bi-plus-circle me-2"></i>Add Field
        </button>
    </div>

    <!-- Products Table -->
    <div class="card-body py-0 px-1  bg-transparent">
        <div class="table-responsive shadow-sm rounded-2 overflow-auto">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Image</th>
                        <th class="py-3">Product Code</th>
                        <th class="py-3">Product Name</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Brand</th>
                        <th class="py-3">Supplier Price</th>
                        <th class="py-3">MRP Price</th>
                        <th class="py-3">Selling Price</th>

                        <th class="py-3">Quantity Inhand</th>
                        <th class="py-3">Sold</th>
                        <th class="py-3 text-center">Stock</th>

                        @foreach ($fieldKeys as $key)
                        <th class="text-center py-3 ">{{ $key }}</th>
                        @endforeach
                        <th class="text-center py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                    <tr class="transition-all hover:bg-gray-50">
                        <td class="ps-4 py-3">{{ $product->id }}</td>
                        <td class="py-3">
                            @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" style="max-width: 60px; max-height: 60px; border-radius: 4px; object-fit: cover;" title="{{ $product->product_name }}">
                            @else
                            <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td class="py-3">{{ $product->product_code }}</td>
                        <td class="py-3">{{ $product->product_name }}</td>
                        <td class="py-3">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="py-3">{{ $product->brand->brand_name ?? 'N/A' }}</td>
                        <td class="py-3">Rs. {{ number_format($product->supplier_price, 2) }}</td>
                        <td class="py-3">{{ $product->mrp_price ? 'Rs. ' . number_format($product->mrp_price, 2) : '-' }}</td>
                        <td class="py-3">Rs. {{ number_format($product->selling_price, 2) }}</td>
                        <td class="py-3 text-center">
                            @php
                            $stockTotal = ($product->stock_quantity ?? 0) + ($product->damage_quantity ?? 0);
                            $stockClass = $stockTotal > 10 ? 'text-success fw-bold' : ($stockTotal > 0 ? 'text-warning fw-semibold' : 'text-danger fw-bold');
                            @endphp
                            <span class="{{ $stockClass }}">{{ $stockTotal }}</span>
                        </td>
                        <td class="py-3 text-center">{{ $product->sold }}</td>
                        <td class="py-3 text-center">
                            @if($product->stock_quantity > 0)
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill">In Stock</span>
                            @else
                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill">Out of Stock</span>
                            @endif
                        </td>

                        @foreach ($fieldKeys as $key)
                        <td class="text-center py-3">{{ $product->customer_field[$key] ?? '-' }}</td>
                        @endforeach
                        <td class="text-center py-3">
                            <div class="d-flex justify-content-center gap-2">
                                <button
                                    class="btn btn-sm "
                                    wire:click="viewProduct({{ $product->id }})"
                                    style="color: #233D7F;"
                                    title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button
                                    class="btn btn-sm "
                                    wire:click="editProduct({{ $product->id }})"
                                    style="color: #00C8FF;"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button
                                    class="btn btn-sm "
                                    wire:click="confirmDelete({{ $product->id }})"
                                    style=" color: #EF4444;"
                                    title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 10 + count($fieldKeys) }}" class="text-center py-4" style="color: #233D7F;">
                            <i class="bi bi-exclamation-circle me-2"></i>No products found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="mt-4">
        {{ $products->links('livewire::bootstrap') }}
    </div>

    <!-- Add Product Modal -->
    @if ($showAddModal)
    <div class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 900px;">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                        wire:click="toggleAddModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body p-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-medium"
                                    style="color: #233D7F;">Category</label>
                                <select id="category_id" wire:model="category_id" class="form-select border-2 shadow-sm"
                                    style=" color: #233D7F;">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand_id" class="form-label fw-medium"
                                    style="color: #233D7F;">Brand</label>
                                <select id="brand_id" wire:model="brand_id" class="form-select border-2 shadow-sm"
                                    style=" color: #233D7F;">
                                    <option value="">Select a brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_name" class="form-label fw-medium" style="color: #233D7F;">Product
                                    Name</label>
                                <input type="text" id="product_name" wire:model="product_name"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;">
                                @error('product_name') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_code" class="form-label fw-medium" style="color: #233D7F;">Product
                                    Code</label>
                                <input type="text" id="product_code" wire:model="product_code"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;">
                                @error('product_code') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="image_url" class="form-label fw-medium" style="color: #233D7F;">Image URL</label>
                                <input type="url" id="image_url" wire:model.live="image_url"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;" placeholder="https://example.com/image.jpg">
                                @error('image_url') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @if($image_url)
                                <div class="mt-3">
                                    <img src="{{ $image_url }}" alt="Image Preview" style="max-width: 200px; max-height: 200px; border-radius: 4px; object-fit: cover; border: 2px solid #233D7F;">
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="supplier_price" class="form-label fw-medium"
                                    style="color: #233D7F;">Supplier Price</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0">Rs.</span>
                                    <input type="number" id="supplier_price" wire:model="supplier_price"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;" step="0.01">
                                </div>
                                @error('supplier_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="selling_price" class="form-label fw-medium" style="color: #233D7F;">Selling
                                    Price</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0">Rs.</span>
                                    <input type="number" id="selling_price" wire:model="selling_price"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;" step="0.01">
                                </div>
                                @error('selling_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="mrp_price" class="form-label fw-medium" style="color: #233D7F;">MRP
                                    Price</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-2 border-end-0">Rs.</span>
                                    <input type="number" id="mrp_price" wire:model="mrp_price"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;" step="0.01">
                                </div>
                                @error('mrp_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stock_quantity" class="form-label fw-medium" style="color: #233D7F;">Total
                                    Quantity</label>
                                <input type="number" id="stock_quantity" wire:model="stock_quantity"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;" min="0" step="1">
                                @error('stock_quantity') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="damage_quantity" class="form-label fw-medium" style="color: #233D7F;">Damage
                                    Quantity</label>
                                <input type="number" id="damage_quantity" wire:model="damage_quantity"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;" min="0" step="1">
                                @error('damage_quantity') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <h6 class="fw-bold text-uppercase text-muted">Customer Fields</h6>
                            <div class="row g-4">
                                @foreach ($fieldKeys as $key)
                                <div class="col-md-6 mb-3">
                                    <label for="customer_field_{{ $key }}" class="form-label fw-medium"
                                        style="color: #233D7F;">{{ $key }}</label>
                                    <input type="text" id="customer_field_{{ $key }}"
                                        wire:model="customer_fields.{{ $loop->index }}.value"
                                        class="form-control border-2 shadow-sm" style="color: #233D7F;"
                                        placeholder="Enter {{ $key }}">
                                    @error('customer_fields.' . $loop->index . '.value') <div
                                        class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer py-3 px-4 d-flex justify-content-end gap-3"
                        style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                        <button type="button"
                            class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                            wire:click="toggleAddModal"
                            style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                        <button type="submit"
                            class="btn btn-primary rounded-pill px-4 fw-medium transition-all hover:shadow"
                            style="background-color: #00C8FF; border-color: #00C8FF; color: white;"
                            onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';"
                            onmouseout="this.style.backgroundColor='#00C8FF'; this.style.borderColor='#00C8FF';">Save
                            Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Product Modal -->
    @if ($showEditModal)
    <div class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                        wire:click="toggleEditModal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body p-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="edit_category_id" class="form-label fw-medium"
                                    style="color: #233D7F;">Category</label>
                                <select id="edit_category_id" wire:model="category_id"
                                    class="form-select border-2 shadow-sm" style=" color: #233D7F;">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_brand_id" class="form-label fw-medium"
                                    style="color: #233D7F;">Brand</label>
                                <select id="edit_brand_id" wire:model="brand_id"
                                    class="form-select border-2 shadow-sm" style=" color: #233D7F;">
                                    <option value="">Select a brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="edit_product_name" class="form-label fw-medium"
                                    style="color: #233D7F;">Product Name</label>
                                <input type="text" id="edit_product_name" wire:model="product_name"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;">
                                @error('product_name') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_product_code" class="form-label fw-medium"
                                    style="color: #233D7F;">Product Code</label>
                                <input type="text" id="edit_product_code" wire:model="product_code"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;">
                                @error('product_code') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="edit_image_url" class="form-label fw-medium"
                                    style="color: #233D7F;">Image URL</label>
                                <input type="url" id="edit_image_url" wire:model.live="image_url"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;" placeholder="https://example.com/image.jpg">
                                @error('image_url') <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @if($image_url)
                                <div class="mt-3">
                                    <img src="{{ $image_url }}" alt="Image Preview" style="max-width: 200px; max-height: 200px; border-radius: 4px; object-fit: cover; border: 2px solid #233D7F;">
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="edit_supplier_price" class="form-label fw-medium"
                                        style="color: #233D7F;">Supplier Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-2 border-end-0"
                                            style="">Rs.</span>
                                        <input type="number" id="edit_supplier_price" wire:model="supplier_price"
                                            class="form-control border-2 shadow-sm" style=" color: #233D7F;"
                                            step="0.01">
                                    </div>
                                    @error('supplier_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="edit_selling_price" class="form-label fw-medium"
                                        style="color: #233D7F;">Selling Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-2 border-end-0"
                                            style="">Rs.</span>
                                        <input type="number" id="edit_selling_price" wire:model="selling_price"
                                            class="form-control border-2 shadow-sm" style=" color: #233D7F;"
                                            step="0.01">
                                    </div>
                                    @error('selling_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="edit_mrp_price" class="form-label fw-medium"
                                        style="color: #233D7F;">MRP Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-2 border-end-0"
                                            style="">Rs.</span>
                                        <input type="number" id="edit_mrp_price" wire:model="mrp_price"
                                            class="form-control border-2 shadow-sm" style=" color: #233D7F;"
                                            step="0.01">
                                    </div>
                                    @error('mrp_price') <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="edit_stock_quantity" class="form-label fw-medium"
                                        style="color: #233D7F;">Stock Quantity</label>
                                    <input type="number" id="edit_stock_quantity" wire:model="stock_quantity"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;" min="0"
                                        step="1">
                                    @error('stock_quantity') <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="edit_damage_quantity" class="form-label fw-medium"
                                        style="color: #233D7F;">Damage Quantity</label>
                                    <input type="number" id="edit_damage_quantity" wire:model="damage_quantity"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;" min="0"
                                        step="1">
                                    @error('damage_quantity') <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium" style="color: #233D7F;">Sold Quantity</label>
                                <input type="number" id="edit_sold_quantity" wire:model="sold"
                                    class="form-control border-2 shadow-sm" style=" color: #233D7F;" min="0" step="1">
                                @error('sold') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label fw-medium" style="color: #233D7F;">Status</label>
                                <select id="status" wire:model="status" class="form-select border-2 shadow-sm"
                                    style=" color: #233D7F;">
                                    <option value="">Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Unavailable">Unavailable</option>
                                </select>
                                @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>


                        <!-- customer Fields -->
                        <div class="mb-4">
                            <label class="form-label fw-medium" style="color: #233D7F;">Customer Fields</label>
                            <div class="row g-3">
                                @foreach ($customer_fields as $index => $field)
                                @php
                                $labelKey = ucwords(strtolower($field['key']));
                                @endphp
                                <div class="col-md-6">
                                    <label class="form-label fw-medium" style="color: #233D7F;">{{ $labelKey }}</label>
                                    <input type="text" placeholder="Enter {{ $labelKey }}"
                                        wire:model="customer_fields.{{ $index }}.value"
                                        class="form-control border-2 shadow-sm" style=" color: #233D7F;">
                                    @error('customer_fields.' . $index . '.value') <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div> @enderror
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                        <button type="button"
                            class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                            wire:click="toggleEditModal"
                            style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                        <button type="submit"
                            class="btn btn-primary rounded-pill px-4 fw-medium transition-all hover:shadow"
                            style="background-color: #00C8FF; border-color: #00C8FF; color: white;"
                            onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';"
                            onmouseout="this.style.backgroundColor='#00C8FF'; this.style.borderColor='#00C8FF';">Update
                            Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
    <div class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                        wire:click="toggleDeleteModal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5" style="color: #233D7F;">
                    <p class="mb-0">Are you sure you want to delete the product "<strong>{{ $deletingProductName
                            }}</strong>"? This action cannot be undone.</p>
                </div>
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                    <button type="button"
                        class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click="toggleDeleteModal"
                        style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click="delete" style="background-color: #EF4444; border-color: #EF4444; color: white;"
                        onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';"
                        onmouseout="this.style.backgroundColor='#EF4444'; this.style.borderColor='#EF4444';">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Field Modal -->
    @if ($showAddFieldModal)
    <div class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">

                <!-- Modal Header -->
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Add New Fields</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                        wire:click="$set('showAddFieldModal', false)" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-5">
                    <!-- Input for new fields -->
                    <input wire:model="newFieldKey" class="form-control border-2 shadow-sm mb-3"
                        placeholder="Enter field names" style=" color: #233D7F; " />
                    @error('newFieldKey') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                    <!-- Display all fields (existing + newly added) -->
                    @if (!empty($fieldKeys))
                    <div class="mt-4">
                        <h6 class="fw-bold mb-2">Fields:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($fieldKeys as $field)
                            <span class="badge bg-primary d-flex align-items-center px-3 py-2 rounded-pill">
                                {{ $field }}
                                <button type="button" wire:click.prevent="manageField('delete', '{{ $field }}')"
                                    class="btn btn-sm btn-danger ms-4 rounded-circle p-0"
                                    style="width: 20px; height: 20px; font-size: 12px; line-height: 1;">
                                    &times;
                                </button>
                            </span>

                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                    <button class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click="$set('showAddFieldModal', false)"
                        style="background-color: #6B7280; border-color: #6B7280; color: white;">Cancel</button>
                    <button class="btn btn-primary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click.prevent="manageField('add')"
                        style="background-color: #00C8FF; border-color: #00C8FF; color: white;"
                        onmouseover="this.style.backgroundColor='#233D7F'; this.style.borderColor='#233D7F';"
                        onmouseout="this.style.backgroundColor='#00C8FF'; this.style.borderColor='#00C8FF';">Add</button>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- Delete Field Modal -->
    @if ($showDeleteFieldModal)
    <div class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);" x-data="{ isOpen: true }"
        x-show="isOpen" @keydown.escape="$wire.set('showDeleteFieldModal', false)">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl overflow-hidden"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="modal-header py-3 px-4" style="background-color: #233D7F; color: white;">
                    <h5 class="modal-title fw-bold tracking-tight">Delete Field</h5>
                    <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                        wire:click="$set('showDeleteFieldModal', false)" @click="isOpen = false"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    <label for="deleteFieldKey" class="form-label fw-medium" style="color: #233D7F;">Select a field to
                        delete</label>
                    <select wire:model.live="deleteFieldKey" id="deleteFieldKey" class="form-select border-2 shadow-sm"
                        style="border-color: #233D7F; color: #233D7F;" wire:loading.attr="disabled">
                        <option value="">Select Field</option>
                        @foreach ($fieldKeys as $key)
                        <option value="{{ $key }}">{{ $key }}</option>
                        @endforeach
                    </select>
                    @error('deleteFieldKey') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer py-3 px-4" style="border-top: 1px solid #233D7F; background: #f8f9fa;">
                    <button class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click="$set('showDeleteFieldModal', false)" @click="isOpen = false"
                        style="background-color: #6B7280; border-color: #6B7280; color: white;"
                        wire:loading.attr="disabled">
                        Cancel
                    </button>
                    <button class="btn btn-danger rounded-pill px-4 fw-medium transition-all hover:shadow"
                        wire:click="deleteField" style="background-color: #DC3545; border-color: #DC3545; color: white;"
                        onmouseover="this.style.backgroundColor='#A71D2A'; this.style.borderColor='#A71D2A';"
                        onmouseout="this.style.backgroundColor='#DC3545'; this.style.borderColor='#DC3545';"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="deleteField">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Deleting...
                        </span>
                        <span wire:loading.remove wire:target="deleteField">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- View Product Modal -->
    @if ($showViewModal)
    <div wire:ignore.self class="modal fade show d-block" id="viewProductModal" tabindex="-1"
        aria-labelledby="viewProductModalLabel" aria-hidden="true"
        style="background-color: rgba(0, 0, 0, 0.65); backdrop-filter: blur(8px);">

        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-xl border-0 overflow-hidden" style="background: #ffffff;">

                <!-- Header -->
                <div class="modal-header py-4 px-5 border-0"
                    style="background: linear-gradient(135deg, #233D7F, #1b2655); color: white;">
                    <h4 class="modal-title fw-bold d-flex align-items-center mb-0" id="viewProductModalLabel">
                        <i class="bi bi-box-seam me-2"></i>
                        Product Details – {{ $this->viewProductName ?? 'N/A' }}
                    </h4>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('showViewModal', false)"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body px-5 py-4">
                    <div class="row g-4">

                        <!-- Left: Product Image & Status -->
                        <div class="col-md-5">
                            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-center">
                                @php
                                $product = \App\Models\ProductDetail::find(
                                $this->viewProductCode
                                ? \App\Models\ProductDetail::where('product_code', $this->viewProductCode)->first()->id
                                : null
                                );
                                @endphp

                                @if ($product && $product->image_url)
                                <img src="{{ $product->image_url }}"
                                    alt="{{ $this->viewProductName ?? 'Product Image' }}"
                                    class="img-fluid rounded-3 mb-3 shadow-sm"
                                    style="max-height: 300px; object-fit: cover; width: 100%;">
                                @elseif ($product && $product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $this->viewProductName ?? 'Product Image' }}"
                                    class="img-fluid rounded-3 mb-3 shadow-sm"
                                    style="max-height: 300px; object-fit: cover; width: 100%;">
                                @else
                                <img src="{{ asset('images/defualt.jpg') }}"
                                    alt="Default Image"
                                    class="img-fluid rounded-3 mb-3 shadow-sm"
                                    style="max-height: 300px; object-fit: cover; width: 100%;">
                                @endif

                                <div class="mt-2">
                                    <span class="fw-semibold text-secondary me-2">
                                        <i class="bi bi-info-circle"></i> Status:
                                    </span>
                                    <span class="badge fs-6 px-3 py-2 rounded-pill 
                                    {{ $this->viewStatus == 'Available' ? 'bg-success' : 
                                       ($this->viewStatus == 'Low Stock' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ $this->viewStatus ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Product Details -->
                        <div class="col-md-7">
                            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                                <h5 class="fw-bold mb-4 text-primary">Product Information</h5>
                                <div class="row g-3">

                                    <div class="col-12">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-tag-fill text-primary me-2"></i>Category:</span>
                                        <span class="text-dark">{{ $this->viewCategoryName ?? 'N/A' }}</span>
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-building text-primary me-2"></i>Brand:</span>
                                        <span class="text-dark">{{ $this->viewBrandName ?? 'N/A' }}</span>
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-box-seam text-primary me-2"></i>Name:</span>
                                        <span class="text-dark">{{ $this->viewProductName ?? 'N/A' }}</span>
                                    </div>

                                    <div class="col-12">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-upc text-primary me-2"></i>Code:</span>
                                        <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                                            {{ $this->viewProductCode ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="col-6">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-currency-dollar text-primary me-2"></i>Supplier
                                            Price:</span>
                                        <span class="fw-semibold text-dark">Rs.{{ number_format($this->viewSupplierPrice
                                            ?? 0, 2) }}</span>
                                    </div>

                                    <div class="col-6">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-wallet2 text-primary me-2"></i>Selling Price:</span>
                                        <span class="fw-semibold text-dark">Rs.{{ number_format($this->viewSellingPrice
                                            ?? 0, 2) }}</span>
                                    </div>

                                    <div class="col-6">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-tag text-primary me-2"></i>MRP Price:</span>
                                        <span class="fw-semibold text-dark">{{ $this->viewMrpPrice ? 'Rs.' . number_format($this->viewMrpPrice, 2) : '-' }}</span>
                                    </div>

                                    <div class="col-4">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-box text-primary me-2"></i>Stock:</span>
                                        @php
                                        $vStock = $this->viewStockQuantity ?? 0;
                                        $vStockClass = $vStock > 10 ? 'text-success fw-bold' : ($vStock > 0 ? 'text-warning fw-semibold' : 'text-danger fw-bold');
                                        @endphp
                                        <span class="{{ $vStockClass }}">{{ $vStock }}</span>
                                    </div>

                                    <div class="col-4">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-exclamation-triangle text-danger me-2"></i>Damage:</span>
                                        <span class="fw-semibold text-dark">{{ $this->viewDamageQuantity ?? 0 }}</span>
                                    </div>

                                    <div class="col-4">
                                        <span class="fw-medium text-secondary"><i
                                                class="bi bi-cart-check text-primary me-2"></i>Sold:</span>
                                        <span class="fw-semibold text-dark">{{ $this->viewSoldQuantity ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Full Width: Custom Fields -->
                        @if (!empty($this->viewCustomerFields))
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header py-3 px-4 bg-light">
                                    <h6 class="fw-bold text-uppercase text-muted mb-0">Custom Fields</h6>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($this->viewCustomerFields as $key => $value)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                            <span class="fw-medium text-secondary">{{ $key }}:</span>
                                            <span class="fw-semibold text-dark">{{ $value ?? '-' }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer py-3 px-5 border-0" style="background: #f9fafb;">
                    <button type="button" class="btn rounded-pill px-4 fw-semibold text-white shadow-sm"
                        onclick="printProductDetails()" style="background-color: #233D7F;">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                    <button type="button" class="btn rounded-pill px-4 fw-semibold text-white shadow-sm"
                        wire:click="$set('showViewModal', false)" style="background-color: #6B7280;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .container-fluid {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%);
            min-height: 100vh;
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

        .btn-primary {
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @endpush

    @push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(function() {
                    alert.classList.add('show');
                }, 100);
                setTimeout(function() {
                    alert.classList.remove('show');
                }, 5000);
            }
        });
    </script>
    @endpush
</div>