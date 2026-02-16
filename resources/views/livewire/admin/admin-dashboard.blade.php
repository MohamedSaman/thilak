<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-gradient mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, Administrator. Here's what's happening today.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex gap-2">
                <button class="btn btn-secondary shadow-premium">
                    <i class="bi bi-download me-2"></i>Export Report
                </button>
                <a href="{{ route('admin.store-billing') }}" target="_blank" class="btn btn-primary shadow-premium">
                    <i class="bi bi-plus-lg me-2"></i>New Sale
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-primary-soft text-primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Revenue</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($totalRevenue ?? 0, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-primary" style="width: 75%"></div>
                </div>
                <span class="text-xs text-muted"><i class="bi bi-arrow-up text-success me-1"></i> +12% from last month</span>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-danger-soft text-danger">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Due Payments</span>
                        <h3 class="mb-0 fw-bold">Rs.{{ number_format($totalDue ?? 0, 2) }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-danger" style="width: 45%"></div>
                </div>
                <span class="text-xs text-muted"><i class="bi bi-info-circle me-1"></i> Action required for 5 bills</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-success-soft text-success">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Total Sales</span>
                        <h3 class="mb-0 fw-bold">{{ $totalSalesCount ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-success" style="width: 85%"></div>
                </div>
                <span class="text-xs text-muted"><i class="bi bi-clock me-1"></i> Updated 2 mins ago</span>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="icon-shape icon-lg bg-warning-soft text-warning">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="text-end">
                        <span class="text-xs fw-bold text-uppercase tracking-wider text-muted">Low Stock</span>
                        <h3 class="mb-0 fw-bold">{{ $lowStockCount ?? 0 }}</h3>
                    </div>
                </div>
                <div class="progress progress-sm rounded-pill mb-2">
                    <div class="progress-bar bg-warning" style="width: 30%"></div>
                </div>
                <span class="text-xs text-muted"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Items below threshold</span>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="glass-effect rounded-xl p-2 mb-4 d-inline-flex gap-2">
        <button class="btn btn-sm px-4 py-2 rounded-lg active" data-tab="overview">Overview</button>
        <button class="btn btn-sm px-4 py-2 rounded-lg" data-tab="analytics">Analytics</button>
        <button class="btn btn-sm px-4 py-2 rounded-lg" data-tab="inventory">Inventory</button>
    </div>

    <!-- Tab Contents -->
    <div id="overview" class="tab-content active transition-all">
        <div class="row g-4">
            <!-- Sales Chart -->
            <div class="col-lg-8">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0">Sales Performance</h5>
                            <p class="text-xs text-muted mb-0">Revenue trends over time</p>
                        </div>
                        <select wire:model.live="filter" class="form-select border-0 bg-light-soft text-sm" style="width: 140px;">
                            <option value="7_days">7 Days</option>
                            <option value="30_days">30 Days</option>
                        </select>
                    </div>
                    <div style="height: 350px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="col-lg-4">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Recent Sales</h5>
                        <a href="{{ route('admin.view-invoice') }}" class="text-xs fw-bold text-primary">View All</a>
                    </div>
                    <div class="custom-scroll" style="max-height: 350px; overflow-y: auto;">
                        @forelse($recentSales->take(8) as $sale)
                        <div class="d-flex align-items-center gap-3 p-3 rounded-xl hover-bg-light transition-all mb-2">
                            <div class="avatar avatar-md rounded-circle bg-primary text-white fw-bold">
                                {{ strtoupper(substr($sale->name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-0 text-truncate fw-bold">{{ $sale->name }}</h6>
                                <p class="text-xs text-muted mb-0">{{ $sale->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-sm">Rs.{{ number_format($sale->total_amount, 2) }}</div>
                                <span class="badge {{ $sale->due_amount > 0 ? 'badge-danger-soft' : 'badge-success-soft' }}">
                                    {{ $sale->due_amount > 0 ? 'Due' : 'Paid' }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-20"></i>
                            <p class="text-muted mt-2">No recent sales</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Tab -->
    <div id="inventory" class="tab-content transition-all d-none">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-4">Stock Status Overview</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="border-0 text-xs text-muted text-uppercase">Product</th>
                            <th class="border-0 text-xs text-muted text-uppercase">Stock Level</th>
                            <th class="border-0 text-xs text-muted text-uppercase">Availability</th>
                            <th class="border-0 text-xs text-muted text-uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productInventory->take(10) as $product)
                        @php
                            $total_stock = $product->stock_quantity + $product->damage_quantity;
                            $stockPercentage = $total_stock > 0 ? round(($product->stock_quantity / $total_stock) * 100) : 0;
                            $status = $product->stock_quantity <= 0 ? 'Out' : ($stockPercentage <= 25 ? 'Low' : 'Healthy');
                            $color = $status == 'Out' ? 'danger' : ($status == 'Low' ? 'warning' : 'success');
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-shape icon-sm bg-light rounded-lg">
                                        <i class="bi bi-box text-primary"></i>
                                    </div>
                                    <span class="fw-bold">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td style="width: 200px;">
                                <div class="progress progress-xs h-1 rounded-pill bg-light">
                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ $stockPercentage }}%"></div>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm fw-bold">{{ $product->stock_quantity }} / {{ $total_stock }}</span>
                                <span class="text-xs text-muted ms-1">units</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $color }}-soft">{{ $status }} Stock</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('livewire:initialized', function() {
        const salesCtx = document.getElementById('salesChart')?.getContext('2d');
        if (!salesCtx) return;

        let salesChartInstance;

        function renderSalesChart(labels, totals) {
            if (salesChartInstance) salesChartInstance.destroy();

            const gradient = salesCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(35, 61, 127, 0.15)');
            gradient.addColorStop(1, 'rgba(35, 61, 127, 0.01)');

            salesChartInstance = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: labels.map(l => {
                        const d = new Date(l);
                        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Revenue',
                        data: totals,
                        borderColor: '#233d7f',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#233d7f',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#233d7f',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#fff',
                            titleColor: '#1a1d1f',
                            bodyColor: '#1a1d1f',
                            borderColor: '#efefef',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 8,
                            usePointStyle: true,
                            callbacks: {
                                label: (context) => ` Revenue: Rs. ${Number(context.parsed.y).toLocaleString()}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { 
                                color: '#9a9fa5',
                                font: { size: 11 },
                                callback: (v) => 'Rs.' + (v >= 1000 ? (v/1000) + 'k' : v)
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9a9fa5', font: { size: 11 } }
                        }
                    }
                }
            });
        }

        renderSalesChart(@json($salesData['labels'] ?? []), @json($salesData['totals'] ?? []));

        window.Livewire.on('refreshSalesChart', (data) => {
            renderSalesChart(data.labels || [], data.totals || []);
        });

        // Tab Switching
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;
                
                // Update buttons
                document.querySelectorAll('[data-tab]').forEach(b => b.classList.remove('active', 'bg-white', 'shadow-sm'));
                btn.classList.add('active', 'bg-white', 'shadow-sm');

                // Update contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('d-none');
                    if (content.id === target) content.classList.remove('d-none');
                });
            });
        });
    });
</script>
@endpush