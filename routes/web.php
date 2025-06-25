<?php

use App\Http\Controllers\Admin\ADashboardController;
use App\Http\Controllers\Admin\CheckCicilanController;
use App\Http\Controllers\Admin\CheckVoucherController;
use App\Http\Controllers\Admin\CicilanController;
use App\Http\Controllers\Admin\KontrakController;
use App\Http\Controllers\Admin\RepeatOrderController;
use App\Http\Controllers\Admin\TrackingController;
use App\Http\Controllers\Admin\TrackingPengajuanController;
use App\Http\Controllers\Admin\TrackingPengajuanLuarController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\CreditAnalyst\ApprovalCAController;
use App\Http\Controllers\CreditAnalyst\CADashboardController;
use App\Http\Controllers\CreditAnalyst\NotificationCAController;
use App\Http\Controllers\CreditAnalyst\RiwayatApprovalCAController;
use App\Http\Controllers\Head\ApprovalHeadController;
use App\Http\Controllers\Head\DataPengajuanLuarController;
use App\Http\Controllers\Head\HDashboardController;
use App\Http\Controllers\Head\NotificationHeadController;
use App\Http\Controllers\Head\RiwayatApprovalHeadController;
use App\Http\Controllers\Head\RiwayatApprovalHeadLuarController;
use App\Http\Controllers\Head\TrackingMarketingController;
use App\Http\Controllers\Luar\CreditAnalyst\ApprovalLuarController;
use App\Http\Controllers\Luar\CreditAnalyst\RiwayatApprovalLuarController;
use App\Http\Controllers\Luar\Marketing\PengajuanLuarController;
use App\Http\Controllers\Luar\Supervisor\CheckPengajuanLuarController;
use App\Http\Controllers\Luar\Supervisor\RiwayatPengajuanLuarController;
use App\Http\Controllers\Luar\Surveyor\NotificationSurveyorController;
use App\Http\Controllers\Luar\Surveyor\RiwayatSurveyController;
use App\Http\Controllers\Luar\Surveyor\SurveyController;
use App\Http\Controllers\Luar\Surveyor\SurveyDashboardController;
use App\Http\Controllers\Marketing\MDashboardController;
use App\Http\Controllers\Marketing\NotificationMarketingController;
use App\Http\Controllers\Marketing\PengajuanController;
use App\Http\Controllers\Marketing\RiwayatPengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\ListApprovalController;
use App\Http\Controllers\SuperAdmin\ListApprovalLuarController;
use App\Http\Controllers\SuperAdmin\ListPengajuanController;
use App\Http\Controllers\SuperAdmin\ListPengajuanLuarController;
use App\Http\Controllers\SuperAdmin\ListUserController;
use App\Http\Controllers\SuperAdmin\ManajemenPengajuanController;
use App\Http\Controllers\SuperAdmin\SADashboardController;
use App\Http\Controllers\Supervisor\ApprovalSpvController;
use App\Http\Controllers\Supervisor\NotificationSpvController;
use App\Http\Controllers\Supervisor\RiwayatApprovalSpvController;
use App\Http\Controllers\Supervisor\SDashboardController;
use App\Http\Controllers\Supervisor\TopUpController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('afterLoginRedirect');

Route::get('/check-voucher', [CheckVoucherController::class, 'index'])->name('check.voucher');
Route::post('/check-voucher', [CheckVoucherController::class, 'checkVoucher'])->name('check.voucher.submit');

Route::get('/topup-pengajuan', [RepeatOrderController::class, 'index'])->name('input.pengajuan');
Route::post('/topup-pengajuan', [RepeatOrderController::class, 'store'])->name('input.pengajuan.submit');

Route::get('/check-cicilan', [CheckCicilanController::class, 'index'])->name('check.cicilan');
Route::post('/check-cicilan', [CheckCicilanController::class, 'checkCicilan'])->name('check.cicilan.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware('auth', 'marketingMiddleware')->group(function () {
    Route::get('/marketing/dashboard', [MDashboardController::class, 'index'])->name('marketing.dashboard');
    Route::get('/marketing/data-pengajuan', [PengajuanController::class, 'index'])->name('marketing.data.pengajuan');
    Route::patch('/marketing/data-pengajuan/banding/{id}', [PengajuanController::class, 'banding'])->name('marketing.data.pengajuan.banding');
    Route::patch('/marketing/data-pengajuan/{id}/clear', [PengajuanController::class, 'clear'])->name('marketing.data.pengajuan.clear');
    Route::get('/marketing/form', [PengajuanController::class, 'form'])->name('marketing.form');
    Route::post('/marketing/form/store', [PengajuanController::class, 'store'])->name('marketing.form.store');
    Route::get('/marketing/form-edit/{id}', [PengajuanController::class, 'edit'])->name('marketing.pengajuan.edit');
    Route::put('marketing/form-edit/{id}/update', [PengajuanController::class, 'update'])->name('marketing.pengajuan.update');
    Route::get('/marketing/detail-pengajuan/{id}', [PengajuanController::class, 'show'])->name('marketing.detail.pengajuan.show');
    Route::get('/marketing/manajemen-nasabah', [RiwayatPengajuanController::class, 'index'])->name('marketing.riwayat');
    Route::get('/marketing/notifikasi', [NotificationMarketingController::class, 'index'])->name('marketing.notifikasi');
    Route::patch('/marketing/notifikasi/{id}/read', [NotificationMarketingController::class, 'read'])->name('marketing.notifikasi.read');
    Route::patch('/marketing/notifikasi/read-all', [NotificationMarketingController::class, 'readAll'])->name('marketing.notifikasi.read-all');

    //Marketing Luar
    Route::get('/marketing-luar/pengajuan-luar', [PengajuanLuarController::class, 'index'])->name('marketingLuar.pengajuan');
    Route::get('/marketing-luar/form', [PengajuanLuarController::class, 'form'])->name('marketingLuar.form');
    Route::get('/marketing-luar/validasi-nik', [PengajuanLuarController::class, 'validateNik'])->name('marketingLuar.validate.nik');
    Route::get('/marketing-luar/validasi-no-kk', [PengajuanLuarController::class, 'validateNoKk'])->name('marketingLuar.validate.no.kk');
    Route::post('/marketing-luar/form/store', [PengajuanLuarController::class, 'store'])->name('marketingLuar.form.store');
    Route::get('/marketing-luar/detail-pengajuan-luar/{id}', [PengajuanLuarController::class, 'show'])->name('marketingLuar.detail.pengajuan.show');
    Route::get('/marketing-luar/form-edit/{id}', [PengajuanLuarController::class, 'edit'])->name('marketingLuar.edit.pengajuan.show');
    Route::put('/marketing-luar/form-edit/{id}/update', [PengajuanLuarController::class, 'update'])->name('marketingLuar.form.update');
    Route::patch('/marketing-luar/pengajuan-luar/banding/{id}', [PengajuanLuarController::class, 'banding'])->name('marketingLuar.banding');
    Route::patch('/marketing-luar/pengajuan-luar/{id}/clear', [PengajuanLuarController::class, 'clear'])->name('marketingLuar.clear');
    Route::get('/marketing-luar/form-topup/{id}', [PengajuanLuarController::class, 'topUp'])->name('marketingLuar.topUp');
    Route::post('/marketing-luar/form-topup/{id}/topup', [PengajuanLuarController::class, 'topUpStore'])->name('marketingLuar.topUp.store');
});

Route::middleware('auth', 'supervisorMiddleware')->group(function () {
    Route::get('/supervisor/dashboard', [SDashboardController::class, 'index'])->name('supervisor.dashboard');
    Route::get('/supervisor/approval-pengajuan', [ApprovalSpvController::class, 'index'])->name('supervisor.approval');
    Route::get('/supervisor/approval-pengajuan/{id}/detail', [ApprovalSpvController::class, 'show'])->name('supervisor.approval.detail');
    Route::post('/supervisor/approval-pengajuan/{id}/approval', [ApprovalSpvController::class, 'approval'])->name('supervisor.approval.approval');
    Route::post('/supervisor/approval-pengajuan/{id}/approve', [ApprovalSpvController::class, 'approve'])->name('supervisor.approval.approve');
    Route::post('/supervisor/approval-pengajuan/{id}/reject', [ApprovalSpvController::class, 'reject'])->name('supervisor.approval.reject');
    Route::get('/supervisor/riwayat-approval', [RiwayatApprovalSpvController::class, 'index'])->name('supervisor.riwayat');
    Route::get('/supervisor/detail-riwayat/{id}', [RiwayatApprovalSpvController::class, 'show'])->name('supervisor.detail');
    Route::get('/supervisor/notifikasi', [NotificationSpvController::class, 'index'])->name('supervisor.notifikasi');
    Route::patch('/supervisor/notifikasi/{id}/read', [NotificationSpvController::class, 'read'])->name('supervisor.notifikasi.read');
    Route::patch('/supervisor/notifikasi/read-all', [NotificationSpvController::class, 'readAll'])->name('supervisor.notifikasi.read-all');

    Route::get('/supervisor/data-pengajuan-ro', [TopUpController::class, 'index'])->name('supervisor.ro');
    Route::patch('/supervisor/data-pengajuan-ro/{id}/selesai', [TopUpController::class, 'selesai'])->name('supervisor.ro.selesai');

    // Supervisor Pengajuan Luar
    Route::get('/supervisor/pengajuan-luar', [CheckPengajuanLuarController::class, 'index'])->name('supervisor.luar');
    Route::get('/supervisor/pengajuan-luar/{id}/detail', [CheckPengajuanLuarController::class, 'show'])->name('supervisor.detail.pengajuan.show');
    Route::post('/supervisor/pengajuan-luar/{id}/check', [CheckPengajuanLuarController::class, 'check'])->name('supervisor.luar.check');
    Route::get('/supervisor/riwayat-pengajuan-luar', [RiwayatPengajuanLuarController::class, 'index'])->name('supervisor.riwayat.pengajuan.luar');
    Route::get('/supervisor/riwayat-pengajuan-luar/{id}/detail', [RiwayatPengajuanLuarController::class, 'show'])->name('supervisor.detail.riwayat.pengajuan.luar');
});

Route::middleware('auth', 'creditMiddleware')->group(function () {
    Route::get('/credit-analyst/dashboard', [CADashboardController::class, 'index'])->name('creditAnalyst.dashboard');
    Route::get('/credit-analyst/approval-ca', [ApprovalCAController::class, 'index'])->name('creditAnalyst.approval');
    Route::get('/credit-analyst/approval-ca/{id}/detail', [ApprovalCAController::class, 'show'])->name('creditAnalyst.approval.detail');
    Route::get('/credit-analyst/approval-ca/{id}/detail-banding', [ApprovalCAController::class, 'showBanding'])->name('creditAnalyst.approval.detail-banding');
    Route::post('/credit-analyst/approval-ca/{id}/approval', [ApprovalCAController::class, 'approval'])->name('creditAnalyst.approval.approval');
    Route::post('/credit-analyst/approval-ca/{id}/approval-banding', [ApprovalCAController::class, 'approvalBanding'])->name('creditAnalyst.approval.approval-banding');
    Route::post('/credit-analyst/approval-ca/{id}/approve', [ApprovalCAController::class, 'approve'])->name('creditAnalyst.approval.approve');
    Route::post('/credit-analyst/approval-ca/{id}/reject', [ApprovalCAController::class, 'reject'])->name('creditAnalyst.approval.reject');
    Route::get('/credit-analyst/riwayat-approval', [RiwayatApprovalCAController::class, 'index'])->name('creditAnalyst.riwayat');
    Route::get('/credit-analyst/detail-riwayat/{id}', [RiwayatApprovalCAController::class, 'show'])->name('creditAnalyst.detail');
    Route::get('/credit-analyst/notifikasi', [NotificationCAController::class, 'index'])->name('creditAnalyst.notifikasi');
    Route::patch('/credit-analyst/notifikasi/{id}/read', [NotificationCAController::class, 'read'])->name('creditAnalyst.notifikasi.read');
    Route::patch('/credit-analyst/notifikasi/read-all', [NotificationCAController::class, 'readAll'])->name('creditAnalyst.notifikasi.read-all');

    //Credit Analyst Luar
    Route::get('/credit-analyst-luar/pengajuan-luar', [ApprovalLuarController::class, 'index'])->name('creditAnalystLuar.pengajuan');
    Route::get('/credit-analyst-luar/detail-pengajuan-luar/{id}', [ApprovalLuarController::class, 'show'])->name('creditAnalystLuar.detail.pengajuan.show');
    Route::post('/credit-analyst-luar/{id}/verifikasi', [ApprovalLuarController::class, 'verifikasi'])->name('creditAnalystLuar.verifikasi');
    Route::get('/credit-analyst-luar/{id}/survey/form', [ApprovalLuarController::class, 'surveyForm'])->name('creditAnalystLuar.survey.form');
    Route::post('/credit-analyst-luar/{id}/survey', [ApprovalLuarController::class, 'survey'])->name('creditAnalystLuar.survey');
    Route::get('/credit-analyst-luar/{id}/detail-hasil-survey', [ApprovalLuarController::class, 'detailHasilSurvey'])->name('creditAnalystLuar.detail.hasil.survey');
    Route::get('/credit-analyst-luar/{id}/approval/form', [ApprovalLuarController::class, 'approvalForm'])->name('creditAnalystLuar.approval.form');
    Route::post('/credit-analyst-luar/{id}/approval', [ApprovalLuarController::class, 'approval'])->name('creditAnalystLuar.approval');
    Route::get('/credit-analyst-luar/riwayat-approval-pengajuan-luar', [RiwayatApprovalLuarController::class, 'index'])->name('creditAnalystLuar.riwayat');
    Route::get('/credit-analyst-luar/detail-riwayat-pengajuan-luar/{id}', [RiwayatApprovalLuarController::class, 'show'])->name('creditAnalystLuar.detail-approval');
    Route::get('/credit-analyst-luar/detail-banding-pengajuan-luar/{id}', [ApprovalLuarController::class, 'showBanding'])->name('creditAnalystLuar.detail-banding');
    Route::post('/credit-analyst-luar/{id}/approval-banding', [ApprovalLuarController::class, 'approvalBanding'])->name('creditAnalystLuar.approval-banding');
});

Route::middleware('auth', 'headMiddleware')->group(function () {
    Route::get('/head/dashboard', [HDashboardController::class, 'index'])->name('headMarketing.dashboard');
    Route::get('/head/approval-head-marketing', [ApprovalHeadController::class, 'index'])->name('headMarketing.approval');
    Route::get('/head/approval-head-marketing/{id}/detail', [ApprovalHeadController::class, 'show'])->name('headMarketing.approval.detail');
    Route::get('/head/approval-head-marketing/{id}/detail-banding', [ApprovalHeadController::class, 'showBanding'])->name('headMarketing.approval.detail-banding');
    Route::post('/head/approval-head-marketing/{id}/approval', [ApprovalHeadController::class, 'approval'])->name('headMarketing.approval.approval');
    Route::post('/head/approval-head-marketing/{id}/approval-banding', [ApprovalHeadController::class, 'approvalBanding'])->name('headMarketing.approval.approval-banding');
    Route::post('/head/approval-head-marketing/{id}/approve', [ApprovalHeadController::class, 'approve'])->name('headMarketing.approval.approve');
    Route::post('/head/approval-head-marketing/{id}/reject', [ApprovalHeadController::class, 'reject'])->name('headMarketing.approval.reject');
    Route::get('/head/riwayat-approval', [RiwayatApprovalHeadController::class, 'index'])->name('headMarketing.riwayat');
    Route::get('/head/detail-riwayat/{id}', [RiwayatApprovalHeadController::class, 'show'])->name('headMarketing.detail');
    Route::get('/head/tracking-marketing', [TrackingMarketingController::class, 'index'])->name('headMarketing.tracking');
    Route::get('/head/notifikasi', [NotificationHeadController::class, 'index'])->name('headMarketing.notifikasi');
    Route::patch('/head/notifikasi/{id}/read', [NotificationHeadController::class, 'read'])->name('headMarketing.notifikasi.read');
    Route::patch('/head/notifikasi/read-all', [NotificationHeadController::class, 'readAll'])->name('headMarketing.notifikasi.read-all');

    //Pengajuan Luar
    Route::get('/head/pengajuan-luar', [DataPengajuanLuarController::class, 'index'])->name('headMarketing.data.pengajuan.luar');
    Route::get('/head/detail-pengajuan-luar/{id}', [DataPengajuanLuarController::class, 'show'])->name('headMarketing.detail.pengajuan.show');
    Route::post('/head/detail-pengajuan-luar/{id}/approval', [DataPengajuanLuarController::class, 'approval'])->name('headMarketing.approval.luar');
    Route::get('/head/riwayat-approval-luar', [RiwayatApprovalHeadLuarController::class, 'index'])->name('headMarketing.data.riwayat.luar');
    Route::get('/head/detail-riwayat-luar/{id}', [RiwayatApprovalHeadLuarController::class, 'show'])->name('headMarketing.detail.luar');
    Route::get('/head/detail-pengajuan-luar/{id}/detail-banding', [DataPengajuanLuarController::class, 'showBanding'])->name('headMarketing.detail.banding-luar');
    Route::post('/head/detail-pengajuan-luar/{id}/approval-banding', [DataPengajuanLuarController::class, 'approvalBanding'])->name('headMarketing.approval.banding-luar');
});

Route::middleware('auth', 'surveyorMiddleware')->group(function () {
    Route::get('/surveyor/dashboard', [SurveyDashboardController::class, 'index'])->name('surveyor.dashboard');
    Route::get('/surveyor/daftar-survey-nasabah', [SurveyController::class, 'index'])->name('surveyor.daftar.survey');
    Route::get('/surveyor/riwayat-hasil-survey', [RiwayatSurveyController::class, 'index'])->name('surveyor.riwayat.survey');
    Route::get('/surveyor/form-hasil-survey/{id}', [SurveyController::class, 'formHasilSurvey'])->name('surveyor.form.hasil.survey');
    Route::post('/surveyor/hasil-survey/{id}', [SurveyController::class, 'store'])->name('surveyor.hasil.survey');
    Route::get('/surveyor/detail-hasil-survey/{id}', [SurveyController::class, 'detailHasilSurvey'])->name('surveyor.detail.hasil.survey');
    Route::get('/surveyor/hasil-survey/{id}', [SurveyController::class, 'edit'])->name('surveyor.hasil.survey.edit');
    Route::put('/surveyor/hasil-survey/{id}/update', [SurveyController::class, 'update'])->name('surveyor.hasil.survey.update');
    Route::get('/surveyor/notifikasi', [NotificationSurveyorController::class, 'index'])->name('surveyor.notifikasi');
    Route::patch('/surveyor/notifikasi/{id}/read', [NotificationSurveyorController::class, 'read'])->name('surveyor.notifikasi.read');
    Route::patch('/surveyor/notifikasi/read-all', [NotificationSurveyorController::class, 'readAll'])->name('surveyor.notifikasi.read-all');
});

Route::middleware('auth', 'adminMiddleware')->group(function () {
    Route::get('/admin/dashboard', [ADashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/data-pengajuan', [TrackingPengajuanController::class, 'index'])->name('admin.data.pengajuan');
    Route::get('/admin/data-pengajuan/{id}/detail', [TrackingPengajuanController::class, 'show'])->name('admin.data.pengajuan.detail');
    Route::get('/admin/data-marketing', [TrackingController::class, 'index'])->name('admin.tracking');

    Route::get('/admin/voucher', [VoucherController::class, 'index'])->name('admin.voucher');
    Route::post('/admin/voucher/store', [VoucherController::class, 'store'])->name('admin.voucher.store');
    Route::put('/admin/voucher/{id}', [VoucherController::class, 'update'])->name('admin.voucher.update');
    Route::delete('/admin/voucher/{id}', [VoucherController::class, 'destroy'])->name('admin.voucher.destroy');
    Route::patch('/admin/voucher/{id}/claim', [VoucherController::class, 'claim'])->name('admin.voucher.claim');
    Route::post('/admin/voucher/import', [VoucherController::class, 'import'])->name('admin.voucher.import');
    Route::post('/admin/voucher/upload/{id}', [VoucherController::class, 'upload'])->name('admin.voucher.upload');
    Route::post('/admin/voucher/bulk-delete-expired', [VoucherController::class, 'bulkDeleteExpired'])->name('admin.voucher.bulkDeleteExpired');

    Route::get('/admin/data-cicilan', [CicilanController::class, 'index'])->name('admin.data.cicilan');
    Route::put('/admin/data-cicilan/{id}', [CicilanController::class, 'update'])->name('admin.cicilan.update');
    Route::post('/admin/data-cicilan/import', [CicilanController::class, 'import'])->name('admin.cicilan.import');
    Route::post('/admin/data-cicilan/truncate', [CicilanController::class, 'truncate'])->name('admin.cicilan.truncate');
    Route::post('/admin/data-cicilan/bulk-delete', [CicilanController::class, 'bulkDeleteCicilan'])->name('admin.cicilan.bulkDeleteCicilan');

    Route::get('/admin/surat-kontrak', [KontrakController::class, 'index'])->name('admin.surat.kontrak');
    Route::post('/admin/surat-kontrak', [KontrakController::class, 'store'])->name('admin.surat.kontrak.store');
    Route::put('/admin/surat-kontrak/{id}', [KontrakController::class, 'update'])->name('admin.surat.kontrak.update');
    Route::delete('/admin/surat-kontrak/{id}', [KontrakController::class, 'destroy'])->name('admin.surat.kontrak.destroy');
    Route::get('/admin/surat-kontrak/{id}/show', [KontrakController::class, 'show'])->name('admin.surat.kontrak.show');
    Route::get('/admin/surat-kontrak/{id}/download', [KontrakController::class, 'download'])->name('admin.surat.kontrak.download');
    Route::get('/admin/surat-kontrak/generate-nomor/{type}', [KontrakController::class, 'generateNomor'])->name('admin.surat.kontrak.generate');

    //Pengajuan Luar
    Route::get('/admin/data-pengajuan-luar', [TrackingPengajuanLuarController::class, 'index'])->name('admin.data.pengajuan.luar');
    Route::get('/admin/data-pengajuan-luar/{id}/detail', [TrackingPengajuanLuarController::class, 'show'])->name('admin.data.pengajuan.luar.detail');
});

Route::middleware('auth', 'superAdminMiddleware')->group(function () {
    Route::get('/super-admin/dashboard', [SADashboardController::class, 'index'])->name('superAdmin.dashboard');
    Route::get('/super-admin/list-pengajuan', [ListPengajuanController::class, 'index'])->name('superAdmin.list.pengajuan');
    Route::get('/super-admin/list-pengajuan/{id}/edit', [ManajemenPengajuanController::class, 'show'])->name('superAdmin.list.pengajuan.edit');
    Route::put('/super-admin/list-pengajuan/{id}/update', [ManajemenPengajuanController::class, 'update'])->name('superAdmin.list.pengajuan.update');
    Route::delete('/super-admin/list-pengajuan/{id}/destroy', [ManajemenPengajuanController::class, 'destroy'])->name('superAdmin.list.pengajuan.delete');
    Route::get('/super-admin/list-pengajuan/{id}/detail', [ListPengajuanController::class, 'show'])->name('superAdmin.list.pengajuan.detail');
    Route::get('/super-admin/list-approval/{id}', [ListApprovalController::class, 'show'])->name('superAdmin.list.approval');
    Route::put('/super-admin/list-approval/{id}/update', [ListApprovalController::class, 'update'])->name('superAdmin.list.approval.update');
    Route::delete('/super-admin/list-approval/{id}/destroy', [ListApprovalController::class, 'destroy'])->name('superAdmin.list.approval.delete');
    Route::get('/super-admin/list-user', [ListUserController::class, 'index'])->name('superAdmin.list.user');
    Route::post('/super-admin/list-user/store', [ListUserController::class, 'store'])->name('superAdmin.list.user.store');
    Route::put('/super-admin/list-user/{id}/update', [ListUserController::class, 'update'])->name('superAdmin.list.user.update');
    Route::delete('/super-admin/list-user/{id}/destroy', [ListUserController::class, 'destroy'])->name('superAdmin.list.user.delete');

    //Pengajuan Luar
    Route::get('/super-admin/data-pengajuan-luar', [ListPengajuanLuarController::class, 'index'])->name('superAdmin.data.pengajuan.luar');
    Route::get('/super-admin/data-pengajuan-luar/{id}/detail', [ListPengajuanLuarController::class, 'show'])->name('superAdmin.data.pengajuan.luar.detail');
    Route::get('/super-admin/data-pengajuan-luar/{id}/edit', [ListPengajuanLuarController::class, 'edit'])->name('superAdmin.data.pengajuan.luar.edit');
    Route::put('/super-admin/data-pengajuan-luar/{id}/update', [ListPengajuanLuarController::class, 'update'])->name('superAdmin.data.pengajuan.luar.update');
    Route::delete('/super-admin/data-pengajuan-luar/{id}/destroy', [ListPengajuanLuarController::class, 'destroy'])->name('superAdmin.data.pengajuan.luar.delete');
    Route::get('/super-admin/list-approval-luar/{id}', [ListApprovalLuarController::class, 'show'])->name('superAdmin.list.approval.luar');
    Route::put('/super-admin/list-approval-luar/{id}/update', [ListApprovalLuarController::class, 'update'])->name('superAdmin.list.approval.luar.update');
    Route::delete('/super-admin/list-approval-luar/{id}/destroy', [ListApprovalLuarController::class, 'destroy'])->name('superAdmin.list.approval.luar.delete');
});
