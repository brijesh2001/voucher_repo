<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\SaleData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Charts;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'checkRole']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        //Enquiry Chart
        $enquiry = new Enquiry();
        $today_enquiry = $enquiry->getDailyEnquiryCount();
        $last_day_enquiry = $enquiry->getLastDayEnquiryCount();
        $last_week_enquiry = $enquiry->getLastWeekEnquiryCount();
        $last_30_days_enquiry = $enquiry->last30DaysCount();
        $total_enquiry = $enquiry->allEnquiryCount();
        $enquiry = Charts::create('bar', 'highcharts')
            ->title('Enquiry Trend')
            ->labels(['Today', 'Last Day', 'Last Week','Last 30 Days','Total Enquiry'])
            ->elementLabel("Total Enquiry")
            ->values([$today_enquiry,$last_day_enquiry,$last_week_enquiry,$last_30_days_enquiry,$total_enquiry])
            ->colors(['#ff0000', '#00ff00', '#0000ff','#f4e541'])
            ->responsive(true)
            ->dimensions(0,500);
        //Sale chart
        $sale = new SaleData();
        $today_sale = $sale->getDailyEnquiryCount();
        $last_day_sale = $sale->getLastDayEnquiryCount();
        $last_week_sale = $sale->getLastWeekEnquiryCount();
        $last_30_days_sale = $sale->last30DaysCount();
        $total_sale_data = $sale->allSaleCount();
        $sale = Charts::create('bar', 'highcharts')
            ->title('Sale Trend')
            ->labels(['Today', 'Last Day', 'Last Week','Last 30 Days','Total Sale'])
            ->elementLabel("Total Sale")
            ->values([$today_sale,$last_day_sale,$last_week_sale,$last_30_days_sale,$total_sale_data])
            ->colors(['#ff0000', '#00ff00', '#0000ff','#f4e541','#ff0000'])
            ->responsive(true)
            ->dimensions(20,500);

        $today_conversion_rate = ($today_enquiry > 0) ? number_format($today_sale / $today_enquiry * 100)  : 0 ;
        $last_day_conversion_rate = ($last_day_enquiry > 0) ? number_format($last_day_sale / $last_day_enquiry * 100)  : 0 ;
        $last_week_conversion_rate = ($last_week_enquiry > 0) ? number_format($last_week_sale / $last_week_enquiry * 100) : 0 ;
        $last30_days_conversion_rate = ($last_30_days_enquiry > 0) ? number_format($last_30_days_sale / $last_30_days_enquiry * 100) : 0 ;
        $total_conversion_rate = ($total_enquiry > 0) ? number_format($total_sale_data / $total_enquiry * 100) : 0 ;

        $ratio = Charts::create('bar', 'highcharts')
            ->title('Conversion Rate')
            ->labels(['Today', 'Last Day', 'Last Week','Last 30 Days','Total Sale'])
            ->elementLabel("Total Conversion")
            ->values([$today_conversion_rate,$last_day_conversion_rate,$last_week_conversion_rate,$last30_days_conversion_rate,$total_conversion_rate])
            ->colors(['#41f4e5', '#f4df41', '#c141f4','#f44141','#41f4e5'])
            ->responsive(true)
            ->dimensions(20,500);



        return view('dashboard',['enquiry' => $enquiry,'sale' => $sale,'ratio' => $ratio])->with('dashboardTab', 'active');
    }
}
