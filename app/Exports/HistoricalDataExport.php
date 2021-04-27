<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HistoricalDataExport implements WithMultipleSheets
{
    use Exportable;

    protected $search;
    
    public function __construct($search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->search->key_metrics as $key_metric) {
            switch ($key_metric) {
                case 'revenue':
                    $sheets[] = new RevenueExport($this->search, 'Revenue');
                    break;
                case 'miles-total':
                    $sheets[] = new MileTotalExport($this->search, 'Total Miles');
                    break;
                case 'miles-driver':
                    $sheets[] = new MileDriverExport($this->search, 'Miles by driver');
                    break;
                case 'miles-vehicle':
                    $sheets[] = new MileVehicleExport($this->search, 'Miles by vehicle');
                    break;
                case 'trips-driver':
                    $sheets[] = new TripsDriverExport($this->search, 'Trips by driver');
                    break;
                case 'mpg-vehicle':
                    $sheets[] = new MpgVehicleExport($this->search, 'MPG by vehicle');
                    break;
                case 'fuelcost-total':
                    $sheets[] = new FuelcostTotalExport($this->search, 'Total Fuel Cost');
                    break;
            }
        }

        return $sheets;
    }
}