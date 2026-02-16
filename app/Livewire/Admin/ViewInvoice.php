<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SalesItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title("View Invoices")]
#[Layout('components.layouts.admin')]
class ViewInvoice extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $paymentType = '';
    public $customerType = '';

    public $saleDetails = null;
    public $selectedSaleId = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
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
        $this->reset(['search', 'dateFrom', 'dateTo', 'paymentType', 'customerType']);
        $this->resetPage();
    }

    public function viewInvoice($saleId)
    {
        $this->selectedSaleId = $saleId;

        $sale = Sale::with(['customer', 'user', 'items.product'])->find($saleId);

        if (!$sale) {
            session()->flash('error', 'Sale not found');
            return;
        }

        $this->saleDetails = [
            'sale' => $sale,
            'items' => $sale->items,
            'returnItems' => collect([]), // Empty collection
            'totalReturnAmount' => 0,
            'adjustedGrandTotal' => $sale->total_amount
        ];

        $this->dispatch('openInvoiceModal');
    }

    public function editInvoice($saleId)
    {
        $sale = Sale::with(['customer', 'items.product'])->find($saleId);

        if (!$sale) {
            session()->flash('error', 'Sale not found');
            return;
        }

        // Redirect to StoreBilling with the sale ID
        return redirect()->route('admin.store-billing')->with('editSaleId', $saleId);
    }

    private function getFilteredQuery()
    {
        $query = Sale::with(['customer', 'user', 'items'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($customerQuery) {
                        $customerQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        if ($this->paymentType) {
            $query->where('payment_type', $this->paymentType);
        }
        if ($this->customerType) {
            $query->whereHas('customer', function ($q) {
                $q->where('type', $this->customerType);
            });
        }

        return $query;
    }

    public function exportCSV()
    {
        $sales = $this->getFilteredQuery()->get();
        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($sales) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Invoice #', 'Date', 'Customer', 'Type', 'Subtotal', 'Discount', 'Total', 'Due', 'Payment Type', 'Status']);
            foreach ($sales as $sale) {
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
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPDF()
    {
        $sales = $this->getFilteredQuery()->get();
        $data = $sales;
        $reportType = 'daily_sales';
        $reportTitle = 'Invoices Report';
        $dateFrom = $this->dateFrom ?: 'All';
        $dateTo = $this->dateTo ?: 'All';
        $stats = [
            'totalRevenue' => $sales->sum('total_amount'),
            'totalSalesCount' => $sales->count(),
            'totalDue' => $sales->sum('due_amount'),
            'totalProfit' => $sales->sum('total_amount') - $sales->sum('discount_amount'),
        ];

        $pdf = \PDF::loadView('reports.pdf', compact('data', 'reportType', 'reportTitle', 'dateFrom', 'dateTo', 'stats'));
        $pdf->setPaper('a4', 'landscape');
        return response()->streamDownload(fn() => print($pdf->output()), 'invoices_' . now()->format('Y-m-d_His') . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        $sales = $query->paginate(15);

        // Calculate stats
        $totalSales = Sale::count();
        $todaySales = Sale::whereDate('created_at', Carbon::today())->count();
        $totalRevenue = Sale::sum('total_amount');
        $todayRevenue = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');

        return view('livewire.admin.view-invoice', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue
        ]);
    }
}
