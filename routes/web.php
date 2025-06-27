<?php

use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\RpsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Admin\ProfileController;
// use App\Http\Controllers\Admin\PenilaianController as AdminPenilaianController;
use App\Http\Controllers\Admin\JenisCplController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;

// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CplController as AdminCplController;
use App\Http\Controllers\Dosen\RpsController as DosenRpsController;
use App\Http\Controllers\Admin\CpmkController as AdminCpmkController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;

use App\Http\Controllers\Admin\NilaiController as AdminNilaiController;
use App\Http\Controllers\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Admin\SubCpmkController as AdminSubCpmkController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;

use App\Http\Controllers\Mahasiswa\NilaiController as MahasiswaNilaiController;
use App\Http\Controllers\Dosen\MataKuliahController as DosenMataKuliahController;
use App\Http\Controllers\Admin\PerkuliahanController as AdminPerkuliahanController;
use App\Http\Controllers\Dosen\PerkuliahanController as DosenPerkuliahanController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\MataKuliahController as MahasiswaMataKuliahController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\Admin\SubCpmkController as AdminSubCpmkController;

// Route::get('/', [DashboardController::class, 'index']);

Route::get('/', function () {
    $user = Auth::user();

    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role == 'dosen') {
        return redirect()->route('dosen.dashboard');
    } else {
        return redirect()->route('mahasiswa.dashboard');
    }
})->middleware('auth');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showFormLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth'], function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'role:admin'], function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/dashboard/chart-cpl', [AdminController::class, 'chartCplDashboard'])->name('admin.dashboard.chartcpl');
        Route::get('/admin/dashboard/chart-cpl-prodi', [AdminController::class, 'chartCplByAngkatan'])->name('admin.dashboard.chartcplprodi');
        Route::get('/admin/dashboard/chart-cpl-kelas', [AdminController::class, 'chartCplKelasDashboard'])->name('admin.dashboard.chartcplkelas');
        Route::get('/admin/dashboard/chart-cpl-smt', [AdminController::class, 'chartCplSmtDashboard'])->name('admin.dashboard.chartcplsmt');

        Route::prefix('admin/user')->group(function () {
            Route::get('/{id}', [ProfileController::class, 'show'])->name('admin.user');
            Route::get('edit/{id}', [ProfileController::class, 'edit'])->name('admin.user.edit');
            Route::put('edit/{id}', [ProfileController::class, 'update'])->name('user.proses.edit');
            Route::get('profile/changePass', [ProfileController::class, 'showFormChangePass'])->name('admin.user.changePass');
            Route::post('profile/changePass', [ProfileController::class, 'changePassword'])->name('changePass');
        });

        Route::prefix('admin/mahasiswa')->group(function () {
            Route::get('', [AdminMahasiswaController::class, 'index'])->name('admin.mahasiswa');
            Route::get('create', [AdminMahasiswaController::class, 'create'])->name('admin.mahasiswa.create');
            Route::post('create', [AdminMahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
            Route::get('/{id}', [AdminMahasiswaController::class, 'show'])->name('admin.mahasiswa.show');
            Route::get('edit/{id}', [AdminMahasiswaController::class, 'edit'])->name('admin.mahasiswa.edit');
            Route::put('edit/{id}', [AdminMahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
            Route::delete('{id}', [AdminMahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');
            Route::post('delete-multiple', [AdminMahasiswaController::class, 'deleteMultiple'])->name('admin.mahasiswa.destroyMultiple');
            Route::get('excel/download', [AdminMahasiswaController::class, 'downloadExcel'])->name('admin.mahasiswa.download-excel');
            Route::post('excel/import', [AdminMahasiswaController::class, 'importExcel'])->name('admin.mahasiswa.import-excel');
            Route::post('reset-password', [AdminMahasiswaController::class, 'resetPassword'])->name('admin.mahasiswa.reset-password');
        });

        Route::prefix('admin/dosen')->group(function () {
            Route::get('', [AdminDosenController::class, 'index'])->name('admin.dosen');
            Route::get('create', [AdminDosenController::class, 'create'])->name('admin.dosen.create');
            Route::post('create', [AdminDosenController::class, 'store'])->name('admin.dosen.store');
            // Route::get('/{id}', [AdminDosenController::class, 'show'])->name('admin.dosen.show');
            Route::get('edit/{id}', [AdminDosenController::class, 'edit'])->name('admin.dosen.edit');
            Route::put('edit/{id}', [AdminDosenController::class, 'update'])->name('admin.dosen.update');
            Route::delete('{id}', [AdminDosenController::class, 'destroy'])->name('admin.dosen.destroy');
            Route::post('delete-multiple', [AdminDosenController::class, 'deleteMultiple'])->name('admin.dosen.destroyMultiple');
            Route::get('download-excel', [AdminDosenController::class, 'downloadExcel'])->name('admin.dosen.download-excel');
            Route::post('import-excel', [AdminDosenController::class, 'importExcel'])->name('admin.dosen.import-excel');
            Route::post('reset-password', [AdminDosenController::class, 'resetPassword'])->name('admin.dosen.reset-password');
        });

        Route::prefix('admin/mata-kuliah')->group(function () {
            Route::get('', [MataKuliahController::class, 'index'])->name('admin.matakuliah');
            Route::get('create', [MataKuliahController::class, 'create'])->name('admin.matakuliah.create.matkul');
            Route::post('create', [MataKuliahController::class, 'store'])->name('admin.matakuliah.store');
            // Route::get('/{id}', [MataKuliahController::class, 'show'])->name('admin.matakuliah.show');
            Route::get('edit/{id}', [MataKuliahController::class, 'edit'])->name('admin.matakuliah.edit');
            Route::put('edit/{id}', [MataKuliahController::class, 'update'])->name('admin.matakuliah.update');
            Route::delete('{id}', [MataKuliahController::class, 'destroy'])->name('admin.matakuliah.destroy');
            // Route::get('detail/cpl', [MataKuliahController::class, 'detailCpl']);
            // Route::get('detail/cpmk', [MataKuliahController::class, 'detailCpmk']);
            // Route::get('detail/sub-cpmk', [MataKuliahController::class, 'detailSubCpmk']);
            // Route::get('detail/tugas', [MataKuliahController::class, 'detailTugas']);
        });

        Route::prefix('admin/rps')->group(function () {
            Route::get('', [RpsController::class, 'index'])->name('admin.rps');
            Route::get('create', [RpsController::class, 'create'])->name('admin.rps.create');
            Route::post('create', [RpsController::class, 'store'])->name('admin.rps.store');
            Route::get('edit/{id}', [RpsController::class, 'edit'])->name('admin.rps.edit');
            Route::put('edit/{id}', [RpsController::class, 'update'])->name('admin.rps.update');
            Route::delete('{id}', [RpsController::class, 'destroy'])->name('admin.rps.destroy');
            Route::get('/{id}', [RpsController::class, 'show'])->name('admin.rps.show');
            Route::get('detail/cpl', [RpsController::class, 'detailCpl']);
            Route::get('detail/cpmk', [RpsController::class, 'detailCpmk']);
            Route::get('detail/sub-cpmk', [RpsController::class, 'detailSubCpmk']);
            Route::get('detail/tugas', [RpsController::class, 'detailTugas']);

            Route::post('create/cpmk/{id}', [RpsController::class, 'storecpmk'])->name('admin.rps.storecpmk');
            Route::post('create/subcpmk/{id}', [RpsController::class, 'storesubcpmk'])->name('admin.rps.storesubcpmk');
            Route::post('create/soal/{id}', [RpsController::class, 'storesoal'])->name('admin.rps.storesoal');
            Route::get('create/{id}', [RpsController::class, 'createDetail'])->name('admin.rpsDetail.create');
            Route::delete('deletecpmk/{id}', [RpsController::class, 'destroyCpmk'])->name('admin.rps.destroycpmk');
            Route::delete('deletecpmk/sub/{id}', [RpsController::class, 'destroySubCpmk'])->name('admin.rps.destroysubcpmk');
            Route::delete('deletecpmk/sub/soal/{id}', [RpsController::class, 'destroySoal'])->name('admin.rps.destroysoal');
            // Route::get('create', [RpsController::class, 'create'])->name('admin.matakuliah.add');
            Route::get('editcpmk/{id}', [RpsController::class, 'editCpmk'])->name('admin.rps.editcpmk');
            Route::put('updatecpmk', [RpsController::class, 'updateCpmk'])->name('admin.rps.updatecpmk');
            Route::get('editsubcpmk/{id}', [RpsController::class, 'editSubCpmk'])->name('admin.rps.editsubcpmk');
            Route::put('updatesubcpmk', [RpsController::class, 'updateSubCpmk'])->name('admin.rps.updatesubcpmk');
            Route::get('editsoalsubcpmk/{id}', [RpsController::class, 'editSoalSubCpmk'])->name('admin.rps.editsoalsubcpmk');
            Route::put('updatesoalsubcpmk', [RpsController::class, 'updateSoalSubCpmk'])->name('admin.rps.updatesoalsubcpmk');

            Route::get('listsubcpmk/{id}', [RpsController::class, 'listSubCpmk'])->name('admin.rps.listsubcpmk');
            Route::get('listcpmk/{id}', [RpsController::class, 'listCpmk'])->name('admin.rps.listcpmk');
            Route::get('listtugas/{id}', [RpsController::class, 'listTugas'])->name('admin.rps.listtugas');

            Route::get('listsubcpmk/input/{id}', [RpsController::class, 'listKodeSubCpmk'])->name('admin.rps.listkodesubcpmk');
            Route::get('listcpmk/input/{id}', [RpsController::class, 'listKodeCpmk'])->name('admin.rps.listkodecpmk');

            Route::get('create/{id}/download-excel', [RpsController::class, 'export'])->name('admin.rps.download-excel');
            Route::post('create/import-excel/{id}', [RpsController::class, 'import'])->name('admin.rps.import-excel');
        });

        Route::prefix('admin/kelas')->group(function () {
            Route::get('', [KelasController::class, 'index'])->name('admin.kelas');
            Route::get('create', [KelasController::class, 'create'])->name('admin.kelas.create');
            Route::post('create', [KelasController::class, 'store'])->name('admin.kelas.store');
            // Route::get('/{id}', [KelasController::class, 'show'])->name('admin.kelas.show');
            Route::get('edit/{id}', [KelasController::class, 'edit'])->name('admin.kelas.edit');
            Route::put('edit/{id}', [KelasController::class, 'update'])->name('admin.kelas.update');
            Route::delete('{id}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');
        });

        Route::prefix('admin/cpl')->group(function () {
            Route::get('', [AdminCplController::class, 'index'])->name('admin.cpl');
            Route::get('create', [AdminCplController::class, 'create'])->name('admin.cpl.create');
            Route::post('create', [AdminCplController::class, 'store'])->name('admin.cpl.store');
            // Route::get('/{id}', [AdminCplController::class, 'show'])->name('admin.cpl.show');
            Route::get('edit/{id}', [AdminCplController::class, 'edit'])->name('admin.cpl.edit');
            Route::put('edit/{id}', [AdminCplController::class, 'update'])->name('admin.cpl.update');
            Route::delete('{id}', [AdminCplController::class, 'destroy'])->name('admin.cpl.destroy');
            Route::get('download-excel', [AdminCplController::class, 'downloadExcel'])->name('admin.cpl.download-excel');
        });

        Route::prefix('admin/data-admin')->group(function () {
            Route::get('', [AdminController::class, 'index'])->name('admin.admins');
            Route::get('create', [AdminController::class, 'create'])->name('admin.admins.create');
            Route::post('create', [AdminController::class, 'store'])->name('admin.admins.store');
            // Route::get('/{id}', [adminsController::class, 'show'])->name('admin.admins.show');
            Route::get('edit/{id}', [AdminController::class, 'edit'])->name('admin.admins.edit');
            Route::put('edit/{id}', [AdminController::class, 'update'])->name('admin.admins.update');
            Route::delete('{id}', [AdminController::class, 'destroy'])->name('admin.admins.destroy');
            Route::post('reset-password', [AdminController::class, 'resetPassword'])->name('admin.admins.reset-password');
        });

        Route::prefix('admin/cpmk')->group(function () {
            Route::get('', [AdminCpmkController::class, 'index'])->name('admin.cpmk');
            Route::get('create', [AdminCpmkController::class, 'create'])->name('admin.cpmk.create');
            Route::post('create', [AdminCpmkController::class, 'store'])->name('admin.cpmk.store');
            // Route::get('/{id}', [AdminCpmkController::class, 'show'])->name('admin.cpmk.show');
            Route::get('edit/{id}', [AdminCpmkController::class, 'edit'])->name('admin.cpmk.edit');
            Route::put('edit/{id}', [AdminCpmkController::class, 'update'])->name('admin.cpmk.update');
            Route::delete('{id}', [AdminCpmkController::class, 'destroy'])->name('admin.cpmk.destroy');
        });

        Route::prefix('admin/sub-cpmk')->group(function () {
            Route::get('', [AdminSubCpmkController::class, 'index'])->name('admin.subcpmk');
            Route::get('create', [AdminSubCpmkController::class, 'create'])->name('admin.subcpmk.create');
            Route::post('create', [AdminSubCpmkController::class, 'store'])->name('admin.subcpmk.store');
            // Route::get('/{id}', [AdminSubCpmkController::class, 'show'])->name('admin.subcpmk.show');
            Route::get('edit/{id}', [AdminSubCpmkController::class, 'edit'])->name('admin.subcpmk.edit');
            Route::put('edit/{id}', [AdminSubCpmkController::class, 'update'])->name('admin.subcpmk.update');
            Route::delete('{id}', [AdminSubCpmkController::class, 'destroy'])->name('admin.subcpmk.destroy');
        });

        Route::prefix('admin/kelas-kuliah')->group(function () {
            Route::get('', [AdminPerkuliahanController::class, 'index'])->name('admin.kelaskuliah');
            Route::get('create', [AdminPerkuliahanController::class, 'create'])->name('admin.kelaskuliah.create');
            Route::get('create-kelas', [AdminPerkuliahanController::class, 'createKelas'])->name('admin.kelaskuliah.createKelas');
            Route::get('excel-kelas', [AdminPerkuliahanController::class, 'downloadExcelKelas'])->name('admin.kelaskuliah.excelKelas');
            Route::post('import-kelas', [AdminPerkuliahanController::class, 'importExcelKelas'])->name('admin.kelaskuliah.importKelas');
            Route::post('create', [AdminPerkuliahanController::class, 'store'])->name('admin.kelaskuliah.store');
            Route::post('update-koordinator/{id}', [AdminPerkuliahanController::class, 'updateKoordinator'])->name('admin.kelaskuliah.update-koordinator');
            Route::get('/{id}', [AdminPerkuliahanController::class, 'show'])->name('admin.kelaskuliah.show');
            Route::get('edit/{id}', [AdminPerkuliahanController::class, 'edit'])->name('admin.kelaskuliah.edit');
            Route::put('edit/{id}', [AdminPerkuliahanController::class, 'update'])->name('admin.kelaskuliah.update');
            Route::delete('{id}', [AdminPerkuliahanController::class, 'destroy'])->name('admin.kelaskuliah.destroy');

            Route::get('/{id}/mahasiswa', [AdminPerkuliahanController::class, 'createMahasiswa'])->name('admin.kelaskuliah.createmahasiswa');
            Route::post('/{id}/mahasiswa', [AdminPerkuliahanController::class, 'storeMahasiswa'])->name('admin.kelaskuliah.storemahasiswa');
            Route::get('mahasiswa/download-excel/{id}', [AdminPerkuliahanController::class, 'downloadExcel'])->name('admin.kelaskuliah.mahasiswa.download-excel');
            Route::post('/{id}/mahasiswa/import-excel', [AdminPerkuliahanController::class, 'importExcel'])->name('admin.kelaskuliah.mahasiswa.import-excel');
            Route::delete('{id}/{id_mahasiswa}', [AdminPerkuliahanController::class, 'destroyMahasiswa'])->name('admin.kelaskuliah.destroymahasiswa');
            Route::post('delete-multiple/{id}', [AdminPerkuliahanController::class, 'destroyMahasiswaMultiple'])->name('admin.kelaskuliah.destroymahasiswamultiple');
            Route::get('{id}/nilai/{id_mahasiswa}', [AdminNilaiController::class, 'show'])->name('admin.kelaskuliah.nilaimahasiswa');
            Route::get('/nilai/tugas', [AdminNilaiController::class, 'nilaiTugas'])->name('admin.kelaskuliah.nilaitugas');
            Route::get('/nilai/sub-cpmk', [AdminNilaiController::class, 'nilaiSubCpmk'])->name('admin.kelaskuliah.nilaisubcpmk');
            Route::get('/nilai/cpmk', [AdminNilaiController::class, 'nilaiCpmk'])->name('admin.kelaskuliah.nilaicpmk');
            Route::get('/nilai/cpl', [AdminNilaiController::class, 'nilaiCpl'])->name('admin.kelaskuliah.nilaicpl');
            Route::get('/{id}/lihat-nilai', [AdminNilaiController::class, 'nilaiMahasiswa'])->name('admin.kelaskuliah.lihatnilai');
            Route::get('{id}/lihat-nilai/pdf', [AdminNilaiController::class, 'generatePdf'])->name('admin.kelaskuliah.generatepdf');

            Route::post('/nilai/edit-nilai-tugas', [AdminNilaiController::class, 'editNilaiTugas'])->name('admin.kelaskuliah.editnilaitugas');
            Route::post('/nilai/edit-nilai-akhir', [AdminNilaiController::class, 'editNilaiAkhir'])->name('admin.kelaskuliah.editnilaiakhir');

            Route::get('{id}/nilai/{id_mahasiswa}/edit/{id_subcpmk}', [AdminNilaiController::class, 'edit'])->name('admin.kelaskuliah.nilaimahasiswa.edit');
            Route::put('{id}/nilai/{id_mahasiswa}/edit/{id_subcpmk}', [AdminNilaiController::class, 'update'])->name('admin.kelaskuliah.nilaimahasiswa.update');

            Route::get('nilai/chart-tugas', [AdminNilaiController::class, 'rataRataTugas'])->name('admin.kelaskuliah.rataratatugas');
            Route::get('nilai/chart-sub-cpmk', [AdminNilaiController::class, 'rataRataSubCPMK'])->name('admin.kelaskuliah.rataratasubcpmk');
            Route::get('nilai/chart-cpmk', [AdminNilaiController::class, 'rataRataCPMK'])->name('admin.kelaskuliah.rataratacpmk');
            Route::get('nilai/chart-cpl', [AdminNilaiController::class, 'rataRataCPL'])->name('admin.kelaskuliah.rataratacpl');
        });

        Route::prefix('admin/semester')->group(function () {
            Route::get('', [SemesterController::class, 'index'])->name('admin.semester');
            Route::get('create', [SemesterController::class, 'create'])->name('admin.semester.create');
            Route::post('store', [SemesterController::class, 'store'])->name('admin.semester.store');
            Route::post('update-active/{id}', [SemesterController::class, 'updateIsActive'])->name('admin.semester.update-active');
            Route::get('edit/{id}', [SemesterController::class, 'edit'])->name('admin.semester.edit');
            Route::put('edit/{id}', [SemesterController::class, 'update'])->name('admin.semester.update');
            Route::delete('{id}', [SemesterController::class, 'destroy'])->name('admin.semester.destroy');
        });
    });


    Route::group(['middleware' => 'role:dosen'], function () {
        Route::get('/dosen/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
        Route::get('/dosen/dashboard/chart-cpl', [DosenController::class, 'chartCplDashboard'])->name('dosen.dashboard.chartcpl');
        Route::get('/dosen/dashboard/chart-cpl-prodi', [DosenController::class, 'chartCplByAngkatan'])->name('dosen.dashboard.chartcplprodi');
        Route::get('/dosen/dashboard/chart-cpl-kelas', [DosenController::class, 'chartCplKelasDashboard'])->name('dosen.dashboard.chartcplkelas');
        Route::get('/dosen/dashboard/chart-cpl-smt', [DosenController::class, 'chartCplSmtDashboard'])->name('dosen.dashboard.chartcplsmt');

        Route::prefix('dosen/user')->group(function () {
            Route::get('/{id}', [DosenProfileController::class, 'show'])->name('dosen.user');
            Route::get('edit/{id}', [DosenProfileController::class, 'edit'])->name('dosen.user.edit');
            Route::put('edit/{id}', [DosenProfileController::class, 'update'])->name('dosen.proses.edit');
            Route::get('profile/changePass', [DosenProfileController::class, 'showFormChangePass'])->name('dosen.user.changePass');
            Route::post('profile/changePass', [DosenProfileController::class, 'changePassword'])->name('dosen.changePass');
        });

        Route::prefix('dosen/kelas-kuliah')->group(function () {
            Route::get('', [DosenPerkuliahanController::class, 'index'])->name('dosen.kelaskuliah');
            Route::get('create', [DosenPerkuliahanController::class, 'create'])->name('dosen.kelaskuliah.create');
            Route::post('create', [DosenPerkuliahanController::class, 'store'])->name('dosen.kelaskuliah.store');
            Route::get('/{id}', [DosenPerkuliahanController::class, 'show'])->name('dosen.kelaskuliah.show');
            Route::get('edit/{id}', [DosenPerkuliahanController::class, 'edit'])->name('dosen.kelaskuliah.edit');
            Route::put('edit/{id}', [DosenPerkuliahanController::class, 'update'])->name('dosen.kelaskuliah.update');
            Route::delete('{id}', [DosenPerkuliahanController::class, 'destroy'])->name('dosen.kelaskuliah.destroy');
            Route::get('/{id}/mahasiswa', [DosenPerkuliahanController::class, 'createMahasiswa'])->name('dosen.kelaskuliah.createmahasiswa');
            Route::post('/{id}/mahasiswa', [DosenPerkuliahanController::class, 'storeMahasiswa'])->name('dosen.kelaskuliah.storemahasiswa');
            Route::delete('{id}/{id_mahasiswa}', [DosenPerkuliahanController::class, 'destroyMahasiswa'])->name('dosen.kelaskuliah.destroymahasiswa');
            Route::post('delete-multiple/{id}', [DosenPerkuliahanController::class, 'destroyMahasiswaMultiple'])->name('dosen.kelaskuliah.destroymahasiswamultiple');
            Route::get('mahasiswa/download-excel/{id}', [DosenPerkuliahanController::class, 'downloadExcel'])->name('dosen.kelaskuliah.mahasiswa.download-excel');
            Route::post('/{id}/mahasiswa/import-excel', [DosenPerkuliahanController::class, 'importExcel'])->name('dosen.kelaskuliah.mahasiswa.import-excel');
            Route::put('updateEvaluasi/{id}', [DosenPerkuliahanController::class, 'updateEvaluasi'])->name('dosen.kelaskuliah.updateEvaluasi');
            Route::get('{id}/pdf', [DosenPerkuliahanController::class, 'generatePdf'])->name('dosen.kelaskuliah.generateportof');

            Route::get('{id}/nilai/{id_mahasiswa}', [DosenNilaiController::class, 'show'])->name('dosen.kelaskuliah.nilaimahasiswa');
            Route::get('/nilai/tugas', [DosenNilaiController::class, 'nilaiTugas'])->name('dosen.kelaskuliah.nilaitugas');
            Route::get('/nilai/sub-cpmk', [DosenNilaiController::class, 'nilaiSubCpmk'])->name('dosen.kelaskuliah.nilaisubcpmk');
            Route::get('/nilai/cpmk', [DosenNilaiController::class, 'nilaiCpmk'])->name('dosen.kelaskuliah.nilaicpmk');
            Route::get('/nilai/cpl', [DosenNilaiController::class, 'nilaiCpl'])->name('dosen.kelaskuliah.nilaicpl');

            Route::post('{id}/nilai/edit-nilai', [DosenNilaiController::class, 'editSemuaNilai'])->name('dosen.kelaskuliah.editsemuanilai');
            Route::post('/nilai/edit-nilai-tugas', [DosenNilaiController::class, 'editNilaiTugas'])->name('dosen.kelaskuliah.editnilaitugas');
            Route::post('/nilai/edit-nilai-akhir', [DosenNilaiController::class, 'editNilaiAkhir'])->name('dosen.kelaskuliah.editnilaiakhir');
            Route::get('/{id}/lihat-nilai', [DosenPerkuliahanController::class, 'nilaiMahasiswa'])->name('dosen.kelaskuliah.masukkannilai');
            Route::get('{id}/lihat-nilai/pdf', [DosenNilaiController::class, 'generatePdf'])->name('dosen.kelaskuliah.generatepdf');
            Route::get('/{id}/masukkan-nilai/tugas/download-excel', [DosenNilaiController::class, 'downloadExcelNilaiTugas'])->name('dosen.download.nilaitugas');
            Route::get('/{id}/masukkan-nilai/akhir/download-excel', [DosenNilaiController::class, 'downloadExcelNilaiAkhir'])->name('dosen.download.nilaiakhir');
            Route::post('/{id}/masukkan-nilai/akhir/import-excel', [DosenNilaiController::class, 'importExcelNilaiAkhir'])->name('dosen.impor.nilaiakhir');
            Route::post('/{id}/masukkan-nilai/tugas/import-excel', [DosenNilaiController::class, 'importExcelNilaiTugas'])->name('dosen.impor.nilaitugas');

            Route::get('nilai/chart-tugas', [DosenNilaiController::class, 'rataRataTugas'])->name('dosen.kelaskuliah.rataratatugas');
            Route::get('nilai/chart-sub-cpmk', [DosenNilaiController::class, 'rataRataSubCPMK'])->name('dosen.kelaskuliah.rataratasubcpmk');
            Route::get('nilai/chart-cpmk', [DosenNilaiController::class, 'rataRataCPMK'])->name('dosen.kelaskuliah.rataratacpmk');
            Route::get('nilai/chart-cpl', [DosenNilaiController::class, 'rataRataCPL'])->name('dosen.kelaskuliah.rataratacpl');
        });

        Route::prefix('dosen/mata-kuliah')->group(function () {
            Route::get('', [DosenMataKuliahController::class, 'index'])->name('dosen.matakuliah');
            Route::get('create', [DosenMataKuliahController::class, 'create'])->name('dosen.matakuliah.create.matkul');
            Route::post('create', [DosenMataKuliahController::class, 'store'])->name('dosen.matakuliah.store');
            Route::get('/{id}', [DosenMataKuliahController::class, 'show'])->name('dosen.matakuliah.show');
            Route::get('edit/{id}', [DosenMataKuliahController::class, 'edit'])->name('dosen.matakuliah.edit');
            Route::put('edit/{id}', [DosenMataKuliahController::class, 'update'])->name('dosen.matakuliah.update');
            Route::delete('{id}', [DosenMataKuliahController::class, 'destroy'])->name('dosen.matakuliah.destroy');
        });

        Route::prefix('dosen/rps')->group(function () {
            Route::get('{id}', [RpsController::class, 'index'])->name('dosen.rps');
            Route::post('create/cpmk/{id}', [DosenRpsController::class, 'storecpmk'])->name('dosen.rps.storecpmk');
            Route::post('create/subcpmk/{id}', [DosenRpsController::class, 'storesubcpmk'])->name('dosen.rps.storesubcpmk');
            Route::post('create/soal/{id}', [DosenRpsController::class, 'storesoal'])->name('dosen.rps.storesoal');
            Route::get('{id}', [DosenRpsController::class, 'create'])->name('dosen.rps.create');
            Route::delete('deletecpmk/{id}', [DosenRpsController::class, 'destroyCpmk'])->name('dosen.rps.destroycpmk');
            Route::delete('deletecpmk/sub/{id}', [DosenRpsController::class, 'destroySubCpmk'])->name('dosen.rps.destroysubcpmk');
            Route::delete('deletecpmk/sub/soal/{id}', [DosenRpsController::class, 'destroySoal'])->name('dosen.rps.destroysoal');
            // Route::get('create', [RpsController::class, 'create'])->name('dosen.matakuliah.add');
            Route::get('editcpmk/{id}', [DosenRpsController::class, 'editCpmk'])->name('dosen.rps.editcpmk');
            Route::put('updatecpmk', [DosenRpsController::class, 'updateCpmk'])->name('dosen.rps.updatecpmk');
            Route::get('editsubcpmk/{id}', [DosenRpsController::class, 'editSubCpmk'])->name('dosen.rps.editsubcpmk');
            Route::put('updatesubcpmk', [DosenRpsController::class, 'updateSubCpmk'])->name('dosen.rps.updatesubcpmk');
            Route::get('editsoalsubcpmk/{id}', [DosenRpsController::class, 'editSoalSubCpmk'])->name('dosen.rps.editsoalsubcpmk');
            Route::put('updatesoalsubcpmk', [DosenRpsController::class, 'updateSoalSubCpmk'])->name('dosen.rps.updatesoalsubcpmk');

            Route::get('listsubcpmk/{id}', [DosenRpsController::class, 'listSubCpmk'])->name('dosen.rps.listsubcpmk');
            Route::get('listcpmk/{id}', [DosenRpsController::class, 'listCpmk'])->name('dosen.rps.listcpmk');
            Route::get('listtugas/{id}', [DosenRpsController::class, 'listTugas'])->name('dosen.rps.listtugas');

            Route::get('detail/cpl', [DosenMataKuliahController::class, 'detailCpl']);
            Route::get('detail/cpmk', [DosenMataKuliahController::class, 'detailCpmk']);
            Route::get('detail/sub-cpmk', [DosenMataKuliahController::class, 'detailSubCpmk']);
            Route::get('detail/tugas', [DosenMataKuliahController::class, 'detailTugas']);

            Route::get('listsubcpmk/input/{id}', [DosenRpsController::class, 'listKodeSubCpmk'])->name('dosen.rps.listkodesubcpmk');
            Route::get('listcpmk/input/{id}', [DosenRpsController::class, 'listKodeCpmk'])->name('dosen.rps.listkodecpmk');

            Route::get('create/{id}/download-excel', [DosenRpsController::class, 'export'])->name('dosen.rps.download-excel');
            Route::post('create/import-excel/{id}', [DosenRpsController::class, 'import'])->name('dosen.rps.import-excel');
        });
    });

    Route::group(['middleware' => 'role:mahasiswa'], function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/dashboard/chart-cpl', [MahasiswaController::class, 'chartDashboard'])->name('mahasiswa.dashboard.chartcpl');
        Route::get('/mahasiswa/dashboard/chart-cpl-angkatan', [MahasiswaController::class, 'chartCplDashboard'])->name('mahasiswa.dashboard.chartcplAngkatan');

        Route::prefix('mahasiswa/user')->group(function () {
            Route::get('/{id}', [MahasiswaProfileController::class, 'show'])->name('mahasiswa.user');
            Route::get('edit/{id}', [MahasiswaProfileController::class, 'edit'])->name('mahasiswa.user.edit');
            Route::put('edit/{id}', [MahasiswaProfileController::class, 'update'])->name('mahasiswa.proses.edit');
            Route::get('profile/changePass', [MahasiswaProfileController::class, 'showFormChangePass'])->name('mahasiswa.user.changePass');
            Route::post('profile/changePass', [MahasiswaProfileController::class, 'changePassword'])->name('mahasiswa.changePass');
        });

        Route::prefix('mahasiswa/mata-kuliah')->group(function () {
            Route::get('', [MahasiswaMataKuliahController::class, 'index'])->name('mahasiswa.matakuliah');
            Route::get('create', [MahasiswaMataKuliahController::class, 'create'])->name('mahasiswa.matakuliah.create.matkul');
            Route::post('create', [MahasiswaMataKuliahController::class, 'store'])->name('mahasiswa.matakuliah.store');
            Route::get('/{id}', [MahasiswaMataKuliahController::class, 'show'])->name('mahasiswa.matakuliah.show');
            Route::get('{id}/pdf', [MahasiswaMataKuliahController::class, 'generatePdf'])->name('mahasiswa.matakuliah.generatepdf');
            Route::get('edit/{id}', [MahasiswaMataKuliahController::class, 'edit'])->name('mahasiswa.matakuliah.edit');
            Route::put('edit/{id}', [MahasiswaMataKuliahController::class, 'update'])->name('mahasiswa.matakuliah.update');
            Route::delete('{id}', [MahasiswaMataKuliahController::class, 'destroy'])->name('mahasiswa.matakuliah.destroy');
            Route::get('detail/cpl', [MahasiswaMataKuliahController::class, 'detailCpl']);
            Route::get('detail/cpmk', [MahasiswaMataKuliahController::class, 'detailCpmk']);
            Route::get('detail/sub-cpmk', [MahasiswaMataKuliahController::class, 'detailSubCpmk']);
            Route::get('detail/tugas', [MahasiswaMataKuliahController::class, 'detailTugas']);
        });

        Route::prefix('mahasiswa/kelas-kuliah')->group(function () {
            Route::get('', [MahasiswaNilaiController::class, 'index'])->name('mahasiswa.kelaskuliah');
            Route::get('{id}/nilai', [MahasiswaNilaiController::class, 'show'])->name('mahasiswa.kelaskuliah.nilaimahasiswa');
            Route::get('/nilai/tugas', [MahasiswaNilaiController::class, 'nilaiTugas'])->name('mahasiswa.matakuliah.nilaitugas');
            Route::get('/nilai/sub-cpmk', [MahasiswaNilaiController::class, 'nilaiSubCpmk'])->name('mahasiswa.kelaskuliah.nilaisubcpmk');
            Route::get('/nilai/cpmk', [MahasiswaNilaiController::class, 'nilaiCpmk'])->name('mahasiswa.kelaskuliah.nilaicpmk');
            Route::get('/nilai/cpl', [MahasiswaNilaiController::class, 'nilaiCpl'])->name('mahasiswa.kelaskuliah.nilaicpl');
            Route::get('/nilai/chart-cpl', [MahasiswaNilaiController::class, 'chartCpl'])->name('mahasiswa.kelaskuliah.chartcpl');
            Route::get('/nilai/chart-cpmk', [MahasiswaNilaiController::class, 'chartCpmk'])->name('mahasiswa.kelaskuliah.chartcpmk');
        });
    });
});

Route::get('/admin/mata_kuliah/detail_mata_kuliah', function () {
    return View::make('pages-admin.mata_kuliah.detail_mata_kuliah');
});
