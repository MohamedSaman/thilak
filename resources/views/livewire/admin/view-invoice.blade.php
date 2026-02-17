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
            const invoiceContent = document.querySelector('#invoiceContent');
            if (!invoiceContent) {
                alert('Invoice content not found.');
                return;
            }

            // Determine format (A4 or thermal)
            const format = document.getElementById('printSizeSelectInvoice')?.value || localStorage.getItem('printFormat') || 'A4';

            // Extract customer information
            let customerName = 'Walk-in Customer';
            let customerAddress = '';
            let customerPhone = '';

            const customerDetailsDiv = invoiceContent.querySelector('.col-md-6.border-end');
            if (customerDetailsDiv) {
                const customerNameEl = customerDetailsDiv.querySelector('h6.fw-bold');
                if (customerNameEl) {
                    customerName = customerNameEl.textContent.trim();
                }

                const customerInfoPs = customerDetailsDiv.querySelectorAll('p.text-sm.text-muted');
                if (customerInfoPs.length > 0) {
                    customerAddress = customerInfoPs[0].textContent.trim();
                }
                if (customerInfoPs.length > 1) {
                    customerPhone = customerInfoPs[1].textContent.trim();
                }
            }

            // Extract invoice information
            let invoiceNumber = '';
            let date = '';

            const invoiceInfoDiv = invoiceContent.querySelector('.col-md-6.ps-md-4');
            if (invoiceInfoDiv) {
                const invoiceElements = invoiceInfoDiv.querySelectorAll('.d-flex');
                invoiceElements.forEach(el => {
                    const text = el.textContent;
                    if (text.includes('Invoice No:')) {
                        const spanEl = el.querySelector('span.fw-bold');
                        if (spanEl) invoiceNumber = spanEl.textContent.trim();
                    } else if (text.includes('Date:')) {
                        const spanEl = el.querySelector('span.fw-bold');
                        if (spanEl) date = spanEl.textContent.trim();
                    }
                });
            }

            // Extract items from table
            const itemsTableBody = invoiceContent.querySelector('.table.table-sm tbody');
            let itemsArray = [];
            if (itemsTableBody) {
                const rows = itemsTableBody.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 4) {
                        const itemName = cells[0].querySelector('.fw-bold')?.textContent.trim() || '';
                        const qty = cells[1].textContent.trim();
                        const price = cells[2].textContent.trim();
                        const total = cells[3].textContent.trim();
                        itemsArray.push({
                            no: index + 1,
                            item: itemName,
                            qty: qty,
                            mrp: price,
                            price: price,
                            total: total
                        });
                    }
                });
            }

            // Extract totals
            let subtotal = '';
            let discount = '';
            let returns = '';
            let grandTotal = '';

            const totalsSection = invoiceContent.querySelector('.glass-card.p-4.bg-light-soft');
            if (totalsSection) {
                const totalRows = totalsSection.querySelectorAll('.d-flex');
                totalRows.forEach(row => {
                    const text = row.textContent;
                    if (text.includes('Subtotal:')) {
                        const valueEl = row.querySelector('span.fw-bold');
                        if (valueEl) subtotal = valueEl.textContent.trim();
                    } else if (text.includes('Discount:')) {
                        const valueEl = row.querySelector('span');
                        if (valueEl) discount = valueEl.textContent.trim();
                    } else if (text.includes('Returns:')) {
                        const valueEl = row.querySelector('span');
                        if (valueEl) returns = valueEl.textContent.trim();
                    } else if (text.includes('Grand Total:')) {
                        const valueEl = row.querySelector('h5.text-primary');
                        if (valueEl) grandTotal = valueEl.textContent.trim();
                    }
                });
            }

            // Build items table HTML
            let itemsTableHTML = '<table style="width:100%; border-collapse:collapse; margin:5px 0 20px;">';
            itemsTableHTML += '<thead><tr>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0;">No</th>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0;">Item</th>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0; text-align:center;">Qty</th>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0; text-align:right;">MRP</th>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0; text-align:right;">Price</th>';
            itemsTableHTML += '<th style="border:1px dashed #000; padding:3px; background-color:#f0f0f0; text-align:right;">Total</th>';
            itemsTableHTML += '</tr></thead><tbody>';

            itemsArray.forEach(item => {
                itemsTableHTML += '<tr>';
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:center;">${item.no}</td>`;
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px;">${item.item}</td>`;
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:center;">${item.qty}</td>`;
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right;">${item.mrp}</td>`;
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right;">${item.price}</td>`;
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right;">${item.total}</td>`;
                itemsTableHTML += '</tr>';
            });

            // Add summary rows
            if (subtotal) {
                itemsTableHTML += '<tr>';
                itemsTableHTML += '<td colspan="5" style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">Subtotal:</td>';
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">${subtotal}</td>`;
                itemsTableHTML += '</tr>';
            }

            if (discount && discount !== '-Rs.0.00') {
                itemsTableHTML += '<tr>';
                itemsTableHTML += '<td colspan="5" style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">Discount:</td>';
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">${discount}</td>`;
                itemsTableHTML += '</tr>';
            }

            if (returns && returns !== '-Rs.0.00') {
                itemsTableHTML += '<tr>';
                itemsTableHTML += '<td colspan="5" style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">Returns:</td>';
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">${returns}</td>`;
                itemsTableHTML += '</tr>';
            }

            if (grandTotal) {
                itemsTableHTML += '<tr>';
                itemsTableHTML += '<td colspan="5" style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">Grand Total:</td>';
                itemsTableHTML += `<td style="border:1px dashed #000; padding:3px; text-align:right; font-weight:bold;">${grandTotal}</td>`;
                itemsTableHTML += '</tr>';
            }

            itemsTableHTML += '</tbody></table>';

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
                            table td, table th{padding:3px; vertical-align:top; border:1px dashed #000}
                            table th{background-color:#f0f0f0; font-weight:bold}
                            .text-right{text-align:right}
                            .text-center{text-align:center}
                            .footer{margin-top:8px; font-size:11px; text-align:center}
                            @media print{ body{padding:2mm} }
                        </style>
                    </head>
                    <body>
                        <div>
                            <div class="company-name">THILAK HARDWARE</div>
                            <div class="company-address">Phone: 077 9089961</div>
                            <div class="dashed"></div>

                            <table style="border:none;">
                                <tr><td style="border:none; font-weight:bold;">Name:</td><td style="border:none;">${customerName}</td></tr>
                                <tr><td style="border:none; font-weight:bold;">Address:</td><td style="border:none;">${customerAddress}</td></tr>
                                <tr><td style="border:none; font-weight:bold;">Phone:</td><td style="border:none;">${customerPhone}</td></tr>
                                <tr><td style="border:none; font-weight:bold;">Invoice:</td><td style="border:none;">${invoiceNumber}</td></tr>
                                <tr><td style="border:none; font-weight:bold;">Date:</td><td style="border:none;">${date}</td></tr>
                            </table>

                            <div class="dashed"></div>

                            ${itemsTableHTML}

                            <div class="dashed"></div>
                            <div class="footer">
                                <div>*****ORIGINAL*****</div>
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
                            border-bottom: 2px dashed #000;
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
                            border: 1px dashed #000;
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

                            ${itemsTableHTML}

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