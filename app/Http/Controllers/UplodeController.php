<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Tcpdf\Fpdi as TcpdfFpdi; // Correct class
use App\Models\Details;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class UplodeController extends Controller
{
    public function index()
    {
        return view('uplode');
    }

    public function uploadMultiplePdfs(Request $request)
    {
        // Validate the uploaded files
        $request->validate([
            'pdf_files.*' => 'required|mimes:pdf|max:10240', // Restrict to PDFs, max size 10MB per file
        ]);

        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $file) {
                // Use the original file name
                $filename = $file->getClientOriginalName();

                // Move the file directly to the public directory
                $file->move(public_path(), $filename);
            }

            // Redirect to the /print route after successful upload
            return redirect()->route('print')->with('success', 'PDF files uploaded successfully!');
        }

        // Redirect to the /print route in case of an error
        return redirect()->route('print')->with('error', 'Failed to upload PDF files.');
    }


}
