@extends('admin_dashboard')

@section('admin')
<div class="container mt-5">
    <!-- Date Filter Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Sales Filter</h5>
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                            <div class="form-check form-check-inline me-3">
                                <input class="form-check-input" type="radio" name="period" value="daily" {{ $period === 'daily' ? 'checked' : '' }}>
                                <label class="form-check-label">Daily</label>
                            </div>
                            <div class="form-check form-check-inline me-3">
                                <input class="form-check-input" type="radio" name="period" value="monthly" {{ $period === 'monthly' ? 'checked' : '' }}>
                                <label class="form-check-label">Monthly</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="period" value="yearly" {{ $period === 'yearly' ? 'checked' : '' }}>
                                <label class="form-check-label">Yearly</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-6 fw-bold">Sales & Payment Analytics Dashboard</h1>
            <p class="lead text-muted">Track sales performance and payment status across categories.</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row gy-4">
        <!-- Category Sales Chart -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Category-wise Sales</h5>
                    <small class="text-muted">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </div>
                <div class="card-body">
                    <canvas id="categorySalesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Payment Status</h5>
                    <small class="text-muted">{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</small>
                </div>
                <div class="card-body">
                    <canvas id="paymentStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row gy-4 mt-5">
        <!-- Total Sales -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h1 class="display-6 fw-bold text-success">₹{{ number_format($totalSales, 2) }}</h1>
                </div>
            </div>
        </div>

        <!-- Total Due -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Due</h5>
                    <h1 class="display-6 fw-bold text-danger">₹{{ number_format($totalDue, 2) }}</h1>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Top 5 Selling Products</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($topProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $product->product_name }}
                            <span class="badge bg-primary rounded-pill">{{ $product->total_quantity }} sold</span>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="d-flex justify-content-center mt-5">
        <a href="{{ route('export.category.csv') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-success me-2">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <a href="{{ route('export.category.pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Category Sales Chart
    const categoryNames = @json($categorySales->pluck('category_name'));
    const categorySalesData = @json($categorySales->pluck('total_sales'));
    new Chart(document.getElementById('categorySalesChart'), {
        type: 'bar',
        data: {
            labels: categoryNames,
            datasets: [{
                label: 'Total Sales',
                data: categorySalesData,
                backgroundColor: '#28a745',
                borderRadius: 10,
                barPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString('en-IN');
                        }
                    }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Payment Status Chart
    const paymentLabels = @json(array_keys($paymentStatus->toArray()));
    const paymentData = @json(array_values($paymentStatus->toArray()));
    new Chart(document.getElementById('paymentStatusChart'), {
        type: 'doughnut',
        data: {
            labels: paymentLabels,
            datasets: [{
                label: 'Payment Status',
                data: paymentData,
                backgroundColor: ['#28a745', '#dc3545', '#17a2b8'],
                borderWidth: 0,
                borderRadius: 10,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ₹' + context.raw.toLocaleString('en-IN');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection