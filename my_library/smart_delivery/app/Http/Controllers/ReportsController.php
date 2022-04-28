<?php

namespace App\Http\Controllers;

use App\Exports\ReportAllDriversExport;
use App\Exports\ReportAllStoresExport;
use App\Exports\ReportGeneralExport;
use App\Exports\ReportToAdminExport;
use App\Exports\SheetOfStoresCharge;
use App\Exports\SheetOfStoresOrders;
use App\Models\OrderStatus;
use App\Models\Store;
use App\Models\User;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ReportRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{

    private OrderRepositoryInterface $orderRepository;
    private StoreRepositoryInterface $storeRepository;
    private DriverRepositoryInterface $driverRepository;
    private ReportRepositoryInterface $reportRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        StoreRepositoryInterface $storeRepository,
        DriverRepositoryInterface $driverRepository,
        ReportRepositoryInterface $reportRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->storeRepository = $storeRepository;
        $this->driverRepository = $driverRepository;
        $this->reportRepository = $reportRepository;
    }

    public function store()
    {
        $data['stores'] = $this->storeRepository->all();
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/Store', $data);
    }

    public function driver()
    {
        $data['drivers'] = $this->driverRepository->get();
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/Driver', $data);
    }

    public function allStores()
    {
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/Stores', $data);
    }

    public function allDrivers()
    {
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/Drivers', $data);
    }

    public function general()
    {
        $data['order_status'] = OrderStatus::all();
        $data['stores'] = $this->storeRepository->all();
        $data['drivers'] = $this->driverRepository->get();
        return Inertia::render('Reports/General', $data);
    }

    public function ordersMovement()
    {
        $data['stores'] = $this->storeRepository->all();
        $data['drivers'] = $this->driverRepository->get();
        return Inertia::render('Reports/Movements', $data);
    }

    public function exportDriverReportToAdmin(Request $request, User $user)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $rate = $request->get('rate', false);
        $filters = $this->prepareFilters($status, $rate);
        $filters['driver_id'] = $user->id;
        $range = $this->prepareRange($from, $to);
        $data = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
           $orders = array();
        foreach ($data['orders'] as $order) {
            array_push($orders, $order['id']);
        }
            return Excel::download(
                new ReportToAdminExport($orders, 'driver'),
                'Driver Report To Admin ' . now() . '.xlsx'
            );
    }

    public function exportOwnerReportToAdmin(Request $request, Store $store)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $filters = $this->prepareFilters($status, false);
        $range = $this->prepareRange($from, $to);
        $filters['store_id'] = $store->id;
        $data = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
        $orders = array();
        foreach ($data['orders'] as $order) {
            array_push($orders, $order['id']);
        }
            return Excel::download(
                new ReportToAdminExport($orders, 'store'),
                'Owner Report To Admin ' . now() . '.xlsx'
            );
    }

    public function driverReportToAdmin(Request $request, User $user): JsonResponse
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $rate = $request->get('rate', false);
        $filters = $this->prepareFilters($status, $rate);
        $filters['driver_id'] = $user->id;
        $range = $this->prepareRange($from, $to);
        $orders = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
        return response()->json(['data' => $orders]);
    }

    public function ownerReportToAdmin(Request $request, Store $store): JsonResponse
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $filters = $this->prepareFilters($status, false);
        $range = $this->prepareRange($from, $to);
        $filters['store_id'] = $store->id;
        $orders = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
        return response()->json(['data' => $orders]);
    }

    public function allDriversReport(Request $request): JsonResponse
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $filter = $request->get('filter', false);
        $range = $this->prepareRange($from, $to);
        $status = $request->get('order_status', false);
        $data = $this->reportRepository->driversReport($filter, $range, $status);
        return response()->json(['data' => $data['drivers'],'totals' => $data['totals']]);
    }

    public function exportAllDriversReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $filter = $request->get('filter', false);
        $range = $this->prepareRange($from, $to);
        $drivers = $this->reportRepository->driversReport($filter, $range);
        return Excel::download(
            new ReportAllDriversExport($drivers),
            'All Drivers Report' . now() . '.xlsx'
        );
    }

    public function allStoresReport(Request $request): JsonResponse
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $filter = $request->get('filter', false);
        $status = $request->get('order_status', false);
        $range = $this->prepareRange($from, $to);
        $data = $this->reportRepository->storesReport($filter, $range, $status);
        return response()->json(['data' => $data['stores'],'totals' => $data['totals']]);
    }
    public function exportAllStoresReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $filter = $request->get('filter', false);
        $range = $this->prepareRange($from, $to);
        $stores = $this->reportRepository->storesReport($filter, $range);
        return Excel::download(
            new ReportAllStoresExport($stores),
            'All Stores Report' . now() . '.xlsx'
        );
    }

    public function generalReportApi(Request $request): JsonResponse
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $driver_id = $request->get('driver', false);
        $store_id = $request->get('store', false);
        $status = $request->get('order_status', false);
        $filters = [];
        if ($driver_id != false) {
            $filters['driver_id'] = $driver_id;
        }
        if ($store_id != false) {
            $filters['store_id'] = $store_id;
        }
        if ($status != false) {
            $filters['status'] = $status;
        }
        $range = $this->prepareRange($from, $to);
        $orders = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
        return response()->json(['data' => $orders]);
    }

    public function getOrdersMovement(Request $request)
    {
        $driver_id = $request->get('driver', false);
        $store_id = $request->get('store', false);
        $filters = [];
        if ($driver_id != false) {
            $filters['driver_id'] = $driver_id;
        }
        if ($store_id != false) {
            $filters['store_id'] = $store_id;
        }
        $range = $request->get('range', false);
        $fromTime = $request->get('from_time', false);
        $toTime = $request->get('to_time', false);
        $noInterval = $request->get('no_interval', false);
        $by = $request->get('change_status', false);
        $data = $this->reportRepository->orderMovements($filters, $noInterval, $range, $fromTime, $toTime, $by);
        return response()->json(['orders' => $data["orders"],"averages" => $data["averages"]]);
    }

    public function exportGeneralReportApi(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $driver_id = $request->get('driver', false);
        $store_id = $request->get('store', false);
        $filters = [];
        if ($driver_id != false) {
            $filters['driver_id'] = $driver_id;
        }
        if ($store_id != false) {
            $filters['store_id'] = $store_id;
        }
        $range = $this->prepareRange($from, $to);
        $orders = $this->orderRepository->getAll($filters, 'sent_at', $range, true);
        return Excel::download(
            new ReportGeneralExport($orders['orders']),
            'All Stores Report' . now() . '.xlsx'
        );
    }

    public function prepareFilters($status, $rate): array
    {
        $filter = [];
        if ($status != false) {
            $filter['status'] = $status;
        }
        if ($rate != false) {
            $filter['rate'] = $rate;
        }
        return $filter;
    }

    public function prepareRange($from, $to)
    {
        if ($from == false || $to == false) {
            return false;
        }
        return "$from,$to";
    }

    public function topOnlineDrivers()
    {
        return Inertia::render('Reports/OnlineRank');
    }

    public function getTopOnline(Request $request): JsonResponse
    {
        $day = $request->get('day', "today");
        $top =  $this->reportRepository->topOnline($day);
        return response()->json(['data' => $top]);
    }



    public function storeOrdersReport(Request $request)
    {
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/Orders', $data);
    }

    public function exportStoreOrdersReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        if ($from == false || $to == false) {
            abort(404);
        }
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        $data = $this->reportRepository->storesOrders($from, $to, $status);
        if ($request->headers->get('Accept') == "application/json") {
            return response()->json(['stores' => $data['stores'],'period' => $data['period']
                ,'rows' => $data['rows'],'totals' => $data['totals']]);
        }
        return Excel::download(
            new SheetOfStoresOrders($data['stores'], $data['period'], $data['rows'], $data['totals']),
            'orders_of_stores_report_' . now() . '.xlsx'
        );
    }

    public function storeChargeReport(Request $request)
    {
        return Inertia::render('Reports/Charge');
    }

    public function exportStoresChargeReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        if ($from == false || $to == false) {
            abort(404);
        }
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        $data = $this->reportRepository->storesCharge($from, $to);
        if ($request->headers->get('Accept') == "application/json") {
            return response()->json(['stores' => $data['stores'],'period' => $data['period']
                ,'rows' => $data['rows'],'totals' => $data['totals']]);
        }
        return Excel::download(
            new SheetOfStoresCharge($data['stores'], $data['period'], $data['rows'], $data['totals']),
            'charge_report_' . now() . '.xlsx'
        );
    }

    public function ordersReport()
    {
        $data['order_status'] = OrderStatus::all();
        return Inertia::render('Reports/GeneralOrders', $data);
    }

    public function ordersReportData(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        if ($from == false || $to == false) {
            abort(404);
        }
        $status = $request->get('order_status', false);
        $data = $this->reportRepository->generalOrdersReport(Carbon::parse($from), Carbon::parse($to), $status);
        return response()->json(['data' => $data["data"],"totals" => $data["totals"]]);
    }
}
