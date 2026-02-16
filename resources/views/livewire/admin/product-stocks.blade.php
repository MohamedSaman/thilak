<div class="container-fluid py-6 transition-colors duration-300">
    <div class="card border-0">
        <!-- Card Header -->
        <div class="card-header text-white p-5 rounded-t-4 d-flex align-items-center"
            style="background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); border-radius: 20px 20px 0 0;">
            <div class="icon-shape icon-lg bg-white bg-opacity-25 rounded-circle p-3 d-flex align-items-center justify-content-center me-3">
                <i class="bi bi-shield-lock text-white fs-4" aria-hidden="true"></i>
            </div>
            <div>
                <h3 class="mb-1 fw-bold tracking-tight text-white">Product Stock Details</h3>
                <p class="text-white opacity-80 mb-0 text-sm">Monitor and manage your product inventorys</p>
            </div>
        </div>
        <div class="card-header bg-transparent pb-4 mt-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 border-bottom">
            <!-- Middle: Search Bar -->
            <div class="flex-grow-1 d-flex justify-content-lg">
                <div class="input-group" style="max-width: 400px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                    <span class="input-group-text bg-gray-100 border-0 px-3">
                        <i class="bi bi-search text-primary"></i>
                    </span>
                    <input type="text"
                        class="form-control"
                        placeholder="Search products..."
                        wire:model.live.debounce.300ms="search"
                        autocomplete="off">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button wire:click="exportToCSV"
                    class="btn text-white rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                    aria-label="Export stock details to CSV"
                    style="background-color: #233D7F; border-color: #233D7F; color: white;transition: all 0.3s ease; hover: transform: scale(1.05)">
                    <i class="bi bi-download me-1" aria-hidden="true"></i> Export CSV
                </button>
                <button wire:click="exportPDF"
                    class="btn btn-danger rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                    aria-label="Export stock details to PDF">
                    <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Export PDF
                </button>
                <button id="printButton"
                    class="btn text-white rounded-full shadow-sm px-4 py-2 transition-transform hover:scale-105"
                    aria-label="Print stock details"
                    style="background-color: #233D7F; border-color: #233D7F; color: white;transition: all 0.3s ease; hover: transform: scale(1.05)">
                    <i class="bi bi-printer me-1" aria-hidden="true"></i> Print
                </button>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-1  pt-5 bg-transparent">

            <!-- Stock Table or Empty State -->

            <div class="table-responsive shadow-sm rounded-2 overflow-hidden">
                <table class="table table-sm ">
                    <thead>
                        <tr>
                            <th class="text-center py-3 ps-4">ID</th>
                            <th class=" text-center py-3">Image</th>
                            <th class="text-center py-3">Product Name</th>
                            <th class="text-center py-3">Product Code</th>
                            <th class="text-center py-3">Category</th>
                            <th class="text-center py-3">Sold</th>
                            <th class="text-center py-3">Available</th>
                            <th class="text-center py-3">Damage</th>
                            <th class="text-center py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr class="transition-all hover:bg-gray-50">
                            <td class="text-sm text-center ps-4 py-3">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td class="text-sm text-center py-3">
                                @if($product->image)
                                <div class="image-wrapper rounded-lg shadow-sm transition-transform hover:scale-110">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="rounded-lg"
                                        style="width: 48px; height: 48px; object-fit: cover;"
                                        alt="Image of {{ $product->product_name }}"
                                        onerror="this.onerror=null; this.src=''; this.parentNode.innerHTML='<div style=\'width:48px;height:48px;background-color:#f3f4f6;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;\'><i class=\'bi bi-watch text-gray-600\'></i></div>';">
                                </div>
                                @else
                                <div style="width:30px;height:30px;background-color:#f3f4f6;border-radius:0.5rem;display:flex;align-items:center;justify-content:center; margin:0 auto;">
                                    <i class="bi bi-box-seam text-gray-600"></i>
                                </div>
                                @endif
                            </td>
                            <td class="text-sm text-center py-3">{{ $product->product_name }}</td>
                            <td class="text-sm text-center py-3">{{ $product->product_code }}</td>
                            <td class="text-sm text-center py-3">{{ $product->category?->name ?? 'N/A' }}</td>
                            <td class="text-sm text-center py-3">{{ $product->sold }}</td>
                            <td class="text-sm text-center py-3">
                                <span class="badge"
                                    style="background-color: {{ $product->stock_quantity > 0 ? '#22c55e' : '#ef4444' }};
                                             color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-weight: 600;">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="text-sm text-center py-3">{{ $product->damage_quantity }}</td>
                            <td class="text-sm text-center py-3">
                                {{ $product->sold + $product->stock_quantity + $product->damage_quantity }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-sm text-center py-3">
                                <div style="width:72px;height:72px;background-color:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;margin-bottom:12px;">
                                    <i class="bi bi-box-seam text-gray-600 fs-3"></i>
                                </div>
                                <h5 class="text-gray-600 fw-normal">No Product Stock Found</h5>
                                <p class="text-sm text-gray-500 mb-0">No matching results found for the current search.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                {{ $products->links('livewire::bootstrap') }}
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

    .tracking-tight {
        letter-spacing: -0.025em;
    }

    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('showToast', ({
            type,
            message
        }) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#ffffff',
                color: '#1f2937',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('printButton').addEventListener('click', function() {
            printProductStockDetails();
        });
    });

    function printProductStockDetails() {
        const tableContent = document.querySelector('.table-responsive')?.cloneNode(true) || '';
        const printWindow = window.open('', '_blank', 'height=600,width=800');

        printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Product Stock Details - Print Report</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Inter', sans-serif; padding: 20px; font-size: 14px; color: #1f2937; }
                .print-container { max-width: 900px; margin: 0 auto; }
                .print-header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #1e40af; display: flex; justify-content: space-between; align-items: center; }
                .print-header h2 { color: #1e40af; font-weight: 700; letter-spacing: -0.025em; }
                .print-footer { margin-top: 20px; padding-top: 15px; border-top: 2px solid #e5e7eb; text-align: center; font-size: 12px; color: #6b7280; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { padding: 12px; border: 1px solid #e5e7eb; text-align: center; vertical-align: middle; }
                th { background-color: #eff6ff; font-weight: 600; text-transform: uppercase; color: #1e3a8a; }
                .img-preview { width:48px; height:48px; object-fit:cover; border-radius:0.5rem; border:1px solid #e5e7eb; }
                .no-image { width:48px; height:48px; background-color:#f3f4f6; display:flex; align-items:center; justify-content:center; border-radius:0.5rem; border:1px solid #e5e7eb; }
                .badge { padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.875rem; font-weight:500; color:#ffffff; }
                .bg-green { background-color:#22c55e; }
                .bg-red { background-color:#ef4444; }
                @media print { .no-print { display:none; } thead { display:table-header-group; } tr { page-break-inside: avoid; } body { padding:10px; } .print-container { max-width:100%; } }
            </style>
        </head>
        <body>
            <div class="print-container">
                <div class="print-header">
                    <h2 class="fw-bold tracking-tight">Product Stock Details</h2>
                    <div class="no-print">
                        <button class="btn btn-primary rounded-full px-4" style="background-color:#1e40af;border-color:#1e40af;" onclick="window.print();">Print</button>
                        <button class="btn btn-outline-secondary rounded-full px-4 ms-2" onclick="window.close();">Close</button>
                    </div>
                </div>
                ${tableContent.outerHTML}
                <div class="print-footer">
                    <small>Generated on ${new Date().toLocaleString('en-US', { timeZone: 'Asia/Colombo' })}</small><br>
                    <p>THILAK HARDWARE</p><br>
                    <small> for <br> MARUTI-LEYLAND - MAHINDRA-TATA-ALTO</small><br>
                    <small>NO. 397/, DUNU ELA, THIHARIYA, KALAGEDIHENA | Phone: 077 9089961</small>
                </div>
            </div>
        </body>
        </html>
    `);

        printWindow.document.close();
        printWindow.focus();
    }
</script>
@endpush