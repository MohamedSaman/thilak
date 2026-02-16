<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Payment Ledger</h2>
                    <p class="text-muted mb-0">Record of all transactions, approvals, and pending collections.</p>
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
                    <div class="icon-shape icon-lg bg-success-soft text-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Paid</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($totalPayments, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Lifetime collection</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-warning-soft text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Pending Total</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($pendingPayments, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                </div>
                <span class="text-xs text-muted">Awaiting clearance</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-info-soft text-info">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Today's Paid</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($todayTotalPayments, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Processed today</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-danger-soft text-danger">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Today's Pending</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($todayPendingPayments, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-danger" style="width: 40%"></div>
                </div>
                <span class="text-xs text-muted">Due for today</span>
            </div>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <!-- Action Bar -->
        <div class="p-4 bg-light-soft border-bottom">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent text-sm" placeholder="Search invoices or customers..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <div style="min-width: 150px;">
                            <select class="form-select text-sm rounded-lg" wire:model.live="filters.status">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div style="min-width: 150px;">
                            <select class="form-select text-sm rounded-lg" wire:model.live="filters.paymentMethod">
                                <option value="">All Methods</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="credit">Credit</option>
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
                        <th class="ps-4">Invoice</th>
                        <th>Customer</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Method</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-primary">{{ $payment->sale->invoice_number }}</td>
                        <td>
                            <h6 class="mb-0 fw-bold">{{ $payment->sale->customer->name ?? 'Walk-in Customer' }}</h6>
                            <span class="text-xs text-muted">{{ $payment->sale->customer->phone ?? 'No phone' }}</span>
                        </td>
                        <td class="text-end fw-bold">Rs.{{ number_format($payment->amount, 2) }}</td>
                        <td class="text-center">
                            <span class="badge badge-secondary-soft rounded-pill text-xs">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $status = $payment->status ?: ($payment->is_completed ? 'paid' : 'pending');
                                $statusClass = [
                                    'pending' => 'badge-warning-soft',
                                    'paid' => 'badge-success-soft',
                                    'approved' => 'badge-success-soft',
                                    'rejected' => 'badge-danger-soft'
                                ][strtolower($status)] ?? 'badge-secondary-soft';
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-xs">
                                {{ strtoupper($status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="fw-bold text-sm">
                                {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : ($payment->due_date ? 'Due: '.$payment->due_date->format('d M, Y') : 'N/A') }}
                            </div>
                            <span class="text-xs text-muted">{{ $payment->payment_date ? $payment->payment_date->format('h:i A') : '' }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-white text-primary shadow-sm rounded-lg" wire:click="viewPaymentDetails({{ $payment->id }})" title="View Receipt">
                                <i class="bi bi-receipt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-muted mb-0">No payment records found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($payments->hasPages())
        <div class="p-4 border-top">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

    <!-- Modals -->
    {{-- Receipt Modal --}}
    <div wire:ignore.self class="modal fade" id="payment-receipt-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Payment Receipt</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-white shadow-sm rounded-lg" onclick="printReceiptContent()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-5" id="receiptContent">
                    @if ($selectedPayment)
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-primary mb-1">THILAK HARDWARE</h2>
                        <p class="text-muted mb-0">NO 569/17A, THIHARIYA, KALAGEDIHENA.</p>
                        <p class="text-muted small">Hotline: 077 9089961</p>
                        <div class="d-inline-block px-4 py-2 bg-light-soft border-0 rounded-pill mt-3">
                            <h5 class="mb-0 fw-bold">Official Receipt</h5>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6 border-end">
                            <span class="text-xs text-muted text-uppercase fw-bold tracking-wider">Customer</span>
                            <h6 class="fw-bold mt-1 mb-1">{{ $selectedPayment->sale->customer->name ?? 'Guest Customer' }}</h6>
                            <p class="text-sm text-muted mb-0">{{ $selectedPayment->sale->customer->phone ?? '' }}</p>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <span class="text-xs text-muted text-uppercase fw-bold tracking-wider">Transaction Info</span>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-sm text-muted">Receipt ID:</span>
                                <span class="text-sm fw-bold">#{{ $selectedPayment->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm text-muted">Date:</span>
                                <span class="text-sm fw-bold">{{ $selectedPayment->payment_date ? $selectedPayment->payment_date->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-5">
                        <table class="table table-sm">
                            <thead class="bg-light-soft text-xs">
                                <tr>
                                    <th class="ps-2">Item Description</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedPayment->sale->items as $item)
                                <tr>
                                    <td class="ps-2 text-sm">{{ $item->product->product_name }}</td>
                                    <td class="text-center text-sm">{{ $item->quantity }}</td>
                                    <td class="text-end pe-2 text-sm">Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top-2">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold py-3">Received Amount:</td>
                                    <td class="text-end fw-bold py-3 pe-2 text-primary fs-5">Rs.{{ number_format($selectedPayment->amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="p-4 bg-light-soft rounded-lg text-center">
                        <p class="text-sm text-muted mb-0 italic">"Thank you for your business!"</p>
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
        Livewire.on('openModal', (modalId) => {
            let modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        window.printReceiptContent = function() {
            const printContent = document.getElementById('receiptContent').cloneNode(true);
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            
            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Payment Receipt</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; }
                        .table { font-size: 13px; }
                        th { background-color: #f8faff !important; border-bottom: 2px solid #ddd; }
                        .text-primary { color: #233d7f !important; }
                        .italic { font-style: italic; }
                    </style>
                </head>
                <body>
                    ${printContent.innerHTML}
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
                timer: 3000,
                timerProgressBar: true
            });
        });
    });
</script>
@endpush