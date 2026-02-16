<div>
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-graph-up me-2"></i>{{ $reportTitle }}</h4>
                <p class="text-muted mb-0">Generate and export business reports</p>
            </div>
            <div class="d-flex gap-2">
                <button wire:click="exportCSV" class="btn btn-success btn-sm">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
                </button>
                <button wire:click="exportPDF" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </button>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

        <!-- Report Type Selector -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'daily_sales')"
                            class="btn btn-sm {{ $reportType === 'daily_sales' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-calendar-day me-1"></i> Daily Sales
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'monthly_revenue')"
                            class="btn btn-sm {{ $reportType === 'monthly_revenue' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-calendar-month me-1"></i> Revenue
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'product_sales')"
                            class="btn btn-sm {{ $reportType === 'product_sales' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-box-seam me-1"></i> Product Sales
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'outstanding_dues')"
                            class="btn btn-sm {{ $reportType === 'outstanding_dues' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-exclamation-triangle me-1"></i> Outstanding Dues
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'customer_ledger')"
                            class="btn btn-sm {{ $reportType === 'customer_ledger' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-person-lines-fill me-1"></i> Customer Ledger
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'stock_alert')"
                            class="btn btn-sm {{ $reportType === 'stock_alert' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-exclamation-circle me-1"></i> Stock Alert
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'payment_summary')"
                            class="btn btn-sm {{ $reportType === 'payment_summary' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-cash-stack me-1"></i> Payments
                        </button>
                    </div>
                    <div class="col-auto">
                        <button wire:click="$set('reportType', 'cheque_status')"
                            class="btn btn-sm {{ $reportType === 'cheque_status' ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-receipt me-1"></i> Cheques
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-end">
                    @if (!in_array($reportType, ['stock_alert', 'customer_ledger']))
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date From</label>
                        <input type="date" wire:model.live="dateFrom" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Date To</label>
                        <input type="date" wire:model.live="dateTo" class="form-control form-control-sm">
                    </div>
                    @endif

                    @if (in_array($reportType, ['daily_sales', 'monthly_revenue', 'outstanding_dues', 'payment_summary']))
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Payment Type</label>
                        <select wire:model.live="paymentType" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="full">Full</option>
                            <option value="partial">Partial</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    @endif

                    @if (in_array($reportType, ['daily_sales', 'monthly_revenue', 'outstanding_dues', 'customer_ledger']))
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Customer Type</label>
                        <select wire:model.live="customerType" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="retail">Retail</option>
                            <option value="wholesale">Wholesale</option>
                        </select>
                    </div>
                    @endif

                    @if (in_array($reportType, ['product_sales', 'stock_alert']))
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Category</label>
                        <select wire:model.live="categoryId" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Brand</label>
                        <select wire:model.live="brandId" class="form-select form-select-sm">
                            <option value="">All Brands</option>
                            @foreach ($brands as $br)
                            <option value="{{ $br->id }}">{{ $br->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if ($reportType === 'customer_ledger')
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Customer</label>
                        <select wire:model.live="customerId" class="form-select form-select-sm">
                            <option value="">All Customers</option>
                            @foreach ($customers as $cust)
                            <option value="{{ $cust->id }}">{{ $cust->name }} {{ $cust->phone ? '(' . $cust->phone . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-auto">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @if (in_array($reportType, ['daily_sales', 'monthly_revenue', 'product_sales', 'outstanding_dues', 'payment_summary']))
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Total Revenue</div>
                        <div class="fs-4 fw-bold text-primary">Rs. {{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">
                            @if ($reportType === 'stock_alert') Total Low Stock Items
                            @elseif ($reportType === 'product_sales') Total Qty Sold
                            @else Total Records @endif
                        </div>
                        <div class="fs-4 fw-bold text-info">{{ number_format($totalSalesCount) }}</div>
                    </div>
                </div>
            </div>

            @if (in_array($reportType, ['daily_sales', 'monthly_revenue', 'outstanding_dues', 'payment_summary', 'cheque_status']))
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">
                            @if ($reportType === 'cheque_status') Pending Amount @else Total Due @endif
                        </div>
                        <div class="fs-4 fw-bold text-danger">Rs. {{ number_format($totalDue, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if (in_array($reportType, ['daily_sales', 'product_sales']))
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Estimated Profit</div>
                        <div class="fs-4 fw-bold text-success">Rs. {{ number_format($totalProfit, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if ($reportType === 'stock_alert')
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Out of Stock</div>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($totalRevenue) }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if ($reportType === 'cheque_status')
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Completed Amount</div>
                        <div class="fs-4 fw-bold text-success">Rs. {{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Returned Amount</div>
                        <div class="fs-4 fw-bold text-warning">Rs. {{ number_format($totalProfit, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if ($reportType === 'customer_ledger')
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Total Revenue</div>
                        <div class="fs-4 fw-bold text-primary">Rs. {{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small mb-1">Total Due</div>
                        <div class="fs-4 fw-bold text-danger">Rs. {{ number_format($totalDue, 2) }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Report Table -->
        <div class="card border-0 shadow-sm" id="printable-report">
            <div class="card-body p-0">
                <div class="table-responsive">
                    {{-- ═══ DAILY SALES REPORT ═══ --}}
                    @if ($reportType === 'daily_sales')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Due</th>
                                <th>Payment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $sale)
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $sale->invoice_number }}</span></td>
                                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                                <td><span class="badge bg-{{ $sale->customer_type === 'wholesale' ? 'info' : 'secondary' }}">{{ ucfirst($sale->customer_type) }}</span></td>
                                <td class="text-end">{{ number_format($sale->subtotal, 2) }}</td>
                                <td class="text-end text-danger">{{ number_format($sale->discount_amount, 2) }}</td>
                                <td class="text-end fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="text-end {{ $sale->due_amount > 0 ? 'text-danger fw-bold' : '' }}">{{ number_format($sale->due_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $sale->payment_type === 'credit' ? 'warning' : ($sale->payment_type === 'partial' ? 'info' : 'success') }}">{{ ucfirst($sale->payment_type) }}</span></td>
                                <td><span class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : ($sale->payment_status === 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($sale->payment_status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">No sales found for the selected period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ MONTHLY REVENUE REPORT ═══ --}}
                    @elseif ($reportType === 'monthly_revenue')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th class="text-end">Sales Count</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Due Amount</th>
                                <th class="text-end">Net Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $row)
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($row->sale_date)->format('D, d M Y') }}</td>
                                <td class="text-end">{{ $row->total_sales }}</td>
                                <td class="text-end fw-bold">{{ number_format($row->total_revenue, 2) }}</td>
                                <td class="text-end text-danger">{{ number_format($row->total_discount, 2) }}</td>
                                <td class="text-end text-warning">{{ number_format($row->total_due, 2) }}</td>
                                <td class="text-end text-success fw-bold">{{ number_format($row->total_revenue - $row->total_discount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No data found for the selected period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ PRODUCT SALES REPORT ═══ --}}
                    @elseif ($reportType === 'product_sales')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th class="text-end">Qty Sold</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Cost</th>
                                <th class="text-end">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $item)
                            @php
                            $product = $item->product;
                            $cost = $product ? $product->supplier_price * $item->total_qty : 0;
                            $profit = $item->total_revenue - $cost;
                            @endphp
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $product ? $product->product_code : '-' }}</span></td>
                                <td>{{ $product ? $product->product_name : '-' }}</td>
                                <td>{{ $product && $product->category ? $product->category->name : '-' }}</td>
                                <td>{{ $product && $product->brand ? $product->brand->brand_name : '-' }}</td>
                                <td class="text-end">{{ $item->total_qty }}</td>
                                <td class="text-end fw-bold">{{ number_format($item->total_revenue, 2) }}</td>
                                <td class="text-end text-muted">{{ number_format($cost, 2) }}</td>
                                <td class="text-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">{{ number_format($profit, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No product sales found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ OUTSTANDING DUES REPORT ═══ --}}
                    @elseif ($reportType === 'outstanding_dues')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">Due Amount</th>
                                <th>Payment Type</th>
                                <th>Days Overdue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $sale)
                            @php $daysOverdue = $sale->created_at->diffInDays(now()); @endphp
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $sale->invoice_number }}</span></td>
                                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                                <td>{{ $sale->customer ? $sale->customer->phone : '-' }}</td>
                                <td class="text-end">{{ number_format($sale->total_amount, 2) }}</td>
                                <td class="text-end text-danger fw-bold">{{ number_format($sale->due_amount, 2) }}</td>
                                <td><span class="badge bg-warning">{{ ucfirst($sale->payment_type) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $daysOverdue > 90 ? 'danger' : ($daysOverdue > 30 ? 'warning' : 'info') }}">
                                        {{ $daysOverdue }} days
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No outstanding dues found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ CUSTOMER LEDGER ═══ --}}
                    @elseif ($reportType === 'customer_ledger')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Business</th>
                                <th class="text-end">Total Sales</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">Due Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $customer)
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $customer->name }}</td>
                                <td>{{ $customer->phone ?? '-' }}</td>
                                <td><span class="badge bg-{{ $customer->type === 'wholesale' ? 'info' : 'secondary' }}">{{ ucfirst($customer->type) }}</span></td>
                                <td>{{ $customer->business_name ?? '-' }}</td>
                                <td class="text-end">{{ $customer->sales_count }}</td>
                                <td class="text-end fw-bold">{{ number_format($customer->sales_sum_total_amount ?? 0, 2) }}</td>
                                <td class="text-end {{ ($customer->sales_sum_due_amount ?? 0) > 0 ? 'text-danger fw-bold' : '' }}">{{ number_format($customer->sales_sum_due_amount ?? 0, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No customer data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ STOCK ALERT REPORT ═══ --}}
                    @elseif ($reportType === 'stock_alert')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th class="text-end">Stock Qty</th>
                                <th class="text-end">Damage Qty</th>
                                <th class="text-end">Selling Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $product)
                            <tr class="{{ $product->stock_quantity <= 0 ? 'table-danger' : 'table-warning' }}">
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td><span class="fw-semibold">{{ $product->product_code }}</span></td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                <td>{{ $product->brand ? $product->brand->brand_name : '-' }}</td>
                                <td class="text-end fw-bold {{ $product->stock_quantity <= 0 ? 'text-danger' : 'text-warning' }}">{{ $product->stock_quantity }}</td>
                                <td class="text-end">{{ $product->damage_quantity ?? 0 }}</td>
                                <td class="text-end">{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if ($product->stock_quantity <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                        @else
                                        <span class="badge bg-warning">Low Stock</span>
                                        @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No low stock items found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ PAYMENT SUMMARY REPORT ═══ --}}
                    @elseif ($reportType === 'payment_summary')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Method</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $payment)
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td class="fw-semibold">{{ $payment->sale ? $payment->sale->invoice_number : '-' }}</td>
                                <td>{{ $payment->sale && $payment->sale->customer ? $payment->sale->customer->name : 'Walk-in' }}</td>
                                <td><span class="badge bg-primary">{{ ucfirst($payment->payment_method ?? 'N/A') }}</span></td>
                                <td class="text-end fw-bold">{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    @if ($payment->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $payment->payment_reference ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No payments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ═══ CHEQUE STATUS REPORT ═══ --}}
                    @elseif ($reportType === 'cheque_status')
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Cheque #</th>
                                <th>Date</th>
                                <th>Bank</th>
                                <th class="text-end">Amount</th>
                                <th>Customer</th>
                                <th>Invoice #</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reportData as $index => $cheque)
                            <tr>
                                <td>{{ $reportData->firstItem() + $index }}</td>
                                <td class="fw-semibold">{{ $cheque->cheque_number }}</td>
                                <td>{{ $cheque->cheque_date->format('Y-m-d') }}</td>
                                <td>{{ $cheque->bank_name }}</td>
                                <td class="text-end fw-bold">{{ number_format($cheque->cheque_amount, 2) }}</td>
                                <td>{{ $cheque->customer ? $cheque->customer->name : '-' }}</td>
                                <td>{{ $cheque->payment && $cheque->payment->sale ? $cheque->payment->sale->invoice_number : '-' }}</td>
                                <td>
                                    @if ($cheque->status === 'complete')
                                    <span class="badge bg-success">Complete</span>
                                    @elseif ($cheque->status === 'return')
                                    <span class="badge bg-danger">Returned</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No cheques found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>

                <!-- Pagination -->
                @if (method_exists($reportData, 'links'))
                <div class="d-flex justify-content-center p-3">
                    {{ $reportData->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {

            .sidebar,
            .top-bar,
            .btn,
            .form-control,
            .form-select,
            select,
            .card:not(#printable-report) {
                display: none !important;
            }

            #printable-report {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .container-fluid {
                padding: 0 !important;
            }
        }
    </style>
</div>