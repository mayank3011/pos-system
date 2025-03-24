<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Services\SalesAnalyticsService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    protected $salesAnalyticsService;

    public function __construct(SalesAnalyticsService $salesAnalyticsService)
    {
        $this->salesAnalyticsService = $salesAnalyticsService;
    }

    public function index(Request $request)
    {
        // Validate input dates
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Get date inputs
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Fetch analytics data
        $categoryWiseSales = $this->salesAnalyticsService->getCategoryWiseSales($startDate, $endDate);

        $paymentStatusBreakdown = $this->salesAnalyticsService->getPaymentStatusBreakdown($startDate, $endDate);

        $salesTrends = $this->salesAnalyticsService->getSalesTrends($startDate, $endDate, 'day');

        $lowStockProducts = $this->salesAnalyticsService->getLowStockProducts();

        $topSellingProducts = $this->salesAnalyticsService->getTopSellingProducts($startDate, $endDate);

        return view('analytics.dashboard', compact(
            'categoryWiseSales',
            'paymentStatusBreakdown',
            'salesTrends',
            'lowStockProducts',
            'topSellingProducts'
        ));
    }

    public function exportReport(Request $request)
    {
        // Validate input dates
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Get date inputs with defaults
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d')); // Default to 7 days ago
        $endDate = $request->input('end_date', now()->format('Y-m-d')); // Default to today

        // Fetch data for the report
        $categoryWiseSales     = $this->salesAnalyticsService->getCategoryWiseSales($startDate, $endDate);
        $paymentStatusBreakdown = $this->salesAnalyticsService->getPaymentStatusBreakdown($startDate, $endDate);
        $salesTrends           = $this->salesAnalyticsService->getSalesTrends($startDate, $endDate, 'day');
        $lowStockProducts      = $this->salesAnalyticsService->getLowStockProducts();
        $topSellingProducts    = $this->salesAnalyticsService->getTopSellingProducts($startDate, $endDate);
        // Log export process

        // Export the data
        return Excel::download(
            new SalesReportExport(
                $categoryWiseSales,
                $paymentStatusBreakdown,
                $salesTrends,
                $lowStockProducts,
                $topSellingProducts
            ),
            'sales_report.xlsx'
        );
 }
}