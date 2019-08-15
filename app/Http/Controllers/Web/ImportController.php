<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\ImportFileCsvRequest;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use DB;
use Session;

use  App\Http\Controller\Web\Importing\CsvImporter;

class ImportController extends Controller
{
    public function importView()
    {
        return view('csv_excel_template.import');
    }

    public function import(ImportFileCsvRequest $request){

        if ($request->input('submit') != null ){
    
            $file = $request->file('file');
            // File Details 
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
    
            // Valid File Extensions
            $valid_extension = array("csv");

            // 2MB in Bytes
            $maxFileSize = 2097152; 

            // Check file extension
            if(in_array(strtolower($extension),$valid_extension)){
    
            // Check file size
            if($fileSize <= $maxFileSize) {
    
                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location,$fileName);

                // Import CSV to Database
                $filePath = public_path($location."/".$fileName);

                // Reading file
                $file = fopen($filePath,"r");

                $importData = [];
                $i = 0;
                $firstLine = fgetcsv($file);
                $line = 0;
                while (($filedata = fgetcsv($file)) !== FALSE) {
                foreach ($filedata as $key => $data) {
                    $importData[$line][$firstLine[$key]] = $data;
                }
                $line++;
                }
                fclose($file);
                //insert database
                \DB::table('users')->insert($importData);
                Session::flash('message', 'Successful !');
            }else {
              Session::flash('message', 'File too large. File must be less than 20MB !');
            }
    
            }else {
                Session::flash('message', 'Invalid File Extension !');
            }
        }
        return redirect(route('view.import'));
    }
}
