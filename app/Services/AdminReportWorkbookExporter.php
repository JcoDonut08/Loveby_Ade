<?php

namespace App\Services;

class AdminReportWorkbookExporter
{
    /**
     * @param  array<string, mixed>  $report
     */
    public function export(array $report): string
    {
        $columnCount = max(1, count($report['columns']));

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<?mso-application progid="Excel.Sheet"?>'."\n".
            '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'.
            $this->styles().
            '<Worksheet ss:Name="'.$this->xml($this->sheetName((string) $report['title'])).'"><Table>'.
            $this->columns($report['columns']).
            $this->titleRows($report, $columnCount).
            $this->summaryRows($report, $columnCount).
            $this->headerRow($report['columns']).
            $this->dataRows($report['columns'], $report['rows']).
            '</Table><WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><FreezePanes/><FrozenNoSplit/><SplitHorizontal>7</SplitHorizontal><TopRowBottomPane>7</TopRowBottomPane></WorksheetOptions></Worksheet>'.
            '</Workbook>';
    }

    private function styles(): string
    {
        return '<Styles>'.
            '<Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="11"/></Style>'.
            '<Style ss:ID="Title"><Font ss:FontName="Calibri" ss:Size="18" ss:Bold="1" ss:Color="#3b1728"/></Style>'.
            '<Style ss:ID="Subtitle"><Font ss:FontName="Calibri" ss:Size="10" ss:Color="#9a6c7b"/></Style>'.
            '<Style ss:ID="Meta"><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#512438"/><Interior ss:Color="#fff1f6" ss:Pattern="Solid"/></Style>'.
            '<Style ss:ID="Header"><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#ffffff"/><Interior ss:Color="#ec4899" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#f9a8cf"/></Borders></Style>'.
            '<Style ss:ID="Text"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#ffe7f1"/></Borders></Style>'.
            '<Style ss:ID="Number"><NumberFormat ss:Format="#,##0"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#ffe7f1"/></Borders></Style>'.
            '<Style ss:ID="Money"><NumberFormat ss:Format="&quot;PHP&quot; #,##0.00"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#ffe7f1"/></Borders></Style>'.
            '<Style ss:ID="Percent"><NumberFormat ss:Format="0.0%"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#ffe7f1"/></Borders></Style>'.
            '</Styles>';
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     */
    private function columns(array $columns): string
    {
        return collect($columns)
            ->map(fn (array $column): string => '<Column ss:Width="'.((int) ($column['width'] ?? 18) * 6).'"/>')
            ->implode('');
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function titleRows(array $report, int $columnCount): string
    {
        return '<Row ss:Height="28">'.
            '<Cell ss:MergeAcross="'.($columnCount - 1).'" ss:StyleID="Title"><Data ss:Type="String">'.$this->xml((string) $report['title']).'</Data></Cell>'.
            '</Row>'.
            '<Row ss:Height="20">'.
            '<Cell ss:MergeAcross="'.($columnCount - 1).'" ss:StyleID="Subtitle"><Data ss:Type="String">'.$this->xml((string) $report['subtitle']).'</Data></Cell>'.
            '</Row>'.
            '<Row ss:Height="20">'.
            '<Cell ss:MergeAcross="'.($columnCount - 1).'" ss:StyleID="Subtitle"><Data ss:Type="String">Range: '.$this->xml((string) $report['range_label']).' | Generated: '.$this->xml((string) $report['generated_at']).$this->searchLabel($report).'</Data></Cell>'.
            '</Row>'.
            '<Row/>';
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function summaryRows(array $report, int $columnCount): string
    {
        $cells = collect($report['summary'])
            ->flatMap(fn (array $item): array => [
                '<Cell ss:StyleID="Meta"><Data ss:Type="String">'.$this->xml((string) $item['label']).'</Data></Cell>',
                '<Cell ss:StyleID="Meta"><Data ss:Type="String">'.$this->xml((string) $item['value']).'</Data></Cell>',
            ])
            ->values()
            ->all();

        while (count($cells) < $columnCount) {
            $cells[] = '<Cell ss:StyleID="Meta"><Data ss:Type="String"></Data></Cell>';
        }

        return '<Row ss:Height="22">'.implode('', array_slice($cells, 0, $columnCount)).'</Row><Row/>';
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     */
    private function headerRow(array $columns): string
    {
        return '<Row ss:Height="22">'.collect($columns)
            ->map(fn (array $column): string => '<Cell ss:StyleID="Header"><Data ss:Type="String">'.$this->xml((string) $column['label']).'</Data></Cell>')
            ->implode('').'</Row>';
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function dataRows(array $columns, array $rows): string
    {
        if ($rows === []) {
            return '<Row><Cell ss:MergeAcross="'.(count($columns) - 1).'" ss:StyleID="Text"><Data ss:Type="String">No records found for this report.</Data></Cell></Row>';
        }

        return collect($rows)
            ->map(fn (array $row): string => '<Row>'.$this->cells($columns, $row).'</Row>')
            ->implode('');
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<string, mixed>  $row
     */
    private function cells(array $columns, array $row): string
    {
        return collect($columns)
            ->map(function (array $column) use ($row): string {
                $value = $row[$column['key']] ?? '';
                $type = (string) ($column['type'] ?? 'text');

                if (in_array($type, ['money', 'number', 'percent'], true)) {
                    $number = $type === 'percent' ? (float) $value / 100 : (float) $value;

                    return '<Cell ss:StyleID="'.$this->styleFor($type).'"><Data ss:Type="Number">'.$number.'</Data></Cell>';
                }

                return '<Cell ss:StyleID="Text"><Data ss:Type="String">'.$this->xml((string) $value).'</Data></Cell>';
            })
            ->implode('');
    }

    /**
     * @param  array<string, mixed>  $report
     */
    private function searchLabel(array $report): string
    {
        $search = trim((string) ($report['search'] ?? ''));

        return $search === '' ? '' : ' | Search: '.$this->xml($search);
    }

    private function styleFor(string $type): string
    {
        return match ($type) {
            'money' => 'Money',
            'percent' => 'Percent',
            default => 'Number',
        };
    }

    private function sheetName(string $title): string
    {
        return str($title)->replaceMatches('/[\[\]\:\*\?\/\\\\]/', '')->limit(31, '')->toString();
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
