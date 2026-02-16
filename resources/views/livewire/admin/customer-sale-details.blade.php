<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Customer Sales</h2>
                    <p class="text-muted mb-0">Monitor and manage customer billing and payment summaries.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
             <div class="d-flex align-items-center justify-content-md-end gap-2">
                <button wire:click="exportToCSV" class="btn btn-white shadow-premium text-success">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </button>
                <button wire:click="printData" class="btn btn-primary shadow-premium">
                    <i class="bi bi-printer me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <!-- Search Bar Area -->
        <div class="p-4 bg-light-soft border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent text-sm" placeholder="Search customers by name or phone..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light-soft text-xs">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Customer Details</th>
                        <th class="text-center">Invoices</th>
                        <th class="text-end">Total Sales</th>
                        <th class="text-end">Received Cheques</th>
                        <th class="text-end">Paid Amount</th>
                        <th class="text-end">Due Amount</th>
                        <th class="text-end">Balance Total</th>
                        <th class="pe-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerSales as $index => $customer)
                    @php
                        $received_cheque = $customer->received_cheque_amount ?? 0;
                        $total_due = $customer->total_due ?? 0;
                        $total_sales = $customer->total_sales ?? 0;
                        $total_paid = $total_sales - ($total_due + $received_cheque);
                        $balance_total = $total_due + $received_cheque;
                    @endphp
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ $customerSales->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $customer->name }}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary-soft rounded-pill">{{ $customer->invoice_count }}</span>
                        </td>
                        <td class="text-end fw-bold">Rs.{{ number_format($total_sales, 2) }}</td>
                        <td class="text-end">
                            <span class="text-warning fw-bold">Rs.{{ number_format($received_cheque, 2) }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-success fw-bold">Rs.{{ number_format($total_paid, 2) }}</span>
                        </td>
                        <td class="text-end">
                            <span class="text-danger fw-bold">Rs.{{ number_format($total_due, 2) }}</span>
                        </td>
                        <td class="text-end">
                             <span class="badge {{ $balance_total > 0 ? 'badge-danger-soft' : 'badge-success-soft' }} px-3 py-2 rounded-pill text-xs">
                                Rs.{{ number_format($balance_total, 2) }}
                            </span>
                        </td>
                        <td class="pe-4 text-center">
                            <button wire:click="viewSaleDetails({{ $customer->customer_id }})" class="btn btn-sm btn-white text-primary shadow-sm rounded-lg" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-person-x fs-1 text-muted opacity-20"></i>
                            <p class="text-muted mt-2">No customer sales data found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customerSales->hasPages())
        <div class="p-4 border-top">
            {{ $customerSales->links() }}
        </div>
        @endif
    </div>

    <!-- Modals -->
    <div wire:ignore.self class="modal fade" id="customerSalesModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-premium rounded-xl overflow-hidden">
                <div class="modal-header border-0 bg-primary-soft py-4 px-5">
                    <h4 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-clock-history me-2"></i>
                        {{ $modalData ? $modalData['customer']->name : 'Customer' }} Sales History
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    @if($modalData)
                    <div class="p-5">
                        <!-- Profile Card -->
                        <div class="glass-card mb-5 p-4 bg-light-soft border-0">
                            <div class="row align-items-center">
                                <div class="col-md-auto mb-3 mb-md-0">
                                    <div class="avatar avatar-xl rounded-circle bg-primary text-white fw-bold fs-2 d-flex align-items-center justify-content-center">
                                        {{ strtoupper(substr($modalData['customer']->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="col-md">
                                    <h4 class="fw-bold mb-1">{{ $modalData['customer']->name }}</h4>
                                    <div class="d-flex flex-wrap gap-3 text-sm text-muted">
                                        @if($modalData['customer']->email)
                                            <span><i class="bi bi-envelope me-1"></i>{{ $modalData['customer']->email }}</span>
                                        @endif
                                        @if($modalData['customer']->phone)
                                            <span><i class="bi bi-telephone me-1"></i>{{ $modalData['customer']->phone }}</span>
                                        @endif
                                        @if($modalData['customer']->business_name)
                                            <span><i class="bi bi-shop me-1"></i>{{ $modalData['customer']->business_name }}</span>
                                        @endif
                                        <span class="badge badge-primary-soft">{{ ucfirst($modalData['customer']->type) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Row -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="stat-card p-4 text-center">
                                    <span class="text-xs text-muted text-uppercase fw-bold tracking-wider">Total Sales</span>
                                    <h3 class="fw-bold mb-1 mt-2">Rs.{{ number_format($modalData['salesSummary']->total_amount, 2) }}</h3>
                                    <p class="text-xs text-muted mb-0">{{ count($modalData['invoices']) }} Invoices</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card p-4 text-center border-success-soft">
                                    <span class="text-xs text-muted text-uppercase fw-bold tracking-wider text-success">Total Paid</span>
                                    <h3 class="fw-bold mb-1 mt-2 text-success">Rs.{{ number_format($modalData['salesSummary']->total_paid, 2) }}</h3>
                                    @php $paidPer = $modalData['salesSummary']->total_amount > 0 ? round(($modalData['salesSummary']->total_paid / $modalData['salesSummary']->total_amount) * 100) : 0; @endphp
                                    <div class="progress progress-xs mt-2 mx-auto" style="width: 100px;">
                                        <div class="progress-bar bg-success" style="width: {{ $paidPer }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card p-4 text-center border-danger-soft">
                                    <span class="text-xs text-muted text-uppercase fw-bold tracking-wider text-danger">Outstanding</span>
                                    <h3 class="fw-bold mb-1 mt-2 text-danger">Rs.{{ number_format($modalData['salesSummary']->total_due, 2) }}</h3>
                                    @php $duePer = $modalData['salesSummary']->total_amount > 0 ? round(($modalData['salesSummary']->total_due / $modalData['salesSummary']->total_amount) * 100) : 0; @endphp
                                    <div class="progress progress-xs mt-2 mx-auto" style="width: 100px;">
                                        <div class="progress-bar bg-danger" style="width: {{ $duePer }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Table -->
                        <div class="glass-card rounded-xl overflow-hidden shadow-premium">
                            <div class="p-3 bg-light-soft border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-sm">Detailed History</h6>
                                <button class="btn btn-sm btn-white shadow-sm" onclick="printTableOnly()">
                                    <i class="bi bi-printer me-1"></i>Print Logs
                                </button>
                            </div>
                            <div class="table-responsive" style="max-height: 400px;">
                                <table class="table align-middle mb-0">
                                    <thead class="bg-light-soft text-xs position-sticky top-0 z-index-1">
                                        <tr>
                                            <th class="ps-4">No</th>
                                            <th>Description</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-end">Sales Amount</th>
                                            <th class="text-end pe-4">Received Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($modalData['displayData'] as $item)
                                        @php
                                            $rowClass = '';
                                            if($item['type'] === 'total') $rowClass = 'bg-primary-soft fw-bold';
                                            if($item['type'] === 'due') $rowClass = 'bg-danger-soft fw-bold';
                                        @endphp
                                        <tr class="{{ $rowClass }} hover-bg-light transition-all">
                                            <td class="ps-4 text-xs text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($item['type'] === 'payment')
                                                        <i class="bi bi-wallet2 text-success text-xs"></i>
                                                    @elseif($item['type'] === 'invoice')
                                                        <i class="bi bi-file-text text-primary text-xs"></i>
                                                    @endif
                                                    <span class="text-sm">{{ $item['description'] }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center text-xs text-muted">
                                                {{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('d M, Y') : '-' }}
                                            </td>
                                            <td class="text-end text-sm">
                                                {{ $item['sales_amount'] > 0 && $item['type'] !== 'due' ? 'Rs.'.number_format($item['sales_amount'], 2) : '-' }}
                                            </td>
                                            <td class="text-end text-sm pe-4">
                                                @if($item['type'] === 'due')
                                                    <span class="text-danger">Rs.{{ number_format($item['sales_amount'], 2) }}</span>
                                                @else
                                                    {{ $item['received_amount'] > 0 ? 'Rs.'.number_format($item['received_amount'], 2) : '-' }}
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No history found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-3">Fetching customer data...</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 bg-light-soft p-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        window.addEventListener('open-customer-sale-details-modal', event => {
            let modal = new bootstrap.Modal(document.getElementById('customerSalesModal'));
            modal.show();
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

        // Main print report
        Livewire.on('print-customer-table', function() {
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            const tableElement = document.querySelector('.table').cloneNode(true);
            
            // Cleanup table for print
            const headerRow = tableElement.querySelector('thead tr');
            headerRow.lastElementChild.remove();
            const rows = tableElement.querySelectorAll('tbody tr');
            rows.forEach(row => { if(row.lastElementChild) row.lastElementChild.remove(); });

            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Customer Sales Summary</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; color: #1a2d5e; }
                        .report-header { border-bottom: 2px solid #233d7f; margin-bottom: 30px; padding-bottom: 20px; text-align: center; }
                        table { width: 100%; font-size: 11px; margin-bottom: 30px; }
                        th { background-color: #f8faff !important; color: #233d7f !important; text-transform: uppercase; padding: 10px; border: 1px solid #ddd; }
                        td { border: 1px solid #ddd; padding: 8px; }
                        .text-end { text-align: right; }
                        .text-center { text-align: center; }
                        .fw-bold { font-weight: 700; }
                    </style>
                </head>
                <body>
                    <div class="report-header">
                        <h2 class="fw-bold">Customer Sales Summary Report</h2>
                        <p class="text-muted">Generated on: ${new Date().toLocaleString()}</p>
                    </div>
                    ${tableElement.outerHTML}
                    <div class="mt-5 text-center text-muted small">
                        <p>Thilak Hardware Management System</p>
                    </div>
                    <script>window.print();<\/script>
                </body>
                </html>
            `;
            printWindow.document.write(htmlContent);
            printWindow.document.close();
        });
    });

    function printTableOnly() {
        const modalTable = document.querySelector('#customerSalesModal .table').cloneNode(true);
        const customerName = document.querySelector('#customerSalesModal h4').innerText;
        const printWindow = window.open('', '_blank', 'width=1000,height=700');

        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>${customerName} - Statement</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: 'Inter', sans-serif; padding: 40px; }
                    .header { border-bottom: 1px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
                    table { font-size: 11px; width: 100%; border: 1px solid #eee; }
                    th { text-transform: uppercase; background: #eee !important; padding: 8px; }
                    td { padding: 6px; border-bottom: 1px solid #eee; }
                    .bg-primary-soft { background-color: #f0f7ff !important; }
                    .bg-danger-soft { background-color: #fff5f5 !important; }
                </style>
            </head>
            <body>
                <div class="header text-center">
                    <h3>Customer Sales & Payment Statement</h3>
                    <h5>${customerName}</h5>
                    <p class="small text-muted">Generated on: ${new Date().toLocaleString()}</p>
                </div>
                ${modalTable.outerHTML}
                <script>window.print();<\/script>
            </body>
            </html>
        `;
        printWindow.document.write(htmlContent);
        printWindow.document.close();
    }
</script>
@endpush