<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Models\ProductDetail;

#[Layout('components.layouts.admin')]
#[Title('Product Management')]
class ProductStocks extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    private function getFilteredQuery()
    {
        return ProductDetail::query()
            ->with(['category', 'brand'])
            ->where(function ($query) {
                $query->where('product_name', 'like', "%{$this->search}%")
                    ->orWhere('product_code', 'like', "%{$this->search}%")
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy('product_name');
    }

    public function exportToCSV()
    {
        $products = $this->getFilteredQuery()->get();
        $filename = 'product_stocks_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Code', 'Product Name', 'Category', 'Brand', 'Stock Qty', 'Damage Qty', 'Sold', 'Supplier Price', 'Selling Price', 'Status']);
            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->product_code,
                    $product->product_name,
                    $product->category ? $product->category->name : '-',
                    $product->brand ? $product->brand->brand_name : '-',
                    $product->stock_quantity,
                    $product->damage_quantity ?? 0,
                    $product->sold ?? 0,
                    number_format($product->supplier_price, 2),
                    number_format($product->selling_price, 2),
                    $product->status,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPDF()
    {
        $products = $this->getFilteredQuery()->get();
        $data = $products;
        $reportType = 'stock_alert';
        $reportTitle = 'Product Stock Report';
        $dateFrom = 'All';
        $dateTo = 'All';
        $stats = [
            'totalRevenue' => $products->where('stock_quantity', '<=', 0)->count(),
            'totalSalesCount' => $products->count(),
            'totalDue' => 0,
            'totalProfit' => 0,
        ];

        $pdf = \PDF::loadView('reports.pdf', compact('data', 'reportType', 'reportTitle', 'dateFrom', 'dateTo', 'stats'));
        $pdf->setPaper('a4', 'landscape');
        return response()->streamDownload(fn() => print($pdf->output()), 'product_stocks_' . now()->format('Y-m-d_His') . '.pdf', ['Content-Type' => 'application/pdf']);
    }

    public function render()
    {
        $products = $this->getFilteredQuery()->paginate(10);
        // $hasStock = $products->sum(function ($product) {
        //     return $product->sold + $product->available + $product->damage;
        // }) > 0;

        return view('livewire.admin.product-stocks', [
            'products' => $products,
            // 'hasStock' => $hasStock
        ]);
    }
}