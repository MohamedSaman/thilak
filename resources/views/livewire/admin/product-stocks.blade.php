<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-shape icon-lg bg-primary-soft text-primary rounded-xl">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-1 border-0">Inventory Status</h2>
                    <p class="text-muted mb-0">Track stock levels, damages, and sales across your entire catalog.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
             <div class="d-flex align-items-center justify-content-md-end gap-2">
                <button wire:click="exportToCSV" class="btn btn-white shadow-premium text-success">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export CSV
                </button>
                <button wire:click="exportPDF" class="btn btn-white shadow-premium text-danger">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </button>
                <button id="printButton" class="btn btn-primary shadow-premium">
                    <i class="bi bi-printer me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Table Card -->
    <div class="glass-card rounded-xl overflow-hidden shadow-premium">
        <!-- Action Bar -->
        <div class="p-4 bg-light-soft border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-transparent ps-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 bg-transparent text-sm" placeholder="Search by product name or code..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover" id="inventoryTable">
                <thead class="bg-light-soft text-xs">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th class="text-center">Sold</th>
                        <th class="text-center">Available</th>
                        <th class="text-center">Damaged</th>
                        <th class="text-center">Total Stock</th>
                        <th class="pe-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr class="hover-bg-light transition-all">
                        <td class="ps-4 fw-bold text-muted text-xs">{{ $products->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($product->image)
                                    <div class="avatar avatar-md rounded-lg overflow-hidden border">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-100 h-100 object-fit-cover"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('{{$product->product_name}}') + '&color=7F9CF5&background=EBF4FF';">
                                    </div>
                                @else
                                    <div class="avatar avatar-md rounded-lg bg-light d-flex align-items-center justify-content-center text-muted border">
                                        <i class="bi bi-box-seam text-xs"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $product->product_name }}</h6>
                                    <span class="text-xs text-muted">{{ $product->product_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary-soft rounded-pill">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                        </td>
                        <td class="text-center font-monospace">{{ $product->sold }}</td>
                        <td class="text-center text-sm">
                            <span class="fw-bold {{ $product->stock_quantity <= 5 ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td class="text-center text-muted text-sm">{{ $product->damage_quantity }}</td>
                        <td class="text-center fw-bold text-sm">
                            {{ $product->sold + $product->stock_quantity + $product->damage_quantity }}
                        </td>
                        <td class="pe-4 text-center">
                            @if($product->stock_quantity <= 0)
                                <span class="badge badge-danger-soft px-3 py-2 rounded-pill text-xs">OUT OF STOCK</span>
                            @elseif($product->stock_quantity <= 10)
                                <span class="badge badge-warning-soft px-3 py-2 rounded-pill text-xs">LOW STOCK</span>
                            @else
                                <span class="badge badge-success-soft px-3 py-2 rounded-pill text-xs">HEALTHY</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-box-seam fs-1 text-muted opacity-20"></i>
                            <p class="text-muted mt-2">No product stock found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="p-4 border-top">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
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

        document.getElementById('printButton').addEventListener('click', function() {
            const printWindow = window.open('', '_blank', 'width=1000,height=700');
            const tableElement = document.getElementById('inventoryTable').cloneNode(true);
            
            // Clean up table for print (remove avatars for simpler print if desired, but here we keep them)
            const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Inventory Report</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: 'Inter', sans-serif; padding: 40px; color: #1a2d5e; }
                        .report-header { border-bottom: 2px solid #233d7f; margin-bottom: 30px; padding-bottom: 20px; text-align: center; }
                        table { width: 100%; font-size: 11px; }
                        th { background-color: #f8faff !important; color: #233d7f !important; text-transform: uppercase; padding: 10px; border: 1px solid #ddd; }
                        td { padding: 8px; border: 1px solid #ddd; }
                        .text-center { text-align: center; }
                        .text-end { text-align: right; }
                        .avatar { display: none; } /* Hide avatars in print to save space */
                    </style>
                </head>
                <body>
                    <div class="report-header">
                        <h2 class="fw-bold">Product Inventory Report</h2>
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
</script>
@endpush