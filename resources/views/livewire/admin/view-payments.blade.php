<div>
    <div class="container-fluid py-6 bg-gray-50 min-vh-100 transition-colors duration-300">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                    <div class="card-header text-white p-5 rounded-t-4 d-flex align-items-center"
                        style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                        <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                            <i class="bi bi-card-list text-white fs-4" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="mb-1 fw-bold tracking-tight text-white">Payment Records</h3>
                            <p class="text-white opacity-80 mb-0 text-sm">View and manage all payment records</p>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <div class="row g-4">
                            <div class="col-xl-3 col-md-6">
                                <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-md rounded-circle bg-success bg-opacity-10 me-3 text-center">
                                                <i class="bi bi-wallet2 text-success"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 mb-0 text-uppercase fw-semibold">Total Payments</p>
                                                <div class="d-flex align-items-baseline mt-1">
                                                    <h4 class="mb-0 fw-bold text-gray-800">Rs.{{ number_format($totalPayments, 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-md rounded-circle bg-warning bg-opacity-10 me-3 text-center">
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 mb-0 text-uppercase fw-semibold">Pending Payments</p>
                                                <div class="d-flex align-items-baseline mt-1">
                                                    <h4 class="mb-0 fw-bold text-gray-800">Rs.{{ number_format($pendingPayments, 2) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-md rounded-circle bg-warning bg-opacity-10 me-3 text-center">
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 mb-0 text-uppercase fw-semibold">Today Total Payments</p>
                                                <div class="d-flex align-items-baseline mt-1">
                                                    <h4 class="mb-0 fw-bold text-gray-800">Rs.{{number_format($todayTotalPayments, 2)}}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card border-0 shadow-lg rounded-4 h-100 transition-all hover:scale-105">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-md rounded-circle bg-warning bg-opacity-10 me-3 text-center">
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 mb-0 text-uppercase fw-semibold">Today Pending Payments</p>
                                                <div class="d-flex align-items-baseline mt-1">
                                                    <h4 class="mb-0 fw-bold text-gray-800">Rs.{{number_format($todayPendingPayments, 2)}}</h4>
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
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
            <div class="card-header p-4" style="background-color: #eff6ff;">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="input-group shadow-sm rounded-full overflow-hidden" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search text-blue-600" aria-hidden="true"></i>
                        </span>
                        <input type="text"
                            class="form-control border-0 py-2.5 bg-white text-gray-800"
                            placeholder="Search invoices or customers..."
                            wire:model.live.debounce.300ms="search"
                            autocomplete="off"
                            aria-label="Search invoices or customers">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-light rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                                type="button" id="filterDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-funnel me-1"></i> Filters
                                @if ($filters['status'] || $filters['paymentMethod'] || $filters['dateRange'])
                                <span class="badge bg-primary ms-1 rounded-full" style="background-color: #1e40af; color: #ffffff;">!</span>
                                @endif
                            </button>
                            <div class="dropdown-menu p-4 shadow-lg border-0 rounded-4" style="width: 300px;"
                                aria-labelledby="filterDropdown">
                                <h6 class="dropdown-header bg-light rounded py-2 mb-3 text-center text-sm fw-semibold" style="color: #1e3a8a;">Filter Options</h6>
                                <div class="mb-3">
                                    <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Payment Status</label>
                                    <select class="form-select form-select-sm rounded-full shadow-sm"
                                        wire:model.live="filters.status">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending Approval</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Payment Method</label>
                                    <select class="form-select form-select-sm rounded-full shadow-sm"
                                        wire:model.live="filters.paymentMethod">
                                        <option value="">All Methods</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-sm fw-semibold" style="color: #1e3a8a;">Date Range</label>
                                    <input type="text"
                                        class="form-control form-control-sm rounded-full shadow-sm"
                                        placeholder="Select date range"
                                        wire:model.live="filters.dateRange">
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

            <div class="card-body p-5">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #eff6ff;">
                            <tr>
                                <th class="ps-4 text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Invoice</th>
                                <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Customer</th>
                                <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Amount</th>
                                <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Method</th>
                                <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Status</th>
                                <th class="text-uppercase text-xs fw-semibold py-3" style="color: #1e3a8a;">Date</th>
                                <th class="text-uppercase text-xs fw-semibold py-3 text-center" style="color: #1e3a8a;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                            <tr>
                                <td class="fw-bold ps-4">{{ $payment->sale->invoice_number }}</td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $payment->sale->customer->name ?? 'Walk-in Customer' }}</div>
                                        <div class="text-xs text-gray-600">{{ $payment->sale->customer->phone ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">Rs.{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                    $status = $payment->status ? $payment->status : ($payment->is_completed ? 'paid' : 'scheduled');
                                    $statusClass = [
                                    'pending' => 'warning',
                                    'Paid' => 'success',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'scheduled' => 'info'
                                    ][$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge rounded-pill bg-{{$statusClass}} bg-opacity-10 text-{{$statusClass}} px-3 py-2">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : 
                                                ($payment->due_date ? 'Due: '.$payment->due_date->format('d M, Y') : 'N/A') }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $payment->payment_date ? $payment->payment_date->format('h:i A') : '' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-info text-white rounded-pill px-3" wire:click="viewPaymentDetails({{ $payment->id }})">
                                        <i class="bi bi-receipt-cutoff"></i> View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-gray-600">No payment records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($payments->hasPages())
                <div class="card-footer p-4 bg-white border-top rounded-b-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="text-sm text-gray-600">
                            Showing <span class="fw-semibold text-gray-800">{{ $payments->firstItem() }}</span>
                            to <span class="fw-semibold text-gray-800">{{ $payments->lastItem() }}</span> of
                            <span class="fw-semibold text-gray-800">{{ $payments->total() }}</span> results
                        </div>
                        <div>
                            {{ $payments->links('livewire::bootstrap') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="payment-receipt-modal" tabindex="-1" aria-labelledby="payment-receipt-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header text-white p-4" style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <h5 class="modal-title fw-bold tracking-tight" id="payment-receipt-modal-label">
                        <i class="bi bi-receipt-cutoff me-2"></i>Payment Receipt
                    </h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-light me-2 rounded-full" onclick="printReceiptContent()">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4" id="receiptContent">
                    @if ($selectedPayment)
                    <div class="receipt-container">
                        <div class="text-center mb-4">
                            <h3 class="mb-0">THILAK HARDWARE </h3>
                            <p class="mb-0 text-muted small"> for <br> <span class="text-denger "> MARUTI-LEYLAND - MAHINDRA-TATA-ALTO </span></p>
                            <p class="mb-0 text-muted small">Phone: 077 6718838 | Address: No. 397/3, Dunu Ela, Thihariya, Kalagedihena.</p>
                            <h4 class="mt-3 border-bottom border-2 pb-2">PAYMENT RECEIPT</h4>
                        </div>



                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">CUSTOMER INFORMATION</h6>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">{{ $selectedPayment->sale->customer->name ?? 'Guest Customer' }}</h6>
                                        @if($selectedPayment->sale->customer)
                                        <p class="mb-1 small"><i class="bi bi-telephone me-2"></i>{{ $selectedPayment->sale->customer->phone }}</p>
                                        @if($selectedPayment->sale->customer->address)
                                        <p class="mb-0 small"><i class="bi bi-geo-alt me-2"></i>{{ $selectedPayment->sale->customer->address }}</p>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">STAFF INFORMATION</h6>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <h6 class="mb-0">{{ $selectedPayment->sale->user->name ?? 'Unknown' }}</h6>
                                        @if($selectedPayment->sale->user)
                                        <p class="mb-1 small"><i class="bi bi-envelope me-2"></i>{{ $selectedPayment->sale->user->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-muted mb-2">PURCHASED ITEMS</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Code</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedPayment->sale->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->product_name }}</td>
                                        <td>{{ $item->product->product_code }}</td>
                                        <td>Rs.{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">PAYMENT INFORMATION</h6>
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Payment ID:</strong> #{{ $selectedPayment->id }}</p>
                                        <p class="mb-1"><strong>Amount:</strong> Rs.{{ number_format($selectedPayment->amount, 2) }}</p>
                                        <p class="mb-1"><strong>Method:</strong> {{ ucfirst($selectedPayment->payment_method) }}</p>
                                        <p class="mb-1"><strong>Date:</strong>
                                            {{ $selectedPayment->payment_date ? $selectedPayment->payment_date->format('d/m/Y h:i A') : 'N/A' }}
                                        </p>
                                        <p class="mb-1"><strong>Status:</strong>
                                            <span class="badge bg-{{ 
                                                $selectedPayment->status === 'pending' ? 'warning' : 
                                                ($selectedPayment->status === 'approved' ? 'success' : 
                                                ($selectedPayment->status === 'rejected' ? 'danger' : 
                                                ($selectedPayment->is_completed ? 'success' : 'secondary'))) }}">
                                                {{ $selectedPayment->status ? ucfirst($selectedPayment->status) : ($selectedPayment->is_completed ? 'Paid' : 'Scheduled') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">INVOICE INFORMATION</h6>
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Invoice:</strong> {{ $selectedPayment->sale->invoice_number }}</p>
                                        <p class="mb-1"><strong>Sale Date:</strong> {{ $selectedPayment->sale->created_at->format('d/m/Y h:i A') }}</p>
                                        <p class="mb-1"><strong>Total:</strong> Rs.{{ number_format($selectedPayment->sale->total_amount, 2) }}</p>
                                        <p class="mb-0"><strong>Payment Status:</strong>
                                            <span class="badge bg-{{ $selectedPayment->sale->payment_status == 'paid' ? 'success' : ($selectedPayment->sale->payment_status == 'partial' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($selectedPayment->sale->payment_status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="mb-0 text-muted small">This is a computer-generated receipt.</p>
                            <p class="mb-0 text-muted small">{{ now()->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                            <i class="bi bi-receipt text-gray-600 fs-3"></i>
                        </div>
                        <h5 class="text-gray-600 fw-normal">Loading Receipt Details</h5>
                        <p class="text-sm text-gray-500 mb-0">Please wait while data is being loaded...</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down modal-xl">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header text-white p-4" style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);">
                    <h5 class="modal-title fw-bold tracking-tight" id="imageModalLabel">Payment Attachment</h5>
                    <div>
                        <a id="downloadImageLink" href="#" class="btn btn-sm btn-light me-2 rounded-full" download>
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body text-center p-0">
                    <div class="position-relative">
                        <img id="fullSizeImage" src="" class="img-fluid w-100" alt="Payment proof">
                        <div class="position-absolute top-0 end-0 p-3">
                            <button id="zoomInBtn" class="btn btn-sm btn-light rounded-circle me-1"><i class="bi bi-zoom-in"></i></button>
                            <button id="zoomOutBtn" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-zoom-out"></i></button>
                        </div>
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
        window.printReceiptContent = function() {
            const printContent = document.getElementById('receiptContent').cloneNode(true);
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            iframeDoc.open();
            iframeDoc.write(`
                <!DOCTYPE html><html><head><title>Print Payment Receipt</title>
                <style>
                    <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body { 
                        font-family: 'Courier New', monospace !important; 
                        padding: 20px;
                        font-size: 12px;
                        line-height: 1.4;
                    }
                    .receipt-container { 
                        max-width: 800px; 
                        margin: auto;
                        border: 2px solid #000;
                        padding: 20px;
                    }
                    .row { 
                        display: flex; 
                        flex-wrap: wrap; 
                        margin: 0 -10px; 
                    }
                    .col-md-6 { 
                        flex: 0 0 50%; 
                        max-width: 50%; 
                        padding: 0 10px; 
                    }
                    .card { 
                        border: 1px solid #000; 
                        padding: 10px; 
                        margin-bottom: 10px; 
                    }
                    .card-body { padding: 10px; }
                    .table { 
                        width: 100%; 
                        border-collapse: collapse; 
                        margin-bottom: 1rem; 
                    }
                    .table th, .table td { 
                        border: 1px solid #000; 
                        padding: 8px; 
                        text-align: left;
                        font-family: 'Courier New', monospace !important;
                    }
                    .table thead th, .table-light { 
                        background-color: #f0f0f0;
                        font-weight: bold;
                    }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-muted { color: #000 !important; }
                    .border-top { border-top: 1px solid #000 !important; }
                    .pt-3 { padding-top: 1rem !important; }
                    .mt-4 { margin-top: 1.5rem !important; }
                    .mb-4 { margin-bottom: 1.5rem !important; }
                    .mb-0 { margin-bottom: 0 !important; }
                    .mb-1 { margin-bottom: 0.25rem !important; }
                    .mb-2 { margin-bottom: 0.5rem !important; }
                    .small { font-size: 11px; }
                    .fw-bold { font-weight: bold; }
                    .d-flex { display: flex; }
                    .justify-content-between { justify-content: space-between; }
                    .justify-content-end { justify-content: flex-end; }
                    h3, h4, h5, h6, p, strong, span, td, th, div { 
                        margin: 0; 
                        padding: 0;
                        font-family: 'Courier New', monospace !important;
                        color: #000 !important;
                    }
                    h3 { font-size: 18px; margin-bottom: 5px; }
                    h4 { font-size: 16px; margin-bottom: 5px; }
                    h6 { font-size: 13px; margin-bottom: 5px; }
                    hr {
                        border: none;
                        border-top: 2px solid #000;
                        margin: 10px 0;
                    }
                    .badge {
                        display: inline-block;
                        padding: 3px 8px;
                        border: 1px solid #000 !important;
                        background-color: transparent !important;
                        font-size: 10px;
                        font-weight: bold;
                        color: #000 !important;
                    }
                    i { display: none !important; }
                    @media print { 
                        .no-print { display: none; }
                        body { padding: 10px; }
                        * { color: #000 !important; }
                    }
                </style></head><body>${printContent.innerHTML}</body></html>
            `);
            iframeDoc.close();

            iframe.onload = function() {
                try {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                } catch (e) {
                    console.error('Print error:', e);
                }
                setTimeout(() => document.body.removeChild(iframe), 1000);
            };
        };

        function openFullImage(imageUrl) {
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('fullSizeImage').src = imageUrl;
            const downloadLink = document.getElementById('downloadImageLink');
            downloadLink.href = imageUrl;
            downloadLink.download = 'payment-reference.jpg';
            imageModal.show();
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openModal', (modalId) => {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        });
    </script>
    @endpush
</div>