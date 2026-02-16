<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Sales Invoices</h2>
                    <p class="text-muted mb-0">Browse and manage historical sales and billing records.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
             <div class="d-flex align-items-center justify-content-md-end gap-2">
                <button wire:click="exportCSV" class="btn btn-white shadow-premium text-success">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV
                </button>
                <button wire:click="exportPDF" class="btn btn-white shadow-premium text-danger">
                    <i class="bi bi-file-earmark-pdf me-2"></i>PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                 <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-indigo-soft text-indigo">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Invoices</span>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalSales) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-indigo" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Lifetime transactions</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-success-soft text-success">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Today's Sales</span>
                        <h3 class="mb-0 fw-bold">{{ number_format($todaySales) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Processed today</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-primary-soft text-primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Revenue</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Gross sales value</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-info-soft text-info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Today's Revenue</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($todayRevenue, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Earning today</span>
            </div>
        </div>
    </div>

    <!-- Invoices Table Card -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <!-- Search Bar Area -->
        <div class="p-4 bg-light-soft border-bottom">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent text-sm" placeholder="Search by invoice number or customer..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <div style="min-width: 150px;">
                            <input type="date" class="form-control text-sm rounded-lg" wire:model.live="dateFrom">
                        </div>
                        <div style="min-width: 150px;">
                            <input type="date" class="form-control text-sm rounded-lg" wire:model.live="dateTo">
                        </div>
                        <div style="min-width: 150px;">
                            <select class="form-select text-sm rounded-lg" wire:model.live="paymentType">
                                <option value="">Payment Type</option>
                                <option value="full">Full</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                         <button class="btn btn-secondary btn-sm rounded-lg" wire:click="resetFilters" title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light-soft text-xs">
                    <tr>
                        <th class="ps-4">Invoice #</th>
                        <th>Customer</th>
                        <th class="text-center">Date & Time</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-primary">#{{ $sale->invoice_number }}</td>
                        <td>
                            <h6 class="mb-0 fw-bold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</h6>
                            <span class="text-xs text-muted">{{ $sale->customer->phone ?? 'Generic' }}</span>
                        </td>
                        <td class="text-center">
                            <div class="text-sm fw-bold">{{ $sale->created_at->format('d M, Y') }}</div>
                            <span class="text-xs text-muted">{{ $sale->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-indigo-soft rounded-pill">{{ $sale->items->count() }}</span>
                        </td>
                        <td class="text-end fw-bold">Rs.{{ number_format($sale->total_amount, 2) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $sale->payment_type === 'full' ? 'badge-success-soft' : 'badge-warning-soft' }} rounded-pill text-xs">
                                {{ strtoupper($sale->payment_type) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = [
                                    'paid' => 'badge-success-soft',
                                    'partial' => 'badge-warning-soft',
                                    'unpaid' => 'badge-danger-soft'
                                ][$sale->payment_status] ?? 'badge-secondary-soft';
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-xs">
                                {{ strtoupper($sale->payment_status) }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                <button class="btn btn-sm btn-white text-info" wire:click="viewInvoice({{ $sale->id }})" title="View Invoice">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-white text-warning" wire:click="editInvoice({{ $sale->id }})" title="Edit Invoice">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No invoices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
        <div class="p-4 border-top">
            {{ $sales->links() }}
        </div>
        @endif
    </div>

    <!-- Invoice Modal -->
    <div wire:ignore.self class="modal fade" id="invoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5 align-items-center">
                    <h4 class="fw-bold mb-0 text-primary">Sales Invoice Receipt</h4>
                    <div class="ms-auto d-flex gap-2">
                        <select id="printSizeSelectInvoice" class="form-select form-select-sm rounded-lg" style="width: 140px;">
                            <option value="A4">A4 Format</option>
                            <option value="thermal">Thermal (80mm)</option>
                        </select>
                        <button class="btn btn-sm btn-white shadow-sm rounded-lg" onclick="printInvoice()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-0" id="invoiceContent">
                    @if($saleDetails)
                    <div class="p-5">
                         <div class="text-center mb-5">
                            <h2 class="fw-bold text-primary mb-1">THILAK HARDWARE</h2>
                            <p class="text-muted mb-0 small">Stone, Sand, Cement and all types of building materials</p>
                            <p class="text-muted mb-0">NO 569/17A, THIHARIYA, KALAGEDIHENA.</p>
                            <p class="text-muted small">Hotline: 077 9089961</p>
                        </div>

                        <div class="row g-4 mb-5 pb-5 border-bottom border-light">
                            <div class="col-md-6 border-end">
                                <span class="text-xs text-muted text-uppercase fw-bold tracking-wider">Customer Details</span>
                                @if ($saleDetails['sale']->customer)
                                    <h6 class="fw-bold mt-2 mb-1">{{ $saleDetails['sale']->customer->name }}</h6>
                                    <p class="text-sm text-muted mb-0">{{ $saleDetails['sale']->customer->address ?? 'N/A' }}</p>
                                    <p class="text-sm text-muted">{{ $saleDetails['sale']->customer->phone ?? 'N/A' }}</p>
                                @else
                                    <h6 class="fw-bold mt-2 mb-1">Walk-in Customer</h6>
                                @endif
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <span class="text-xs text-muted text-uppercase fw-bold tracking-wider">Invoice Info</span>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-sm text-muted">Invoice No:</span>
                                    <span class="text-sm fw-bold">#{{ $saleDetails['sale']->invoice_number }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm text-muted">Date:</span>
                                    <span class="text-sm fw-bold">{{ $saleDetails['sale']->created_at->format('d/m/Y h:i A') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm text-muted">Status:</span>
                                    <span class="text-primary fw-bold">{{ strtoupper($saleDetails['sale']->payment_status) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mb-5">
                            <table class="table table-sm">
                                <thead class="bg-light-soft text-xs">
                                    <tr>
                                        <th class="ps-2">Item Description</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end pe-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($saleDetails['items'] as $item)
                                    <tr>
                                        <td class="ps-2 py-2">
                                            <div class="fw-bold text-sm">{{ $item->product->product_name ?? 'Unknown' }}</div>
                                            <span class="text-xs text-muted">{{ $item->product->product_code ?? '' }}</span>
                                        </td>
                                        <td class="text-center text-sm">{{ $item->quantity }}</td>
                                        <td class="text-end text-sm">Rs.{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end pe-2 text-sm">Rs.{{ number_format(($item->price * $item->quantity) - ($item->discount * $item->quantity), 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="glass-card p-4 bg-light-soft border-0">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal:</span>
                                        <span class="fw-bold">Rs.{{ number_format($saleDetails['sale']->subtotal, 2) }}</span>
                                    </div>
                                    @if($saleDetails['sale']->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-danger">
                                        <span>Discount:</span>
                                        <span>-Rs.{{ number_format($saleDetails['sale']->discount_amount, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($saleDetails['totalReturnAmount'] > 0)
                                    <div class="d-flex justify-content-between mb-2 text-warning">
                                        <span>Returns:</span>
                                        <span>-Rs.{{ number_format($saleDetails['totalReturnAmount'], 2) }}</span>
                                    </div>
                                    @endif
                                    <div class="d-flex justify-content-between border-top pt-3 mt-3">
                                        <h5 class="fw-bold mb-0">Grand Total:</h5>
                                        <h5 class="fw-bold mb-0 text-primary">Rs.{{ number_format($saleDetails['adjustedGrandTotal'], 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openInvoiceModal', () => {
            let modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
            modal.show();
        });

        Livewire.on('openModal', (modalId) => {
            let modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        window.printInvoice = function() {
            const format = document.getElementById('printSizeSelectInvoice').value;
            const content = document.getElementById('invoiceContent').cloneNode(true);
            const printWindow = window.open('', '_blank', 'width=1000,height=700');

            let style = '';
            if (format === 'thermal') {
                style = `
                    @page { size: 80mm auto; margin: 0; }
                    body { font-family: 'Courier New', monospace; font-size: 11px; padding: 5mm; width: 70mm; }
                    h2 { font-size: 14px; }
                    table { font-size: 10px; width: 100%; border-bottom: 1px dashed #000; }
                    .ps-2, .pe-2 { padding: 0 !important; }
                    .glass-card { background: none !important; border: none !important; padding: 0 !important; }
                    .col-md-5 { width: 100% !important; }
                `;
            } else {
                style = `
                    @page { size: A4; margin: 20mm; }
                    body { font-family: 'Inter', sans-serif; padding: 20px; color: #1a2d5e; }
                    .report-header { border-bottom: 2px solid #233d7f; margin-bottom: 30px; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background-color: #f8faff !important; border: 1px solid #ddd; padding: 8px; }
                    td { border: 1px solid #ddd; padding: 8px; }
                `;
            }

            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice Receipt</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>${style}</style>
                </head>
                <body>
                    ${content.innerHTML}
                    <script>window.print();<\/script>
                </body>
                </html>
            `;
            printWindow.document.write(htmlContent);
            printWindow.document.close();
        };

        Livewire.on('showToast', (data) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 3000
            });
        });
    });
</script>
@endpush