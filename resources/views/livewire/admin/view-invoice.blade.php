<div class="container-fluid py-4">
    <!-- Page Header with Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <div class="card-header text-white p-5 rounded-t-4 d-flex align-items-center"
                    style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-receipt-cutoff text-white fs-4" aria-hidden="true"></i>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold tracking-tight text-white">Bills & Invoices</h3>
                        <p class="text-white opacity-80 mb-0 text-sm">View and manage all sales invoices</p>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="row g-4">

                        <!-- Stats Cards -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="bi bi-receipt text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Total Sales</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">{{ number_format($totalSales) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                                            <i class="bi bi-calendar-check text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Today's Sales</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">{{ number_format($todaySales) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);">
                                            <i class="bi bi-currency-dollar text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Total Revenue</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">Rs.{{ number_format($totalRevenue, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape icon-md rounded-circle p-3 d-flex align-items-center justify-content-center me-3"
                                            style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                                            <i class="bi bi-cash-coin text-white fs-5" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-uppercase fw-semibold mb-1" style="color: #6b7280;">Today's Revenue</p>
                                            <h4 class="mb-0 fw-bold" style="color: #1f2937;">Rs.{{ number_format($todayRevenue, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
        <!-- Search & Filter Bar -->
        <div class="card-header p-4" style="background-color: #eff6ff;">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="input-group shadow-sm rounded-full overflow-hidden" style="max-width: 400px;">
                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-search text-blue-600" aria-hidden="true"></i>
                    </span>
                    <input type="text"
                        class="form-control border-0 py-2.5 bg-white text-gray-800"
                        placeholder="Search by invoice number or customer..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off"
                        aria-label="Search by invoice number or customer">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button wire:click="exportCSV" class="btn btn-success btn-sm rounded-pill shadow-sm px-3 py-2">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> CSV
                    </button>
                    <button wire:click="exportPDF" class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-2">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                            type="button" id="filterDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-funnel me-1"></i> Filters
                        </button>
                        <div class="dropdown-menu p-4 shadow-lg border-0 rounded-4" style="width: 300px;"
                            aria-labelledby="filterDropdown">
                            <h6 class="dropdown-header bg-light rounded py-2 mb-3 text-center text-sm fw-semibold" style="color: #1e3a8a;">Filter Options</h6>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Date From</label>
                                <input type="date" class="form-control form-control-sm rounded-full shadow-sm" wire:model.live="dateFrom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Date To</label>
                                <input type="date" class="form-control form-control-sm rounded-full shadow-sm" wire:model.live="dateTo">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Payment Type</label>
                                <select class="form-select form-select-sm rounded-full shadow-sm" wire:model.live="paymentType">
                                    <option value="">All</option>
                                    <option value="full">Full Payment</option>
                                    <option value="partial">Partial Payment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Customer Type</label>
                                <select class="form-select form-select-sm rounded-full shadow-sm" wire:model.live="customerType">
                                    <option value="">All</option>
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                                    wire:click="resetFilters">
                                    <i class="bi bi-x-circle me-1"></i> Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #eff6ff;">
                        <tr>
                            <th class="ps-4 text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Invoice #</th>
                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Customer</th>
                            <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Date</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Items</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-end" style="color: #1e3a8a;">Total Amount</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Payment</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Status</th>
                            <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td class="fw-bold ps-4">#{{ $sale->invoice_number }}</td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                                    <div class="text-xs text-gray-600">{{ $sale->customer->phone ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $sale->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-600">{{ $sale->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                    {{ $sale->items->count() }} Items
                                </span>
                            </td>
                            <td class="text-end fw-bold">Rs.{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-center">
                                @if($sale->payment_type === 'full')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Full</span>
                                @else
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">Partial</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($sale->payment_status === 'paid')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Paid</span>
                                @elseif($sale->payment_status === 'partial')
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2">Partial</span>
                                @else
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">Unpaid</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white rounded-pill px-3 me-2" wire:click="viewInvoice({{ $sale->id }})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-warning text-white rounded-pill px-3" wire:click="editInvoice({{ $sale->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-600">No invoices found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
            <div class="card-footer p-4 bg-white border-top rounded-b-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="text-sm text-gray-600">
                        Showing <span class="fw-semibold text-gray-800">{{ $sales->firstItem() }}</span>
                        to <span class="fw-semibold text-gray-800">{{ $sales->lastItem() }}</span> of
                        <span class="fw-semibold text-gray-800">{{ $sales->total() }}</span> results
                    </div>
                    <div>
                        {{ $sales->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Invoice Details Modal -->
    <div wire:ignore.self class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 shadow-xl"
                style="border: 2px solid #233D7F; background: linear-gradient(145deg, #FFFFFF, #F8F9FA);">
                <div class="modal-header"
                    style="background-color: #233D7F; color: #FFFFFF; border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                    <h5 class="modal-title fw-bold tracking-tight">
                        <i class="bi bi-receipt me-2"></i>Sales Receipt
                    </h5>
                    <div class="ms-auto d-flex gap-2 align-items-center">
                        <select id="printSizeSelectInvoice" class="form-select form-select-sm me-2" style="width:170px;">
                            <option value="A4">A4</option>
                            <option value="thermal">Thermal (80mm)</option>
                        </select>
                        <button type="button" class="btn btn-sm rounded-full px-3 transition-all hover:shadow"
                            onclick="printInvoice()" style="background-color: #233D7F;border-color:#fff; color: #fff;">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close btn-close-white opacity-75 hover:opacity-100"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                @if($saleDetails)
                <div class="modal-body p-4" id="invoiceContent">
                    <div class="receipt-container">
                        <div class="text-center mb-4">
                            <h3 class="mb-1 fw-bold tracking-tight" style="color: #233D7F;">THILAK HARDWARE</h3>
                            <h5 class="mb-1 fw-medium" style="color: #233D7F;">Stone, Sand, Cement all types of building items<br>are provided at affordable prices</h5>
                            <p class="mb-0 text-muted small" style="color: #6B7280;">NO 569/17A, THIHARIYA, KALAGEDIHENA</p>
                            <p class="mb-0 text-muted small" style="color: #6B7280;">Phone: 077 9089961</p>
                            <hr style="border: 2px solid #233D7F;">
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                @if ($saleDetails['sale']->customer)
                                <p class="mb-1" style="color: #233D7F;"><strong>Customer Name:</strong> {{ $saleDetails['sale']->customer->name }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Address:</strong> {{ $saleDetails['sale']->customer->address ?? 'N/A' }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Phone:</strong> {{ $saleDetails['sale']->customer->phone ?? 'N/A' }}</p>
                                @else
                                <p class="text-muted" style="color: #6B7280;">Walk-in Customer</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1" style="color: #233D7F;"><strong>Invoice Number:</strong> {{ $saleDetails['sale']->invoice_number }}</p>
                                <p class="mb-1" style="color: #233D7F;"><strong>Date:</strong> {{ $saleDetails['sale']->created_at->setTimezone('Asia/Colombo')->format('d/m/Y h:i A') }}</p>
                                <p class="mb-1"><strong>Payment Status:</strong>
                                    @if(ucfirst($saleDetails['sale']->payment_status) == 'Paid')
                                    <span class="badge" style="background-color: #0F5132; color: #FFFFFF;">Paid</span>
                                    @else
                                    <span class="badge" style="background-color: #842029; color: #FFFFFF;">Credit</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm border-1" style="border-color: #233D7F;">
                                <thead style="background-color: #233D7F; color: #FFFFFF;">
                                    <tr>
                                        <th scope="col" class="text-center py-2">No</th>
                                        <th scope="col" class="text-center py-2">Code</th>
                                        <th scope="col" class="text-center py-2">Product Name</th>
                                        <th scope="col" class="text-center py-2">Qty</th>
                                        <th scope="col" class="text-center py-2">Unit Price</th>
                                        <th scope="col" class="text-center py-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody style="color: #233D7F;">
                                    @foreach ($saleDetails['items'] as $index => $item)
                                    <tr>
                                        <td class="text-center py-1">{{ $index + 1 }}</td>
                                        <td class="text-center py-1">{{ $item->product->product_code ?? 'N/A' }}</td>
                                        <td class="text-left py-1">{{ $item->product->product_name ?? 'N/A' }}</td>
                                        <td class="text-center py-1">{{ $item->quantity }}</td>
                                        <td class="text-right py-1">{{ number_format($item->price, 2) }}</td>
                                        <td class="text-right py-1">{{ number_format(($item->price * $item->quantity) - ($item->discount * $item->quantity), 2) }}</td>
                                    </tr>
                                    @endforeach

                                    <!-- Summary rows -->
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold" style="font-size: 14px;">Amount (LKR):</td>
                                        <td class="text-right py-2 fw-bold" style="font-size: 14px;">{{ number_format($saleDetails['sale']->subtotal, 2) }}</td>
                                    </tr>
                                    @if($saleDetails['sale']->discount_amount > 0)
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Total Discount:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">({{ number_format($saleDetails['sale']->discount_amount, 2) }})</td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">{{ number_format($saleDetails['adjustedGrandTotal'], 2) }}</td>
                                    </tr>
                                    @else
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Total Discount:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;"></td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;"></td>
                                    </tr>
                                    @endif

                                    @if($saleDetails['totalReturnAmount'] > 0)
                                    <tr style="background-color: #f8f9fa;">
                                        <td colspan="5" class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">Returns:</td>
                                        <td class="text-right py-2 fw-bold text-danger" style="font-size: 14px;">({{ number_format($saleDetails['totalReturnAmount'], 2) }})</td>
                                    </tr>
                                    <tr style="background-color: #233D7F; color: #FFFFFF;">
                                        <td colspan="5" class="text-right py-2 fw-bold fs-6" style="font-size: 14px;">After Return Total:</td>
                                        <td class="text-right py-2 fw-bold fs-6" style="font-size: 14px;"></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Return Items Section (if any) -->
                        @if($saleDetails['returnItems']->count() > 0)
                        <div class="return-items-section mb-4">
                            <h6 class="fw-bold mb-3 text-danger">
                                <i class="bi bi-arrow-return-left me-2"></i>Returned Items Details
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-danger">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Total</th>
                                            <th>Notes</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saleDetails['returnItems'] as $index => $return)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $return->product->product_name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $return->return_quantity }}</td>
                                            <td class="text-right">Rs.{{ number_format($return->selling_price, 2) }}</td>
                                            <td class="text-right fw-bold">Rs.{{ number_format($return->total_amount, 2) }}</td>
                                            <td>{{ $return->notes ?? '-' }}</td>
                                            <td class="text-center">{{ $return->created_at->format('d M Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-4 pt-3 border-top" style="border-color: #233D7F;">
                            <p class="mb-0 text-muted small" style="color: #6B7280;">Thank you for your purchase!</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="modal-body p-4">
                    <div class="text-center py-5">
                        <p class="text-muted">Loading invoice details...</p>
                    </div>
                </div>
                @endif
                <div class="modal-footer border-top py-3" style="border-color: #233D7F; background: #F8F9FA;">
                    <button type="button"
                        class="btn btn-secondary rounded-pill px-4 fw-medium transition-all hover:shadow"
                        data-bs-dismiss="modal"
                        style="background-color: #6B7280; border-color: #6B7280; color: #FFFFFF;">Close</button>
                </div>
            </div>
        </div>
    </div>
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
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(35, 61, 127, 0.1);
    }

    .card-header {
        border-radius: 1rem 1rem 0 0;
        border: none;
    }

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        padding: 0.5rem 0.75rem !important;
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

    /* Stat Cards Styling */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(35, 61, 127, 0.08);
        border: 1px solid #e5e7eb;
    }

    .stat-card:hover {
        box-shadow: 0 8px 20px rgba(35, 61, 127, 0.12);
        transform: translateY(-2px);
    }

    /* Text Styles */
    .text-sm {
        font-size: 0.875rem;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .text-gray-600 {
        color: #4b5563;
    }

    .text-gray-800 {
        color: #1f2937;
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

@push('scripts')
<script>
    function printInvoice() {
        const modal = document.getElementById('invoiceModal');
        if (!modal) {
            alert('Please open an invoice first.');
            return;
        }

        const modalBody = modal.querySelector('#invoiceContent');

        // Determine print format
        const format = document.getElementById('printSizeSelectInvoice')?.value || localStorage.getItem('printFormat') || 'A4';

        // Extract customer information
        const leftCol = modalBody.querySelector('.col-md-6:first-child');
        let customerName = 'Walk-in Customer';
        let customerAddress = '';
        let customerPhone = '';

        if (leftCol) {
            const paragraphs = leftCol.querySelectorAll('p');
            if (paragraphs.length >= 3) {
                customerName = paragraphs[0].textContent.replace('Customer Name:', '').trim();
                customerAddress = paragraphs[1].textContent.replace('Address:', '').trim();
                customerPhone = paragraphs[2].textContent.replace('Phone:', '').trim();
            }
        }

        // Extract invoice information
        const rightCol = modalBody.querySelector('.col-md-6:last-child');
        let invoiceNumber = '';
        let date = '';

        if (rightCol) {
            const paragraphs = rightCol.querySelectorAll('p');
            if (paragraphs.length >= 2) {
                invoiceNumber = paragraphs[0].textContent.replace('Invoice Number:', '').trim();
                date = paragraphs[1].textContent.replace('Date:', '').trim();
            }
        }

        // Get the main items table
        const itemsTable = modalBody.querySelector('.table-bordered');

        // Check for return items section
        const returnSection = modalBody.querySelector('.return-items-section');
        let returnTableHTML = '';
        if (returnSection) {
            const returnTable = returnSection.querySelector('.table');
            if (returnTable) {
                returnTableHTML = `
                    <h6 style="color: #000; font-weight: bold; margin-top: 20px; margin-bottom: 10px; font-size: 12px;">
                        RETURNED ITEMS DETAILS
                    </h6>
                    ${returnTable.outerHTML}
                `;
            }
        }

        const printWindow = window.open('', '_blank', 'height=600,width=800');

        if (format === 'thermal') {
            // Thermal (80mm) compact style
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Sales Invoice - ${invoiceNumber}</title>
                    <style>
                        *{margin:0;padding:0;box-sizing:border-box}
                        @page{size:80mm auto; margin:3mm}
                        body{font-family:'Courier New', monospace !important; padding:8px; font-size:12px; width:78mm; color:#000}
                        .company-name{font-size:18px;text-align:center;font-weight:bold}
                        .company-address{font-size:11px;text-align:center;margin-bottom:6px}
                        .dashed{border-top:1px dashed #000;margin:6px 0}
                        table{width:100%;border-collapse:collapse;font-size:11px}
                        table td{padding:3px 0}
                        .text-right{text-align:right}
                        .footer{margin-top:8px;font-size:11px;text-align:center}
                        @media print{body{padding:2mm}}
                    </style>
                </head>
                <body>
                    <div>
                        <div class="company-name">THILAK HARDWARE</div>
                        <div class="company-address">Phone: 077 9089961</div>
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
                            <div>Thank you for your purchase!</div>
                        </div>
                    </div>
                </body>
                </html>
            `);
        } else {
            // A4 full layout (existing)
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Sales Invoice - ${invoiceNumber}</title>
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
                    .info-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 2px;
                    }
                    .info-section {
                        width: 48%;
                        padding: 8px;
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
                        font-size: 11px;
                    }
                    table th {
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-left { text-align: left; }
                    
                    /* Footer Styling */
                    .footer {
                        text-align: center;
                        margin-top: 30px;
                        padding-top: 20px;
                        color: #000;
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
                    
                    .footer{
                        text-align: center;
                        margin-bottom: 100px;
                        padding-top: 20px;
                        color: #000;
                        position: absolute;
                        bottom: 20px;
                        width: 100%;
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

    document.addEventListener('livewire:initialized', () => {
        @this.on('openInvoiceModal', () => {
            let modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            const sel = document.getElementById('printSizeSelectInvoice');
            if (sel) {
                sel.value = localStorage.getItem('printFormat') || 'A4';
                sel.addEventListener('change', function() {
                    localStorage.setItem('printFormat', this.value);
                });
            }
            modal.show();
        });
    });
</script>
@endpush