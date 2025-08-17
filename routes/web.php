<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkTypeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // Route::group(['middleware' => ['role_or_permission:superadmin|sales']], function () {
    //     Route::get('sales', [SalesController::class, 'index'])->name('sales');
    // });

    Route::group(['middleware' => ['role_or_permission:superadmin|project']], function () {
        Route::resource('project', ProjectController::class)->names('project');
        Route::put('project-get-work_type', [ProjectController::class, 'get_work_type'])->name('project.get.work_type');
        Route::put('project-get-customer', [ProjectController::class, 'get_customer'])->name('project.get.customer');
        Route::put('project-update-status/{project}', [ProjectController::class, 'update_status'])->name('project.update.status');
        Route::put('project-cancel/{project}', [ProjectController::class, 'cancel'])->name('project.cancel');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|setting']], function () {
        Route::get('setting', [SettingController::class, 'index'])->name('setting');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|work_type']], function () {
        Route::resource('work_type', WorkTypeController::class)->names('work_type');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|task_board']], function () {
        Route::get('task_board', [TaskBoardController::class, 'index'])->name('task_board.index');
        Route::get('task_board/{project}', [TaskBoardController::class, 'show'])->name('task_board.show');

        /**
         * Pre sales
         */
        Route::group(['middleware' => ['role_or_permission:task_board.pre_sales']], function () {
            Route::put('task_board/take-survey/{project_survey}', [TaskBoardController::class, 'take_survey'])->name('task_board.take_survey');
            Route::put('task_board/hold-survey/{project_survey}', [TaskBoardController::class, 'hold_survey'])->name('task_board.hold_survey');
            Route::put('task_board/continue-survey/{project_survey}', [TaskBoardController::class, 'continue_survey'])->name('task_board.continue_survey');
            Route::put('task_board/finish-survey/{project_survey}', [TaskBoardController::class, 'finish_survey'])->name('task_board.finish_survey');
            Route::get('task_board/document-survey/{project_survey}', [TaskBoardController::class, 'document_survey'])->name('task_board.document_survey');
            Route::put('task_board/document-survey/{project_survey}', [TaskBoardController::class, 'document_survey_update'])->name('task_board.document_survey.update');
        });

        /**
         * Sales admin
         */
        Route::group(['middleware' => ['role_or_permission:task_board.sales_admin']], function () {
            Route::put('task_board/take-offer/{project_offer}', [TaskBoardController::class, 'take_offer'])->name('task_board.take_offer');
            Route::put('task_board/hold-offer/{project_offer}', [TaskBoardController::class, 'hold_offer'])->name('task_board.hold_offer');
            Route::put('task_board/continue-offer/{project_offer}', [TaskBoardController::class, 'continue_offer'])->name('task_board.continue_offer');
            Route::put('task_board/approval-offer/{project_offer}', [TaskBoardController::class, 'approval_offer'])->name('task_board.approval_offer');
            Route::put('task_board/finish-offer/{project_offer}', [TaskBoardController::class, 'finish_offer'])->name('task_board.finish_offer');
            Route::get('task_board/document-offer/{project_offer}', [TaskBoardController::class, 'document_offer'])->name('task_board.document_offer');
            Route::put('task_board/document-offer/{project_offer}', [TaskBoardController::class, 'document_offer_update'])->name('task_board.document_offer.update');

            Route::put('task_board/take-sales-order/{project_sales_order}', [TaskBoardController::class, 'take_sales_order'])->name('task_board.take_sales_order');
            Route::put('task_board/hold-sales-order/{project_sales_order}', [TaskBoardController::class, 'hold_sales_order'])->name('task_board.hold_sales_order');
            Route::put('task_board/continue-sales-order/{project_sales_order}', [TaskBoardController::class, 'continue_sales_order'])->name('task_board.continue_sales_order');
            Route::put('task_board/approval-sales-order/{project_sales_order}', [TaskBoardController::class, 'approval_sales_order'])->name('task_board.approval_sales_order');
            Route::put('task_board/finish-sales-order/{project_sales_order}', [TaskBoardController::class, 'finish_sales_order'])->name('task_board.finish_sales_order');
            Route::get('task_board/document-sales-order/{project_sales_order}', [TaskBoardController::class, 'document_sales_order'])->name('task_board.document_sales_order');
            Route::put('task_board/document-sales-order/{project_sales_order}', [TaskBoardController::class, 'document_sales_order_update'])->name('task_board.document_sales_order.update');
        });

        /**
         * Operation
         */

        /**
         * Finance Accounting
         */
        Route::group(['middleware' => ['role_or_permission:task_board.finance_accounting']], function () {
            Route::put('task_board/take-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'take_invoice_dp'])->name('task_board.take_invoice_dp');
            Route::put('task_board/hold-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'hold_invoice_dp'])->name('task_board.hold_invoice_dp');
            Route::put('task_board/continue-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'continue_invoice_dp'])->name('task_board.continue_invoice_dp');
            Route::put('task_board/approval-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'approval_invoice_dp'])->name('task_board.approval_invoice_dp');
            Route::put('task_board/finish-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'finish_invoice_dp'])->name('task_board.finish_invoice_dp');
            Route::get('task_board/document-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'document_invoice_dp'])->name('task_board.document_invoice_dp');
            Route::put('task_board/document-invoice_dp/{project_invoice_dp}', [TaskBoardController::class, 'document_invoice_dp_update'])->name('task_board.document_invoice_dp.update');
            Route::put('task_board/permit-to-wo/{project_invoice_dp}', [TaskBoardController::class, 'permit_to_wo'])->name('task_board.permit_to_wo');
        });

        Route::put('task_board/cancel', [TaskBoardController::class, 'cancel'])->name('task_board.cancel');
        Route::get('task_board-file-download/{file_upload}', [TaskBoardController::class, 'document_download'])->name('task_board.document_download');
        Route::delete('task_board', [TaskBoardController::class, 'destroy'])->name('task_board.delete');
        Route::delete('task_board/file-remove/{file_upload}', [TaskBoardController::class, 'document_remove'])->name('task_board.document_remove');
        Route::delete('task_board/link-remove/{file_upload}', [TaskBoardController::class, 'link_remove'])->name('task_board.link_remove');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|role']], function () {
        Route::resource('role', RoleController::class)->names('role');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|user']], function () {
        Route::resource('user', UserController::class)->names('user');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|brand']], function () {
        Route::resource('brand', BrandController::class)->names('brand');
    });

    Route::group(['middleware' => ['role_or_permission:superadmin|customer']], function () {
        Route::resource('customer', CustomerController::class)->names('customer');

        Route::get('customer/file-download/{file_upload}', [CustomerController::class, 'file_download'])->name('customer.file_download');
        Route::delete('customer/file-remove/{file_upload}', [CustomerController::class, 'file_remove'])->name('customer.file_remove');
    });

    Route::get('get_file_pdf', [HelperController::class, 'get_file_pdf'])->name('get_file_pdf');
    Route::get('get_file_image', [HelperController::class, 'get_file_image'])->name('get_file_image');

    Route::get('change_password', [ChangePasswordController::class, 'index'])->name('change_password.index');
    Route::put('change_password', [ChangePasswordController::class, 'store'])->name('change_password.store');
});
