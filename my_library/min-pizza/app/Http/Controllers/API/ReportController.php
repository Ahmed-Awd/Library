<?php

namespace App\Http\Controllers\API;

use App\Exports\OrdersExport;
use App\Exports\RestaurantsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportCustomersRequest;
use App\Http\Requests\ExportOrdersRequest;
use App\Http\Requests\ExportRestaurantsRequest;
use App\Http\Requests\ReportCustomerRequest;
use App\Http\Requests\ReportOrderRequest;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\RestaurantRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private OrderRepositoryInterface $orderRepository;
    private UserRepositoryInterface $userRepository;
    private RestaurantRepositoryInterface $restaurantRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        RestaurantRepositoryInterface $restaurantRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->restaurantRepository = $restaurantRepository;
    }


    public function index(ReportOrderRequest $request): JsonResponse
    {
        $range = $request->get('range', false);
        $filters = array();
        if ($request->order_status_id) {
            $filters['order_status_id'] = $request->order_status_id;
        }
        if ($request->restaurant_id) {
            $filters['restaurant_id'] = $request->restaurant_id;
        }
        if ($request->service_info_type) {
            $filters['service_info_type'] = $request->service_info_type;
        }
        $orders = $this->orderRepository->report($filters, $range);
        $filter = array();
        $total_amount['all_orders'] = $this->orderRepository->sum($filter);
        $filter['service_info_type'] = 1;
        $total_amount['delivery_orders'] = $this->orderRepository->sum($filter);
        $filter['service_info_type'] = 0;
        $total_amount['takeaway_orders'] = $this->orderRepository->sum($filter);

        return response()->json([
            'total_amount' => $total_amount,
            'filters' => $filters,'range' => $range,
            'orders' => $orders], 200);
    }
    public function customers(ReportCustomerRequest $request): JsonResponse
    {
        $search = $request->get('search', '');
        $customer = $this->userRepository->report($search);
        $data['new_customer'] = $this->userRepository->count('new');
        $data['all_customer'] = $this->userRepository->count();
        $data['has_order_customer'] = $this->userRepository->count('has_order');
        $data['has_more_order_customer'] = $this->userRepository->count('has_more_order');
        return response()->json([
            'data' => $data,
            'customer' => $customer], 200);
    }

    public function restaurants(Request $request): JsonResponse
    {
        $search = $request->get('search', false);
        $restaurant = $this->restaurantRepository->report($search);
        $data['new_restaurant'] = $this->restaurantRepository->count('new');
        $data['all_restaurant'] = $this->restaurantRepository->count();
        return response()->json([
            'data' => $data,
            'restaurant' => $restaurant], 200);
    }

    public function exportCustomers(ExportCustomersRequest $request)
    {
        $data = isset($request->data) ? $request->data : array();
        $other = array();
        if (($key = array_search('orders_sum_total_amount', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'total_amount';
        }
        if (($key = array_search('orders_count', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'orders_count';
        }
        if ($request->type == 'excel') {
            return Excel::download(
                new UsersExport($data, $other),
                'customer ' . now() . '.xlsx'
            );
        } else {
            return Excel::download(
                new UsersExport($data, $other),
                'customer ' . now() . '.pdf',
                \Maatwebsite\Excel\Excel::MPDF
            );
        }
    }
    public function exportRestaurants(ExportRestaurantsRequest $request)
    {
        $data = isset($request->data) ? $request->data : array();
        $other = array();
        if (($key = array_search('orders_sum_total_amount', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'total_amount';
        }
        if (($key = array_search('orders_count', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'orders_count';
        }
        if (($key = array_search('ratings_avg_rate', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'rate';
        }
        if ($request->type == 'excel') {
            return Excel::download(
                new RestaurantsExport($data, $other),
                'customer ' . now() . '.xlsx'
            );
        } else {
            return Excel::download(
                new RestaurantsExport($data, $other),
                'customer ' . now() . '.pdf',
                \Maatwebsite\Excel\Excel::MPDF
            );
        }
    }
    public function exportOrders(ExportOrdersRequest $request)
    {
        $data = isset($request->data) ? $request->data : array();
        $range = isset($request->range) ? $request->range : array();
        $other = array();
        $filters = array();

        if ($request->restaurant_id) {
            $filters['orders.restaurant_id'] = $request->restaurant_id;
        }
        if ($request->service_info_type) {
            $filters['service_info_type'] = $request->service_info_type;
        }
        if (($key = array_search('address', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'address';
        }

        if (($key = array_search('user', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'user';
        }
        if (($key = array_search('restaurant', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'restaurant';
        }
        if (($key = array_search('driver', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'driver';
        }
        if (($key = array_search('status', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'status';
        }

        if (($key = array_search('code', $data)) !== false) {
            unset($data[$key]);
            $other[] = 'code';
        }

        if ($request->type == 'excel') {
            return Excel::download(
                new OrdersExport($data, $other, $filters, $range),
                'orders ' . now() . '.xlsx'
            );
        } else {
            return Excel::download(
                new OrdersExport($data, $other, $filters, $range),
                'orders ' . now() . '.pdf',
                \Maatwebsite\Excel\Excel::MPDF
            );
        }
    }

    public function exportByOrders(Request $request): JsonResponse
    {
        $range = $request->get('range', false);
        $type = $request->get('type', "none");
        $status = $request->get('status', false);
        $restaurant = $request->get('restaurant', false);
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $orders = $this->orderRepository->getAll($range, $type, $status, $restaurant, $from, $to);
        return response()->json(['orders' => $orders,"count" => count($orders)]);
    }

    public function countOfOrders(): JsonResponse
    {
        $count = $this->orderRepository->getAllCount();
        return response()->json(['count' => $count]);
    }
}
