<div>
    @push('styles')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Manrope', sans-serif;
            margin: 0;
            padding: 0;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Subtle scrollbar for categories */
        .category-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .category-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .category-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .category-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .search-results-container {
            z-index: 1050;
        }

        .discount-type-btn {
            padding: 8px 16px;
            border: 2px solid #dee2e6;
            background: white;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .discount-type-btn:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
            transform: translateY(-1px);
        }

        .discount-type-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: #667eea !important;
            color: white !important;
            font-weight: bold;
        }

        .discount-type-btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .discount-type-btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            border-left: none;
        }

        /* Search Dropdown Styles */
        .search-dropdown-container {
            scroll-behavior: smooth;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .search-dropdown-item {
            transition: all 0.15s ease;
        }

        .search-dropdown-item:hover {
            transform: translateX(2px);
        }

        .search-dropdown-item.highlighted {
            background-color: #eff6ff !important;
            border-left: 4px solid #2563eb !important;
        }

        /* Focus visible styles for accessibility */
        input:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }
    </style>
    @endpush

    <div class="flex flex-col h-screen overflow-hidden bg-gray-50">
        <!-- Top Navigation Bar -->
        <header class="flex items-center justify-between bg-white border-b border-gray-200 px-6 py-3 shrink-0 shadow-sm">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-1.5 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">construction</span>
                    </div>
                    <h1 class="text-gray-900 text-xl font-bold tracking-tight">THILAK HARDWARE</h1>
                </div>
                <div class="w-80 hidden md:block"></div>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <button class="p-2 bg-gray-100 rounded-lg text-gray-900 hover:bg-gray-200">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                    </button>
                    <button class="p-2 bg-gray-100 rounded-lg text-gray-900 hover:bg-gray-200">
                        <span class="material-symbols-outlined text-xl">settings</span>
                    </button>
                    <div class="flex items-center gap-3 ml-2">
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-blue-600 font-medium">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <span class="material-symbols-outlined fill-icon">account_circle</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex flex-1 overflow-hidden">
            <!-- Left Side: Product Discovery (3/5 width) -->
            <div class="w-3/5 flex flex-col min-w-0 bg-gray-50" x-data="searchNavigation()" @keydown.window="handleGlobalKeydown($event)">
                <!-- Search Bar -->
                <div class="px-6 pb-2 pt-4">
                    <div class="relative w-full max-w-full" x-ref="searchContainer">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">search</span>
                        <input
                            id="productSearchInput"
                            x-ref="searchInput"
                            wire:model.live="search"
                            x-on:focus="searchFocused = true"
                            x-on:blur="setTimeout(() => { searchFocused = false }, 300)"
                            x-on:keydown.down.prevent="navigateResults('down')"
                            x-on:keydown.up.prevent="navigateResults('up')"
                            x-on:keydown.enter.prevent="selectHighlightedProduct()"
                            x-on:keydown.escape="searchFocused = false; highlightedIndex = -1"
                            class="w-full h-10 pl-10 pr-4 bg-white border-2 border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                            placeholder="Search products by name or code (F2)"
                            type="text"
                            autocomplete="off" />

                        <!-- Dropdown Search Results -->
                        @if(strlen($search) >= 2)
                        <div
                            x-show="searchFocused"
                            x-cloak
                            x-transition
                            class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-2xl max-h-96 overflow-y-auto z-50 search-dropdown-container">
                            @if(count($searchResults) > 0)
                            @foreach($searchResults as $index => $result)
                            @if($result->status == 'Available')
                            <div
                                wire:click="addToCart({{ $result->id }})"
                                x-on:mousedown.prevent
                                x-bind:class="highlightedIndex === {{ $index }} ? 'bg-blue-50 border-l-4 border-l-blue-600' : ''"
                                class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-all border-b border-gray-100 last:border-b-0 search-dropdown-item"
                                x-ref="searchResult{{ $index }}"
                                data-product-id="{{ $result->id }}">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($result->image_url)
                                    <img src="{{ $result->image_url }}" alt="{{ $result->product_name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                    @else
                                    <img src="{{ asset('images/defualt.jpg') }}" alt="{{ $result->product_name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase truncate">{{ $result->product_name }}</h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $result->product_code }}
                                        | <span class="{{ ($result->stock_quantity ?? 0) > 10 ? 'text-green-600' : (($result->stock_quantity ?? 0) > 0 ? 'text-orange-500' : 'text-red-600') }} font-semibold">Available: {{ $result->stock_quantity ?? 0 }}</span>
                                    </p>
                                </div>

                                <!-- Price -->
                                <div class="text-right flex-shrink-0">
                                    @if($result->mrp_price)
                                    <p class="text-xs text-gray-400 line-through">MRP: Rs. {{ number_format($result->mrp_price, 2) }}</p>
                                    @endif
                                    <p class="text-sm font-bold text-orange-500">Rs. {{ number_format($result->selling_price, 2) }}</p>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @else
                            <div class="p-4 text-center text-gray-500">
                                <p class="text-sm">No products found for "{{ $search }}"</p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Categories Chips -->
                <div class="px-6 py-2 overflow-hidden shrink-0">
                    <div class="flex gap-3 overflow-x-auto category-scroll pb-2" style="scroll-behavior: smooth;">
                        <button wire:click="filterByCategory(null)"
                            class="flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg {{ $selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-900 hover:border-blue-600' }} px-5 text-sm font-semibold whitespace-nowrap transition-colors shadow-sm">
                            All Products
                        </button>
                        @foreach($categories as $category)
                        <button wire:click="filterByCategory({{ $category->id }})"
                            class="flex h-10 shrink-0 items-center justify-center gap-2 rounded-lg {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-900 hover:border-blue-600' }} px-5 text-sm font-medium whitespace-nowrap transition-colors shadow-sm">
                            {{ $category->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="flex-1 overflow-y-auto px-6 pb-6 pt-4">
                    @if (count($searchResults) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach ($searchResults as $result)
                        @if($result->status == 'Available')
                        <div wire:click="addToCart({{ $result->id }})" class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col group hover:shadow-lg transition-all cursor-pointer">
                            <div class="w-full bg-gray-200 bg-center bg-no-repeat bg-cover relative overflow-hidden" style="height:160px;">
                                @if($result->image_url)
                                <img src="{{ $result->image_url }}" alt="{{ $result->product_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" style="height:100%;">
                                @else
                                <img src="{{ asset('images/defualt.jpg') }}" alt="{{ $result->product_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" style="height:100%;">
                                @endif
                                @if($result->stock_quantity > 10)
                                <div class="absolute top-2 right-2 bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">In Stock</div>
                                @elseif($result->stock_quantity > 0)
                                <div class="absolute top-2 right-2 bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Low Stock</div>
                                @else
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Out of Stock</div>
                                @endif
                            </div>
                            <div class="p-3 flex flex-col flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-1">{{ $result->product_name }}</h4>
                                <div class="mt-auto">
                                    <div class="flex items-center justify-between mb-1">
                                        <div>
                                            @if($result->mrp_price)
                                            <p class="text-xs text-gray-400 line-through">MRP: Rs {{ number_format($result->mrp_price, 2) }}</p>
                                            @endif
                                            <p class="text-lg font-bold text-blue-600">Rs {{ number_format($result->selling_price, 2) }}</p>
                                        </div>
                                        @php
                                        $stockQty = $result->stock_quantity ?? 0;
                                        $stockClass = $stockQty > 10 ? 'text-green-600 font-bold' : ($stockQty > 0 ? 'text-orange-500 font-semibold' : 'text-red-600 font-bold');
                                        @endphp
                                        <p class="text-sm {{ $stockClass }}">{{ $stockQty }} units</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @else
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">inventory_2</span>
                            <p class="text-gray-500 text-lg font-medium">No products found</p>
                            <p class="text-gray-400 text-sm mt-2">Try a different search term or category</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Order/Cart Sidebar (1/3 width) -->
            <div class="w-2/5 bg-white border-l border-gray-200 flex flex-col shadow-2xl relative z-10">
                <!-- Header with Invoice and Customer Selection -->
                <div class="p-2 border-b border-gray-200">
                    <!-- Invoice and Customer in one row -->
                    <div class="flex gap-4 items-start">
                        <!-- Invoice Number -->
                        <!-- <div class="flex-shrink-0">
                            <h3 class="text-lg font-bold text-gray-900">{{ $currentInvoiceNumber }}</h3>
                            <p class="text-xs text-gray-500">Current Invoice</p>
                        </div> -->

                        <!-- Customer Selection -->
                        <div class="flex-1">
                            <label class="text-sm font-semibold text-gray-900 block mb-2">Customer</label>
                            <div class="flex gap-2">
                                <select wire:model="customerId" class="flex-1 h-10 px-3 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="">Walk-in Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                    @endforeach
                                </select>
                                <button data-bs-toggle="modal" data-bs-target="#addCustomerModal" class="h-10 px-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex-shrink-0">
                                    <span class="material-symbols-outlined text-lg">person_add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="flex-1 overflow-y-auto px-3 py-3">
                    @forelse($cart as $id => $item)
                    <div class="mb-4 pb-4 border-b border-gray-200 last:border-b-0" wire:key="cart-item-{{ $id }}">
                        <!-- Product Item Row -->
                        <div class="flex gap-3">
                            <!-- Product Image/Icon -->
                            <div class="flex-shrink-0">
                                @if(isset($item['image_url']) && $item['image_url'])
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded-lg object-cover" style="border: 1px solid #e5e7eb;">
                                @else
                                <img src="{{ asset('images/defualt.jpg') }}" alt="{{ $item['name'] }}" class="w-12 h-12 rounded-lg object-cover" style="border: 1px solid #e5e7eb;">
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-gray-900 truncate">{{ $item['name'] }}</h5>
                                        @if(isset($item['mrp_price']) && $item['mrp_price'])
                                        <p class="text-xs text-gray-400">MRP: Rs. {{ number_format($item['mrp_price'], 2) }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="removeFromCart({{ $id }})" class="text-gray-400 hover:text-red-500 p-0 flex-shrink-0">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>

                                <!-- Price, Quantity, Total Row -->
                                <div class="flex items-center gap-3 justify-between mt-2">

                                    <!-- Quantity Selector -->
                                    <div>
                                        <label class="text-xs text-gray-500 block mb-0.5">Qty</label>
                                        <div class="flex items-center gap-2 bg-gray-100 rounded px-2 py-1">
                                            <button wire:click="decrementQuantity({{ $id }})" class="text-gray-600 hover:text-gray-900 text-lg font-bold">−</button>
                                            <input
                                                type="number"
                                                wire:model.blur="quantities.{{ $id }}"
                                                data-cart-qty="{{ $id }}"
                                                onkeydown="if(event.key==='Enter'){event.preventDefault();var p=document.querySelector('input[data-cart-price=\'{{ $id }}\']');if(p){p.focus();p.select();}}"
                                                class="w-16 text-sm font-bold text-center bg-transparent border-none focus:outline-none"
                                                min="1" />
                                            <button wire:click="incrementQuantity({{ $id }})" class="text-gray-600 hover:text-gray-900 text-lg font-bold">+</button>
                                        </div>
                                    </div>

                                    <!-- Unit Price (Editable) -->
                                    <div>
                                        <label class="text-xs text-gray-500 block mb-0.5">Price</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-600">Rs.</span>
                                            <input
                                                type="number"
                                                wire:model.blur="prices.{{ $id }}"
                                                value="{{ $prices[$id] ?? $item['price'] }}"
                                                data-cart-price="{{ $id }}"
                                                onkeydown="if(event.key==='Enter'){event.preventDefault();var s=document.getElementById('productSearchInput');if(s){s.focus();s.select();}}"
                                                class="w-28 px-2 py-1.5 text-sm font-semibold border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                min="0"
                                                step="0.01" />
                                        </div>
                                    </div>



                                    <!-- Discount Badge -->
                                    @if(isset($discounts[$id]) && $discounts[$id] > 0)
                                    @php
                                    $unitPrice = $prices[$id] ?? $item['price'];
                                    $discountPercent = ($discounts[$id] / $unitPrice) * 100;
                                    @endphp
                                    <div>
                                        <button onclick="showDiscountModal({{ $id }}, '{{ $item['name'] }}', {{ $unitPrice }})" class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold hover:bg-green-200 transition-colors mt-3">
                                            {{ number_format($discountPercent, 0) }}% OFF
                                        </button>
                                    </div>
                                    @else
                                    <div>
                                        <button onclick="showDiscountModal({{ $id }}, '{{ $item['name'] }}', {{ $prices[$id] ?? $item['price'] }})" class="text-blue-600 hover:text-blue-700 text-xs font-semibold px-2 py-1 rounded hover:bg-blue-50 transition-colors mt-3">
                                            + DISCOUNT
                                        </button>
                                    </div>
                                    @endif

                                    <!-- Total Amount (After Unit Discount) -->
                                    @php
                                    $unitPrice = $prices[$id] ?? $item['price'];
                                    $qty = $quantities[$id] ?? 1;
                                    $grossTotal = $unitPrice * $qty;
                                    $itemDiscount = isset($discounts[$id]) ? $discounts[$id] * $qty : 0;
                                    $netTotal = $grossTotal - $itemDiscount;
                                    @endphp
                                    <div class="text-right">
                                        <p class="text-xs text-gray-600 mb-0.5">Total</p>
                                        <p class="text-sm font-bold text-gray-900">Rs.{{ number_format($netTotal, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-center py-12">
                        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">shopping_cart</span>
                        <p class="text-gray-500 font-medium">Your cart is empty</p>
                        <p class="text-gray-400 text-sm mt-1">Add products to get started</p>
                    </div>
                    @endforelse
                </div>

                <!-- Payment Summary & Actions -->
                <div class="p-5 bg-gray-50 border-t border-gray-200 space-y-4">
                    @if(count($cart) > 0)
                    <div class="space-y-2">
                        <div class="flex justify-end items-center mb-3">
                            <button onclick="showOverallDiscountModal()" class="flex items-center gap-1 text-blue-600 hover:text-blue-700 font-semibold text-sm transition-colors" title="Add Discount">
                                <span class="material-symbols-outlined text-base">add</span>
                                <span>ADD DISCOUNT</span>
                            </button>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Subtotal</span>
                            <span class="font-medium text-gray-900">Rs {{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($calculatedDiscount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-medium text-green-600">- Rs {{ number_format($calculatedDiscount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="text-base font-bold text-gray-900">Total Amount</span>
                            <span class="text-xl font-bold text-blue-600">Rs {{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="showPaymentModal('cash', {{ $grandTotal }})" class="py-3 px-2 rounded-lg bg-white border border-gray-200 hover:border-blue-600 hover:bg-blue-50 transition-all flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined text-2xl text-green-600">payments</span>
                                <span class="text-xs font-semibold text-gray-900">CASH</span>
                            </button>
                            <button type="button" onclick="showPaymentModal('cheque', {{ $grandTotal }})" class="py-3 px-2 rounded-lg bg-white border border-gray-200 hover:border-blue-600 hover:bg-blue-50 transition-all flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined text-2xl text-purple-600">receipt</span>
                                <span class="text-xs font-semibold text-gray-900">CHEQUE</span>
                            </button>
                            <button type="button" onclick="showPaymentModal('credit', {{ $grandTotal }})" class="py-3 px-2 rounded-lg bg-white border border-gray-200 hover:border-blue-600 hover:bg-blue-50 transition-all flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined text-2xl text-orange-600">schedule</span>
                                <span class="text-xs font-semibold text-gray-900">CREDIT</span>
                            </button>
                            <button type="button" onclick="showPaymentModal('multiple', {{ $grandTotal }})" class="py-3 px-2 rounded-lg bg-white border border-gray-200 hover:border-blue-600 hover:bg-blue-50 transition-all flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined text-2xl text-indigo-600">account_balance</span>
                                <span class="text-xs font-semibold text-gray-900">MULTIPLE</span>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>

        <!-- Payment Modal -->
        <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-blue-600 text-white">
                        <h5 class="modal-title" id="paymentModalLabel">
                            <span id="paymentMethodIcon" class="material-symbols-outlined align-middle me-2">payments</span>
                            <span id="paymentMethodName">Cash Payment</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body space-y-3">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Net Total</span>
                                <span class="text-lg font-bold text-gray-900">Rs <span id="netTotal">0.00</span></span>
                            </div>
                        </div>

                        <div>
                            <label for="receivedAmount" class="text-sm font-semibold text-gray-900 block mb-2">Received Amount (Rs)</label>
                            <input type="number" id="receivedAmount" class="form-control" placeholder="Enter amount received" min="0" step="0.01" oninput="calculateBalance()">
                            <small class="text-red-600 mt-1 hidden" id="amountError">Amount cannot be less than net total</small>
                        </div>

                        <div class="bg-blue-50 p-3 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Balance Amount</span>
                                <span class="text-lg font-bold text-blue-600">Rs <span id="balanceAmount">0.00</span></span>
                            </div>
                        </div>

                        <!-- Cheque Details (Hidden by default) -->
                        <div id="chequeDetails" class="hidden space-y-3">
                            <div class="bg-yellow-50 p-3 rounded-lg">
                                <p class="text-sm text-yellow-800 mb-3">
                                    <span class="material-symbols-outlined text-yellow-600 align-middle me-1">info</span>
                                    Add multiple cheques for this payment
                                </p>
                                <button type="button" class="btn btn-primary btn-sm" id="addChequeBtn" onclick="showChequeForm()">
                                    <span class="material-symbols-outlined align-middle me-1 text-sm">add</span>
                                    Add Cheque
                                </button>
                            </div>

                            <!-- Cheque Form (Hidden initially) -->
                            <div id="chequeForm" class="hidden border rounded-lg p-3 bg-gray-50">
                                <div class="mb-3 p-2 bg-blue-50 rounded border border-blue-200">
                                    <p class="text-sm text-blue-800">
                                        <strong>Net Total:</strong> Rs. <span id="netTotalDisplay">0.00</span> |
                                        <strong>Added:</strong> Rs. <span id="addedChequeAmount">0.00</span> |
                                        <strong style="color: #d32f2f;">Balance:</strong> Rs. <span id="balanceChequeAmount">0.00</span>
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label for="chequeNumber" class="text-sm font-semibold text-gray-900 block mb-1">Cheque Number</label>
                                        <input type="text" id="chequeNumber" class="form-control form-control-sm" placeholder="Enter cheque number">
                                    </div>
                                    <div>
                                        <label for="chequeAmount" class="text-sm font-semibold text-gray-900 block mb-1">Amount (Rs)</label>
                                        <input type="number" id="chequeAmount" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01">
                                        <small class="text-muted" id="maxChequeAmount"></small>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-3">
                                    <div>
                                        <label for="chequeBank" class="text-sm font-semibold text-gray-900 block mb-1">Bank Name</label>
                                        <input type="text" id="chequeBank" class="form-control form-control-sm" placeholder="Enter bank name">
                                    </div>
                                    <div>
                                        <label for="chequeDate" class="text-sm font-semibold text-gray-900 block mb-1">Cheque Date</label>
                                        <input type="date" id="chequeDate" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addChequeToList()">
                                        <span class="material-symbols-outlined align-middle me-1 text-sm">check</span>
                                        Add to List
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideChequeForm()">Cancel</button>
                                </div>
                            </div>

                            <!-- Added Cheques List -->
                            <div id="chequesList" class="space-y-2">
                                <!-- Cheques will be added here dynamically -->
                            </div>
                        </div>

                        <!-- Card Details (Hidden by default) -->
                        <div id="cardDetails" class="hidden space-y-3">
                            <div>
                                <label for="cardTransactionId" class="text-sm font-semibold text-gray-900 block mb-2">Transaction ID</label>
                                <input type="text" id="cardTransactionId" class="form-control" placeholder="Enter transaction ID">
                            </div>
                        </div>

                        <!-- Credit Payment Details (Hidden by default) -->
                        <div id="creditDetails" class="hidden space-y-3">
                            <div class="bg-amber-50 p-3 rounded-lg">
                                <p class="text-sm text-amber-800">
                                    <span class="material-symbols-outlined text-amber-600 align-middle me-1">info</span>
                                    This will be recorded as a credit sale. The customer will need to pay later.
                                </p>
                            </div>
                            <div>
                                <label for="creditDueDate" class="text-sm font-semibold text-gray-900 block mb-2">Due Date</label>
                                <input type="date" id="creditDueDate" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                            </div>
                            <div>
                                <label for="creditNotes" class="text-sm font-semibold text-gray-900 block mb-2">Notes (Optional)</label>
                                <textarea id="creditNotes" class="form-control" rows="2" placeholder="Enter any notes about this credit sale"></textarea>
                            </div>
                        </div>

                        <!-- Multiple Payment Details (Hidden by default) -->
                        <div id="multipleDetails" class="hidden space-y-3">
                            <!-- Payment Method Selection -->
                            <div class="flex gap-2 mb-3">
                                <button type="button" class="flex-1 btn btn-sm btn-outline-success multiPaymentBtn" data-method="cash" onclick="selectMultiplePaymentMethod('cash')">
                                    <span class="material-symbols-outlined align-middle me-1 text-sm">payments</span>
                                    Cash
                                </button>
                                <button type="button" class="flex-1 btn btn-sm btn-outline-warning multiPaymentBtn" data-method="cheque" onclick="selectMultiplePaymentMethod('cheque')">
                                    <span class="material-symbols-outlined align-middle me-1 text-sm">confirmation_number</span>
                                    Cheque
                                </button>
                            </div>

                            <!-- Cash Input Section (Initially Hidden) -->
                            <div id="multipleCashSection" class="hidden space-y-3">
                                <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                    <p class="text-sm text-green-800 mb-3">
                                        <span class="material-symbols-outlined text-green-600 align-middle me-1">currency_exchange</span>
                                        Enter cash amount for this payment
                                    </p>
                                    <div class="mb-3">
                                        <label for="multipleCashAmount" class="text-sm font-semibold text-gray-900 block mb-2">Cash Amount (Rs)</label>
                                        <input type="number" id="multipleCashAmount" class="form-control" placeholder="0.00" min="0" step="0.01">
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm w-100" onclick="addMultipleCashPayment()">
                                        <span class="material-symbols-outlined align-middle me-1 text-sm">add_circle</span>
                                        Add Cash Payment
                                    </button>
                                </div>
                            </div>

                            <!-- Cheque Input Section (Initially Hidden) -->
                            <div id="multipleChequeSection" class="hidden space-y-3">
                                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                    <p class="text-sm text-yellow-800 mb-3">
                                        <span class="material-symbols-outlined text-yellow-600 align-middle me-1">info</span>
                                        Add cheque details for this payment
                                    </p>
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div>
                                            <label for="multipleChequeNumber" class="text-sm font-semibold text-gray-900 block mb-1">Cheque #</label>
                                            <input type="text" id="multipleChequeNumber" class="form-control form-control-sm" placeholder="Cheque number">
                                        </div>
                                        <div>
                                            <label for="multipleChequeAmount" class="text-sm font-semibold text-gray-900 block mb-1">Amount (Rs)</label>
                                            <input type="number" id="multipleChequeAmount" class="form-control form-control-sm" placeholder="0.00" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div>
                                            <label for="multipleChequeBank" class="text-sm font-semibold text-gray-900 block mb-1">Bank Name</label>
                                            <input type="text" id="multipleChequeBank" class="form-control form-control-sm" placeholder="Bank name">
                                        </div>
                                        <div>
                                            <label for="multipleChequeDate" class="text-sm font-semibold text-gray-900 block mb-1">Date</label>
                                            <input type="date" id="multipleChequeDate" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm w-100" onclick="addMultipleChequePayment()">
                                        <span class="material-symbols-outlined align-middle me-1 text-sm">add_circle</span>
                                        Add Cheque Payment
                                    </button>
                                </div>
                            </div>

                            <!-- Added Payments List -->
                            <div id="paymentMethodsList" class="space-y-2">
                                <!-- Payment methods will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" onclick="completeSaleWithPayment()">
                            <span class="material-symbols-outlined align-middle me-1">check_circle</span>
                            Complete Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Add Customer Modal -->
        <div wire:ignore.self class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-blue-600 text-white">
                        <h5 class="modal-title" id="addCustomerModalLabel">
                            <i class="bi bi-user-plus me-2"></i>Add New Customer
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveCustomer">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="newCustomerName" class="form-control" placeholder="Enter customer name" required>
                                    @error('newCustomerName') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="newCustomerPhone" class="form-control" placeholder="Enter phone number" required>
                                    @error('newCustomerPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="newCustomerEmail" class="form-control" placeholder="Enter email (optional)">
                                    @error('newCustomerEmail') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Customer Type</label>
                                    <select wire:model="newCustomerType" class="form-select">
                                        <option value="wholesale">Wholesale</option>
                                        <option value="retail">Retail</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="saveCustomer">Save Customer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 shadow-xl" style="border: 2px solid #233D7F;">
                    <div class="modal-header" style="background-color: #233D7F; color: #FFFFFF;">
                        <h5 class="modal-title fw-bold" id="receiptModalLabel">
                            <i class="bi bi-receipt me-2"></i>Sales Receipt
                        </h5>
                        <div class="ms-auto d-flex gap-2 align-items-center">
                            <select id="printSizeSelect" class="form-select form-select-sm me-2" style="width: 170px;">
                                <option value="A4">A4</option>
                                <option value="thermal">Thermal (80mm)</option>
                            </select>
                            <button type="button" class="btn btn-sm" id="printButton" style="background-color: #233D7F; border-color:#fff; color: #fff;">
                                <i class="bi bi-printer me-1"></i>Print
                            </button>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body p-4" id="receiptContent">
                        @if ($receipt)
                        <div class="receipt-container">
                            <div class="text-center mb-4">
                                <h3 class="mb-1 fw-bold" style="color: #233D7F;">THILAK HARDWARE</h3>
                                <h5 class="mb-1 fw-medium" style="color: #233D7F;">Stone, Sand, Cement all types of building items are provided at affordable prices</h5>
                                <p class="mb-0 text-muted small">NO 569/17A, THIHARIYA, KALAGEDIHENA </p>
                                <p class="mb-0 text-muted small">Phone: 077 9089961</p>
                                <hr style="border: 2px solid #233D7F;">
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <p><strong>Customer Name:</strong> {{ $receipt['customer_name'] ?? 'Walk-in Customer' }}</p>
                                    <p><strong>Address:</strong> {{ $receipt['customer_address'] ?? 'N/A' }}</p>
                                    <p><strong>Phone:</strong> {{ $receipt['customer_phone'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Invoice Number:</strong> {{ $receipt['invoice_number'] }}</p>
                                    <p><strong>Date:</strong> {{ $receipt['date'] }}</p>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-sm">
                                    <thead style="background-color: #233D7F; color: white;">
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>MRP</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($receipt['items']) && is_array($receipt['items']) && count($receipt['items']) > 0)
                                        @foreach($receipt['items'] as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['name'] ?? 'Unknown Item' }}</td>
                                            <td>{{ $item['quantity'] ?? 0 }}</td>
                                            <td>{{ isset($item['mrp_price']) && $item['mrp_price'] ? number_format($item['mrp_price'], 2) : '-' }}</td>
                                            <td>{{ number_format($item['price'] ?? 0, 2) }}</td>
                                            <td> {{ number_format($item['total'] ?? 0, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No items found</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                            <td><strong>{{ number_format($receipt['subtotal'] ?? 0, 2) }}</strong></td>
                                        </tr>
                                        @if(($receipt['discount'] ?? 0) > 0)
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Total Discount:</strong></td>
                                            <td><strong>- {{ number_format($receipt['discount'] ?? 0, 2) }}</strong></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="5" class="text-end"><strong>Grand Total:</strong></td>
                                            <td><strong>{{ number_format($receipt['total'] ?? 0, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4 pt-3 border-top" style="border-color: #233D7F;">
                                <p class="mb-0 text-muted small">Thank you for your business!</p>
                            </div>
                        </div>
                        @else
                        <div class="text-center p-4">
                            <p class="text-muted">No receipt data available</p>
                            <small class="text-danger">Debug: Receipt data is missing</small>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top py-3" style="border-color: #233D7F; background: #F8F9FA;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Alpine.js Search Navigation Component
        function searchNavigation() {
            return {
                searchFocused: false,
                highlightedIndex: -1,

                init() {
                    // Focus search bar on page load
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    });
                },

                handleGlobalKeydown(event) {
                    if (event.key === 'F2' || (event.ctrlKey && event.key === 'k')) {
                        event.preventDefault();
                        this.focusSearchBar();
                    }
                },

                navigateResults(direction) {
                    const container = this.$refs.searchContainer;
                    if (!container) return;
                    const items = container.querySelectorAll('.search-dropdown-item');
                    const count = items.length;
                    if (count === 0) return;

                    if (direction === 'down') {
                        this.highlightedIndex = Math.min(this.highlightedIndex + 1, count - 1);
                    } else {
                        this.highlightedIndex = Math.max(this.highlightedIndex - 1, -1);
                    }

                    if (this.highlightedIndex >= 0 && items[this.highlightedIndex]) {
                        items[this.highlightedIndex].scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                },

                selectHighlightedProduct() {
                    const container = this.$refs.searchContainer;
                    if (!container) return;
                    const items = container.querySelectorAll('.search-dropdown-item');
                    if (this.highlightedIndex >= 0 && items[this.highlightedIndex]) {
                        const productId = items[this.highlightedIndex].getAttribute('data-product-id');
                        if (productId) {
                            this.$wire.addToCart(parseInt(productId));
                            this.highlightedIndex = -1;
                            // Focus qty input after Livewire update
                            setTimeout(() => {
                                const qtyInput = document.querySelector(`input[data-cart-qty="${productId}"]`);
                                if (qtyInput) {
                                    qtyInput.focus();
                                    qtyInput.select();
                                }
                            }, 300);
                        }
                    }
                },

                focusNextPrice(productId) {
                    const priceInput = document.querySelector(`input[data-cart-price="${productId}"]`);
                    if (priceInput) {
                        priceInput.focus();
                        priceInput.select();
                    }
                },

                focusSearchBar() {
                    if (this.$refs.searchInput) {
                        this.$refs.searchInput.focus();
                        this.$refs.searchInput.select();
                        this.highlightedIndex = -1;
                    }
                }
            }
        }

        document.addEventListener('livewire:initialized', () => {
            // Modal listeners
            window.addEventListener('showModal', event => {
                console.log('showModal event received:', event.detail);
                const modalId = event.detail[0].modalId;
                const modalEl = document.getElementById(modalId);
                if (modalEl) {
                    // Force Livewire to refresh before showing modal
                    if (modalId === 'receiptModal') {
                        setTimeout(() => {
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                            console.log('Receipt modal opened after delay');
                        }, 100);
                    } else {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                        console.log('Modal opened:', modalId);
                    }
                } else {
                    console.error('Modal not found:', modalId);
                }
            });

            window.addEventListener('closeModal', event => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(event.detail[0].modalId));
                if (modal) {
                    modal.hide();
                }
            });

            // Toast notifications
            window.addEventListener('show-toast', event => {
                const data = event.detail[0];
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: data.type,
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });

            // Print button + print size selector (persisted)
            const printButton = document.getElementById('printButton');
            const printSizeSelect = document.getElementById('printSizeSelect');
            if (printSizeSelect) {
                const savedFormat = localStorage.getItem('printFormat') || 'A4';
                printSizeSelect.value = savedFormat;
                printSizeSelect.addEventListener('change', function() {
                    localStorage.setItem('printFormat', this.value);
                });
            }
            if (printButton) {
                printButton.addEventListener('click', function() {
                    printSalesReceipt();
                });
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'F2') {
                    e.preventDefault();
                    document.querySelector('input[placeholder*="Search"]').focus();
                }
            });

            // Payment modal listener
            window.addEventListener('showPaymentModal', event => {
                const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
                paymentModal.show();
            });

            // Receipt Modal - Refresh page when closed
            const receiptModalEl = document.getElementById('receiptModal');
            if (receiptModalEl) {
                receiptModalEl.addEventListener('hide.bs.modal', function() {
                    // Reload the page after a short delay to allow modal to fully close
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                });
            }
        });

        function printSalesReceipt() {
            const receiptContent = document.querySelector('#receiptContent');
            if (!receiptContent) {
                alert('Receipt content not found.');
                return;
            }

            // Determine format (A4 or thermal)
            const format = document.getElementById('printSizeSelect')?.value || localStorage.getItem('printFormat') || 'A4';

            // Extract data from receipt modal
            const rowDiv = receiptContent.querySelector('.row.mb-2');
            const leftCol = rowDiv?.querySelector('.col-md-6:first-child');
            const rightCol = rowDiv?.querySelector('.col-md-6:last-child');

            // Extract customer information (from left column)
            let customerName = 'Walk-in Customer';
            let customerAddress = '';
            let customerPhone = '';

            if (leftCol) {
                const customerNameP = leftCol.querySelector('p:first-child');
                if (customerNameP && customerNameP.textContent.includes('Customer Name:')) {
                    customerName = customerNameP.textContent.replace('Customer Name:', '').trim();
                }

                const customerAddressP = leftCol.querySelector('p:nth-child(2)');
                if (customerAddressP && customerAddressP.textContent.includes('Address :')) {
                    customerAddress = customerAddressP.textContent.replace('Address :', '').trim();
                }

                const customerPhoneP = leftCol.querySelector('p:nth-child(3)');
                if (customerPhoneP && customerPhoneP.textContent.includes('Phone :')) {
                    customerPhone = customerPhoneP.textContent.replace('Phone :', '').trim();
                }
            }

            // Extract invoice information (from right column)
            let invoiceNumber = '';
            let date = '';

            if (rightCol) {
                const invoiceP = rightCol.querySelector('p:first-child');
                if (invoiceP && invoiceP.textContent.includes('Invoice Number:')) {
                    invoiceNumber = invoiceP.textContent.replace('Invoice Number:', '').trim();
                }

                const dateP = rightCol.querySelector('p:nth-child(2)');
                if (dateP && dateP.textContent.includes('Date:')) {
                    date = dateP.textContent.replace('Date:', '').trim();
                }
            }

            // Get items table
            const itemsTable = receiptContent.querySelector('.table-bordered');

            const printWindow = window.open('', '_blank', 'height=600,width=800');

            if (format === 'thermal') {
                // Compact styles for 80mm thermal paper
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Sales Receipt - ${invoiceNumber}</title>
                        <style>
                            *{margin:0;padding:0;box-sizing:border-box}
                            @page { size: 80mm auto; margin: 3mm; }
                            body{font-family: 'Courier New', monospace !important; padding:8px; font-size:12px; line-height:1.2; color:#000; width:78mm}
                            .company-name{font-size:18px; font-weight:bold; text-align:center}
                            .company-address{font-size:11px; text-align:center; margin-bottom:6px}
                            .dashed{border-top:1px dashed #000; margin:6px 0}
                            table{width:100%; border-collapse:collapse; font-size:11px}
                            table td{padding:3px 0; vertical-align:top}
                            .text-right{text-align:right}
                            .text-center{text-align:center}
                            .footer{margin-top:8px; font-size:11px; text-align:center}
                            @media print{ body{padding:2mm} }
                        </style>
                    </head>
                    <body>
                        <div>
                            <div class="company-name">THILAK HARDWARE</div>
                            <div class="company-address"></div>
                            <div class="company-address">Phone: 077 6718838</div>
                            <div class="dashed"></div>

                            <table>
                                <tr><td><strong>Name:</strong></td><td>${customerName}</td></tr>
                                <tr><td><strong>Address:</strong></td><td>${customerAddress}</td></tr>
                                <tr><td><strong>Phone:</strong></td><td>${customerPhone}</td></tr>
                                <tr><td><strong>Invoice:</strong></td><td>${invoiceNumber}</td></tr>
                                <tr><td><strong>Date:</strong></td><td>${date}</td></tr>
                            </table>

                            <div class="dashed"></div>

                            ${itemsTable ? itemsTable.outerHTML.replace(/table\s*/i, 'table style="width:100%;"') : ''}

                            <div class="dashed"></div>
                            <div class="footer">
                                <div>*****ORIGINAL*****</div>
                                <div>Please draw the cheque in favor of M.A.Z Ahamed</div>
                                <div>Peoples Bank Acc No: 2781-0010-2421-207</div>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
            } else {
                // A4 (default) - use existing full layout
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Sales Receipt - ${invoiceNumber}</title>
                        <style>
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        @page { size: A4; margin: 1cm; }
                        body { 
                            font-family: 'Courier New', monospace !important; 
                            padding: 20px;
                            font-size: 14px;
                            line-height: 1.4;
                            color: #000;
                            font-weight: bold;
                        }
                        .receipt-container {
                            max-width: 800px;
                            margin: 0 auto;
                            padding: 0;
                        }
                        .company-header {
                            text-align: center;
                            margin-bottom: 5px;
                            border-bottom: 2px solid #000;
                            padding-bottom: 15px;
                            font-weight: bold;
                        }
                        .company-name {
                            font-size: 28px;
                            font-weight: bold;
                            color: #000;
                            margin-bottom: 5px;
                        }
                        .company-address {
                            font-size: 14px;
                            color: #000;
                            margin: 3px 0;
                        }
                        .receipt-title {
                            font-size: 14px;
                            font-weight: bold;
                            color: #000;
                            text-align: right;
                            margin: 5px 0;
                            padding-bottom: 10px;
                        }
                        .info-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 2px;
                        }
                        .info-section {
                            width: 48%;
                            padding: 8px;
                        }
                        .info-section h6 {
                            color: #000;
                            font-weight: bold;
                            padding-bottom: 4px;
                            margin-bottom: 4px;
                            font-size: 13px;
                        }
                        .info-section table {
                            width: 100%;
                            font-size: 14px;
                            border: none;
                        }
                        .info-section td {
                            padding: 2px 0;
                            color: #000 !important;
                            border: none;
                        }
                        .info-label {
                            font-weight: bold;
                            display: inline-block;
                            width: 140px;
                        }
                        .info-value {
                            display: inline-block;
                        }
                        .text-center { text-align: center; }
                        .text-right { text-align: right; }
                        .fw-bold { font-weight: bold; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 5px 0 20px;
                        }
                        table th, table td {
                            border: 1px solid #000;
                            padding: 3px;
                            text-align: left;
                            font-family: 'Courier New', monospace !important;
                            color: #000 !important;
                        }
                        table th {
                            background-color: #f0f0f0;
                            font-weight: bold;
                        }
                        
                        /* Footer Styling */
                        .footer {
                            text-align: center;
                            margin-bottom: 100px;
                            padding-top: 20px;
                            color: #000;
                            position: absolute;
                            bottom: 20px;
                            width: 100%;
                        }
                        
                        .signature-row {
                            display: flex;
                            justify-content: space-around;
                            margin-bottom: 5px;
                            text-align: center;
                        }
                        
                        .check {
                            flex: 1;
                            padding: 0 10px;
                        }
                        
                        .check .signature-line {
                            padding-bottom: 2px;
                            min-height: 30px;
                        }
                        
                        .check .label {
                            font-size: 11px;
                            font-weight: bold;
                            color: #000 !important;
                        }
                        
                        .footer p {
                            margin: 0;
                            font-size: 11px;
                            color: #000 !important;
                            font-weight: bold;
                        }
                        
                        .footer .original {
                            font-size: 12px;
                            font-weight: bold;
                            margin: 25px 0 0;
                            letter-spacing: 2px;
                        }
                        
                        .footer .bank-info {
                            font-size: 11px;
                            margin: 2px 0;
                        }
                        
                        .footer .return-policy {
                            font-size: 11px;
                            margin-top: 5px;
                            padding: 3px 0;
                            font-weight: bold;
                        }
                        
                        h3, h4, h5, h6, p, strong, span, td, th, div {
                            font-family: 'Courier New', monospace !important;
                            color: #000 !important;
                        }
                        
                        @media print { 
                            .no-print { display: none; }
                            body { padding: 10px; }
                            * { color: #000 !important; }
                        }
                    </style>
                    </head>
                    <body>
                        <div class="receipt-container">
                            <div class="company-header">
                                <div class="company-name">THILAK HARDWARE</div>
                                <div class="company-address">Stone, Sand, Cement all types of building items<br>are provided at affordable prices</div>
                                <div class="company-address">Phone: 077 9089961 | Address: NO 569/17A, THIHARIYA, KALAGEDIHENA.</div>
                            </div>

                            <div class="info-row">
                                <div class="info-section">
                                    <table>
                                        <tr><td>Name:</td><td>${customerName}</td></tr>
                                        <tr><td>Address:</td><td>${customerAddress}</td></tr>
                                        <tr><td>Phone:</td><td>${customerPhone}</td></tr>
                                    </table>
                                </div>
                                <div class="info-section">
                                    <table>
                                        <tr><td>Invoice Number:</td><td>${invoiceNumber}</td></tr>
                                        <tr><td>Date:</td><td>${date}</td></tr>
                                    </table>
                                </div>
                            </div>

                            ${itemsTable ? itemsTable.outerHTML : ''}

                            <div class="footer">
                                <div class="signature-row">
                                    <div class="check">
                                        <div class="signature-line">...........................</div>
                                        <div class="label">Receiver's Signature</div>
                                    </div>
                                    <div class="check">
                                        <div class="signature-line">...........................</div>
                                        <div class="label">Check By</div>
                                    </div>
                                    <div class="check">
                                        <div class="signature-line">...........................</div>
                                        <div class="label">Authorized Signature</div>
                                    </div>
                                </div>
                                <p class="original">*****ORIGINAL*****</p>
                            </div>
                        </div>
                    </body>
                    </html>
                `);
            }

            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        function showOverallDiscountModal() {
            console.log('showOverallDiscountModal called');
            try {
                document.getElementById('discountProductId').value = 'overall';
                document.getElementById('discountProductName').innerText = 'Cart Discount';
                document.getElementById('discountType').value = 'percentage';
                document.getElementById('discountAmount').value = '';
                document.getElementById('discountPercentage').value = '';

                const modalEl = document.getElementById('discountModal');
                if (!modalEl) {
                    console.error('Discount modal not found');
                    return;
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                console.log('Discount modal opened');
            } catch (error) {
                console.error('Error opening discount modal:', error);
                Swal.fire('Error', 'Failed to open discount modal', 'error');
            }
        }

        function showDiscountModal(productId, productName, price) {
            console.log('showDiscountModal called with:', productId, productName, price);
            try {
                document.getElementById('discountProductId').value = productId;
                document.getElementById('discountProductName').innerText = productName;
                document.getElementById('discountType').value = 'percentage';
                document.getElementById('discountAmount').value = '';
                document.getElementById('discountPercentage').value = '';

                const modalEl = document.getElementById('discountModal');
                if (!modalEl) {
                    console.error('Discount modal not found');
                    return;
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
                console.log('Discount modal opened for product:', productName);
            } catch (error) {
                console.error('Error opening discount modal:', error);
                Swal.fire('Error', 'Failed to open discount modal', 'error');
            }
        }

        function applyDiscount() {
            const productId = document.getElementById('discountProductId').value;
            const type = document.querySelector('input[name="discountType"]:checked').value;
            const amount = document.getElementById('discountAmount').value;
            const percentage = document.getElementById('discountPercentage').value;

            if (type === 'amount' && !amount) {
                Swal.fire('Error', 'Please enter discount amount', 'error');
                return;
            }
            if (type === 'percentage' && !percentage) {
                Swal.fire('Error', 'Please enter discount percentage', 'error');
                return;
            }

            const discountValue = type === 'amount' ? amount : percentage;
            Livewire.dispatch('applyDiscount', {
                productId,
                type,
                value: discountValue
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('discountModal'));
            modal.hide();
        }

        let currentPaymentMethod = 'cash';
        let currentNetTotal = 0;
        let multiplePayments = [];

        function showPaymentModal(method, netTotal) {
            try {
                currentPaymentMethod = method;
                currentNetTotal = netTotal;
                multiplePayments = [];
                chequesList = []; // Reset cheques list

                // Update modal header
                const icons = {
                    'cash': 'payments',
                    'card': 'credit_card',
                    'cheque': 'receipt',
                    'credit': 'schedule',
                    'multiple': 'account_balance'
                };
                const names = {
                    'cash': 'Cash Payment',
                    'card': 'Card Payment',
                    'cheque': 'Cheque Payment',
                    'credit': 'Credit Sale',
                    'multiple': 'Multiple Payment Methods'
                };

                const iconEl = document.getElementById('paymentMethodIcon');
                const nameEl = document.getElementById('paymentMethodName');
                const netTotalEl = document.getElementById('netTotal');
                const receivedAmountEl = document.getElementById('receivedAmount');

                if (!iconEl || !nameEl || !netTotalEl) {
                    console.error('Required modal elements not found:', {
                        iconEl,
                        nameEl,
                        netTotalEl
                    });
                    Swal.fire('Error', 'Modal elements not found', 'error');
                    return;
                }

                console.log('Setting modal content for method:', method);
                iconEl.innerText = icons[method];
                nameEl.innerText = names[method];
                netTotalEl.innerText = netTotal.toFixed(2);

                // Reset inputs
                if (receivedAmountEl) {
                    if (method !== 'credit') {
                        receivedAmountEl.value = netTotal.toFixed(2);
                    } else {
                        receivedAmountEl.value = '0.00';
                    }
                }
                const amountError = document.getElementById('amountError');
                if (amountError) {
                    amountError.classList.add('hidden');
                }

                // Show/hide payment method details
                const chequeDetails = document.getElementById('chequeDetails');
                const cardDetails = document.getElementById('cardDetails');
                const creditDetails = document.getElementById('creditDetails');
                const multipleDetails = document.getElementById('multipleDetails');

                // Hide all details first
                [chequeDetails, cardDetails, creditDetails, multipleDetails].forEach(el => {
                    if (el) el.classList.add('hidden');
                });

                // Show relevant details
                if (method === 'cheque' && chequeDetails) {
                    chequeDetails.classList.remove('hidden');
                    // Reset cheque form and list
                    hideChequeForm();
                    updateChequesList();
                } else if (method === 'card' && cardDetails) {
                    cardDetails.classList.remove('hidden');
                } else if (method === 'credit' && creditDetails) {
                    creditDetails.classList.remove('hidden');
                } else if (method === 'multiple' && multipleDetails) {
                    multipleDetails.classList.remove('hidden');
                    updateMultiplePaymentsList();
                }

                // Calculate balance for cash, card, cheque
                if (method === 'cash' || method === 'card' || method === 'cheque') {
                    calculateBalance();
                }

                // Open the modal for all methods
                const paymentModalEl = document.getElementById('paymentModal');
                if (paymentModalEl) {
                    console.log('Opening payment modal for:', method);
                    const modal = new bootstrap.Modal(paymentModalEl);
                    modal.show();
                    console.log('Payment modal opened successfully');
                } else {
                    console.error('Payment modal element not found');
                    Swal.fire('Error', 'Payment modal not found', 'error');
                }

            } catch (error) {
                console.error('Error in showPaymentModal:', error);
                Swal.fire('Error', 'Failed to open payment modal: ' + error.message, 'error');
            }
        }

        function calculateBalance() {
            const netTotal = currentNetTotal;
            const receivedAmount = parseFloat(document.getElementById('receivedAmount').value) || 0;
            const amountError = document.getElementById('amountError');

            if (receivedAmount < netTotal) {
                amountError.classList.remove('hidden');
            } else {
                amountError.classList.add('hidden');
            }

            const balance = receivedAmount - netTotal;
            document.getElementById('balanceAmount').innerText = balance.toFixed(2);
        }

        function addPaymentMethod() {
            const receivedAmount = parseFloat(document.getElementById('receivedAmount').value) || 0;
            const netTotal = currentNetTotal;

            // Validation
            if (receivedAmount < netTotal) {
                Swal.fire('Error', 'Received amount cannot be less than net total', 'error');
                return;
            }

            if (receivedAmount === 0) {
                Swal.fire('Error', 'Please enter a valid amount', 'error');
                return;
            }

            // Collect payment details based on method
            const paymentDetails = {
                method: currentPaymentMethod,
                amount: receivedAmount,
                balance: receivedAmount - netTotal
            };

            if (currentPaymentMethod === 'cheque') {
                const chequeNumber = document.getElementById('chequeNumber').value.trim();
                const chequeBank = document.getElementById('chequeBank').value.trim();
                const chequeDate = document.getElementById('chequeDate').value;

                if (!chequeNumber || !chequeBank || !chequeDate) {
                    Swal.fire('Error', 'Please fill in all cheque details', 'error');
                    return;
                }

                paymentDetails.chequeNumber = chequeNumber;
                paymentDetails.chequeBank = chequeBank;
                paymentDetails.chequeDate = chequeDate;
            } else if (currentPaymentMethod === 'card') {
                const transactionId = document.getElementById('cardTransactionId').value.trim();
                if (!transactionId) {
                    Swal.fire('Error', 'Please enter transaction ID', 'error');
                    return;
                }
                paymentDetails.transactionId = transactionId;
            }

            // Dispatch to Livewire
            Livewire.dispatch('completeSaleWithPayment', [paymentDetails]);

            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            modal.hide();
        }

        function completeSaleWithPayment() {
            console.log('completeSaleWithPayment called, method:', currentPaymentMethod);
            const receivedAmount = parseFloat(document.getElementById('receivedAmount').value) || 0;
            const netTotal = currentNetTotal;

            console.log('Received amount:', receivedAmount, 'Net total:', netTotal);

            // Different validation based on payment method
            if (currentPaymentMethod === 'credit') {
                // For credit, amount can be 0
                const dueDate = document.getElementById('creditDueDate').value;
                const notes = document.getElementById('creditNotes').value.trim();

                if (!dueDate) {
                    Swal.fire('Error', 'Please select a due date', 'error');
                    return;
                }

                const paymentDetails = {
                    method: 'credit',
                    amount: 0,
                    balance: netTotal,
                    dueDate: dueDate,
                    notes: notes
                };

                Livewire.dispatch('completeSaleWithPayment', [paymentDetails]);
            } else if (currentPaymentMethod === 'multiple') {
                // For multiple payments, check if total matches
                const totalPaid = multiplePayments.reduce((sum, payment) => sum + payment.amount, 0);

                if (multiplePayments.length === 0) {
                    Swal.fire('Error', 'Please add at least one payment method', 'error');
                    return;
                }

                const paymentDetails = {
                    method: 'multiple',
                    amount: totalPaid,
                    balance: totalPaid - netTotal,
                    payments: multiplePayments
                };

                Livewire.dispatch('completeSaleWithPayment', [paymentDetails]);
            } else {
                // For cash, card, cheque - validate amount based on method
                if (currentPaymentMethod === 'cash' || currentPaymentMethod === 'card') {
                    if (receivedAmount <= 0) {
                        Swal.fire('Error', 'Please enter a valid amount', 'error');
                        return;
                    }
                    // Allow any amount for cash/card - can be partial or full payment
                }

                // Collect payment details based on method
                const paymentDetails = {
                    method: currentPaymentMethod,
                    amount: receivedAmount,
                    balance: receivedAmount - netTotal
                };

                if (currentPaymentMethod === 'cheque') {
                    // Validate that at least one cheque is added
                    if (chequesList.length === 0) {
                        Swal.fire('Error', 'Please add at least one cheque', 'error');
                        return;
                    }

                    const totalCheques = chequesList.reduce((sum, cheque) => sum + cheque.amount, 0);
                    if (totalCheques <= 0) {
                        Swal.fire('Error', 'Cheque amount must be greater than 0', 'error');
                        return;
                    }

                    paymentDetails.amount = totalCheques;
                    paymentDetails.balance = totalCheques - netTotal;
                    paymentDetails.cheques = chequesList;
                } else if (currentPaymentMethod === 'card') {
                    const transactionId = document.getElementById('cardTransactionId').value.trim();
                    if (!transactionId) {
                        Swal.fire('Error', 'Please enter transaction ID', 'error');
                        return;
                    }
                    paymentDetails.transactionId = transactionId;
                }

                console.log('Dispatching payment details:', paymentDetails);
                Livewire.dispatch('completeSaleWithPayment', [paymentDetails]);
            }

            // Close payment modal
            try {
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                if (modal) {
                    modal.hide();
                } else {
                    console.warn('Payment modal instance not found');
                }
            } catch (error) {
                console.error('Error closing payment modal:', error);
            }
        }

        function addMultiplePayment(method) {
            const amount = prompt(`Enter ${method} amount:`);
            if (!amount || parseFloat(amount) <= 0) return;

            let paymentInfo = {
                method: method,
                amount: parseFloat(amount)
            };

            if (method === 'cheque') {
                const chequeNumber = prompt('Enter cheque number:');
                const chequeBank = prompt('Enter bank name:');
                const chequeDate = prompt('Enter cheque date (YYYY-MM-DD):');

                if (!chequeNumber || !chequeBank || !chequeDate) {
                    Swal.fire('Error', 'Please provide all cheque details', 'error');
                    return;
                }

                paymentInfo.chequeNumber = chequeNumber;
                paymentInfo.chequeBank = chequeBank;
                paymentInfo.chequeDate = chequeDate;
            } else if (method === 'card') {
                const transactionId = prompt('Enter transaction ID:');
                if (!transactionId) {
                    Swal.fire('Error', 'Please enter transaction ID', 'error');
                    return;
                }
                paymentInfo.transactionId = transactionId;
            }

            multiplePayments.push(paymentInfo);
            updateMultiplePaymentsList();
        }

        // New functions for improved multiple payment UI
        function selectMultiplePaymentMethod(method) {
            // Update button states
            document.querySelectorAll('.multiPaymentBtn').forEach(btn => {
                btn.classList.remove('active');
                btn.style.background = '';
                btn.style.borderColor = '';
            });

            const selectedBtn = document.querySelector(`[data-method="${method}"]`);
            if (selectedBtn) {
                selectedBtn.classList.add('active');
                selectedBtn.style.background = method === 'cash' ? '#dcfce7' : '#fef3c7';
                selectedBtn.style.borderColor = method === 'cash' ? '#22c55e' : '#fbbf24';
            }

            // Hide all input sections
            document.getElementById('multipleCashSection').classList.add('hidden');
            document.getElementById('multipleChequeSection').classList.add('hidden');

            // Show selected input section
            if (method === 'cash') {
                document.getElementById('multipleCashSection').classList.remove('hidden');
                document.getElementById('multipleCashAmount').focus();
            } else if (method === 'cheque') {
                document.getElementById('multipleChequeSection').classList.remove('hidden');
                document.getElementById('multipleChequeNumber').focus();
            }
        }

        function addMultipleCashPayment() {
            const amount = parseFloat(document.getElementById('multipleCashAmount').value) || 0;

            if (amount <= 0) {
                Swal.fire('Error', 'Please enter a valid cash amount', 'error');
                return;
            }

            multiplePayments.push({
                method: 'cash',
                amount: amount
            });

            document.getElementById('multipleCashAmount').value = '';
            updateMultiplePaymentsList();
            Swal.fire('Success', `Cash payment of Rs. ${amount.toFixed(2)} added!`, 'success');
        }

        function addMultipleChequePayment() {
            const chequeNumber = document.getElementById('multipleChequeNumber').value.trim();
            const chequeAmount = parseFloat(document.getElementById('multipleChequeAmount').value) || 0;
            const chequeBank = document.getElementById('multipleChequeBank').value.trim();
            const chequeDate = document.getElementById('multipleChequeDate').value;

            // Validation
            if (!chequeNumber || !chequeBank || !chequeDate || chequeAmount <= 0) {
                Swal.fire('Error', 'Please fill in all cheque details with a valid amount', 'error');
                return;
            }

            // Check if cheque number already exists
            const duplicateCheque = multiplePayments.find(p =>
                p.method === 'cheque' && p.chequeNumber === chequeNumber
            );
            if (duplicateCheque) {
                Swal.fire('Error', 'This cheque number already exists in the payment list', 'error');
                return;
            }

            multiplePayments.push({
                method: 'cheque',
                chequeNumber: chequeNumber,
                amount: chequeAmount,
                chequeBank: chequeBank,
                chequeDate: chequeDate
            });

            // Clear form
            document.getElementById('multipleChequeNumber').value = '';
            document.getElementById('multipleChequeAmount').value = '';
            document.getElementById('multipleChequeBank').value = '';
            document.getElementById('multipleChequeDate').value = '';

            updateMultiplePaymentsList();
            Swal.fire('Success', `Cheque ${chequeNumber} for Rs. ${chequeAmount.toFixed(2)} added!`, 'success');
        }

        function updateMultiplePaymentsList() {
            const container = document.getElementById('paymentMethodsList');
            if (!container) return;

            const totalPaid = multiplePayments.reduce((sum, payment) => sum + payment.amount, 0);
            const remaining = Math.max(0, currentNetTotal - totalPaid);

            if (multiplePayments.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No payments added yet</p>';
                return;
            }

            container.innerHTML = `
                <div class="space-y-2">
                    ${multiplePayments.map((payment, index) => `
                        <div class="bg-white border rounded-lg p-3 flex items-center justify-between">
                            <div class="flex-1">
                                ${payment.method === 'cash' ? `
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600">payments</span>
                                        <div>
                                            <p class="font-semibold text-gray-900">Cash Payment</p>
                                            <p class="text-sm text-gray-600">Rs. ${payment.amount.toFixed(2)}</p>
                                        </div>
                                    </div>
                                ` : `
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-yellow-600">confirmation_number</span>
                                        <div>
                                            <p class="font-semibold text-gray-900">Cheque #${payment.chequeNumber}</p>
                                            <p class="text-sm text-gray-600">${payment.chequeBank} - Rs. ${payment.amount.toFixed(2)}</p>
                                            <p class="text-xs text-gray-500">Date: ${payment.chequeDate}</p>
                                        </div>
                                    </div>
                                `}
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeMultiplePayment(${index})">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    `).join('')}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex justify-between mb-2 text-sm">
                            <span class="text-gray-700">Total Paid:</span>
                            <span class="font-bold text-gray-900">Rs. ${totalPaid.toFixed(2)}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Remaining:</span>
                            <span class="font-bold ${remaining > 0 ? 'text-red-600' : 'text-green-600'}">Rs. ${remaining.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        function removeMultiplePayment(index) {
            const payment = multiplePayments[index];
            multiplePayments.splice(index, 1);

            const paymentType = payment.method === 'cash' ? 'Cash payment' : `Cheque #${payment.chequeNumber}`;
            Swal.fire('Removed', `${paymentType} has been removed`, 'info');
            updateMultiplePaymentsList();
        }

        // Cheque management functions
        let chequesList = [];

        function showChequeForm() {
            updateChequesBalanceDisplay();
            document.getElementById('chequeForm').classList.remove('hidden');
            document.getElementById('addChequeBtn').style.display = 'none';

            // Set today's date as default
            document.getElementById('chequeDate').value = new Date().toISOString().split('T')[0];
        }

        function hideChequeForm() {
            document.getElementById('chequeForm').classList.add('hidden');
            document.getElementById('addChequeBtn').style.display = 'inline-flex';
            clearChequeForm();
        }

        function clearChequeForm() {
            document.getElementById('chequeNumber').value = '';
            document.getElementById('chequeAmount').value = '';
            document.getElementById('chequeBank').value = '';
            document.getElementById('chequeDate').value = new Date().toISOString().split('T')[0];
        }

        function addChequeToList() {
            const chequeNumber = document.getElementById('chequeNumber').value.trim();
            const chequeAmount = parseFloat(document.getElementById('chequeAmount').value) || 0;
            const chequeBank = document.getElementById('chequeBank').value.trim();
            const chequeDate = document.getElementById('chequeDate').value;
            const netTotal = currentNetTotal;

            // Validation: check all fields are filled
            if (!chequeNumber || !chequeBank || !chequeDate || chequeAmount <= 0) {
                Swal.fire('Error', 'Please fill in all cheque details with a valid amount', 'error');
                return;
            }

            // Check if cheque number already exists
            if (chequesList.find(cheque => cheque.number === chequeNumber)) {
                Swal.fire('Error', 'Cheque number already exists', 'error');
                return;
            }

            // Calculate total cheques after adding this one
            const totalExistingCheques = chequesList.reduce((sum, cheque) => sum + cheque.amount, 0);
            const newTotalCheques = totalExistingCheques + chequeAmount;

            // Validation: check if new total would exceed net total
            if (newTotalCheques > netTotal) {
                const maxAllowed = netTotal - totalExistingCheques;
                Swal.fire('Error',
                    `Cheque amount exceeds available balance!\n\nNet Total: Rs. ${netTotal.toFixed(2)}\nAlready Added: Rs. ${totalExistingCheques.toFixed(2)}\nMax You Can Add: Rs. ${maxAllowed.toFixed(2)}\n\nEntered Amount: Rs. ${chequeAmount.toFixed(2)}`,
                    'error');
                return;
            }

            const cheque = {
                number: chequeNumber,
                amount: chequeAmount,
                bank: chequeBank,
                date: chequeDate
            };

            chequesList.push(cheque);
            updateChequesList();
            updateChequeTotal();
            updateChequesBalanceDisplay();
            hideChequeForm();

            Swal.fire('Success', `Cheque added! Remaining balance: Rs. ${(netTotal - newTotalCheques).toFixed(2)}`, 'success');
        }

        function removeCheque(index) {
            chequesList.splice(index, 1);
            updateChequesList();
            updateChequeTotal();
            updateChequesBalanceDisplay();
        }

        function updateChequesList() {
            const container = document.getElementById('chequesList');
            if (!container) return;

            if (chequesList.length === 0) {
                container.innerHTML = '';
                return;
            }

            const totalCheques = chequesList.reduce((sum, cheque) => sum + cheque.amount, 0);
            const netTotal = currentNetTotal;
            const remainingBalance = netTotal - totalCheques;

            container.innerHTML = `
                <div class="bg-white border rounded-lg p-3">
                    <h6 class="text-sm font-semibold text-gray-800 mb-2">Added Cheques:</h6>
                    <div class="space-y-2">
                        ${chequesList.map((cheque, index) => `
                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded border-l-4 border-blue-500">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">${cheque.bank} - #${cheque.number}</div>
                                    <div class="text-xs text-gray-500">Date: ${cheque.date} | Amount: Rs. ${cheque.amount.toFixed(2)}</div>
                                </div>
                                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeCheque(${index})">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-3 pt-3 border-t">
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <div class="bg-blue-50 p-2 rounded">
                                <div class="text-xs text-gray-600">Net Total</div>
                                <div class="font-bold text-blue-700">Rs. ${netTotal.toFixed(2)}</div>
                            </div>
                            <div class="bg-green-50 p-2 rounded">
                                <div class="text-xs text-gray-600">Cheques Added</div>
                                <div class="font-bold text-green-700">Rs. ${totalCheques.toFixed(2)}</div>
                            </div>
                            <div class="${remainingBalance === 0 ? 'bg-yellow-50' : remainingBalance > 0 ? 'bg-orange-50' : 'bg-red-50'} p-2 rounded">
                                <div class="text-xs text-gray-600">Balance</div>
                                <div class="font-bold ${remainingBalance === 0 ? 'text-yellow-700' : remainingBalance > 0 ? 'text-orange-700' : 'text-red-700'}">
                                    Rs. ${remainingBalance.toFixed(2)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateChequeTotal() {
            const totalCheques = chequesList.reduce((sum, cheque) => sum + cheque.amount, 0);
            const receivedAmountEl = document.getElementById('receivedAmount');
            if (receivedAmountEl && currentPaymentMethod === 'cheque') {
                receivedAmountEl.value = totalCheques.toFixed(2);
                calculateBalance();
            }
        }

        function updateChequesBalanceDisplay() {
            const netTotal = currentNetTotal;
            const totalCheques = chequesList.reduce((sum, cheque) => sum + cheque.amount, 0);
            const balance = netTotal - totalCheques;
            const maxCan = netTotal - totalCheques;

            // Update display elements
            const netTotalDisplay = document.getElementById('netTotalDisplay');
            const addedAmount = document.getElementById('addedChequeAmount');
            const balanceAmount = document.getElementById('balanceChequeAmount');
            const maxChequeNote = document.getElementById('maxChequeAmount');

            if (netTotalDisplay) netTotalDisplay.textContent = netTotal.toFixed(2);
            if (addedAmount) addedAmount.textContent = totalCheques.toFixed(2);
            if (balanceAmount) {
                balanceAmount.textContent = balance.toFixed(2);
                balanceAmount.style.color = balance === 0 ? '#ffc107' : balance > 0 ? '#ff9800' : '#d32f2f';
            }
            if (maxChequeNote) {
                if (maxCan > 0) {
                    maxChequeNote.textContent = `Max can add: Rs. ${maxCan.toFixed(2)}`;
                    maxChequeNote.style.color = '#666';
                } else if (maxCan === 0) {
                    maxChequeNote.textContent = 'Amount is complete';
                    maxChequeNote.style.color = '#ffc107';
                } else {
                    maxChequeNote.textContent = 'No more cheques can be added';
                    maxChequeNote.style.color = '#d32f2f';
                }
            }
        }
    </script>

    <!-- Discount Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-blue-50 border-0">
                    <h5 class="modal-title" id="discountModalLabel">
                        Apply Discount to <span id="discountProductName" class="font-bold text-blue-600"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body space-y-4">
                    <input type="hidden" id="discountProductId" value="">

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Discount Type</label>
                        <div class="flex gap-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="discountType" id="discountType" value="percentage" checked class="w-4 h-4">
                                <span class="ml-2 text-gray-700">Percentage (%)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="discountType" id="discountTypeAmount" value="amount" class="w-4 h-4">
                                <span class="ml-2 text-gray-700">Fixed Amount (Rs)</span>
                            </label>
                        </div>
                    </div>

                    <div id="percentageInput" class="block">
                        <label for="discountPercentage" class="block text-sm font-semibold text-gray-900 mb-2">Discount Percentage</label>
                        <input type="number" id="discountPercentage" class="form-control" placeholder="Enter percentage" min="0" max="100" step="0.01">
                    </div>

                    <div id="amountInput" class="hidden">
                        <label for="discountAmount" class="block text-sm font-semibold text-gray-900 mb-2">Discount Amount (Rs)</label>
                        <input type="number" id="discountAmount" class="form-control" placeholder="Enter amount" min="0" step="0.01">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-gray-50">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="applyDiscount()">Apply Discount</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between percentage and fixed amount input
        document.querySelectorAll('input[name="discountType"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'percentage') {
                    document.getElementById('percentageInput').classList.remove('hidden');
                    document.getElementById('amountInput').classList.add('hidden');
                    document.getElementById('discountPercentage').focus();
                } else {
                    document.getElementById('percentageInput').classList.add('hidden');
                    document.getElementById('amountInput').classList.remove('hidden');
                    document.getElementById('discountAmount').focus();
                }
            });
        });
    </script>
    @endpush
</div>