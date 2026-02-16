<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            width: 100%;
            height: 100%;

        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
            margin: 0;
            line-height: 1.5;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 3px solid #233d7f;
            padding: 12px 15px;
            background: #f8f9fa;
            margin-left: -10mm;
            margin-right: -10mm;
            margin-top: -10mm;
            width: calc(100% + 20mm);
        }

        .header h1 {
            font-size: 18px;
            color: #233d7f;
            margin-bottom: 3px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header h2 {
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .header p {
            font-size: 8px;
            color: #999;
            margin-bottom: 1px;
        }

        .meta {
            display: block;
            margin-bottom: 15px;
            font-size: 9px;
            padding: 10px 12px;
            background: #f0f4f8;
            border-left: 4px solid #233d7f;
            width: 100%;
            overflow: hidden;
        }

        .meta-left {
            display: inline-block;
            width: 60%;
            word-wrap: break-word;
        }

        .meta-right {
            display: inline-block;
            width: 40%;
            text-align: right;
            word-wrap: break-word;
        }

        .meta strong {
            color: #233d7f;
        }

        .stats {
            margin-bottom: 15px;
        }

        .stats table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .stats td {
            padding: 10px 8px;
            text-align: center;
            background: white;
            border: 2px solid #e5e7eb;
        }

        .stats td:first-child {
            border-left: 3px solid #233d7f;
        }

        .stats td:last-child {
            border-right: 3px solid #233d7f;
        }

        .stats td:nth-child(2n) {
            background: #f8f9fa;
        }

        .stats .stat-label {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 2px;
            display: block;
        }

        .stats .stat-value {
            font-size: 13px;
            font-weight: bold;
            color: #233d7f;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            font-size: 8px;
        }

        table.report th {
            background: #233d7f;
            color: white;
            padding: 7px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid #233d7f;
            word-wrap: break-word;
        }

        table.report td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }

        table.report tr:nth-child(even) {
            background: #f9fafb;
        }

        table.report tr:last-child td {
            border-bottom: 2px solid #e5e7eb;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-danger {
            color: #ef4444;
        }

        .text-success {
            color: #10b981;
        }

        .text-warning {
            color: #f59e0b;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 2px solid #233d7f;
            padding: 12px 0;
            background: #f8f9fa;
            margin-left: -10mm;
            margin-right: -10mm;
            margin-bottom: -10mm;
            width: calc(100% + 20mm);
            padding-left: 10mm;
            padding-right: 10mm;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            color: white;
            font-weight: 600;
            display: inline-block;
        }

        .bg-success {
            background: #10b981;
        }

        .bg-danger {
            background: #ef4444;
        }

        .bg-warning {
            background: #f59e0b;
            color: #333;
        }

        .bg-info {
            background: #0dcaf0;
        }

        .bg-secondary {
            background: #6c757d;
        }

        .bg-primary {
            background: #233d7f;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>THILAK HARDWARE</h1>
        <h2>{{ $reportTitle }}</h2>
        <p>NO 569/17A, THIHARIYA, KALAGEDIHENA | Generated: {{ now()->format('d M Y h:i A') }}</p>
    </div>

    <div class="meta">
        <div class="meta-left">
            <strong>Period:</strong>
            @if($dateFrom && $dateFrom !== 'All')
            {{ \Carbon\Carbon::parse($dateFrom)->format('d M y') }}
            @else
            All
            @endif
            -
            @if($dateTo && $dateTo !== 'All')
            {{ \Carbon\Carbon::parse($dateTo)->format('d M y') }}
            @else
            All
            @endif
        </div>
        <div class="meta-right">
            <strong>Records:</strong> {{ $data->count() }}
        </div>
    </div>

    <div class="stats">
        <table>
            <tr>
                <td>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">Rs. {{ number_format($stats['totalRevenue'], 2) }}</div>
                </td>
                <td>
                    <div class="stat-label">Total Records</div>
                    <div class="stat-value">{{ number_format($stats['totalSalesCount']) }}</div>
                </td>
                <td>
                    <div class="stat-label">Total Due</div>
                    <div class="stat-value text-danger">Rs. {{ number_format($stats['totalDue'], 2) }}</div>
                </td>
                <td>
                    <div class="stat-label">Profit</div>
                    <div class="stat-value text-success">Rs. {{ number_format($stats['totalProfit'], 2) }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if ($reportType === 'daily_sales')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Type</th>
                <th class="text-end">Total</th>
                <th class="text-end">Due</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $sale)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-bold">{{ $sale->invoice_number }}</td>
                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                <td>{{ ucfirst($sale->customer_type) }}</td>
                <td class="text-end fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                <td class="text-end {{ $sale->due_amount > 0 ? 'text-danger' : '' }}">{{ number_format($sale->due_amount, 2) }}</td>
                <td>{{ ucfirst($sale->payment_type) }}</td>
                <td>{{ ucfirst($sale->payment_status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'monthly_revenue')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th class="text-end">Sales</th>
                <th class="text-end">Revenue</th>
                <th class="text-end">Discount</th>
                <th class="text-end">Due</th>
                <th class="text-end">Net Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->sale_date)->format('D, d M Y') }}</td>
                <td class="text-end">{{ $row->total_sales }}</td>
                <td class="text-end fw-bold">{{ number_format($row->total_revenue, 2) }}</td>
                <td class="text-end text-danger">{{ number_format($row->total_discount, 2) }}</td>
                <td class="text-end text-warning">{{ number_format($row->total_due, 2) }}</td>
                <td class="text-end text-success fw-bold">{{ number_format($row->total_revenue - $row->total_discount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'product_sales')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Brand</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Revenue</th>
                <th class="text-end">Cost</th>
                <th class="text-end">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
            @php
            $product = $item->product;
            $cost = $product ? $product->supplier_price * $item->total_qty : 0;
            $profit = $item->total_revenue - $cost;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $product ? $product->product_code : '-' }}</td>
                <td>{{ $product ? $product->product_name : '-' }}</td>
                <td>{{ $product && $product->category ? $product->category->name : '-' }}</td>
                <td>{{ $product && $product->brand ? $product->brand->brand_name : '-' }}</td>
                <td class="text-end">{{ $item->total_qty }}</td>
                <td class="text-end fw-bold">{{ number_format($item->total_revenue, 2) }}</td>
                <td class="text-end">{{ number_format($cost, 2) }}</td>
                <td class="text-end {{ $profit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">{{ number_format($profit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'outstanding_dues')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th class="text-end">Total</th>
                <th class="text-end">Due</th>
                <th>Payment</th>
                <th>Days Overdue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $sale)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-bold">{{ $sale->invoice_number }}</td>
                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                <td>{{ $sale->customer ? $sale->customer->phone : '-' }}</td>
                <td class="text-end">{{ number_format($sale->total_amount, 2) }}</td>
                <td class="text-end text-danger fw-bold">{{ number_format($sale->due_amount, 2) }}</td>
                <td>{{ ucfirst($sale->payment_type) }}</td>
                <td>{{ $sale->created_at->diffInDays(now()) }} days</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'customer_ledger')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Business</th>
                <th class="text-end">Sales</th>
                <th class="text-end">Total</th>
                <th class="text-end">Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $customer)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-bold">{{ $customer->name }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ ucfirst($customer->type) }}</td>
                <td>{{ $customer->business_name ?? '-' }}</td>
                <td class="text-end">{{ $customer->sales_count }}</td>
                <td class="text-end fw-bold">{{ number_format($customer->sales_sum_total_amount ?? 0, 2) }}</td>
                <td class="text-end text-danger">{{ number_format($customer->sales_sum_due_amount ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'stock_alert')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Product</th>
                <th>Category</th>
                <th>Brand</th>
                <th class="text-end">Stock</th>
                <th class="text-end">Damage</th>
                <th class="text-end">Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $product)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $product->product_code }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->category ? $product->category->name : '-' }}</td>
                <td>{{ $product->brand ? $product->brand->brand_name : '-' }}</td>
                <td class="text-end {{ $product->stock_quantity <= 0 ? 'text-danger fw-bold' : 'text-warning' }}">{{ $product->stock_quantity }}</td>
                <td class="text-end">{{ $product->damage_quantity ?? 0 }}</td>
                <td class="text-end">{{ number_format($product->selling_price, 2) }}</td>
                <td>{{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Low Stock' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'payment_summary')
    <table class="report">
        <thead>
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
            @foreach ($data as $i => $payment)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $payment->sale ? $payment->sale->invoice_number : '-' }}</td>
                <td>{{ $payment->sale && $payment->sale->customer ? $payment->sale->customer->name : 'Walk-in' }}</td>
                <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                <td class="text-end fw-bold">{{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->is_completed ? 'Completed' : 'Pending' }}</td>
                <td>{{ $payment->payment_reference ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif ($reportType === 'cheque_status')
    <table class="report">
        <thead>
            <tr>
                <th>#</th>
                <th>Cheque #</th>
                <th>Date</th>
                <th>Bank</th>
                <th class="text-end">Amount</th>
                <th>Customer</th>
                <th>Invoice</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $cheque)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $cheque->cheque_number }}</td>
                <td>{{ $cheque->cheque_date->format('Y-m-d') }}</td>
                <td>{{ $cheque->bank_name }}</td>
                <td class="text-end fw-bold">{{ number_format($cheque->cheque_amount, 2) }}</td>
                <td>{{ $cheque->customer ? $cheque->customer->name : '-' }}</td>
                <td>{{ $cheque->payment && $cheque->payment->sale ? $cheque->payment->sale->invoice_number : '-' }}</td>
                <td>{{ ucfirst($cheque->status ?? 'pending') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>This is a computer-generated report from Thilak Hardware Management System. | Page 1</p>
    </div>
</body>

</html>