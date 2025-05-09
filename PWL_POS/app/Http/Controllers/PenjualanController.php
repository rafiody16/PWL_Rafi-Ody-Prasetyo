<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpParser\Node\Stmt\Catch_;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar Penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $user = UserModel::all();

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with('user');

        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)

            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) { // menambahkan kolom aksi

                // $btn = '<a href="'.url('/penjualan/' . $penjualan->penjualan_id).'" class="btn btn-info btnsm">Detail</a> ';
                // $btn .= '<a href="'.url('/penjualan/' . $penjualan->penjualan_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/penjualan/'.$penjualan->penjualan_id).'">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                // return $btn;

                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create_ajax()
    {
        $barang = BarangModel::with('stok')
            ->whereHas('stok', function ($q) {
                $q->where('stok_jumlah', '>', 0);
            })
            ->select('barang_id', 'barang_nama', 'harga_jual')
            ->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.create_ajax')
            ->with('barang', $barang)
            ->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        foreach ($request->barang_id as $i => $barang_id) {
            $jumlah = $request->jumlah[$i];
            $stok = DB::table('t_stok')->where('barang_id', $barang_id)->value('stok_jumlah');
            $brg = DB::table('m_barang')->where('barang_id', $barang_id)->value('barang_nama');

            if ($stok < $jumlah) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => "Stok barang " . $brg . " tidak mencukupi. Sisa stok: " . $stok . ", dibutuhkan: " . $jumlah,
                ]);
            }
        }

        $rules = [
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20',
            'penjualan_tanggal' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        DB::beginTransaction();

        try {
            $penjualan = new PenjualanModel();
            $penjualan->user_id = $request->user_id;
            $penjualan->pembeli = $request->pembeli;
            $penjualan->penjualan_kode = $request->penjualan_kode;
            $penjualan->penjualan_tanggal = $request->penjualan_tanggal;
            $penjualan->save();

            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah = $request->jumlah[$i];
                PenjualanDetailModel::create([
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id' => $barang_id,
                    'jumlah' => $jumlah ?? 0,
                    'harga' => $request->harga[$i] * $jumlah ?? 0,
                ]);

                DB::table('t_stok')->where('barang_id', $barang_id)->decrement('stok_jumlah', $jumlah);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Simpan Penjualan', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data',
                'msgField' => $e->getMessage(),
            ], 500);
        }
    }

    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);

        return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
    }

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);
        $user = UserModel::select('user_id', 'nama')->get();
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('penjualan.edit_ajax', ['penjualan' => $penjualan, 'user' => $user, 'barang' => $barang]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:50',
                'penjualan_kode' => 'required|string|max:20',
                'penjualan_tanggal' => 'required|date',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            DB::beginTransaction();

            try {
                $penjualan = PenjualanModel::find($id);
                if (!$penjualan) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }

                $detailLama = PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->get();
                foreach ($detailLama as $d) {
                    DB::table('t_stok')->where('barang_id', $d->barang_id)->increment('stok_jumlah', $d->jumlah);
                }

                PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();

                foreach ($request->barang_id as $i => $barang_id) {
                    $jumlah = $request->jumlah[$i];
                    $stok = DB::table('t_stok')->where('barang_id', $barang_id)->value('stok_jumlah');
                    $brg = DB::table('m_barang')->where('barang_id', $barang_id)->value('barang_nama');

                    if ($stok < $jumlah) {
                        DB::rollBack();
                        return response()->json([
                            'status' => false,
                            'message' => "Stok barang \"$brg\" tidak mencukupi. Sisa stok: $stok, dibutuhkan: $jumlah"
                        ]);
                    }
                }

                $penjualan->update([
                    'user_id' => $request->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode,
                    'tanggal_penjualan' => $request->penjualan_tanggal,
                ]);

                foreach ($request->barang_id as $i => $barang_id) {
                    $jumlah = $request->jumlah[$i];
                    $harga = $request->harga[$i];

                    PenjualanDetailModel::create([
                        'penjualan_id' => $penjualan->penjualan_id,
                        'barang_id' => $barang_id,
                        'jumlah' => $jumlah,
                        'harga' => $harga * $jumlah,
                    ]);

                    DB::table('t_stok')->where('barang_id', $barang_id)->decrement('stok_jumlah', $jumlah);
                }

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = PenjualanModel::with('penjualanDetail')->find($id);
            if ($penjualan) {
                PenjualanDetailModel::where('penjualan_id', $penjualan->penjualan_id)->delete();
                $penjualan->delete();
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_penjualan'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];

            DB::beginTransaction();
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $kode = $value['A'];

                        if (!isset($insert[$kode])) {
                            $penjualan = PenjualanModel::create([
                                'penjualan_kode' => $kode,
                                'pembeli' => $value['B'],
                                'user_id' => $value['C'],
                                'penjualan_tanggal' => is_numeric($value['D'])
                                    ? Date::excelToDateTimeObject($value['D'])->format('Y-m-d')
                                    : Carbon::parse($value['D'])->format('Y-m-d'),
                            ]);
                            $insert[$kode] = $penjualan->penjualan_id;
                        }

                        PenjualanDetailModel::create([
                            'penjualan_id' => $insert[$kode],
                            'barang_id' => $value['E'],
                            'jumlah' => $value['F'],
                            'harga' => $value['G'] * $value['F']
                        ]);
                        DB::table('t_stok')->where('barang_id', $value['E'])->decrement('stok_jumlah', $value['F']);
                    }
                }
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_pdf()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->with('user')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'potrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function export_excel()
    {
        $penjualan = PenjualanModel::select('user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->orderBy('user_id')
            ->with('user')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Penjualan Kode');
        $sheet->setCellValue('E1', 'Penjualan Tanggal');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $no = 1;
        $baris = 2;
        foreach ($penjualan as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->user->nama);
            $sheet->setCellValue('C' . $baris, $value->pembeli);
            $sheet->setCellValue('D' . $baris, $value->penjualan_kode);
            $sheet->setCellValue('E' . $baris, $value->penjualan_tanggal);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $write = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $write->save('php://output');
        exit;
    }

    public function export_pdfdtl(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);

        $pdf = Pdf::loadView('penjualan.export_pdfdtl', ['penjualan' => $penjualan]);
        $pdf->setPaper('a4', 'potrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Detail Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }

    public function export_exceldtl(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail')->find($id);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', 'ID:');
        $sheet->setCellValue('B2', $penjualan->penjualan_id);
        $sheet->setCellValue('D2', 'User:');
        $sheet->setCellValue('E2', $penjualan->user->nama);
        $sheet->setCellValue('G2', 'Penjualan Kode:');
        $sheet->setCellValue('H2', $penjualan->penjualan_kode);
        $sheet->setCellValue('A4', 'Pembeli:');
        $sheet->setCellValue('B4', $penjualan->pembeli);
        $sheet->setCellValue('D4', 'Tanggal Penjualan:');
        $sheet->setCellValue('E4', $penjualan->penjualan_tanggal);

        $sheet->getStyle('A2:A4')->getFont()->setBold(true);
        $sheet->getStyle('D2:D4')->getFont()->setBold(true);
        $sheet->getStyle('G2')->getFont()->setBold(true);

        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Barang');
        $sheet->setCellValue('C6', 'Jumlah');
        $sheet->setCellValue('D6', 'Harga');

        $sheet->getStyle('A6:F6')->getFont()->setBold(true);
        $no = 1;
        $baris = 7;
        $totalHarga = 0;
        foreach ($penjualan->penjualanDetail as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('C' . $baris, $value->jumlah);
            $sheet->setCellValue('D' . $baris, $value->harga);
            $baris++;
            $no++;
            $totalHarga += $value->harga;
        }

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->setCellValue('C' . $baris, 'Total');
        $sheet->setCellValue('D' . $baris, $totalHarga);
        $sheet->getStyle('C' . $baris . ':D' . $baris)->getFont()->setBold(true);

        $lastRow = $baris;
        $sheet->getStyle('A6:D' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        $sheet->setTitle('Data Detail Penjualan');

        $write = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Detail Penjualan' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $write->save('php://output');
        exit;
    }
}
