<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;
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
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.create_ajax')
            ->with('barang', $barang)
            ->with('user', $user);
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20',
            'penjualan_tanggal' => 'required|date',
            'barang_id.*' => 'required|integer',
            'harga.*' => 'required|numeric',
            'jumlah.*' => 'required|numeric',
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
                    'barang_id'    => $barang_id,
                    'harga'        => $request->harga[$i],
                    'jumlah'       => $jumlah
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
}
