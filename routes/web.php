<?php

use function Psy\debug;
use Illuminate\Support\Str;
use App\Exports\CfdisExport;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use ErlandMuchasaj\LaravelFileUploader\FileUploader;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{filename?}', function ($fileName = null) {
    return view('index', compact('fileName'));
})->name('files.index');

Route::post('/export', function (Request $request) {
    $max_size = (int) ini_get('upload_max_filesize') * 1000;

    $extensions = implode(',', array('application/xml', 'application/xhtml+xml', 'xml', 'text/xml'));

    $request->validate([
        'files' => [
            'required',
            'max:' . $max_size,
        ]
    ]);

    $files = $request->file('files');
    $cfdis = collect();

    foreach ($files as $xml) {
        $cfdi = \CfdiUtils\Cfdi::newFromString($xml->get());
        $cfdis->push($cfdi);
    }

    $export = new CfdisExport($cfdis);

    $fileName = Str::orderedUuid();

    if (!empty($request['filename'])) {
        $fileName = $request['filename'];
    }

    Excel::store($export, $fileName . '.xlsx', 'public');

    return redirect()->route('files.index', ['filename' => $fileName]);
})->name('files.store');

Route::get('/remove/{filename}', function($fileName = null) {
    if (empty($fileName)) {
        return redirect()->route('files.index')->with('error', 'No existe el archivo.');
    }

    Storage::disk('public')->delete($fileName . '.xlsx');

    return redirect()->route('files.index');
})->name('files.remove');
