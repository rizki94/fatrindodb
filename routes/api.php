<?php

use App\Http\Controllers\AccountReceivableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountReceivableTempController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ModuleGroupController;
use App\Http\Controllers\UserProfileController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login_native', [AuthController::class, 'loginNative']);
Route::get('/active_branch', [BranchController::class, 'activeBranch']);

Route::prefix('system_admin')->group(function () {
    Route::post('login', [AuthController::class, 'systemAdminLogin']);
    Route::prefix('activity_log')->group(function () {
        Route::get('list', [ActivityController::class, 'list']);
    });
});

Route::get('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkingauthenticated', [function () {
        return response()->json([
            'status' => 200,
            'message' => 'You are authenticated'
        ]);
    }]);

    Route::prefix('profile')->group(function () {
        Route::get('active', [ProfileController::class, 'active']);
        Route::get('list', [ProfileController::class, 'list']);
        Route::post('store', [ProfileController::class, 'store']);
        Route::put('{id}', [ProfileController::class, 'update']);
        Route::get('show', [ProfileController::class, 'show']);
    });

    Route::prefix('image')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
        Route::post('update', [ImageController::class, 'update']);
        Route::get('show', [ImageController::class, 'show']);
    });

    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('company')->group(function () {
        Route::get('list', [CompanyController::class, 'index']);
        Route::get('get', [CompanyController::class, 'get']);
        Route::post('store', [CompanyController::class, 'companyCreate']);
        Route::get('show', [CompanyController::class, 'companyShow']);
        Route::post('update', [CompanyController::class, 'companyUpdate']);
    });

    Route::get('branches', [BranchController::class, 'index']);
    Route::post('branch', [BranchController::class, 'branchCreate']);
    Route::get('branch/show', [BranchController::class, 'branchShow']);
    Route::put('branch/{id}', [BranchController::class, 'branchUpdate']);


    Route::get('active_roles', [RoleController::class, 'activeRole']);
    Route::get('roles', [RoleController::class, 'roleList']);
    Route::get('role/show', [RoleController::class, 'roleShow']);
    Route::post('role', [RoleController::class, 'roleCreate']);
    Route::put('role/{id}', [RoleController::class, 'roleUpdate']);
    Route::get('active_module_groups', [ModuleGroupController::class, 'activeModuleGroups']);
    Route::get('module_groups', [ModuleGroupController::class, 'moduleGroups']);
    Route::get('active_modules', [ModuleController::class, 'activeModules']);
    Route::get('modules', [ModuleController::class, 'index']);
    Route::get('permission', [PermissionController::class, 'permissionList']);

    Route::get('images', [ImageController::class, 'index']);
    Route::post('image', [ImageController::class, 'upload']);
    Route::get('imagedownload/{id}', [ImageController::class, 'download']);
    Route::get('filterimage', [ImageController::class, 'filterimage']);

    Route::prefix('status')->group(function () {
        Route::get('list', [StatusController::class, 'list']);
    });

    Route::get('users', [UserController::class, 'userList']);
    Route::post('user', [UserController::class, 'userCreate']);
    Route::prefix('user')->group(function () {
        Route::get('salesman', [UserController::class, 'indexsales']);
        Route::get('show', [UserController::class, 'userShow']);
        Route::post('update', [UserController::class, 'userUpdate']);
        Route::post('change_password', [UserController::class, 'changePassword']);
        Route::get('profile', [UserProfileController::class, 'userProfileList']);
        Route::get('roles', [RoleUserController::class, 'UserRoleList']);
        Route::post('roles', [RoleUserController::class, 'storeRoleUser']);
        Route::post('roles/delete', [RoleUserController::class, 'deleteRoleUser']);
        Route::post('profile/create', [UserProfileController::class, 'userProfileStore']);
        Route::get('prefix', [UserProfileController::class, 'prefixList']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('sales_by_salesman', [DashboardController::class, 'salesBySalesman']);
        Route::get('out_of_stock', [DashboardController::class, 'outOfStock']);
        Route::get('sales_return', [DashboardController::class, 'salesReturn']);
    });

    Route::prefix('account_receivable')->group(function () {
        Route::post('import', [AccountReceivableTempController::class, 'import']);
        Route::post('convert', [AccountReceivableTempController::class, 'convert']);
        Route::post('update', [AccountReceivableController::class, 'update']);
        Route::get('status_import', [AccountReceivableTempController::class, 'statusImport']);
        Route::get('list', [AccountReceivableController::class, 'list']);
    });

    Route::get('user_profile', [UserProfileController::class, 'userProfile']);
});
