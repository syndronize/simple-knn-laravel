<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $type;
    protected $data; // <-- add this

    public function __construct($type)
    {
        $this->type = $type;

        // Query data in constructor so it's available for both view() and styles()
        $query = \DB::table('followup as t1')
            ->join(
                \DB::raw('(SELECT lead_id, MAX(followup_ke) AS max_followup_ke FROM followup GROUP BY lead_id) as t2'),
                function ($join) {
                    $join->on('t1.lead_id', '=', 't2.lead_id')
                        ->on('t1.followup_ke', '=', 't2.max_followup_ke');
                }
            )
            ->leftJoin('leads as ls', 'ls.id', '=', 't1.lead_id')
            ->leftJoin('industry', 'industry.id', '=', 'ls.industry_id')
            ->select(
                't1.lead_id',
                't1.followup_ke',
                'ls.name',
                'ls.email',
                'ls.notelp',
                'ls.industry_id',
                'industry.name as indname',
                'ls.alamat',
                'ls.type'
            );

        // If you want to filter by type, make sure you join with product table and use correct field
        if ($this->type) {
            // Make sure you join with product table if needed!
            $query->where('ls.type', $this->type);
        }

        $this->data = $query->get();
    }

    public function view(): View
    {
        return view('exports.leads.index', [
            'data' => $this->data,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Number of data rows (+4 for header rows)
        $rowCount = count($this->data) + 2;

        // Borders for all cells
        $sheet->getStyle('A2:G' . $rowCount)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Bold headers
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        // Center align header row
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal('center');
        // Optionally, make title bold and bigger
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        return [];
    }
}
