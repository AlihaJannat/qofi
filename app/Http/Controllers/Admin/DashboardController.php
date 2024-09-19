<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SwShop;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $topShops = SwShop::paginate(5);
        // $topShops = $this->getTopShops();
        // // dd($topShops);
        // $percentageChange = 0;
        // if (count($topShops)) {
        //     $percentageChange = $this->getSellPercentageChange($topShops[0]);
        // }

        // $currentYearSale = $this->getSalesData();
        $userStats = $this->getUserStats();

        return view('admin.dashboard', get_defined_vars());
    }

    function getTopShops() : Collection
    {
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // paid order status is 5

        // Query to get the shop with the highest sales in the current month
        $topShops = DB::table('sw_orders')
            ->join('sw_shops', 'sw_shops.id', 'sw_orders.sw_shop_id')
            ->select('sw_orders.sw_shop_id', 'sw_shops.name', 'sw_shops.image_name', DB::raw('SUM(amount) as total_sales'))
            ->whereBetween('sw_orders.created_at', [$startOfMonth, $endOfMonth])
            ->where('sw_orders.status', 1)
            ->where('sw_orders.sw_order_status_id', 5)
            ->groupBy('sw_shop_id', 'sw_shops.image_name', 'sw_shops.name')
            ->orderBy('total_sales', 'desc')
            ->skip(0)
            ->take(4)->get();

        return $topShops;
    }

    function getSellPercentageChange($topShop)
    {
        // Get the start and end of today
        $startOfToday = Carbon::today()->startOfDay();
        $endOfToday = Carbon::today()->endOfDay();

        // Get the start and end of yesterday
        $startOfYesterday = Carbon::yesterday()->startOfDay();
        $endOfYesterday = Carbon::yesterday()->endOfDay();

        // Fetch total sales for today
        $todaySales = DB::table('sw_orders')
            ->where('status', 1)
            ->where('sw_shop_id', $topShop->sw_shop_id)
            ->where('sw_order_status_id', 5)
            ->whereBetween('created_at', [$startOfToday, $endOfToday])
            ->sum('amount');

        // Fetch total sales for yesterday
        $yesterdaySales = DB::table('sw_orders')
            ->where('status', 1)
            ->where('sw_shop_id', $topShop->sw_shop_id)
            ->where('sw_order_status_id', 5)
            ->whereBetween('created_at', [$startOfYesterday, $endOfYesterday])
            ->sum('amount');

        // Calculate the percentage change
        if ($yesterdaySales == 0) {
            // To avoid division by zero, handle the case where yesterday's sales are zero
            if ($todaySales > 0) {
                $percentageChange = 100; // 100% increase if yesterday's sales were zero and today's sales are greater than zero
            } else {
                $percentageChange = 0; // No change if both are zero
            }
        } else {
            $percentageChange = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
        }

        return $percentageChange;
    }


    function getSalesData()
    {
        // Fetch revenue data for the current year
        $currentYearSale = DB::table('sw_orders')
            ->whereBetween('created_at', [now()->subYear(), now()])
            ->where('status', 1)
            ->where('sw_order_status_id', 5)
            ->select(
                DB::raw('SUM(amount) as sum'),
                DB::raw('UPPER(DATE_FORMAT(created_at, "%b")) as name')
            )
            ->groupBy('name')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return $currentYearSale;
    }

    function getUserStats()
    {
        $userCount = DB::table('users')
            ->whereNull('deleted_at')
            // ->where('is_approved', 1)
            // ->selectRaw('SUM(CASE WHEN user_role = "student" THEN 1 ELSE 0 END) as students')
            ->selectRaw('SUM(CASE WHEN role = "customer" THEN 1 ELSE 0 END) as customers')
            ->selectRaw('SUM(CASE WHEN role = "admin" THEN 1 ELSE 0 END) as admins')
            ->first();

        $vendorCount = DB::table('sw_vendors')->where('role', 'owner')->count('id');
        $totalUsers = (int)$vendorCount + (int)$userCount->customers + (int) $userCount->admins;

        $customerPercentage = ($totalUsers > 0) ? ($userCount->customers / $totalUsers) * 100 : 0.00;
        $adminPercentage = ($totalUsers > 0) ? ($userCount->admins / $totalUsers) * 100 : 0.00;
        $vendorPercentage = ($totalUsers > 0) ? ($vendorCount / $totalUsers) * 100 : 0.00;

        $userStats = [
            'customerCount' => $userCount->customers,
            'adminCount' => $userCount->admins,
            'vendorCount' => $vendorCount,
            'customerPercentage' => $customerPercentage,
            'adminPercentage' => $adminPercentage,
            'vendorPercentage' => $vendorPercentage,
            'totalUsers' => $totalUsers
        ];

        return $userStats;
    }
}
