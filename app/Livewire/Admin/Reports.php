<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use App\Models\SalesItem;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Cheque;
use App\Models\ProductDetail;
use App\Models\ProductCategory;
use App\Models\brand;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.admin')]
#[Title('Reports')]
class Reports extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Current report type
    public $reportType = 'daily_sales';

    // Filter properties
    public $dateFrom;
    public $dateTo;
    public $categoryId = '';
    public $brandId = '';
    public $customerId = '';
    public $paymentType = '';
    public $customerType = '';

    // Computed stats
    public $totalRevenue = 0;
    public $totalSalesCount = 0;
    public $totalProfit = 0;
    public $totalDue = 0;

    public function mount()
    {
        $this->dateFrom = Carbon::today()->format('Y-m-d');
        $this->dateTo = Carbon::today()->format('Y-m-d');
    }

    public function updatedReportType()
    {
        $this->resetPage();
        // Set appropriate default dates for different reports
        if ($this->reportType === 'monthly_revenue') {
            $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        } elseif ($this->reportType === 'daily_sales') {
            $this->dateFrom = Carbon::today()->format('Y-m-d');
            $this->dateTo = Carbon::today()->format('Y-m-d');
        }
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function updatingBrandId()
    {
        $this->resetPage();
    }

    public function updatingPaymentType()
    {
        $this->resetPage();
    }

    public function updatingCustomerType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->dateFrom = Carbon::today()->format('Y-m-d');
        $this->dateTo = Carbon::today()->format('Y-m-d');
        $this->categoryId = '';
        $this->brandId = '';
        $this->customerId = '';
        $this->paymentType = '';
        $this->customerType = '';
        $this->resetPage();
    }

    // ─── Report Data Methods ────────────────────────────────────────

    private function getDailySalesData($paginate = true)
    {
        $query = Sale::with(['customer', 'user', 'items.product'])
            ->whereBetween('created_at', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ]);

        if ($this->paymentType) {
            $query->where('payment_type', $this->paymentType);
        }
        if ($this->customerType) {
            $query->where('customer_type', $this->customerType);
        }

        $query->orderBy('created_at', 'desc');

        // Compute stats
        $statsQuery = clone $query;
        $this->totalRevenue = $statsQuery->sum('total_amount');
        $this->totalSalesCount = $statsQuery->count();
        $this->totalDue = $statsQuery->sum('due_amount');
        $this->totalProfit = $statsQuery->get()->sum(function ($sale) {
            return $sale->items->sum(function ($item) {
                $cost = $item->product ? $item->product->supplier_price : 0;
                return ($item->price - $cost) * $item->quantity;
            });
        });

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getMonthlyRevenueData($paginate = true)
    {
        $query = Sale::selectRaw('
                DATE(created_at) as sale_date,
                COUNT(*) as total_sales,
                SUM(total_amount) as total_revenue,
                SUM(discount_amount) as total_discount,
                SUM(due_amount) as total_due
            ')
            ->whereBetween('created_at', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ]);

        if ($this->paymentType) {
            $query->where('payment_type', $this->paymentType);
        }
        if ($this->customerType) {
            $query->where('customer_type', $this->customerType);
        }

        $query->groupBy('sale_date')->orderBy('sale_date', 'desc');

        $allData = $query->get();
        $this->totalRevenue = $allData->sum('total_revenue');
        $this->totalSalesCount = $allData->sum('total_sales');
        $this->totalDue = $allData->sum('total_due');
        $this->totalProfit = $this->totalRevenue - $allData->sum('total_discount');

        if ($paginate) {
            // Re-run with pagination
            return Sale::selectRaw('
                    DATE(created_at) as sale_date,
                    COUNT(*) as total_sales,
                    SUM(total_amount) as total_revenue,
                    SUM(discount_amount) as total_discount,
                    SUM(due_amount) as total_due
                ')
                ->whereBetween('created_at', [
                    Carbon::parse($this->dateFrom)->startOfDay(),
                    Carbon::parse($this->dateTo)->endOfDay()
                ])
                ->when($this->paymentType, fn($q) => $q->where('payment_type', $this->paymentType))
                ->when($this->customerType, fn($q) => $q->where('customer_type', $this->customerType))
                ->groupBy('sale_date')
                ->orderBy('sale_date', 'desc')
                ->paginate(15);
        }

        return $allData;
    }

    private function getProductSalesData($paginate = true)
    {
        $query = SalesItem::selectRaw('
                product_id,
                SUM(quantity) as total_qty,
                SUM(price * quantity) as total_revenue,
                SUM(discount * quantity) as total_discount
            ')
            ->whereHas('sale', function ($q) {
                $q->whereBetween('created_at', [
                    Carbon::parse($this->dateFrom)->startOfDay(),
                    Carbon::parse($this->dateTo)->endOfDay()
                ]);
            })
            ->with(['product.category', 'product.brand']);

        if ($this->categoryId) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $this->categoryId));
        }
        if ($this->brandId) {
            $query->whereHas('product', fn($q) => $q->where('brand_id', $this->brandId));
        }

        $query->groupBy('product_id')->orderByDesc('total_qty');

        if (!$paginate) {
            $data = $query->get();
            $this->totalRevenue = $data->sum('total_revenue');
            $this->totalSalesCount = $data->sum('total_qty');
            return $data;
        }

        // Get stats from all data
        $allData = (clone $query)->get();
        $this->totalRevenue = $allData->sum('total_revenue');
        $this->totalSalesCount = $allData->sum('total_qty');
        $this->totalProfit = $allData->sum(function ($item) {
            $cost = $item->product ? $item->product->supplier_price * $item->total_qty : 0;
            return $item->total_revenue - $cost;
        });

        return $query->paginate(15);
    }

    private function getOutstandingDuesData($paginate = true)
    {
        $query = Sale::with(['customer'])
            ->where('due_amount', '>', 0)
            ->whereBetween('created_at', [
                Carbon::parse($this->dateFrom)->startOfDay(),
                Carbon::parse($this->dateTo)->endOfDay()
            ]);

        if ($this->customerType) {
            $query->where('customer_type', $this->customerType);
        }

        $query->orderBy('due_amount', 'desc');

        $allData = (clone $query)->get();
        $this->totalDue = $allData->sum('due_amount');
        $this->totalSalesCount = $allData->count();
        $this->totalRevenue = $allData->sum('total_amount');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getCustomerLedgerData($paginate = true)
    {
        $query = Customer::withCount('sales')
            ->withSum('sales', 'total_amount')
            ->withSum('sales', 'due_amount')
            ->having('sales_count', '>', 0);

        if ($this->customerType) {
            $query->where('type', $this->customerType);
        }

        if ($this->customerId) {
            $query->where('id', $this->customerId);
        }

        $query->orderByDesc('sales_sum_total_amount');

        $allData = (clone $query)->get();
        $this->totalRevenue = $allData->sum('sales_sum_total_amount');
        $this->totalDue = $allData->sum('sales_sum_due_amount');
        $this->totalSalesCount = $allData->sum('sales_count');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getStockAlertData($paginate = true)
    {
        $query = ProductDetail::with(['category', 'brand'])
            ->where('stock_quantity', '<=', 10)
            ->where('status', 'active');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }
        if ($this->brandId) {
            $query->where('brand_id', $this->brandId);
        }

        $query->orderBy('stock_quantity', 'asc');

        $allData = (clone $query)->get();
        $this->totalSalesCount = $allData->count();
        $outOfStock = $allData->where('stock_quantity', '<=', 0)->count();
        $this->totalRevenue = $outOfStock; // Repurposed for "Out of Stock count"

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getPaymentSummaryData($paginate = true)
    {
        $query = Payment::with(['sale.customer'])
            ->whereHas('sale', function ($q) {
                $q->whereBetween('created_at', [
                    Carbon::parse($this->dateFrom)->startOfDay(),
                    Carbon::parse($this->dateTo)->endOfDay()
                ]);
            });

        if ($this->paymentType) {
            // Filter by payment_method on payments table
            $query->where('payment_method', $this->paymentType);
        }

        $query->orderBy('created_at', 'desc');

        $allData = (clone $query)->get();
        $this->totalRevenue = $allData->sum('amount');
        $this->totalSalesCount = $allData->count();
        $this->totalDue = $allData->where('is_completed', false)->sum('amount');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    private function getChequeStatusData($paginate = true)
    {
        $query = Cheque::with(['customer', 'payment.sale'])
            ->where('customer_id', '!=', null);

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('cheque_date', [$this->dateFrom, $this->dateTo]);
        }

        $query->orderBy('cheque_date', 'desc');

        $allData = (clone $query)->get();
        $this->totalSalesCount = $allData->count();
        $this->totalRevenue = $allData->where('status', 'complete')->sum('cheque_amount');
        $this->totalDue = $allData->where('status', 'pending')->sum('cheque_amount');
        $this->totalProfit = $allData->where('status', 'return')->sum('cheque_amount');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    // ─── Export Methods ─────────────────────────────────────────────

    public function exportCSV()
    {
        $reportType = $this->reportType;
        $data = $this->getReportData(false);
        $filename = $reportType . '_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($data, $reportType) {
            $handle = fopen('php://output', 'w');

            // Write headers based on report type
            switch ($reportType) {
                case 'daily_sales':
                    fputcsv($handle, ['Invoice #', 'Date', 'Customer', 'Type', 'Subtotal', 'Discount', 'Total', 'Due', 'Payment Type', 'Status']);
                    foreach ($data as $sale) {
                        fputcsv($handle, [
                            $sale->invoice_number,
                            $sale->created_at->format('Y-m-d H:i'),
                            $sale->customer ? $sale->customer->name : 'Walk-in',
                            $sale->customer_type,
                            number_format($sale->subtotal, 2),
                            number_format($sale->discount_amount, 2),
                            number_format($sale->total_amount, 2),
                            number_format($sale->due_amount, 2),
                            $sale->payment_type,
                            $sale->payment_status,
                        ]);
                    }
                    break;

                case 'monthly_revenue':
                    fputcsv($handle, ['Date', 'Total Sales', 'Revenue', 'Discount', 'Due Amount']);
                    foreach ($data as $row) {
                        fputcsv($handle, [
                            $row->sale_date,
                            $row->total_sales,
                            number_format($row->total_revenue, 2),
                            number_format($row->total_discount, 2),
                            number_format($row->total_due, 2),
                        ]);
                    }
                    break;

                case 'product_sales':
                    fputcsv($handle, ['Product Code', 'Product Name', 'Category', 'Brand', 'Qty Sold', 'Revenue', 'Discount', 'Cost Price', 'Profit']);
                    foreach ($data as $item) {
                        $product = $item->product;
                        fputcsv($handle, [
                            $product ? $product->product_code : '-',
                            $product ? $product->product_name : '-',
                            $product && $product->category ? $product->category->name : '-',
                            $product && $product->brand ? $product->brand->brand_name : '-',
                            $item->total_qty,
                            number_format($item->total_revenue, 2),
                            number_format($item->total_discount, 2),
                            $product ? number_format($product->supplier_price * $item->total_qty, 2) : '-',
                            $product ? number_format($item->total_revenue - ($product->supplier_price * $item->total_qty), 2) : '-',
                        ]);
                    }
                    break;

                case 'outstanding_dues':
                    fputcsv($handle, ['Invoice #', 'Date', 'Customer', 'Phone', 'Total Amount', 'Due Amount', 'Payment Type']);
                    foreach ($data as $sale) {
                        fputcsv($handle, [
                            $sale->invoice_number,
                            $sale->created_at->format('Y-m-d'),
                            $sale->customer ? $sale->customer->name : 'Walk-in',
                            $sale->customer ? $sale->customer->phone : '-',
                            number_format($sale->total_amount, 2),
                            number_format($sale->due_amount, 2),
                            $sale->payment_type,
                        ]);
                    }
                    break;

                case 'customer_ledger':
                    fputcsv($handle, ['Customer Name', 'Phone', 'Type', 'Total Sales', 'Total Amount', 'Due Amount']);
                    foreach ($data as $customer) {
                        fputcsv($handle, [
                            $customer->name,
                            $customer->phone ?? '-',
                            $customer->type,
                            $customer->sales_count,
                            number_format($customer->sales_sum_total_amount, 2),
                            number_format($customer->sales_sum_due_amount, 2),
                        ]);
                    }
                    break;

                case 'stock_alert':
                    fputcsv($handle, ['Product Code', 'Product Name', 'Category', 'Brand', 'Stock Qty', 'Damage Qty', 'Selling Price', 'Status']);
                    foreach ($data as $product) {
                        fputcsv($handle, [
                            $product->product_code,
                            $product->product_name,
                            $product->category ? $product->category->name : '-',
                            $product->brand ? $product->brand->brand_name : '-',
                            $product->stock_quantity,
                            $product->damage_quantity,
                            number_format($product->selling_price, 2),
                            $product->stock_quantity <= 0 ? 'Out of Stock' : 'Low Stock',
                        ]);
                    }
                    break;

                case 'payment_summary':
                    fputcsv($handle, ['Date', 'Invoice #', 'Customer', 'Method', 'Amount', 'Status', 'Reference']);
                    foreach ($data as $payment) {
                        fputcsv($handle, [
                            $payment->created_at->format('Y-m-d H:i'),
                            $payment->sale ? $payment->sale->invoice_number : '-',
                            $payment->sale && $payment->sale->customer ? $payment->sale->customer->name : 'Walk-in',
                            $payment->payment_method,
                            number_format($payment->amount, 2),
                            $payment->is_completed ? 'Completed' : 'Pending',
                            $payment->payment_reference ?? '-',
                        ]);
                    }
                    break;

                case 'cheque_status':
                    fputcsv($handle, ['Cheque #', 'Date', 'Bank', 'Amount', 'Customer', 'Invoice #', 'Status']);
                    foreach ($data as $cheque) {
                        fputcsv($handle, [
                            $cheque->cheque_number,
                            $cheque->cheque_date->format('Y-m-d'),
                            $cheque->bank_name,
                            number_format($cheque->cheque_amount, 2),
                            $cheque->customer ? $cheque->customer->name : '-',
                            $cheque->payment && $cheque->payment->sale ? $cheque->payment->sale->invoice_number : '-',
                            ucfirst($cheque->status ?? 'pending'),
                        ]);
                    }
                    break;
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportPDF()
    {
        $data = $this->getReportData(false);
        $reportType = $this->reportType;
        $reportTitle = $this->getReportTitle();
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;

        $stats = [
            'totalRevenue' => $this->totalRevenue,
            'totalSalesCount' => $this->totalSalesCount,
            'totalDue' => $this->totalDue,
            'totalProfit' => $this->totalProfit,
        ];

        $pdf = \PDF::loadView('reports.pdf', compact(
            'data', 'reportType', 'reportTitle', 'dateFrom', 'dateTo', 'stats'
        ));

        $pdf->setPaper('a4', 'landscape');
        $filename = $reportType . '_' . now()->format('Y-m-d_His') . '.pdf';

        return response()->streamDownload(fn() => print($pdf->output()), $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    // ─── Helper Methods ─────────────────────────────────────────────

    private function getReportData($paginate = true)
    {
        return match ($this->reportType) {
            'daily_sales' => $this->getDailySalesData($paginate),
            'monthly_revenue' => $this->getMonthlyRevenueData($paginate),
            'product_sales' => $this->getProductSalesData($paginate),
            'outstanding_dues' => $this->getOutstandingDuesData($paginate),
            'customer_ledger' => $this->getCustomerLedgerData($paginate),
            'stock_alert' => $this->getStockAlertData($paginate),
            'payment_summary' => $this->getPaymentSummaryData($paginate),
            'cheque_status' => $this->getChequeStatusData($paginate),
            default => collect(),
        };
    }

    private function getReportTitle()
    {
        return match ($this->reportType) {
            'daily_sales' => 'Daily Sales Report',
            'monthly_revenue' => 'Revenue Report',
            'product_sales' => 'Product Sales Report',
            'outstanding_dues' => 'Outstanding Dues Report',
            'customer_ledger' => 'Customer Ledger',
            'stock_alert' => 'Stock Alert Report',
            'payment_summary' => 'Payment Summary Report',
            'cheque_status' => 'Cheque Status Report',
            default => 'Report',
        };
    }

    public function render()
    {
        $reportData = $this->getReportData(true);
        $categories = ProductCategory::orderBy('name')->get();
        $brands = brand::orderBy('brand_name')->get();
        $customers = Customer::orderBy('name')->get();

        return view('livewire.admin.reports', [
            'reportData' => $reportData,
            'categories' => $categories,
            'brands' => $brands,
            'customers' => $customers,
            'reportTitle' => $this->getReportTitle(),
        ]);
    }
}
