<div class="container-fluid py-4">
    <!-- Page Header & Key Stats -->
    <div class="row g-4 mb-5 align-items-center">
        <div class="col-xl-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-danger-soft text-danger rounded-xl">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Due Payments</h2>
                    <p class="text-muted mb-0 font-medium">Tracking and managing pending customer balances.</p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="glass-card p-3 border-start border-4 border-danger rounded-xl shadow-premium">
                        <div class="text-xs text-muted text-uppercase fw-bold tracking-wider mb-1">Total Outstanding</div>
                        <h4 class="fw-bold mb-0 text-danger">Rs.{{ number_format($totalDue, 2) }}</h4>
                        <div class="text-xs mt-1 text-muted">{{ $duePaymentsCount }} active credit accounts</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="glass-card p-3 border-start border-4 border-warning rounded-xl shadow-premium">
                        <div class="text-xs text-muted text-uppercase fw-bold tracking-wider mb-1">Expected Today</div>
                        <h4 class="fw-bold mb-0 text-warning">Rs.{{ number_format($todayDuePayments, 2) }}</h4>
                        <div class="text-xs mt-1 text-muted">{{ $todayDuePaymentsCount }} payments maturing today</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card p-4 mb-4 rounded-xl shadow-premium border-0">
        <div class="row g-3 align-items-center">
            <div class="col-lg-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text bg-transparent border-0 pe-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-transparent py-2" 
                           placeholder="Search by invoice # or customer name..." 
                           wire:model.live.debounce.300ms="search">
                </div>
            </div>
            <div class="col-lg-3">
                <select class="form-select border-0 bg-light-soft" wire:model.live="filters.status">
                    <option value="">All Statuses</option>
                    <option value="null">Pending Collection</option>
                    <option value="Paid">Marked as Paid</option>
                </select>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-inline-flex gap-2">
                    <button wire:click="exportCSV" class="btn btn-sm btn-secondary">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV
                    </button>
                    <button wire:click="exportPDF" class="btn btn-sm btn-secondary">
                        <i class="bi bi-file-earmark-pdf me-2"></i>PDF
                    </button>
                    <button wire:click="printDuePayments" class="btn btn-sm btn-secondary">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                    <button wire:click="resetFilters" class="btn btn-sm btn-light-soft text-primary fw-bold">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Due Payments Table -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light-soft">
                    <tr>
                        <th class="ps-4">Invoice Detail</th>
                        <th>Customer</th>
                        <th class="text-center">Due Amount</th>
                        <th class="text-center">Status</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duePayments as $payment)
                    @php
                        $isOverdue = now()->gt($payment->due_date) && $payment->status === null;
                    @endphp
                    <tr class="hover-bg-light transition-all {{ $isOverdue ? 'bg-danger-soft-lite' : '' }}">
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $payment->sale->invoice_number }}</span>
                                <span class="text-xs text-muted">Issued: {{ $payment->sale->created_at->format('M d, Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-sm rounded-circle bg-primary-soft text-primary fw-bold text-xs">
                                    {{ strtoupper(substr($payment->sale?->customer?->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 text-sm fw-bold">{{ $payment->sale?->customer?->name ?? 'Guest Customer' }}</h6>
                                    <span class="text-xs text-muted">{{ $payment->sale?->customer?->phone ?? 'No phone' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="fw-bold text-danger">Rs.{{ number_format($payment->amount, 2) }}</span>
                            @if($isOverdue)
                                <div class="text-xs text-danger fw-bold"><i class="bi bi-clock-history"></i> Overdue</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($payment->status === null)
                                <span class="badge badge-danger-soft px-3 py-2 rounded-pill">Unpaid</span>
                            @elseif($payment->status === 'Paid')
                                <span class="badge badge-success-soft px-3 py-2 rounded-pill">Paid</span>
                            @else
                                <span class="badge badge-secondary-soft px-3 py-2 rounded-pill">{{ $payment->status }}</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-primary btn-sm px-4 rounded-lg shadow-premium" 
                                    wire:click="getPaymentDetails({{ $payment->id }}, 0)">
                                <i class="bi bi-wallet2 me-2"></i>Receive
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="opacity-20 mb-3 fs-1"><i class="bi bi-check2-circle text-success"></i></div>
                            <h6 class="text-muted">Zero pending due payments. Good job!</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($duePayments->hasPages())
        <div class="p-4 border-top bg-light-soft">
            {{ $duePayments->links('livewire::bootstrap') }}
        </div>
        @endif
    </div>

    <!-- Payment Detail Modal -->
    @if($paymentDetail)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary py-4 px-5 text-white">
                    <h4 class="fw-bold mb-0">Record Payment Collection</h4>
                    <button type="button" class="btn-close btn-close-white" wire:click="$set('paymentDetail', null)"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Summary Sidebar -->
                        <div class="col-lg-4 bg-light-soft p-5 border-end">
                            <div class="text-center mb-5">
                                <div class="avatar avatar-xl rounded-circle bg-primary text-white mx-auto shadow-premium mb-3 d-flex align-items-center justify-content-center" style="font-size: 2.5rem;">
                                    {{ strtoupper(substr($paymentDetail->sale->customer->name, 0, 1)) }}
                                </div>
                                <h4 class="fw-bold mb-1">{{ $paymentDetail->sale->customer->name }}</h4>
                                <span class="text-muted">{{ $paymentDetail->sale->customer->phone }}</span>
                            </div>

                            <div class="glass-card p-4 rounded-xl border-danger border-start border-4 mb-4">
                                <label class="text-xs text-muted text-uppercase fw-bold d-block mb-1">Outstanding Balance</label>
                                <h3 class="fw-bold text-danger mb-0">Rs.{{ number_format($paymentDetail->amount, 2) }}</h3>
                            </div>

                            <div class="list-group list-group-flush bg-transparent">
                                <div class="list-group-item bg-transparent border-0 px-0 d-flex justify-content-between">
                                    <span class="text-muted">Invoice #</span>
                                    <span class="fw-bold text-dark">{{ $paymentDetail->sale->invoice_number }}</span>
                                </div>
                                <div class="list-group-item bg-transparent border-0 px-0 d-flex justify-content-between">
                                    <span class="text-muted">Sale Date</span>
                                    <span class="fw-bold text-dark">{{ $paymentDetail->sale->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Entry Form -->
                        <div class="col-lg-8 p-5">
                            <form wire:submit.prevent="submitPayment">
                                <h5 class="fw-bold mb-4 border-bottom pb-3">Collection Details</h5>
                                
                                <div class="row g-4 mb-5">
                                    <div class="col-md-6">
                                        <label class="form-label text-sm fw-bold">Cash Received (Rs.)</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light text-muted border-0">Rs.</span>
                                            <input type="text" class="form-control border-0 bg-light-soft fw-bold text-primary" 
                                                   wire:model="receivedAmount" placeholder="0.00">
                                        </div>
                                        @error('receivedAmount') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <p class="text-xs text-muted mb-2">Recording partial or full cash payments directly affects cash in hand.</p>
                                    </div>
                                </div>

                                <div class="bg-light-soft p-4 rounded-xl mb-4 border border-dashed">
                                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-bank text-primary"></i> Cheque Payments
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" wire:model="chequeNumber" placeholder="Cheque #">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select form-select-sm" wire:model="bankName">
                                                <option value="">Bank Name</option>
                                                @foreach ($banks as $bank) <option value="{{ $bank }}">{{ $bank }}</option> @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="date" class="form-control form-control-sm" wire:model="chequeDate">
                                        </div>
                                        <div class="col-md-3 d-flex gap-2">
                                            <input type="text" class="form-control form-control-sm" wire:model="chequeAmount" placeholder="Amount">
                                            <button type="button" class="btn btn-sm btn-primary" wire:click="addCheque"><i class="bi bi-plus"></i></button>
                                        </div>
                                    </div>

                                    @if(count($cheques) > 0)
                                    <div class="table-responsive mt-3">
                                        <table class="table table-sm text-xs">
                                            <thead>
                                                <tr>
                                                    <th>Check #</th>
                                                    <th>Bank</th>
                                                    <th>Date</th>
                                                    <th class="text-end">Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cheques as $index => $c)
                                                <tr>
                                                    <td>{{ $c['number'] }}</td>
                                                    <td>{{ $c['bank'] }}</td>
                                                    <td>{{ $c['date'] }}</td>
                                                    <td class="text-end fw-bold">Rs.{{ number_format($c['amount'], 2) }}</td>
                                                    <td class="text-end"><button type="button" class="btn btn-xs text-danger" wire:click="removeCheque({{ $index }})"><i class="bi bi-x-circle"></i></button></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-sm fw-bold">Reception Notes</label>
                                    <textarea class="form-control border-0 bg-light-soft" rows="3" wire:model="paymentNote" placeholder="Any details for the accounts team..."></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <button type="button" class="btn btn-light-soft text-warning fw-bold" wire:click="$dispatch('showExtensionModal')">
                                        <i class="bi bi-calendar-event me-2"></i>Extend Due Date
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-secondary px-4" wire:click="$set('paymentDetail', null)">Discard</button>
                                        <button type="submit" class="btn btn-primary px-5 shadow-premium">Complete Collection</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Extend Due Date Modal -->
    <div wire:ignore.self class="modal fade" id="extend-due-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-warning py-4 px-5">
                    <h4 class="fw-bold mb-0 text-dark">Grant Payment Extension</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="extendDueDate">
                <div class="modal-body p-5">
                    <div class="text-center mb-5">
                        <div class="icon-shape icon-xl bg-warning-soft text-warning mx-auto rounded-circle mb-3">
                            <i class="bi bi-calendar-check fs-2"></i>
                        </div>
                        <p class="text-muted">You are extending the credit period for this invoice. Please document the reason carefully.</p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-sm fw-bold">New Maturity Date</label>
                        <input type="date" class="form-control form-control-lg border-0 bg-light-soft" wire:model="newDueDate" min="{{ date('Y-m-d') }}">
                        @error('newDueDate') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-sm fw-bold">Official Reason</label>
                        <textarea class="form-control border-0 bg-light-soft" rows="4" wire:model="extensionReason" placeholder="Customer requested extension due to..."></textarea>
                        @error('extensionReason') <span class="text-danger text-xs mt-1 d-block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-5 shadow-premium fw-bold">Authorize Extension</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('showExtensionModal', () => {
        const modal = new bootstrap.Modal(document.getElementById('extend-due-modal'));
        modal.show();
    });
</script>
@endpush