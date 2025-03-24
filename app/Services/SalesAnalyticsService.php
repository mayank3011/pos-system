<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Order;
use App\Models\Orderdetails;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesAnalyticsService
{
    /**
     * Fetch all category-wise sales (removing order status/payment status filters).
     * If $startDate and $endDate are given (YYYY-MM-DD), filter by that range.
     */
    public function getCategoryWiseSales($startDate = null, $endDate = null)
    {
        \Log::info('Fetching category-wise sales');
        \Log::debug('Start Date: ' . $startDate);
        \Log::debug('End Date: ' . $endDate);

        $query = Category::select(
            'categories.category_name',
            DB::raw('COALESCE(SUM(orderdetails.total), 0) as total_sales'),
            DB::raw('COALESCE(SUM(orderdetails.quantity), 0) as total_quantity')
        )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('orderdetails', 'products.id', '=', 'orderdetails.product_id')
            ->leftJoin('orders', 'orderdetails.order_id', '=', 'orders.id');

        // Date range filter (optional)
        if ($startDate && $endDate) {
            $query->whereRaw("STR_TO_DATE(orders.order_date, '%d-%M-%Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        // *** Removed order_status/payment_status filters so ALL orders appear ***
        // ->where(function ($q) {
        //     $q->whereNull('orders.order_status')
        //       ->orWhere('orders.order_status', 'complete');
        // })
        // ->where(function ($q) {
        //     $q->whereNull('orders.payment_status')
        //       ->orWhere('orders.payment_status', 'HandCash');
        // });

        $salesData = $query->groupBy('categories.category_name')->get();

        \Log::info('Category-wise Sales Data:', ['data' => $salesData->toArray()]);
        return $salesData;
    }

    /**
     * Fetch top-selling products (removing order status/payment status filters).
     * If $startDate and $endDate are given (YYYY-MM-DD), filter by that range.
     * @param  int $limit
     */
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10)
    {
        \Log::info('Fetching top-selling products');
        \Log::debug('Start Date: ' . $startDate);
        \Log::debug('End Date: ' . $endDate);

        $query = Orderdetails::select(
            'products.product_name',
            DB::raw('SUM(orderdetails.quantity) as total_quantity'),
            DB::raw('SUM(orderdetails.total) as total_sales')
        )
            ->join('products', 'orderdetails.product_id', '=', 'products.id')
            ->join('orders', 'orderdetails.order_id', '=', 'orders.id');

        // Date range filter (optional)
        if ($startDate && $endDate) {
            $query->whereRaw("STR_TO_DATE(orders.order_date, '%d-%M-%Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        // *** Removed the filters that restricted to 'complete' & 'HandCash' ***
        // ->where('orders.order_status', 'complete')
        // ->where('orders.payment_status', 'HandCash');

        $topSellingProducts = $query
            ->groupBy('products.product_name')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();

        \Log::info('Top Selling Products Data:', ['data' => $topSellingProducts->toArray()]);
        return $topSellingProducts;
    }

    /**
     * Fetch payment status breakdown for all orders (removing order status filter).
     */
    public function getPaymentStatusBreakdown($startDate = null, $endDate = null)
    {
        \Log::info('Fetching payment status breakdown');

        $query = Order::select(
            'payment_status',
            DB::raw('COUNT(*) as total_orders')
        );
        // Removed ->where('order_status', 'complete');

        // Date range filter (optional)
        if ($startDate && $endDate) {
            $query->whereRaw("STR_TO_DATE(order_date, '%d-%M-%Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        $paymentStatusData = $query->groupBy('payment_status')->get();

        \Log::info('Payment Status Breakdown:', ['data' => $paymentStatusData->toArray()]);
        return $paymentStatusData;
    }

    /**
     * Fetch sales trends (removing order status/payment status filters).
     * Group by date to see total sales per day.
     */
    public function getSalesTrends($startDate = null, $endDate = null, $interval = 'day')
    {
        \Log::info('Fetching sales trends');
        \Log::debug('Start Date: ' . $startDate);
        \Log::debug('End Date: ' . $endDate);

        $query = Order::select(
            DB::raw("DATE_FORMAT(STR_TO_DATE(order_date, '%d-%M-%Y'), '%Y-%m-%d') as date"),
            DB::raw('SUM(total) as total_sales')
        );

        // *** Removed ->where('order_status', 'complete')->where('payment_status', 'HandCash'); ***

        // Date range filter (optional)
        if ($startDate && $endDate) {
            $query->whereRaw("STR_TO_DATE(order_date, '%d-%M-%Y') BETWEEN ? AND ?", [$startDate, $endDate]);
        }

        $salesTrends = $query
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        \Log::info('Sales Trends Data:', ['data' => $salesTrends->toArray()]);
        return $salesTrends;
    }

    /**
     * Fetch products with low stock (same as before).
     */
    public function getLowStockProducts($threshold = 10)
    {
        \Log::info("Fetching low stock products with threshold: {$threshold}");

        $lowStockProducts = Product::where('product_store', '<=', $threshold)
            ->orderBy('product_store', 'asc')
            ->get();

        \Log::info('Low Stock Products:', ['data' => $lowStockProducts->toArray()]);
        return $lowStockProducts;
    }
}
