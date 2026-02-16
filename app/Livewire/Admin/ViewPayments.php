<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Payment;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
#[Title('View Payments')]
class ViewPayments extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedPayment = null;
    public $filters = [
        'status' => '',
        'paymentMethod' => '',
        'dateRange' => '',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function viewPaymentDetails($paymentId)
    {
        try {
            $this->selectedPayment = Payment::with([
                'sale',
                'sale.customer',
                'sale.user',
                'sale.items',
                'sale.items.product'
            ])->findOrFail($paymentId);

            $this->dispatch('openModal', 'payment-receipt-modal');
        } catch (\Exception $e) {
            $this->dispatch('showToast', [
                'type' => 'error',
                'message' => 'Error loading payment: ' . $e->getMessage()
            ]);
        }
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    private function getFilteredQuery()
    {
        return Payment::query()
            ->with(['sale', 'sale.customer', 'sale.user'])
            ->where('status', 'Paid')
            ->when($this->search, function ($q) {
                return $q->whereHas('sale', function ($sq) {
                    $sq->where('invoice_number', 'like', "%{$this->search}%")
                        ->orWhereHas('customer', function ($cq) {
                            $cq->where('name', 'like', "%{$this->search}%")
                                ->orWhere('phone', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->filters['paymentMethod'], function ($q) {
                return $q->where('payment_method', $this->filters['paymentMethod']);
            })
            ->orderBy('created_at', 'desc');
    }

    public function exportCSV()
    {
        $payments = $this->getFilteredQuery()->get();
        $filename = 'payments_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Invoice #', 'Customer', 'Method', 'Amount', 'Status', 'Reference']);
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->created_at->format('Y-m-d H:i'),
                    $payment->sale ? $payment->sale->invoice_number : '-',
                    $payment->sale && $payment->sale->customer ? $payment->sale->customer->name : 'Walk-in',
                    ucfirst($payment->payment_method ?? 'N/A'),
                    number_format($payment->amount, 2),
                    $payment->is_completed ? 'Completed' : 'Pending',
                    $payment->payment_reference ?? '-',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPDF()
    {
        $payments = $this->getFilteredQuery()->get();
        $data = $payments;
        $reportType = 'payment_summary';
        $reportTitle = 'Payments Report';
        $dateFrom = 'All';
        $dateTo = 'All';
        $stats = [
            'totalRevenue' => $payments->sum('amount'),
            'totalSalesCount' => $payments->count(),
            'totalDue' => $payments->where('is_completed', false)->sum('amount'),
            'totalProfit' => 0,
        ];

        $pdf = \PDF::loadView('reports.pdf', compact('data', 'reportType', 'reportTitle', 'dateFrom', 'dateTo', 'stats'));
        $pdf->setPaper('a4', 'landscape');
        return response()->streamDownload(fn() => print($pdf->output()), 'payments_' . now()->format('Y-m-d_His') . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        $payments = $query->paginate(15);

        // Get summary stats
        $totalPayments = Payment::where('is_completed', 1)->sum('amount');
        $pendingPayments = Payment::where('is_completed', 0)->sum('amount');
        $todayTotalPayments = Payment::whereDate('created_at', now()->toDateString())->where('is_completed', 1)->sum('amount');
        $todayPendingPayments = Payment::whereDate('created_at', now()->toDateString())->where('is_completed', 0)->sum('amount');

        return view('livewire.admin.view-payments', [
            'payments' => $payments,
            'totalPayments' => $totalPayments,
            'pendingPayments' => $pendingPayments,
            'todayTotalPayments' => $todayTotalPayments,
            'todayPendingPayments' => $todayPendingPayments
        ]);
    }
}
