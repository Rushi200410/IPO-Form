<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Tcpdf\Fpdi as TcpdfFpdi; // Correct class
use App\Models\Details;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
class DemoController extends Controller
{
    public function index()
    {
        // $url = "";
        $url = route('form');

        $title = "IPO Form Auto Fill";
        $receipt = '';
        $donor = '';


        $users = Details::all();

        $users_data = [];
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $users_data[$user->id] = $user->name;
            }
        }

        $path = public_path(); // Path to the public folder
        $pdfFiles = File::files($path); // Retrieve all files from the public folder

        // Filter only PDF files
        $pdfFiles = array_filter($pdfFiles, function ($file) {
            return $file->getExtension() === 'pdf';
        });

        // Get the filenames only
        $pdfFileNames = array_map(function ($file) {
            return $file->getFilename();
        }, $pdfFiles);

        $data = compact('url','title', 'receipt', 'donor', 'users_data', 'pdfFileNames');

        return view('form')->with($data);
    }

    public function form(Request $request)
    {

        $filePath = public_path($request->input('FormNo'));
        // echo $filePath;
        // die();
        $outputFilePath = public_path("sample_output.pdf");

        // Retrieve data from request
        $lot_size = $request->input('lot_size');
        $price = $request->input('price');
        $no_of_lot = $request->input('no_of_lot');
        $id = $request->input('user');

        $name = Details::where('id', $id)->value('name');

        // Call your custom function to modify the PDF
        $this->fillForm($filePath, $outputFilePath, $id, $lot_size, $price, $no_of_lot);

        // Return the generated PDF as a download response
        return Response::download($outputFilePath, $name.".pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function fillForm($file, $outputFilePath, $id, $lot_size, $price, $no_of_lot)
    {
        $details = Details::find($id);

        $rs = $lot_size * $price * $no_of_lot;
        $no_of_shares = $lot_size * $no_of_lot;
        $rs_in_words = $this->convertNumberToWords($rs);

        $pdf = new TcpdfFpdi();  // Use TcpdfFpdi instead of Tcpdf and Fpdi separately

        // Set the source PDF file and import only the first page
        $pdf->setSourceFile($file);
        $template = $pdf->importPage(1);  // Import only the first page
        $size = $pdf->getTemplateSize($template);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($template);

        // Print multiple different texts with different widths, font sizes, and positions
        $texts = [
            ['text' => $details->name, 'left' => 120, 'top' => 35, 'font_size' => 12, 'width' => 85, 'char_spacing' => 0, 'align' => 'L'], // Align Left
            ['text' => '   '. $details->address, 'left' => 108, 'top' => 45, 'font_size' => 10, 'width' => 100, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->email, 'left' => 157, 'top' => 49, 'font_size' => 10, 'width' => 60, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->phone_no, 'left' => 149, 'top' => 53, 'font_size' => 12, 'width' => 70, 'char_spacing' => 5.7, 'align' => ''],
            ['text' => $details->pan, 'left' => 110, 'top' => 64.5, 'font_size' => 14, 'width' => 96, 'char_spacing' => 10, 'align' => ''],
            ['text' => $details->dmait_acc, 'left' => 7, 'top' => 77, 'font_size' => 14, 'width' => 96, 'char_spacing' => 9.8, 'align' => ''],

            ['text' => $no_of_shares, 'left' => 41.5, 'top' => 108.5, 'font_size' => 10, 'width' => 22, 'char_spacing' => 6.4, 'align' => ''],
            // ['text' => $price, 'left' => 75, 'top' => 108.5, 'font_size' => 10, 'width' => 22, 'char_spacing' => 0, 'align' => 'L'],

            ['text' => $rs, 'left' => 40, 'top' => 128.5, 'font_size' => 10, 'width' => 96, 'char_spacing' => 4.6, 'align' => ''],
            ['text' => $rs_in_words, 'left' => 102, 'top' => 128.5, 'font_size' => 8, 'width' => 105, 'char_spacing' => 0, 'align' => 'L'], // Align Left
            ['text' => $details->saving_acc, 'left' => 24, 'top' => 135, 'font_size' => 10, 'width' => 96, 'char_spacing' => 4, 'align' => ''],
            ['text' => $details->bank_name, 'left' => 30, 'top' => 141, 'font_size' => 9, 'width' => 160, 'char_spacing' => 0, 'align' => 'L'], // Align Left
            ['text' => $details->dmait_acc, 'left' => 12, 'top' => 212, 'font_size' => 14, 'width' => 96, 'char_spacing' => 7.7, 'align' => ''],
            ['text' => $details->pan, 'left' => 137, 'top' => 212, 'font_size' => 14, 'width' => 96, 'char_spacing' => 7.2, 'align' => ''],
            ['text' => $rs, 'left' => 35, 'top' => 222.5, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->saving_acc, 'left' => 90, 'top' => 222.5, 'font_size' => 10, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->bank_name, 'left' => 30, 'top' => 227.5, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => 'L'],
            ['text' => $details->name, 'left' => 33, 'top' => 232.5, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => 'L'],
            ['text' => $details->phone_no, 'left' => 33, 'top' => 238, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->email, 'left' => 90, 'top' => 238, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->name, 'left' => 143, 'top' => 251.8, 'font_size' => 9, 'width' => 63, 'char_spacing' => 0, 'align' => 'L'],
            ['text' => $rs, 'left' => 46, 'top' => 263, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->saving_acc, 'left' => 45, 'top' => 268, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => ''],
            ['text' => $details->bank_name, 'left' => 40, 'top' => 272.5, 'font_size' => 9, 'width' => 96, 'char_spacing' => 0, 'align' => 'L'],
        ];











        // Loop through and add the texts to the first page with wrapping and simulated character spacing
        foreach ($texts as $textItem) {
            // Check if all necessary keys are present
            if (!isset($textItem['text'], $textItem['left'], $textItem['top'], $textItem['font_size'], $textItem['width'], $textItem['char_spacing'], $textItem['align'])) {
                throw new \Exception('Invalid data structure in $texts array. Missing key(s) in: ' . json_encode($textItem));
            }

            // Ensure that 'text' is always a string
            $textItem['text'] = (string) $textItem['text'];

            $pdf->SetFont("helvetica", "", $textItem['font_size']);

            // If the field should be aligned left, set alignment
            if ($textItem['align'] === 'L') {
                $pdf->SetXY($textItem['left'], $textItem['top']);
                $pdf->MultiCell($textItem['width'], 0, $textItem['text'], 0, 'L'); // Align Left
            }
            // If character spacing is defined, simulate it by printing each character separately
            else if ($textItem['char_spacing'] > 0) {
                $xPos = $textItem['left'];
                $pdf->SetXY($xPos, $textItem['top']);

                // Ensure $textItem['text'] is not empty before looping
                if (!empty($textItem['text']) && is_string($textItem['text'])) {
                    for ($i = 0; $i < strlen($textItem['text']); $i++) {
                        $pdf->Text($xPos, $textItem['top'], $textItem['text'][$i]);
                        $xPos += $textItem['char_spacing']; // Move the cursor by the spacing value
                    }
                }
            } else {
                $pdf->SetXY($textItem['left'], $textItem['top']);
                $pdf->MultiCell($textItem['width'], 0, $textItem['text']);
            }
        }













        // Output the modified PDF, only containing the first page
        return $pdf->Output($outputFilePath, 'F');
    }

    function convertNumberToWords($rs)
    {
        $no = floor($rs);
        $point = round($rs - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety'
        );
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $rs = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;

            if ($rs) {
                $plural = (($counter = count($str)) && $rs > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($rs < 21) ? $words[$rs] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($rs / 10) * 10]
                    . " " . $words[$rs % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }
        }

        $str = array_reverse($str);
        $result = implode('', $str);

        $points = ($point) ?
            "." . $words[$point / 10] . " " .
                  $words[$point = $point % 10] : '';

        return $result . "Rupees " . $points . "Only/-";
    }

}



// INSERT INTO `details` (`id`, `name`, `address`, `pan`, `email`, `phone_no`, `dmait_acc`, `saving_acc`, `bank_name`) VALUES (NULL, 'Ashish S Bhansali', '302 Jainam enclave Jambali gully Borivali(w)', 'AAIP38586A', 'bhansali1974@gmail.com', '9820989476', '1201090025909519', '50100168615293', 'HDFC Bank Borivali(w)');
