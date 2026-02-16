<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Cheque Payments</h2>
                    <p class="text-muted mb-0">Manage and track pending cheque payments from customers.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
             <div class="d-flex align-items-center justify-content-md-end gap-2">
                <button wire:click="exportCSV" class="btn btn-white shadow-premium text-success">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </button>
                <button wire:click="exportPDF" class="btn btn-white shadow-premium text-danger">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </button>
                <button wire:click="printDueChequePayments" class="btn btn-primary shadow-premium">
                    <i class="bi bi-printer me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-info-soft text-info">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Pending Cheques</span>
                        <h3 class="mb-0 fw-bold">{{ $pendingChequeCount }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-info" style="width: 70%"></div>
                </div>
                <span class="text-xs text-muted">Awaiting collection</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-success-soft text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Complete Cheques</span>
                        <h3 class="mb-0 fw-bold">{{ $completeChequeCount }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Successfully cleared</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-danger-soft text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Returned Cheques</span>
                        <h3 class="mb-0 fw-bold">{{ $returnChequeCount }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-danger" style="width: 30%"></div>
                </div>
                <span class="text-xs text-muted">Attention required</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-primary-soft text-primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Due</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($totalDueAmount, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
                <span class="text-xs text-muted">Overall pending value</span>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <!-- Action Bar -->
        <div class="p-4 bg-light-soft border-bottom">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent text-sm" placeholder="Search invoice or customer..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="d-flex flex-wrap justify-content-md-end gap-2">
                        <div style="min-width: 180px;">
                            <select class="form-select text-sm rounded-lg" wire:model.live="filters.status">
                                <option value="">All Statuses</option>
                                <option value="null">Pending Payment</option>
                                <option value="pending">Pending Approval</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div style="min-width: 150px;">
                            <input type="date" class="form-control text-sm rounded-lg" placeholder="Start Date" wire:model.live="filters.startDate">
                        </div>
                        <div style="min-width: 150px;">
                            <input type="date" class="form-control text-sm rounded-lg" placeholder="End Date" wire:model.live="filters.endDate">
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
                        <th class="ps-4">#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Cheque Details</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Cheque Date</th>
                        <th class="text-center">Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duePayments as $cheque)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ $loop->iteration }}</td>
                        <td><span class="fw-bold text-primary">{{ $cheque->payment->sale->invoice_number ?? 'N/A' }}</span></td>
                        <td>
                            <h6 class="mb-0 fw-bold">{{ $cheque->customer->name ?? 'N/A' }}</h6>
                            <span class="text-xs text-muted">{{ $cheque->customer->phone ?? 'No phone' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 text-xs">
                                <i class="bi bi-credit-card text-muted"></i>
                                <span class="fw-medium text-muted">No: {{ $cheque->cheque_number ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold">Rs. {{ number_format($cheque->cheque_amount ?? 0, 2) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-sm {{ $cheque->cheque_date?->isPast() && $cheque->status === 'pending' ? 'text-danger fw-bold' : '' }}">
                                {{ $cheque->cheque_date?->format('d/m/Y') ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = [
                                    'pending' => 'badge-warning-soft',
                                    'complete' => 'badge-success-soft',
                                    'return' => 'badge-danger-soft'
                                ][$cheque->status] ?? 'badge-secondary-soft';
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-xs">
                                @if($cheque->status == 'pending')
                                    PENDING
                                @elseif($cheque->status == 'complete')
                                    COMPLETED
                                @elseif($cheque->status == 'return')
                                    RETURNED
                                @else
                                    {{ strtoupper($cheque->status ?? 'N/A') }}
                                @endif
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group shadow-sm rounded-lg overflow-hidden bg-white">
                                @if($cheque->status == 'pending')
                                <button onclick="confirmCompletePayment({{ $cheque->id }})" class="btn btn-sm btn-white text-success" title="Mark as Complete">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                <button onclick="confirmReturnCheque({{ $cheque->id }})" class="btn btn-sm btn-white text-danger" title="Mark as Returned">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </button>
                                @elseif($cheque->status == 'return')
                                <button wire:click="refundCheque({{ $cheque->id }})" class="btn btn-sm btn-white text-warning" title="Process Refund">
                                    <i class="bi bi-arrow-left-right"></i>
                                </button>
                                @else
                                <button class="btn btn-sm btn-white text-muted" disabled>
                                    <i class="bi bi-lock-fill"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-receipt fs-1 text-muted opacity-20"></i>
                            <p class="text-muted mt-2">No due cheque payments found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($duePayments->hasPages())
        <div class="p-4 border-top">
            {{ $duePayments->links() }}
        </div>
        @endif
    </div>

    <!-- Modals -->

    {{-- Receive Cheque Payment Modal --}}
    <div wire:ignore.self class="modal fade" id="payment-detail-modal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">Process Cheque Payment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    @if ($paymentDetail)
                    <div class="row g-5">
                        <div class="col-md-4">
                            <div class="glass-card p-4 h-100">
                                <div class="text-center mb-4">
                                    <div class="avatar avatar-xl rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold fs-2 mb-3">
                                        {{ strtoupper(substr($paymentDetail->customer->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $paymentDetail->customer->name ?? 'N/A' }}</h5>
                                    <p class="text-muted text-sm">{{ $paymentDetail->customer->phone ?? 'N/A' }}</p>
                                </div>
                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-sm text-muted">Invoice:</span>
                                        <span class="text-sm fw-bold">#{{ $paymentDetail->payment->sale->invoice_number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-sm text-muted">Cheque No:</span>
                                        <span class="text-sm fw-bold">{{ $paymentDetail->cheque_number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="text-sm text-muted">Cheque Date:</span>
                                        <span class="text-sm fw-bold">
                                            {{ $paymentDetail->cheque_date?->format('d/m/Y') ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="bg-primary-soft p-3 rounded-lg text-center">
                                        <span class="text-xs text-primary text-uppercase fw-bold tracking-wider">Amount Due</span>
                                        <h4 class="fw-bold text-primary mb-0">Rs.{{ number_format($paymentDetail->cheque_amount ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <form wire:submit.prevent="submitPayment">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Received Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light-soft border-0">Rs.</span>
                                            <input type="number" step="0.01" class="form-control" wire:model="receivedAmount">
                                        </div>
                                        @error('receivedAmount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Attached Document (CSV/PDF/Image)</label>
                                        <input type="file" class="form-control" wire:model="duePaymentAttachment">
                                        @error('duePaymentAttachment') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Payment Notes (Optional)</label>
                                        <textarea class="form-control" rows="3" wire:model="paymentNote" placeholder="Any additional information..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <div class="glass-card p-3 bg-light-soft border-0">
                                            <h6 class="fw-bold mb-3 text-sm">Attachment Preview</h6>
                                            <div class="text-center">
                                                @if ($duePaymentAttachment)
                                                    <div class="preview-box p-3 border rounded-xl bg-white">
                                                        <i class="bi bi-file-earmark-check fs-1 text-success"></i>
                                                        <p class="mb-0 mt-2 text-sm">{{ $duePaymentAttachment->getClientOriginalName() }}</p>
                                                    </div>
                                                @elseif($paymentDetail && $paymentDetail->due_payment_attachment)
                                                    <div class="preview-box p-3 border rounded-xl bg-white">
                                                        <i class="bi bi-file-earmark-check fs-1 text-primary"></i>
                                                        <p class="mb-0 mt-2 text-sm">Existing Attachment Available</p>
                                                    </div>
                                                @else
                                                    <div class="p-4 border border-dashed rounded-xl opacity-50">
                                                        <i class="bi bi-image fs-1 mb-2"></i>
                                                        <p class="mb-0 text-sm">No document attached</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-3">Loading details...</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                    @if($paymentDetail)
                    <button type="button" class="btn btn-primary px-5 shadow-premium" wire:click="submitPayment">
                        Complete Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Extend Due Date Modal --}}
    <div wire:ignore.self class="modal fade" id="extend-due-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-warning-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-warning">Extend Cheque Due Date</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label">New Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="newDueDate" min=\"{{ date('Y-m-d') }}\">
                            @error('newDueDate') <span class="text-danger text-xs text-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason for Extension <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3" wire:model="extensionReason" placeholder="Explain the reason for extension..."></textarea>
                            @error('extensionReason') <span class="text-danger text-xs text-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Discard</button>
                    <button type="button" class="btn btn-warning px-5 shadow-premium" wire:click="extendDueDate">Confirm Extension</button>
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

        Livewire.on('closeModal', (modalId) => {
            let modalElement = document.getElementById(modalId);
            let modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        });

        Livewire.on('showToast', (data) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: data.type,
                title: data.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1f2937'
            });
        });

        window.confirmCompletePayment = function(chequeId) {
            Swal.fire({
                title: 'Mark as Complete?',
                text: 'Are you sure you want to mark this cheque as paid and complete?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Mark Paid',
                customClass: {
                    popup: 'rounded-xl border-0 shadow-premium',
                    confirmButton: 'btn btn-success px-4',
                    cancelButton: 'btn btn-secondary px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('completePaymentDetails', chequeId);
                }
            });
        };

        window.confirmReturnCheque = function(chequeId) {
            Swal.fire({
                title: 'Mark as Returned?',
                text: 'Confirming this will mark the cheque as returned/bounced.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Mark Returned',
                customClass: {
                    popup: 'rounded-xl border-0 shadow-premium',
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-secondary px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('returnCheque', chequeId);
                }
            });
        };

        // Print function
        Livewire.on('print-due-cheque-payments', function() {
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            const tableElement = document.querySelector('.table').cloneNode(true);
            
            // Remove actions column
            const headerRow = tableElement.querySelector('thead tr');
            headerRow.lastElementChild.remove();
            const rows = tableElement.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.lastElementChild) row.lastElementChild.remove();
            });

            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Cheque Payments Report</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; color: #1a2d5e; }
                        .report-header { border-bottom: 2px solid #233d7f; margin-bottom: 30px; padding-bottom: 20px; }
                        table { width: 100%; font-size: 13px; }
                        th { background-color: #f8faff !important; color: #233d7f !important; text-transform: uppercase; font-size: 11px; }
                        .badge { border: 1px solid #ddd; color: #333; background: none; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="report-header">
                        <h2 class="fw-bold">Cheque Payments Due Report</h2>
                        <p class="text-muted mb-0">Generated on: ${new Date().toLocaleString()}</p>
                    </div>
                    ${tableElement.outerHTML}
                    <div class="mt-5 text-center text-muted small">
                        <p>Thilak Hardware Management System</p>
                    </div>
                    <script>window.print();<\/script>
                </body>
                </html>
            `;

            printWindow.document.open();
            printWindow.document.write(htmlContent);
            printWindow.document.close();
        });
    });
</script>
@endpush