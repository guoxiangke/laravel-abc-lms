<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Charts\OverViews;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\User;
use DB;

class ChartController extends Controller
{
    public function index()
    {
        $data = [];
        $chart = new OverViews;
        $chart->labels(['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange']);
        $chart->title = 'Pie Chart';
        $chart->dataset('My dataset 1', 'pie', [12, 19, 3, 5, 2, 3])->options([
            'title' => [
                'display' => 1,
                'text' => 'Pie Chart',
            ],
            'backgroundColor' => [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'],
            'borderColor'=> ['rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'],
            'borderWidth'=> 1,
        ]);

        return view('charts.index',compact('data','chart'));
    }
}
