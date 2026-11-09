
<?php

/**
 * Generador PDF
 */

class PdfReportGenerator
{
    private $pages = [];
    private $currentPage = '';

    private $pageWidth = 842;
    private $pageHeight = 595;

    public function __construct($orientation = 'landscape')
    {
        if ($orientation === 'portrait') {
            $this->pageWidth = 595;
            $this->pageHeight = 842;
        }
    }

    /**
     * Crea una nueva página.
     */
    public function addPage()
    {
        if ($this->currentPage !== '') {
            $this->pages[] = $this->currentPage;
        }

        $this->currentPage = '';

        // Fondo blanco.
        $this->currentPage .=
            "1 1 1 rg 0 0 {$this->pageWidth} {$this->pageHeight} re f\n";

        // Línea amarilla superior.
        $this->currentPage .=
            "0.98 0.78 0.08 RG 40 580 m "
            . ($this->pageWidth - 40)
            . " 580 l S\n";
    }

    /**
     * Agrega el texto
     */
    public function addText(
        $x,
        $y,
        $text,
        $size = 10,
        $color = [0.12, 0.15, 0.20],
        $bold = false
    ) {
        $font = $bold ? '/F2' : '/F1';

        $color = $this->normalizarColor($color);
        $text = $this->escaparTexto($text);

        $this->currentPage .= sprintf(
            "BT %s %s Tf %s rg 1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n",
            $font,
            (int) $size,
            $color,
            $x,
            $y,
            $text
        );
    }

    /**
     * línea
     */
    public function addLine(
        $x1,
        $y1,
        $x2,
        $y2,
        $color = [0.8, 0.8, 0.8],
        $width = 0.6
    ) {
        $color = $this->normalizarColor($color);

        $this->currentPage .= sprintf(
            "%s RG %.2f w %.2f %.2f m %.2f %.2f l S\n",
            $color,
            $width,
            $x1,
            $y1,
            $x2,
            $y2
        );
    }

    /**
     * encabezado la tabla
     */
    public function drawTableHeader($x, $y, $columns)
    {
        $totalWidth = array_sum(
            array_column($columns, 'width')
        );

        $this->currentPage .= sprintf(
            "0.08 0.12 0.20 rg %.2f %.2f %.2f 16 re f\n",
            $x,
            $y - 4,
            $totalWidth
        );

        $currentX = $x + 5;

        foreach ($columns as $column) {

            $this->addText(
                $currentX,
                $y,
                $column['text'],
                8,
                [1, 1, 1],
                true
            );

            $currentX += $column['width'];
        }
    }

    /**
     * fila de datos.
     */
    public function drawTableRow(
        $x,
        $y,
        $values,
        $widths
    ) {
        $currentX = $x + 5;

        foreach ($values as $index => $value) {

            $width = $widths[$index] ?? 100;

            $value = $this->recortarTexto(
                (string) $value,
                $width
            );

            $this->addText(
                $currentX,
                $y,
                $value,
                8,
                [0.12, 0.15, 0.20]
            );

            $currentX += $width;
        }

        $this->addLine(
            $x,
            $y - 5,
            $x + array_sum($widths),
            $y - 5,
            [0.86, 0.88, 0.90],
            0.35
        );
    }

    /**
     * Genera y descarga el archivo PDF.
     */
    public function output($filename)
    {
        if ($this->currentPage !== '') {
            $this->pages[] = $this->currentPage;
            $this->currentPage = '';
        }

        $objects = [];

        $pageCount = count($this->pages);

        $fontRegularId = 3 + ($pageCount * 2);
        $fontBoldId = $fontRegularId + 1;

        $kids = [];

        $objects[1] =
            '<< /Type /Catalog /Pages 2 0 R >>';

        foreach ($this->pages as $index => $content) {

            $pageId = 3 + ($index * 2);
            $contentId = $pageId + 1;

            $kids[] = $pageId . ' 0 R';

            $objects[$pageId] = sprintf(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %d %d] /Resources << /Font << /F1 %d 0 R /F2 %d 0 R >> >> /Contents %d 0 R >>',
                $this->pageWidth,
                $this->pageHeight,
                $fontRegularId,
                $fontBoldId,
                $contentId
            );

            $streamLength = strlen($content);

            $objects[$contentId] =
                "<< /Length {$streamLength} >>\n"
                . "stream\n"
                . $content
                . "\nendstream";
        }

        $objects[2] =
            '<< /Type /Pages /Kids ['
            . implode(' ', $kids)
            . '] /Count '
            . $pageCount
            . ' >>';

        $objects[$fontRegularId] =
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';

        $objects[$fontBoldId] =
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        ksort($objects);

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";

        $offsets = [0];

        foreach ($objects as $id => $object) {

            $offsets[$id] = strlen($pdf);

            $pdf .= $id
                . " 0 obj\n"
                . $object
                . "\nendobj\n";
        }

        $xrefPosition = strlen($pdf);

        $maxId = max(array_keys($objects));

        $pdf .= "xref\n0 " . ($maxId + 1) . "\n";

        $pdf .= "0000000000 65535 f \n";

        for ($id = 1; $id <= $maxId; $id++) {

            $offset = $offsets[$id] ?? 0;

            $pdf .= sprintf(
                "%010d 00000 n \n",
                $offset
            );
        }

        $pdf .=
            "trailer\n<< /Size "
            . ($maxId + 1)
            . " /Root 1 0 R >>\n";

        $pdf .=
            "startxref\n"
            . $xrefPosition
            . "\n%%EOF";

        // Evita que contenido de HTML interfiera con el PDF.
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');

        header(
            'Content-Disposition: attachment; filename="'
            . basename($filename)
            . '"'
        );

        header('Content-Length: ' . strlen($pdf));

        header(
            'Cache-Control: private, max-age=0, must-revalidate'
        );

        header('Pragma: public');

        echo $pdf;
    }

    /**
     * Convierte los colores a formato RGB.
     */
    private function normalizarColor($color)
    {
        $r = max(0, min(1, (float) ($color[0] ?? 0)));
        $g = max(0, min(1, (float) ($color[1] ?? 0)));
        $b = max(0, min(1, (float) ($color[2] ?? 0)));

        return sprintf(
            '%.3f %.3f %.3f',
            $r,
            $g,
            $b
        );
    }

    /**
     * Prepara el texto para insertarlo en el PDF.
     */
    private function escaparTexto($text)
    {
        $text = html_entity_decode(
            $text,
            ENT_QUOTES,
            'UTF-8'
        );

        if (function_exists('iconv')) {

            $convertido = iconv(
                'UTF-8',
                'Windows-1252//TRANSLIT//IGNORE',
                $text
            );

            if ($convertido !== false) {
                $text = $convertido;
            }
        }

        return str_replace(
            ["\\", "(", ")", "\r", "\n"],
            ["\\\\", "\\(", "\\)", " ", " "],
            $text
        );
    }

    /**
     * Evita que los textos sobrepasen el ancho de las columnas.
     */
    private function recortarTexto($text, $width)
    {
        $maxChars = max(
            8,
            (int) floor(($width - 12) / 4.2)
        );

        if (function_exists('iconv')) {

            $longitud = iconv_strlen(
                $text,
                'UTF-8'
            );

            if (
                $longitud !== false
                && $longitud > $maxChars
            ) {
                $text = iconv_substr(
                    $text,
                    0,
                    $maxChars - 3,
                    'UTF-8'
                ) . '...';
            }

        } elseif (strlen($text) > $maxChars) {

            $text = substr(
                $text,
                0,
                $maxChars - 3
            ) . '...';
        }

        return $text;
    }
}
