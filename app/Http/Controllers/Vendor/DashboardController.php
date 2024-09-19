<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SwCategory;
use App\Models\SwProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = auth('vendor')->user();
        $shopId = $vendor->sw_shop_id;
        
        $topItems = SwProduct::where('sw_shop_id', $shopId)->paginate(5);
        // $topItems = $this->getTopItems($shopId);
        // $orderStats = $this->getOrderStats($shopId);
        // $currentYearSale = $this->getSalesData($shopId);
        $userStats = $this->getUserStats($shopId);
        
        return view('vendor.dashboard', get_defined_vars());
    }

    public function getTopItems($shopId)
    {
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // paid order status is 5

        // Query to get the shop with the highest sales in the current month
        $topItems = DB::table('sw_cart_items')
            ->join('sw_carts', function($join){
                $join->on('sw_carts.id', '=', 'sw_cart_items.sw_cart_id')
                ->where('sw_carts.status', 0);
            })
            ->join('sw_orders', function($join) use ($startOfMonth, $endOfMonth, $shopId){
                $join->on('sw_orders.sw_cart_id', '=', 'sw_carts.id')
                ->where('sw_orders.status', 1)
                ->where('sw_orders.sw_order_status_id', 5)
                ->where('sw_orders.sw_shop_id', $shopId)
                ->whereBetween('sw_orders.created_at', [$startOfMonth, $endOfMonth]);
            })
            ->select('sw_cart_items.sw_shop_item_id', 'sw_cart_items.item_name', DB::raw('SUM(sw_cart_items.qty) as items_sold'), 'sw_cart_items.price')
            ->groupBy('item_name', 'sw_shop_item_id', 'price')
            ->orderBy('items_sold', 'desc')
            ->skip(0)
            ->take(4)
            ->get();

        return $topItems;
    }

    function getOrderStats($shopId)
    {
        $currentYear = Carbon::now()->year;

        $query = DB::table('sw_orders')
            ->where('status', 1)
            ->where('sw_order_status_id', 5)
            ->where('sw_shop_id', $shopId)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('COUNT(*) as totalOrders')
            ->selectRaw('SUM(CASE WHEN by_shop = 1 THEN 1 ELSE 0 END) as shopOrders')
            ->selectRaw('SUM(CASE WHEN by_shop = 0 THEN 1 ELSE 0 END) as siteOrders')
            ->selectRaw('SUM(CASE WHEN by_shop = 1 THEN amount ELSE 0 END) as shopAmount')
            ->selectRaw('SUM(CASE WHEN by_shop = 0 THEN amount ELSE 0 END) as siteAmount')
            ->first();

        $totalOrders = $query->totalOrders;
        $shopOrders = $query->shopOrders;
        $siteOrders = $query->siteOrders;
        $shopOrdersSum = $query->shopAmount;
        $siteOrdersSum = $query->siteAmount;

        $result = [
            'shopOrdersPercentage' => ($totalOrders > 0) ? ($shopOrders / $totalOrders) * 100 : 0,
            'siteOrdersPercentage' => ($totalOrders > 0) ? ($siteOrders / $totalOrders) * 100 : 0,
            'shopOrdersCount' => $shopOrders,
            'siteOrdersCount' => $siteOrders,
            'shopOrdersSum' => $shopOrdersSum,
            'siteOrdersSum' => $siteOrdersSum,
        ];

        return $result;
    }

    function getSalesData($shopId)
    {
        // Fetch revenue data for the current year
        $currentYearSale = DB::table('sw_orders')
            ->whereBetween('created_at', [now()->subYear(), now()])
            ->where('status', 1)
            ->where('sw_order_status_id', 5)
            ->where('sw_shop_id', $shopId)
            ->select(
                DB::raw('SUM(amount) as sum'),
                DB::raw('UPPER(DATE_FORMAT(created_at, "%b")) as name')
            )
            ->groupBy('name')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return $currentYearSale;
    }

    function getUserStats($shopId)
    {
        $userCount = DB::table('sw_vendors')
            ->whereNull('sw_vendors.deleted_at')
            ->where('sw_vendors.sw_shop_id', $shopId)
            ->join('sw_roles', 'sw_roles.id', 'sw_vendors.sw_role_id')
            ->select('sw_roles.name as role', DB::raw('COUNT(sw_vendors.id) as role_count'))
            ->groupBy('sw_roles.name')
            ->orderBy('role_count', 'DESC')
            ->get();

        return $userCount;
    }

    
    public function getChildCategories(Request $request)
    {
        $categories = SwCategory::where('parent_id', $request->id)->get(['id', 'name']);

        return response()->json(['categories' => $categories]);
    }
}
