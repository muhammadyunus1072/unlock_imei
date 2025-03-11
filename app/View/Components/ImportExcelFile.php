<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImportExcelFile extends Component
{
    public $import_excel;

    /**
     * Create a new component instance.
     *
     * @param array $import_excel
     */
    public function __construct($import_excel = null)
    {
        $this->import_excel = $import_excel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.import-excel-file');
    }
}
