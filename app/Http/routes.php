<?php
/*Route::post('login','Auth\AuthController@postLogin')->name('login');
Route::get('login','Auth\AuthController@getLogin');*/
Route::get('demo','TestController@demo');
Route::get('/','LoginController@getLogin');
Route::get('login','LoginController@getLogin')->name('login');
Route::get('logout','LoginController@getLogout');
Route::post('login','LoginController@postLogin');

Route::post('forgotAjax', 'UserController@forgotAjax');
Route::get('resetpass', 'UserController@resetpass');
Route::post('resetpass-ajax', 'UserController@resetpassAjax');
Route::post('insertAttendance','AttendanceController@insertAttendance');
Route::get('insertManual','AttendanceController@insertManual');
Route::get('cronjobManual','AttendanceController@cronjobManual');


// CRON JOBS
// Route::get('demo','LoginController@getDemo');
// Route::get('salary_status_done_batv','CronjobsController@getSalaryDoneAuto');
// Route::get('email_salary_batv','CronjobsController@emailSalaryAuto');
Route::get('remind_notification_review_salary','CronjobsController@remindNotificationReviewSalary');
Route::get('salary_batv','CronjobsController@getSalaryAuto');
Route::get('allowance_batv','CronjobsController@getAllowanceAuto'); // TẠM TẮT ĐI, SAU KHI NHẬN THƯỞNG TẾT MỞ LẠI
Route::get('taxinsurrance_batv','CronjobsController@getTaxInsurranceAuto');
Route::get('attendance_auto_batv','CronjobsController@getAttendanceAuto');
// Route::get('salaryother_batv','CronjobsController@getSalaryOtherAuto');
// Route::get('send_mail_register_overtime_batv','CronjobsController@sendMailRegisterOvertimeAuto');
Route::get('remind_pay_loan_capital_auto_batv','CronjobsController@remindPayLoanCapitalAutoBatv');
Route::get('update_all_evaluate_faith_auto_batv','CronjobsController@updateAllEvaluateFaith');
Route::get('calculate_pay_loan_capital_auto_batv','CronjobsController@calculatePayLoanCapitalAutoBatv');
Route::get('remind_approved_report_auto_batv','CronjobsController@remindApprovedReportAutoBatv');


Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix'=>'toh_hrm'],function(){
		Route::get('/','HomeController@getIndex');

		Route::group(['prefix' => 'roles'],function () {
			Route::get('list',['as' => 'getRoleList', 'uses' => 'RoleController@getRoleList']);
			Route::get('add',['as' => 'getRoleAdd', 'uses' => 'RoleController@getRoleAdd']);
	        Route::post('add',['as' => 'postRoleAdd', 'uses' => 'RoleController@postRoleAdd']);
	        Route::get('edit/{id}',['as' => 'getRoleEdit', 'uses' => 'RoleController@getRoleEdit']);
	        Route::post('edit/{id}',['as' => 'postRoleEdit', 'uses' => 'RoleController@postRoleEdit']);
	        Route::get('del/{id}',['as' => 'getRoleDel', 'uses' => 'RoleController@getRoleDel'])->where('id', '[0-9]+');
	        /*Route::get('delete/{id}',['as' => 'getRoleDel', 'uses' => 'RoleController@getRoleDel'])->where('id', '[0-9]+');*/    
		});
		Route::group(['prefix' => 'user'],function () {
			Route::get('list',['as' => 'getUserList', 'uses' => 'UserController@getUserList']);
			Route::get('add',['as' => 'getUserAdd', 'uses' => 'UserController@getUserAdd']);
	        Route::post('add',['as' => 'postUserAdd', 'uses' => 'UserController@postUserAdd']);
	        Route::get('edit/{id}',['as' => 'getUserEdit', 'uses' => 'UserController@getUserEdit']);
	        Route::post('edit/{id}',['as' => 'postUserEdit', 'uses' => 'UserController@postUserEdit']);
	        Route::get('del/{id}',['as' => 'getUserDel', 'uses' => 'UserController@getUserDel'])->where('id', '[0-9]+');
		});

		Route::group(['prefix' => 'tintuc'],function () {
			Route::get('danhsach',['as' => 'getNewsList', 'uses' => 'NewsController@getNewsList']);
			Route::get('themtintuc',['as' => 'getNewsAdd', 'uses' => 'NewsController@getNewsAdd']);
	        Route::post('themtintuc',['as' => 'postNewsAdd', 'uses' => 'NewsController@postNewsAdd']);
	        Route::get('suatintuc/{id}',['as' => 'getNewsEdit', 'uses' => 'NewsController@getNewsEdit'])->where('id','[0-9]+');
	        Route::post('suatintuc/{id}',['as' => 'postNewsEdit', 'uses' => 'NewsController@postNewsEdit'])->where('id','[0-9]+');
	        Route::get('xoatintuc/{id}',['as' => 'getNewsDel', 'uses' => 'NewsController@getNewsDel'])->where('id','[0-9]+');
	        Route::get('danhsachtinnoibat',['as' => 'getNewsListHighlight', 'uses' => 'NewsController@getNewsListHighlight']);
	        Route::get('danhsachtinkhac',['as' => 'getNewsListOther', 'uses' => 'NewsController@getNewsListOther']);
		});

		Route::group(['prefix' => 'chiphi'],function () {
			Route::get('danhsachquy',['as' => 'getFundsList', 'uses' => 'ExpenseController@getFundsList']);
			Route::get('themquy',['as' => 'getFundsAdd', 'uses' => 'ExpenseController@getFundsAdd']);
	        Route::post('themquy',['as' => 'postFundsAdd', 'uses' => 'ExpenseController@postFundsAdd']);
	        Route::get('suaquy/{id}',['as' => 'getFundsEdit', 'uses' => 'ExpenseController@getFundsEdit'])->where('id','[0-9]+');
	        Route::post('suaquy/{id}',['as' => 'postFundsEdit', 'uses' => 'ExpenseController@postFundsEdit'])->where('id','[0-9]+');
	        Route::get('xoaquy/{id}',['as' => 'getFundsDel', 'uses' => 'ExpenseController@getFundsDel'])->where('id','[0-9]+');

			Route::get('danhsachchiphi',['as' => 'getExpenseList', 'uses' => 'ExpenseController@getExpenseList']);
			Route::get('themchiphi',['as' => 'getExpenseAdd', 'uses' => 'ExpenseController@getExpenseAdd']);
	        Route::post('themchiphi',['as' => 'postExpenseAdd', 'uses' => 'ExpenseController@postExpenseAdd']);
	        Route::get('suachiphi/{id}',['as' => 'getExpenseEdit', 'uses' => 'ExpenseController@getExpenseEdit'])->where('id','[0-9]+');
	        Route::post('suachiphi/{id}',['as' => 'postExpenseEdit', 'uses' => 'ExpenseController@postExpenseEdit'])->where('id','[0-9]+');
	        Route::get('xoachiphi/{id}',['as' => 'getExpenseDel', 'uses' => 'ExpenseController@getExpenseDel'])->where('id','[0-9]+');

	        Route::get('xemchitietchiphi/{id}',['as' => 'viewExpenseDetail', 'uses' => 'ExpenseController@viewExpenseDetail'])->where('id','[0-9]+');
			Route::get('tonghopchiphi',['as' => 'getExpenseGeneral', 'uses' => 'ExpenseController@getExpenseGeneral']);

			Route::get('danhsachkyquy',['as' => 'getSignFundsList', 'uses' => 'ExpenseController@getSignFundsList']);
			Route::get('themkyquy',['as' => 'getSignFundsAdd', 'uses' => 'ExpenseController@getSignFundsAdd']);
	        Route::post('themkyquy',['as' => 'postSignFundsAdd', 'uses' => 'ExpenseController@postSignFundsAdd']);
	        Route::get('suakyquy/{id}',['as' => 'getSignFundsEdit', 'uses' => 'ExpenseController@getSignFundsEdit'])->where('id','[0-9]+');
	        Route::post('suakyquy/{id}',['as' => 'postSignFundsEdit', 'uses' => 'ExpenseController@postSignFundsEdit'])->where('id','[0-9]+');
	        Route::get('xoakyquy/{id}',['as' => 'signFundsDel', 'uses' => 'ExpenseController@signFundsDel'])->where('id','[0-9]+');

			Route::get('danhsachchitieuquyphucloi',['as' => 'getWelfareFundsList', 'uses' => 'ExpenseController@getWelfareFundsList']);
			Route::post('danhsachchitieuquyphucloi',['as' => 'postWelfareFundsList', 'uses' => 'ExpenseController@postWelfareFundsList']);
			Route::get('themchitieuquyphucloi',['as' => 'getWelfareFundsAdd', 'uses' => 'ExpenseController@getWelfareFundsAdd']);
	        Route::post('themchitieuquyphucloi',['as' => 'postWelfareFundsAdd', 'uses' => 'ExpenseController@postWelfareFundsAdd']);
	        Route::get('suachitieuquyphucloi/{id}',['as' => 'getWelfareFundsEdit', 'uses' => 'ExpenseController@getWelfareFundsEdit'])->where('id','[0-9]+');
	        Route::post('suachitieuquyphucloi/{id}',['as' => 'postWelfareFundsEdit', 'uses' => 'ExpenseController@postWelfareFundsEdit'])->where('id','[0-9]+');
	        Route::get('xoachitieuquyphucloi/{id}',['as' => 'welfareFundsDel', 'uses' => 'ExpenseController@welfareFundsDel'])->where('id','[0-9]+');

			Route::get('danhsachcauhinhngoaite',['as' => 'getSettingCurrency', 'uses' => 'ExpenseController@getSettingCurrency']);
			Route::get('themcauhinhngoaite',['as' => 'getSettingCurrencyAdd', 'uses' => 'ExpenseController@getSettingCurrencyAdd']);
	        Route::post('themcauhinhngoaite',['as' => 'postSettingCurrencyAdd', 'uses' => 'ExpenseController@postSettingCurrencyAdd']);
	        Route::get('suacauhinhngoaite/{id}',['as' => 'getSettingCurrencyEdit', 'uses' => 'ExpenseController@getSettingCurrencyEdit'])->where('id','[0-9]+');
	        Route::post('suacauhinhngoaite/{id}',['as' => 'postSettingCurrencyEdit', 'uses' => 'ExpenseController@postSettingCurrencyEdit'])->where('id','[0-9]+');
	        Route::get('xoacauhinhngoaite/{id}',['as' => 'getSettingCurrencyDel', 'uses' => 'ExpenseController@getSettingCurrencyDel'])->where('id','[0-9]+');
		});

		Route::group(['prefix' => 'taikhoan'],function () {
			Route::get('thongtin',['as' => 'getTaikhoanInfo', 'uses' => 'TaikhoanController@getTaikhoanInfo']);
		    Route::get('editpass',['as' => 'getTaikhoanEditPass','uses' => 'TaikhoanController@getTaikhoanEditPass']);
		    Route::post('editpass',['as' => 'postTaikhoanEditPass','uses' => 'TaikhoanController@postTaikhoanEditPass']);
		    Route::get('editinfo',['as' => 'getTaikhoanEditInfo','uses' => 'TaikhoanController@getTaikhoanEditInfo']);
		    Route::post('editinfo',['as' => 'postTaikhoanEditInfo','uses' => 'TaikhoanController@postTaikhoanEditInfo']);
		});
		Route::group(['prefix'=>'hoso'],function(){
			Route::get('thongtin',['as' => 'getHosoInfo','uses'=>'PersonnelController@getHosoInfo']);
			Route::post('thongtin',['as' => 'postHosoInfo','uses'=>'PersonnelController@postHosoInfo']);
			Route::get('phongban',['as' => 'getDepartment','uses'=>'PersonnelController@getDepartment']);
			Route::get('themphongban',['as' => 'addDepartment','uses'=>'PersonnelController@addDepartment']);
			Route::post('themphongban',['as' => 'postDepartment','uses'=>'PersonnelController@postDepartment']);
			Route::get('suaphongban/{id}',['as'=>'getDepartmentEdit','uses'=>'PersonnelController@getDepartmentEdit']);
			Route::post('suaphongban/{id}',['as'=>'postDepartmentEdit','uses'=>'PersonnelController@postDepartmentEdit']);
			Route::get('xoaphongban/{id}',['as' => 'getDepartmentDel', 'uses' => 'PersonnelController@getDepartmentDel'])->where('id', '[0-9]+');
			Route::get('chucdanh',['as' => 'getJobTitles','uses'=>'PersonnelController@getJobTitles']);
			Route::get('themchucdanh',['as' => 'addJobTitles','uses'=>'PersonnelController@addJobTitles']);
			Route::post('themchucdanh',['as' => 'postJobTitles','uses'=>'PersonnelController@postJobTitles']);
			Route::get('suachucdanh/{id}',['as'=>'getJobTitlesEdit','uses'=>'PersonnelController@getJobTitlesEdit']);
			Route::post('suachucdanh/{id}',['as'=>'postJobTitlesEdit','uses'=>'PersonnelController@postJobTitlesEdit']);
			Route::get('xoachucdanh/{id}',['as' => 'getJobTitlesDel', 'uses' => 'PersonnelController@getJobTitlesDel'])->where('id', '[0-9]+');
			Route::get('hopdong',['as' => 'getContract','uses'=>'PersonnelController@getContract']);
			Route::get('themhopdong',['as' => 'addContract','uses'=>'PersonnelController@addContract']);
			Route::post('themhopdong',['as' => 'postContract','uses'=>'PersonnelController@postContract']);
			Route::get('suahopdong/{id}',['as'=>'getContractEdit','uses'=>'PersonnelController@getContractEdit']);
			Route::post('suahopdong/{id}',['as'=>'postContractEdit','uses'=>'PersonnelController@postContractEdit']);
			Route::get('xoahopdong/{id}',['as' => 'getContractDel', 'uses' => 'PersonnelController@getContractDel'])->where('id', '[0-9]+');
			Route::get('congtac',['as'=>'getHosoCongtac','uses' => 'PersonnelController@getHosoCongtac']);
			Route::get('thongtin/capnhat/{id}',['as' => 'getHosoEditInfo','uses'=>'PersonnelController@getHosoEditInfo']);
			Route::post('thongtin/capnhat/{id}',['as' => 'postHosoEditInfo','uses'=>'PersonnelController@postHosoEditInfo']);
			//
			Route::get('list',['as'=>'getPersonnelList','uses'=>'PersonnelController@getPersonnelList']);
			Route::get('edit/{id}',['as'=>'getPersonnelEdit','uses'=>'PersonnelController@getPersonnelEdit']);
			Route::post('edit/{id}',['as'=>'postPersonnelEdit','uses'=>'PersonnelController@postPersonnelEdit']);
			Route::get('add',['as'=>'getPersonnelAdd', 'uses'=>'PersonnelController@getPersonnelAdd']);
			Route::post('add',['as'=>'postPersonnelAdd', 'uses'=>'PersonnelController@postPersonnelAdd']);
			Route::get('del/{id}',['as' => 'getPersonnelDel', 'uses' => 'PersonnelController@getPersonnelDel'])->where('id', '[0-9]+');
			Route::get('assign/{id}',['as'=>'getPersonnelAssign','uses'=>'PersonnelController@getPersonnelAssign'])->where('id', '[0-9]+');
			Route::post('assign/{id}',['as'=>'postPersonnelAssign','uses'=>'PersonnelController@postPersonnelAssign'])->where('id', '[0-9]+');

			Route::get('chitietquy/{id}',['as'=>'getFundsDetail','uses'=>'PersonnelController@getFundsDetail'])->where('id', '[0-9]+');	
			Route::get('themquynhanvien/{id}',['as'=>'getFundsAddPersonnel', 'uses'=>'PersonnelController@getFundsAddPersonnel'])->where('id', '[0-9]+');
			Route::post('themquynhanvien/{id}',['as'=>'postFundsAddPersonnel', 'uses'=>'PersonnelController@postFundsAddPersonnel'])->where('id', '[0-9]+');

			Route::get('chitietthoigiannghithaisan/{id}',['as'=>'getMaternityLeave','uses'=>'PersonnelController@getMaternityLeave'])->where('id', '[0-9]+');	
			Route::get('themthoigiannghithaisan/{id}',['as'=>'addMaternityLeave', 'uses'=>'PersonnelController@addMaternityLeave'])->where('id', '[0-9]+');
			Route::post('themthoigiannghithaisan/{id}',['as'=>'postAddMaternityLeave', 'uses'=>'PersonnelController@postAddMaternityLeave'])->where('id', '[0-9]+');
			Route::get('suathoigiannghithaisan/{personal}/{id}',['as'=>'editMaternityLeave','uses'=>'PersonnelController@editMaternityLeave'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::post('suathoigiannghithaisan/{personal}/{id}',['as'=>'postEditMaternityLeave','uses'=>'PersonnelController@postEditMaternityLeave'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::get('xoathoigiannghithaisan/{personal_id}/{id}',['as' => 'delMaternityLeave', 'uses' => 'PersonnelController@delMaternityLeave'])->where(['personal_id' => '[0-9]+','id'=> '[0-9]+']);

			Route::get('suaquynhanvien/{personal}/{id}',['as'=>'getFundsEditPersonnel','uses'=>'PersonnelController@getFundsEditPersonnel'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::post('suaquynhanvien/{personal}/{id}',['as'=>'postFundsEditPersonnel','uses'=>'PersonnelController@postFundsEditPersonnel'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::get('xoaquynhanvien/{personal_id}/{id}',['as' => 'getFundsPersonnelDel', 'uses' => 'PersonnelController@getFundsPersonnelDel'])->where(['personal_id' => '[0-9]+','id'=> '[0-9]+']);

			Route::get('nhanvientructhuoc',['as'=>'getFilePersonnelByManger','uses'=>'PersonnelController@getFilePersonnelByManger']);
			Route::get('thongtinnhanvientructhuoc',['as'=>'getFilePersonnelByMangerAjax','uses'=>'PersonnelController@getFilePersonnelByMangerAjax']);
		});
		Route::group(['prefix'=>'quatrinh'],function(){
			//
			Route::get('list',['as'=>'getHistoryList','uses'=>'HistoryController@getHistoryList']);
			Route::get('detail/{id}',['as'=>'getHistoryDetail','uses'=>'HistoryController@getHistoryDetail'])->where('id', '[0-9]+');
			Route::get('add/{id}',['as'=>'getHistoryAdd', 'uses'=>'HistoryController@getHistoryAdd'])->where('id', '[0-9]+');
			Route::post('add/{id}',['as'=>'postHistoryAdd', 'uses'=>'HistoryController@postHistoryAdd'])->where('id', '[0-9]+');
			Route::get('edit/{personal}/{id}',['as'=>'getHistoryEdit','uses'=>'HistoryController@getHistoryEdit'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::post('edit/{personal}/{id}',['as'=>'postHistoryEdit','uses'=>'HistoryController@postHistoryEdit'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::get('del/{personal_id}/{id}',['as' => 'getHistoryDel', 'uses' => 'HistoryController@getHistoryDel'])->where(['personal_id' => '[0-9]+','id'=> '[0-9]+']);

			//
			Route::get('addratio/{id}',['as'=>'getHistoryAddRatio','uses'=>'HistoryController@getHistoryAddRatio'])->where('id', '[0-9]+');
			Route::post('addratio/{id}',['as'=>'postHistoryAddRatio','uses'=>'HistoryController@postHistoryAddRatio'])->where('id', '[0-9]+');
			Route::get('editratio/{personal}/{id}',['as'=>'getHistoryEditRatio','uses'=>'HistoryController@getHistoryEditRatio'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::post('editratio/{personal}/{id}',['as'=>'postHistoryEditRatio','uses'=>'HistoryController@postHistoryEditRatio'])->where(['personal' => '[0-9]+','id'=> '[0-9]+']);
			Route::get('delratio/{personal_id}/{id}',['as' => 'getHistoryDelRatio', 'uses' => 'HistoryController@getHistoryDelRatio'])->where(['personal_id' => '[0-9]+','id'=> '[0-9]+']);
		});

		Route::group(['prefix'=>'chamcong'],function(){
			Route::get('list',['as'=>'getAttendanceList','uses'=>'AttendanceController@getAttendanceList']);
			Route::post('list',['as'=>'postAttendanceList','uses'=>'AttendanceController@postAttendanceList']);
			// Route::get('lichsu',['as'=>'listAttendanceHistory','uses'=>'AttendanceController@listAttendanceHistory']);
			// Route::post('lichsu',['as'=>'postlistAttendanceHistory','uses'=>'AttendanceController@postlistAttendanceHistory']);

			Route::get('tonghop',['as'=>'getAttendanceTotal','uses'=>'AttendanceController@getAttendanceTotal']);
			Route::get('dilam',['as'=>'getAttendanceWork','uses'=>'AttendanceController@getAttendanceWork']);
			Route::get('dimuon',['as'=>'getAttendanceWorkLate','uses'=>'AttendanceController@getAttendanceWorkLate']);
			Route::get('ngayphep',['as'=>'getAttendanceWorkHoliday','uses'=>'AttendanceController@getAttendanceWorkHoliday']);
		});


		Route::group(['prefix'=>'lam-them-gio'],function(){
			Route::get('index', 'OvertimeController@index');
			Route::get('dang-ky','OvertimeController@registerOvertime');
			Route::get('quan-ly','OvertimeController@managerOvertime')->name('lam-them-gio.quan-ly');
			Route::get('giam-sat','OvertimeController@managerAttendanceOvertime');
			Route::get('cau-hinh','OvertimeController@settingOvertime');
			// Route::get('dilam',['as'=>'getAttendanceWork','uses'=>'AttendanceController@getAttendanceWork']);
			// Route::get('dimuon',['as'=>'getAttendanceWorkLate','uses'=>'AttendanceController@getAttendanceWorkLate']);
			// Route::get('ngayphep',['as'=>'getAttendanceWorkHoliday','uses'=>'AttendanceController@getAttendanceWorkHoliday']);
		});

		Route::group(['prefix'=>'vay-von'],function(){
			Route::get('index', 'LoanCapitalController@index');
			Route::get('cau-hinh','LoanCapitalController@settingLoanCapital');
			Route::get('quan-ly','LoanCapitalController@managerLoanCapital');
			Route::get('quan-ly/{loan_capital_id}',['as'=>'detailLoanCapital','uses'=>'LoanCapitalController@detailLoanCapital'])->where('loan_capital_id', '[0-9]+');
			Route::get('diem-tin-nhiem','LoanCapitalController@scoreFaithLoanCapital');
		});

		Route::group(['prefix'=>'danhgia'],function(){
			Route::get('viethuongdan',['as'=>'getEvaluationSupport','uses'=>'EvaluationController@getEvaluationSupport']);
			Route::post('viethuongdan',['as'=>'postEvaluationSupport','uses'=>'EvaluationController@postEvaluationSupport']);
			Route::get('suahuongdan/{id}',['as'=>'editEvaluationSupport','uses'=>'EvaluationController@editEvaluationSupport'])->where(['id'=> '[0-9]+']);
			Route::post('suahuongdan/{id}',['as'=>'postEvaluationSupportDetail','uses'=>'EvaluationController@postEvaluationSupportDetail'])->where(['id'=> '[0-9]+']);
			Route::get('xoahuongdan/{id}',['as' => 'deleteEvaluationSupport', 'uses' => 'EvaluationController@deleteEvaluationSupport'])->where('id', '[0-9]+');
			Route::get('danhsachtieuchi',['as'=>'getEvaluationCriteria','uses'=>'EvaluationController@getEvaluationCriteria']);
			Route::get('themtieuchi',['as'=>'addEvaluationCriteria','uses'=>'EvaluationController@addEvaluationCriteria']);
			Route::post('themtieuchi',['as'=>'postaddEvaluationCriteria','uses'=>'EvaluationController@postaddEvaluationCriteria']);
			Route::get('suatieuchi/{id}',['as'=>'editEvaluationCriteria','uses'=>'EvaluationController@editEvaluationCriteria'])->where(['id'=> '[0-9]+']);
			Route::post('suatieuchi/{id}',['as'=>'postEditEvaluationCriteria','uses'=>'EvaluationController@postEditEvaluationCriteria'])->where(['id'=> '[0-9]+']);
			Route::get('danhsachbotieuchi',['as'=>'listDepartmentCriteria','uses'=>'EvaluationController@listDepartmentCriteria']);
			Route::get('xoabotieuchi/{id}',['as'=>'deleteDepartmentCriteria','uses'=>'EvaluationController@deleteDepartmentCriteria'])->where(['id'=> '[0-9]+']);
			Route::get('suabotieuchi/{id}',['as'=>'editDepartmentCriteria','uses'=>'EvaluationController@editDepartmentCriteria'])->where(['id'=> '[0-9]+']);
			Route::post('suabotieuchi/{id}',['as'=>'postEditDepartmentCriteria','uses'=>'EvaluationController@postEditDepartmentCriteria'])->where(['id'=> '[0-9]+']);
			Route::get('caidat',['as'=>'settingEvaluationCriteria','uses'=>'EvaluationController@settingEvaluationCriteria']);
			Route::post('caidat',['as'=>'postsettingEvaluationCriteria','uses'=>'EvaluationController@postsettingEvaluationCriteria']);
			Route::get('xemhuongdan',['as'=>'viewEvaluationSupport','uses'=>'EvaluationController@viewEvaluationSupport']);
			Route::get('tudanhgiathang',['as'=>'getEvaluationMonthbyUser','uses'=>'EvaluationController@getEvaluationMonthbyUser']);
			Route::post('tudanhgiathang',['as'=>'postEvaluationMonthbyUser','uses'=>'EvaluationController@postEvaluationMonthbyUser']);
			Route::get('suatudanhgiathang/{id}',['as'=>'getEvaluationMonthbyUserEdit','uses'=>'EvaluationController@getEvaluationMonthbyUserEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suatudanhgiathang/{id}',['as'=>'postEvaluationMonthbyUserEdit','uses'=>'EvaluationController@postEvaluationMonthbyUserEdit'])->where('id', '[0-9]+');
			Route::get('danhgiaquanlytheothang',['as'=>'getEvaluationManagerbyMonth','uses'=>'EvaluationController@getEvaluationManagerbyMonth']);
			Route::post('danhgiaquanlytheothang',['as'=>'postEvaluationManagerbyMonth','uses'=>'EvaluationController@postEvaluationManagerbyMonth']);
			Route::get('suadanhgiaquanlytheothang/{id}',['as'=>'getEvaluationManagerbyMonthEdit','uses'=>'EvaluationController@getEvaluationManagerbyMonthEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suadanhgiaquanlytheothang/{id}',['as'=>'postEvaluationManagerbyMonthEdit','uses'=>'EvaluationController@postEvaluationManagerbyMonthEdit'])->where('id', '[0-9]+');
			Route::get('danhsachnhanvientructhuoctheothang',['as'=>'listPersonnelbyManger_Month','uses'=>'EvaluationController@listPersonnelbyManger_Month']);
			Route::get('quanlydanhgiathang/{id}',['as'=>'getEvaluationMonthbyManager','uses'=>'EvaluationController@getEvaluationMonthbyManager'])->where(['id'=> '[0-9]+']);
			Route::post('quanlydanhgiathang/{id}',['as'=>'postEvaluationMonthbyManager','uses'=>'EvaluationController@postEvaluationMonthbyManager'])->where('id', '[0-9]+');
			Route::get('suaquanlydanhgiathang/{id}',['as'=>'getEvaluationMonthbyManagerEdit','uses'=>'EvaluationController@getEvaluationMonthbyManagerEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suaquanlydanhgiathang/{id}',['as'=>'postEvaluationMonthbyManagerEdit','uses'=>'EvaluationController@postEvaluationMonthbyManagerEdit'])->where('id', '[0-9]+');

			Route::get('tudanhgianam',['as'=>'getEvaluationYearbyUser','uses'=>'EvaluationController@getEvaluationYearbyUser']);
			Route::post('tudanhgianam',['as'=>'postEvaluationYearbyUser','uses'=>'EvaluationController@postEvaluationYearbyUser']);
			Route::get('suatudanhgianam/{id}',['as'=>'getEvaluationYearbyUserEdit','uses'=>'EvaluationController@getEvaluationYearbyUserEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suatudanhgianam/{id}',['as'=>'postEvaluationYearbyUserEdit','uses'=>'EvaluationController@postEvaluationYearbyUserEdit'])->where('id', '[0-9]+');
			Route::get('danhgiaquanlytheonam',['as'=>'getEvaluationManagerbyYear','uses'=>'EvaluationController@getEvaluationManagerbyYear']);
			Route::post('danhgiaquanlytheonam',['as'=>'postEvaluationManagerbyYear','uses'=>'EvaluationController@postEvaluationManagerbyYear']);
			Route::get('suadanhgiaquanlytheonam/{id}',['as'=>'getEvaluationManagerbyYearEdit','uses'=>'EvaluationController@getEvaluationManagerbyYearEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suadanhgiaquanlytheonam/{id}',['as'=>'postEvaluationManagerbyYearEdit','uses'=>'EvaluationController@postEvaluationManagerbyYearEdit'])->where('id', '[0-9]+');
			Route::get('tonghopdiemdanhgiatheonam',['as'=>'getResultEvaluationManagerbyYear','uses'=>'EvaluationController@getResultEvaluationManagerbyYear']);
			Route::get('danhsachnhanvientructhuoctheonam',['as'=>'listPersonnelbyManger_Year','uses'=>'EvaluationController@listPersonnelbyManger_Year']);
			Route::get('quanlydanhgianam/{id}/{year}/{turns}',['as'=>'getEvaluationYearbyManager','uses'=>'EvaluationController@getEvaluationYearbyManager'])->where(['id' => '[0-9]+','year'=> '[0-9]+','turns'=> '[0-9]+']);
			Route::post('quanlydanhgianam/{id}/{year}/{turns}',['as'=>'postEvaluationYearbyManager','uses'=>'EvaluationController@postEvaluationYearbyManager'])->where(['id' => '[0-9]+','year'=> '[0-9]+','turns'=> '[0-9]+']);
			Route::get('suaquanlydanhgianam/{id}/{year}/{turns}',['as'=>'getEvaluationYearbyManagerEdit','uses'=>'EvaluationController@getEvaluationYearbyManagerEdit'])->where(['id' => '[0-9]+','year'=> '[0-9]+','turns'=> '[0-9]+']);
			Route::post('suaquanlydanhgianam/{id}/{year}/{turns}',['as'=>'postEvaluationYearbyManagerEdit','uses'=>'EvaluationController@postEvaluationYearbyManagerEdit'])->where(['id' => '[0-9]+','year'=> '[0-9]+','turns'=> '[0-9]+']);
			Route::get('nhanvien/{id}',['as' => 'getEvaluationItem','uses'=>'EvaluationController@getEvaluationItem']);
			// Route::post('nhanvien/{id}',['as' => 'postHosoInfo','uses'=>'PersonnelController@postHosoInfo']);
			// Route::get('tonghop',['as'=>'getAttendanceTotal','uses'=>'AttendanceController@getAttendanceTotal']);
			// Route::get('dilam',['as'=>'getAttendanceWork','uses'=>'AttendanceController@getAttendanceWork']);
			// Route::get('dimuon',['as'=>'getAttendanceWorkLate','uses'=>'AttendanceController@getAttendanceWorkLate']);
			// Route::get('ngayphep',['as'=>'getAttendanceWorkHoliday','uses'=>'AttendanceController@getAttendanceWorkHoliday']);
			Route::get('diem-tin-nhiem','EvaluationController@getScoreFaith')->name('danhgia.diem-tin-nhiem');
		});

		Route::group(['prefix'=>'luongthuong'],function(){
			Route::get('danhsachchitieuquyphucloi',['as' => 'getWelfareFundsListClient', 'uses' => 'SalaryController@getWelfareFundsListClient']);
			Route::get('cauhinh',['as'=>'getSalaryConfig','uses'=>'SalaryController@getSalaryConfig']);
			Route::post('cauhinh',['as'=>'postSalaryConfig','uses'=>'SalaryController@postSalaryConfig']);
			Route::get('cauhinhthamso',['as'=>'getParametersConfig','uses'=>'SalaryController@getParametersConfig']);
			Route::get('themcauhinhthamso',['as'=>'addParametersConfig','uses'=>'SalaryController@addParametersConfig']);
			Route::post('themcauhinhthamso',['as'=>'postParametersConfig','uses'=>'SalaryController@postParametersConfig']);
			Route::get('suacauhinhthamso/{id}',['as'=>'editParametersConfig','uses'=>'SalaryController@editParametersConfig'])->where('id', '[0-9]+');
			Route::post('suacauhinhthamso/{id}',['as'=>'postEditParametersConfig','uses'=>'SalaryController@postEditParametersConfig'])->where('id', '[0-9]+');
			Route::get('xoacauhinhthamso/{id}',['as'=>'deleteParametersConfig','uses'=>'SalaryController@deleteParametersConfig'])->where(['id'=> '[0-9]+']);
			Route::get('cauhinhnhomnguoi',['as'=>'getGroupPersonalConfig','uses'=>'SalaryController@getGroupPersonalConfig']);		
			Route::get('themcauhinhnhomnguoi',['as'=>'addGroupPersonalConfig','uses'=>'SalaryController@addGroupPersonalConfig']);
			Route::post('themcauhinhnhomnguoi',['as'=>'postGroupPersonalConfig','uses'=>'SalaryController@postGroupPersonalConfig']);
			Route::get('suacauhinhnhomnguoi/{id}',['as'=>'editGroupPersonalConfig','uses'=>'SalaryController@editGroupPersonalConfig'])->where('id', '[0-9]+');
			Route::post('suacauhinhnhomnguoi/{id}',['as'=>'postEditGroupPersonalConfig','uses'=>'SalaryController@postEditGroupPersonalConfig'])->where('id', '[0-9]+');
			Route::get('xoacauhinhnhomnguoi/{id}',['as'=>'deleteGroupPersonalConfig','uses'=>'SalaryController@deleteGroupPersonalConfig'])->where(['id'=> '[0-9]+']);	

			Route::get('cauhinhcongthuc',['as'=>'getRecipeConfig','uses'=>'SalaryController@getRecipeConfig']);		
			Route::get('themcauhinhcongthuc',['as'=>'addRecipeConfig','uses'=>'SalaryController@addRecipeConfig']);
			Route::post('themcauhinhcongthuc',['as'=>'postRecipeConfig','uses'=>'SalaryController@postRecipeConfig']);
			Route::get('suacauhinhcongthuc/{id}',['as'=>'editRecipeConfig','uses'=>'SalaryController@editRecipeConfig'])->where('id', '[0-9]+');

			Route::get('xemcauhinhkihieusuatnam',['as'=>'viewConfigKiPerformance','uses'=>'SalaryController@viewConfigKiPerformance']);
			Route::get('themcauhinhkihieusuatnam',['as'=>'addConfigKiPerformance','uses'=>'SalaryController@addConfigKiPerformance']);
			Route::post('themcauhinhkihieusuatnam',['as'=>'postAddConfigKiPerformance','uses'=>'SalaryController@postAddConfigKiPerformance']);
			Route::get('suacauhinhkihieusuatnam/{id}',['as'=>'editConfigKiPerformance','uses'=>'SalaryController@editConfigKiPerformance'])->where('id', '[0-9]+');
			Route::post('suacauhinhkihieusuatnam/{id}',['as'=>'postEditConfigKiPerformance','uses'=>'SalaryController@postEditConfigKiPerformance'])->where('id', '[0-9]+');
			Route::get('xoacauhinhkihieusuatnam/{id}',['as'=>'deleteConfigKiPerformance','uses'=>'SalaryController@deleteConfigKiPerformance'])->where(['id'=> '[0-9]+']);	
			
			Route::get('danhsachkinoiquynam',['as'=>'getKiRules','uses'=>'SalaryController@getKiRules']);
			Route::get('cauhinhki',['as'=>'settingKi','uses'=>'SalaryController@settingKi']);
			Route::get('cauhinhkinoiquynam',['as'=>'settingConfigKiRules','uses'=>'SalaryController@settingConfigKiRules']);
			Route::post('cauhinhkinoiquynam',['as'=>'postSettingConfigKiRules','uses'=>'SalaryController@postSettingConfigKiRules']);

			Route::get('xoacauhinhcongthuc/{id}',['as'=>'deleteRecipeConfig','uses'=>'SalaryController@deleteRecipeConfig'])->where(['id'=> '[0-9]+']);	
			Route::get('cauhinhngaynghile',['as'=>'getHolidaysConfig','uses'=>'SalaryController@getHolidaysConfig']);
			Route::get('themngaynghile',['as'=>'addHolidays','uses'=>'SalaryController@addHolidays']);
			Route::post('themngaynghile',['as'=>'postHolidaysAdd','uses'=>'SalaryController@postHolidaysAdd']);
			Route::get('suangaynghile/{id}',['as'=>'editHolidays','uses'=>'SalaryController@editHolidays'])->where(['id'=> '[0-9]+']);
			Route::post('suangaynghile/{id}',['as'=>'postHolidaysEdit','uses'=>'SalaryController@postHolidaysEdit'])->where(['id'=> '[0-9]+']);
			Route::get('xoangaynghile/{id}',['as'=>'deleteHolidays','uses'=>'SalaryController@deleteHolidays'])->where(['id'=> '[0-9]+']);	

			Route::get('cauhinhngaynghiphep',['as'=>'getLeaveConfig','uses'=>'SalaryController@getLeaveConfig']);
			Route::get('themngaynghiphep',['as'=>'addLeave','uses'=>'SalaryController@addLeave']);
			Route::post('themngaynghiphep',['as'=>'postLeaveAdd','uses'=>'SalaryController@postLeaveAdd']);
			Route::get('suangaynghiphep/{id}',['as'=>'editLeave','uses'=>'SalaryController@editLeave'])->where(['id'=> '[0-9]+']);
			Route::post('suangaynghiphep/{id}',['as'=>'postLeaveEdit','uses'=>'SalaryController@postLeaveEdit'])->where(['id'=> '[0-9]+']);
			Route::get('xoangaynghiphep/{id}',['as'=>'deleteLeave','uses'=>'SalaryController@deleteLeave'])->where(['id'=> '[0-9]+']);

			Route::get('cackhoankhac',['as'=>'getSalaryOther','uses'=>'SalaryController@getSalaryOther']);
			Route::get('dsnvdutieuchuantangluong',['as'=>'getSalaryIncreaseCriterion','uses'=>'SalaryController@getSalaryIncreaseCriterion']);
			Route::get('dsnvtruylinh',['as'=>'getSalaryTL','uses'=>'SalaryController@getSalaryTL']);
			Route::get('dexuatnangluongdotxuat',['as'=>'getSalaryPropose','uses'=>'SalaryController@getSalaryPropose']);
			Route::post('dexuatnangluongdotxuat',['as'=>'postSalaryPropose','uses'=>'SalaryController@postSalaryPropose']);
			Route::get('xoanhanvientangluongdotxuat/{id}',['as'=>'deleteSalaryPropose','uses'=>'SalaryController@deleteSalaryPropose'])->where(['id'=> '[0-9]+']);
			Route::get('luong',['as'=>'getSalary','uses'=>'SalaryController@getSalary']);
			Route::get('luong2',['as'=>'getSalary2','uses'=>'SalaryController@getSalary2']);
			// Route::post('luong',['as'=>'postSalary','uses'=>'SalaryController@postSalary']);
			Route::get('phucap',['as'=>'getAllowance','uses'=>'SalaryController@getAllowance']);
			// Route::post('phucap',['as'=>'postAllowance','uses'=>'SalaryController@postAllowance']);
			Route::get('thuebaohiem',['as'=>'getTaxInsurrance','uses'=>'SalaryController@getTaxInsurrance']);
			// Route::post('thuebaohiem',['as'=>'postTaxInsurrance','uses'=>'SalaryController@postTaxInsurrance']);
			Route::get('tonghop',['as'=>'getAllSalary','uses'=>'SalaryController@getAllSalary']);

			Route::get('tonghopchitiet',['as'=>'getAllClient','uses'=>'SalaryController@getAllClient']);
			Route::get('thongtinluong',['as'=>'getSalaryClient','uses'=>'SalaryController@getSalaryClient']);
			Route::get('thongtinthuongphucap',['as'=>'getAllowanceClient','uses'=>'SalaryController@getAllowanceClient']);
			Route::get('thongtinthuebaohiem',['as'=>'getTaxInsurranceClient','uses'=>'SalaryController@getTaxInsurranceClient']);
			Route::get('thongtincackhoankhac',['as'=>'getSalaryOtherClient','uses'=>'SalaryController@getSalaryOtherClient']);

			Route::get('cauhinhchukyxettangluong',['as'=>'getSettingPeriodSalary','uses'=>'SalaryController@getSettingPeriodSalary']);
			Route::post('cauhinhchukyxettangluong',['as'=>'postSettingPeriodSalary','uses'=>'SalaryController@postSettingPeriodSalary']);
			Route::get('suachukyxettangluong',['as'=>'getSettingPeriodSalaryEdit','uses'=>'SalaryController@getSettingPeriodSalaryEdit'])->where(['id'=> '[0-9]+']);
			Route::post('suachukyxettangluong',['as'=>'postSettingPeriodSalaryEdit','uses'=>'SalaryController@postSettingPeriodSalaryEdit'])->where(['id'=> '[0-9]+']);
			Route::get('xoachukyxettangluong',['as'=>'deleteSettingPeriodSalary','uses'=>'SalaryController@deleteSettingPeriodSalary'])->where(['id'=> '[0-9]+']);
		});

		Route::group(['prefix'=>'quydoiheso'],function(){
			Route::get('congthuc',['as'=>'getConvert','uses'=>'ConvertController@getConvert']);	
			Route::post('congthuc',['as'=>'postConvert','uses'=>'ConvertController@postConvert']);				
		});

		Route::group(['prefix'=>'chucnangkhac'],function(){
			Route::get('cauhinhemail',['as'=>'settingEmail','uses'=>'ConvertController@settingEmail']);	
			Route::get('themcauhinhguimail',['as'=>'addsettingEmail','uses'=>'ConvertController@addsettingEmail']);	
			Route::post('themcauhinhguimail',['as'=>'postsettingEmailAdd','uses'=>'ConvertController@postsettingEmailAdd']);
			Route::get('suacauhinhguimail/{id}',['as'=>'editsettingEmail','uses'=>'ConvertController@editsettingEmail'])->where(['id'=> '[0-9]+']);
			Route::post('suacauhinhguimail/{id}',['as'=>'postsettingEmailEdit','uses'=>'ConvertController@postsettingEmailEdit'])->where(['id'=> '[0-9]+']);
			Route::get('xoacauhinhguimail/{id}',['as'=>'deletesettingEmail','uses'=>'ConvertController@deletesettingEmail'])->where(['id'=> '[0-9]+']);
			Route::get('cauhinhmienchamcong',['as'=>'settingExceptionalAttendance','uses'=>'ConvertController@settingExceptionalAttendance']);	
			Route::post('cauhinhmienchamcong',['as'=>'postExceptionalAttendance','uses'=>'ConvertController@postExceptionalAttendance']);
			Route::get('xoacauhinhmienchamcong/{id}',['as'=>'deleteExceptionalAttendance','uses'=>'ConvertController@deleteExceptionalAttendance'])->where(['id'=> '[0-9]+']);

			Route::get('cauhinhchamcongnghiphep',['as'=>'settingAbsentAttendance','uses'=>'ConvertController@settingAbsentAttendance']);	
			Route::post('cauhinhchamcongnghiphep',['as'=>'postsettingAbsentAttendance','uses'=>'ConvertController@postsettingAbsentAttendance']);
			Route::get('xoacauhinhchamcongnghiphep/{id}',['as'=>'deletesettingAbsentAttendance','uses'=>'ConvertController@deletesettingAbsentAttendance'])->where(['id'=> '[0-9]+']);

			Route::get('cauhinhluongcoban',['as'=>'settingSalaryBasic','uses'=>'ConvertController@settingSalaryBasic']);	
			Route::get('cauhinhmucchiuthue',['as'=>'settingTax','uses'=>'ConvertController@settingTax']);	
			Route::get('cauhinhkhac',['as'=>'settingOthers','uses'=>'ConvertController@settingOthers']);	
			Route::get('themcauhinhkhac',['as'=>'addsettingOthers','uses'=>'ConvertController@addsettingOthers']);	
			Route::post('themcauhinhkhac',['as'=>'postsettingOthersAdd','uses'=>'ConvertController@postsettingOthersAdd']);
			Route::get('suacauhinhkhac/{id}',['as'=>'editsettingOthers','uses'=>'ConvertController@editsettingOthers'])->where(['id'=> '[0-9]+']);
			Route::post('suacauhinhkhac/{id}',['as'=>'postsettingOthersEdit','uses'=>'ConvertController@postsettingOthersEdit'])->where(['id'=> '[0-9]+']);
			Route::get('xoacauhinhkhac/{id}',['as'=>'deletesettingOthers','uses'=>'ConvertController@deletesettingOthers'])->where(['id'=> '[0-9]+']);


			Route::get('cauhinhtrangchu',['as'=>'settingPageHome','uses'=>'GroupPageHomeController@settingPageHome']);	
			Route::get('themvunghienthitrangchu',['as'=>'addPageHome','uses'=>'GroupPageHomeController@addPageHome']);
			Route::post('themvunghienthitrangchu',['as'=>'postPageHomeAdd','uses'=>'GroupPageHomeController@postPageHomeAdd']);
			Route::get('suavunghienthitrangchu/{id}',['as'=>'editPageHome','uses'=>'GroupPageHomeController@editPageHome'])->where(['id'=> '[0-9]+']);
			Route::post('suavunghienthitrangchu/{id}',['as'=>'postPageHomeEdit','uses'=>'GroupPageHomeController@postPageHomeEdit'])->where(['id'=> '[0-9]+']);
		});

		Route::group(['prefix' => 'thietbi'],function () {
			Route::get('danhsachphianguoidung',['as' => 'getDeviceClientList', 'uses' => 'DeviceController@getDeviceClientList']);
			Route::get('danhsach',['as' => 'getDeviceList', 'uses' => 'DeviceController@getDeviceList']);
			Route::get('add',['as' => 'getDeviceAdd', 'uses' => 'DeviceController@getDeviceAdd']);
	        Route::post('add',['as' => 'postDeviceAdd', 'uses' => 'DeviceController@postDeviceAdd']);
	        Route::get('edit/{id}',['as' => 'getDeviceEdit', 'uses' => 'DeviceController@getDeviceEdit']);
	        Route::post('edit/{id}',['as' => 'postDeviceEdit', 'uses' => 'DeviceController@postDeviceEdit']);
	        Route::get('del/{id}',['as' => 'getDeviceDel', 'uses' => 'DeviceController@getDeviceDel'])->where('id', '[0-9]+');
 
 			Route::get('danhmucthietbi',['as' => 'getCateDeviceList', 'uses' => 'DeviceController@getCateDeviceList']);
			Route::get('themdanhmucthietbi',['as' => 'getCateDeviceAdd', 'uses' => 'DeviceController@getCateDeviceAdd']);
	        Route::post('themdanhmucthietbi',['as' => 'postCateDeviceAdd', 'uses' => 'DeviceController@postCateDeviceAdd']);
	        Route::get('suadanhmucthietbi/{id}',['as' => 'getCateDeviceEdit', 'uses' => 'DeviceController@getCateDeviceEdit']);
	        Route::post('suadanhmucthietbi/{id}',['as' => 'postCateDeviceEdit', 'uses' => 'DeviceController@postCateDeviceEdit']);
	        Route::get('xoadanhmucthietbi/{id}',['as' => 'getCateDeviceDel', 'uses' => 'DeviceController@getCateDeviceDel'])->where('id', '[0-9]+');

 			Route::get('danhsachbangiaothietbi',['as' => 'getTakeDeviceList', 'uses' => 'DeviceController@getTakeDeviceList']);
			Route::get('thembangiaothietbi',['as' => 'getTakeDeviceAdd', 'uses' => 'DeviceController@getTakeDeviceAdd']);
	        Route::post('thembangiaothietbi',['as' => 'postTakeDeviceAdd', 'uses' => 'DeviceController@postTakeDeviceAdd']);
	        Route::get('suabangiaothietbi/{id}',['as' => 'getTakeDeviceEdit', 'uses' => 'DeviceController@getTakeDeviceEdit']);
	        Route::post('suabangiaothietbi/{id}',['as' => 'postTakeDeviceEdit', 'uses' => 'DeviceController@postTakeDeviceEdit']);
	        Route::get('xoabangiaothietbi/{id}',['as' => 'getTakeDeviceDel', 'uses' => 'DeviceController@getTakeDeviceDel'])->where('id', '[0-9]+');
		});

		Route::group(['prefix'=>'api'],function(){
			Route::get('updateExtendAjax','EvaluationController@updateExtendAjax')->name('updateExtendAjax');
			Route::get('updated-increase-insurrance','SalaryController@updatedIncreaseInsurrance')->name('updated-increase-insurrance');
			Route::get('listajax',['as'=>'getEvaluationTypeAjax','uses'=>'EvaluationController@getEvaluationTypeAjax']);
			//Route::post('personalajax',['as'=>'postAttendancePersonalAjax','uses'=>'AttendanceController@postAttendancePersonalAjax']);
			Route::get('personalajax',['as'=>'getAttendancePersonalAjax','uses'=>'AttendanceController@getAttendancePersonalAjax']);
			Route::get('listajax',['as'=>'getAttendanceTypeAjax','uses'=>'AttendanceController@getAttendanceTypeAjax']);
			Route::get('editItemajax',['as'=>'editItemAttendanceAjax','uses'=>'AttendanceController@editItemAttendanceAjax']);
			Route::get('searchItemajax',['as'=>'searchItemDepartAjax','uses'=>'AttendanceController@searchItemDepartAjax']);
			Route::get('editAttendanceAjaxHistory',['as'=>'editAttendanceAjaxHistory','uses'=>'AttendanceController@editAttendanceAjaxHistory']);
			Route::get('addAttendanceSymbolAjax',['as'=>'addAttendanceSymbolAjax','uses'=>'AttendanceController@addAttendanceSymbolAjax']);
			Route::get('editAttendanceSymbolAjax',['as'=>'editAttendanceSymbolAjax','uses'=>'AttendanceController@editAttendanceSymbolAjax']);
			Route::get('addAttendanceSpecialAjax',['as'=>'addAttendanceSpecialAjax','uses'=>'AttendanceController@addAttendanceSpecialAjax']);
			Route::get('deleteAttendanceSymbolAjax',['as'=>'deleteAttendanceSymbolAjax','uses'=>'AttendanceController@deleteAttendanceSymbolAjax']);
			Route::get('getAttendanceItemAjax',['as' => 'getAttendanceItemAjax', 'uses' => 'AttendanceController@getAttendanceItemAjax']);
			Route::get('delAttendanceItemAjax',['as' => 'delAttendanceItemAjax', 'uses' => 'AttendanceController@delAttendanceItemAjax']);
			Route::get('getEvaluationbyManagerAjax',['as'=>'getEvaluationbyManagerAjax','uses'=>'EvaluationController@getEvaluationbyManagerAjax']);
			Route::get('deleteEvaluationCriteriaAjax',['as' => 'deleteEvaluationCriteriaAjax', 'uses' => 'EvaluationController@deleteEvaluationCriteriaAjax']);

			Route::get('addRecipeConfigAjax',['as' => 'addRecipeConfigAjax', 'uses' => 'SalaryController@addRecipeConfigAjax']);
			Route::get('editRecipeConfigAjax',['as' => 'editRecipeConfigAjax', 'uses' => 'SalaryController@editRecipeConfigAjax']);
			Route::get('getConvertAjax',['as' => 'getConvertAjax', 'uses' => 'ConvertController@getConvertAjax']);
			Route::get('getSalaryAjax',['as' => 'getSalaryAjax', 'uses' => 'SalaryController@getSalaryAjax']);
			Route::get('getSalaryDoneAjax',['as' => 'getSalaryDoneAjax', 'uses' => 'SalaryController@getSalaryDoneAjax']);
			Route::get('getSalaryBonusDoneAjax',['as' => 'getSalaryBonusDoneAjax', 'uses' => 'SalaryController@getSalaryBonusDoneAjax']);
			Route::get('getSalaryRecalCulationAjax',['as' => 'getSalaryRecalCulationAjax', 'uses' => 'SalaryController@getSalaryRecalCulationAjax']);
			Route::get('getSalaryOtherAjax',['as' => 'getSalaryOtherAjax', 'uses' => 'SalaryController@getSalaryOtherAjax']);
			Route::get('getAllowanceAjax',['as' => 'getAllowanceAjax', 'uses' => 'SalaryController@getAllowanceAjax']);
			Route::get('getTaxInsurranceAjax',['as' => 'getTaxInsurranceAjax', 'uses' => 'SalaryController@getTaxInsurranceAjax']);
			Route::get('getSalaryDefaultAjax',['as'=>'getSalaryDefaultAjax','uses'=>'SalaryController@getSalaryDefaultAjax']);
			Route::get('approvalSalaryTLAjax',['as'=>'approvalSalaryTLAjax','uses'=>'SalaryController@approvalSalaryTLAjax']);
			Route::get('editSalaryTLAjax',['as'=>'editSalaryTLAjax','uses'=>'SalaryController@editSalaryTLAjax']);

			Route::get('editKIAjax',['as'=>'editKIAjax','uses'=>'SalaryController@editKIAjax']);
			Route::get('delKIAjax',['as'=>'delKIAjax','uses'=>'SalaryController@delKIAjax']);

			Route::get('editKIRulesAjax',['as'=>'editKIRulesAjax','uses'=>'SalaryController@editKIRulesAjax']);
			Route::get('delKIRulesAjax',['as'=>'delKIRulesAjax','uses'=>'SalaryController@delKIRulesAjax']);
			// Route::get('checkGroupPersonalConfigAjax',['as' => 'checkGroupPersonalConfigAjax', 'uses' => 'SalaryController@checkGroupPersonalConfigAjax']);
			Route::get('addGroupPersonalConfigAjax',['as' => 'addGroupPersonalConfigAjax', 'uses' => 'SalaryController@addGroupPersonalConfigAjax']);
			Route::get('editGroupPersonalConfigAjax',['as' => 'editGroupPersonalConfigAjax', 'uses' => 'SalaryController@editGroupPersonalConfigAjax']);

			Route::get('settingSalaryBasicAjax',['as' => 'settingSalaryBasicAjax', 'uses' => 'ConvertController@settingSalaryBasicAjax']);
			Route::get('settingTaxAjax',['as' => 'settingTaxAjax', 'uses' => 'ConvertController@settingTaxAjax']);

			Route::get('setDefaultFundsAjax',['as'=>'setDefaultFundsAjax','uses'=>'ExpenseController@setDefaultFundsAjax']);
			Route::post('approvalSalaryAjax',['as'=>'approvalSalaryAjax','uses'=>'EvaluationController@approvalSalaryAjax']);
			

			Route::get('fundbyPersonnelAjax',['as'=>'fundbyPersonnelAjax','uses'=>'ExpenseController@fundbyPersonnelAjax']);
			Route::get('getCurencyAjax',['as'=>'getCurencyAjax','uses'=>'ExpenseController@getCurencyAjax']);
			Route::get('getByPersonnelAjax',['as'=>'getByPersonnelAjax','uses'=>'ExpenseController@getByPersonnelAjax']);
			Route::get('getDefaultFundsAjax',['as'=>'getDefaultFundsAjax','uses'=>'ExpenseController@getDefaultFundsAjax']);

			Route::post('changePositionAjax',['as'=>'changePositionAjax','uses'=>'GroupPageHomeController@changePositionAjax']);
			Route::post('delPageHomeAjax',['as'=>'delPageHomeAjax','uses'=>'GroupPageHomeController@delPageHomeAjax']);

			Route::post('register-overtime','OvertimeController@registerOvertimeAjax');
			Route::post('approved-register-overtime','OvertimeController@approvedRegisterOvertime');
			Route::post('reject-register-overtime','OvertimeController@rejectRegisterOvertime');
			Route::post('check-reject-register-overtime','OvertimeController@checkRejectRegisterOvertime');
			Route::post('edit-register-overtime','OvertimeController@editRegisterOvertimeAjax');
			Route::post('edit-overtime','OvertimeController@editOvertimeAjax');
			Route::post('del-overtime','OvertimeController@delOvertimeAjax');
			Route::post('report-overtime','OvertimeController@reportOvertimeAjax');
			Route::post('manager-report-overtime','OvertimeController@managerReportOvertimeAjax');
			Route::post('setting-overtime','OvertimeController@settingOvertimeAjax');
			Route::post('info-overtime','OvertimeController@infoOvertimeAjax');
			Route::post('info-overtime-setting','OvertimeController@infoOvertimeSettingAjax');

			Route::post('update-loan-capital','LoanCapitalController@updateLoanCapitalAjax');
			Route::post('review-score','LoanCapitalController@reviewScoreLoanCapitalAjax');
			Route::post('update-fund-loan-capital','LoanCapitalController@updateFundLoanCapitalAjax');
			Route::post('info-interest-rate-config','LoanCapitalController@infoInterestRateConfigAjax');
			Route::post('check-interest-rate-config','LoanCapitalController@checkInterestRateConfigAjax');
			Route::post('insert-interest-rate-config','LoanCapitalController@insertInterestRateConfigAjax');
			Route::post('update-interest-rate-config','LoanCapitalController@updateInterestRateConfigAjax');
			Route::post('delete-interest-rate-config','LoanCapitalController@deleteInterestRateConfigAjax');
			Route::post('user-register-loan-capital','LoanCapitalController@userRegisterLoanCapitalAjax');
			Route::post('user-edit-register-loan-capital','LoanCapitalController@userEditRegisterLoanCapitalAjax');
			Route::post('approved-loan-capital','LoanCapitalController@approvedLoanCapitalAjax');
			Route::post('approved-pay-month-loan-capital','LoanCapitalController@approvedPayMonthLoanCapitalAjax');
			Route::post('done-pay-month-loan-capital','LoanCapitalController@donePayMonthLoanCapitalAjax');
			Route::post('calculate-demo-loan-capital','LoanCapitalController@calculateDemoLoanCapitalAjax');
			Route::post('remind-month-loan-capital','LoanCapitalController@remindMonthLoanCapitalAjax');
			Route::post('update-score-faith-config','LoanCapitalController@updateScoreFaithConfig');
			Route::post('update-other-config','LoanCapitalController@updateOtherConfig');
			Route::post('evaluate-faith','LoanCapitalController@evaluateFaith');
			Route::post('evaluate-faith-by-ceo','LoanCapitalController@evaluateFaithByCEO');
			Route::post('approved-evaluate-faith','LoanCapitalController@approvedEvaluateFaith');
			Route::post('update-all-evaluate-faith','LoanCapitalController@updateAllEvaluateFaith');
			Route::post('approved-partial-settlement','LoanCapitalController@approvedPartialSettlement');
			Route::post('approved-final-settlement','LoanCapitalController@approvedFinalSettlement');
			Route::post('remind-pay-partial-settlement-by-user','LoanCapitalController@remindPayPartialSettlementByUser')->name('remind-pay-partial-settlement-by-user');
			Route::post('remind-pay-month-now-by-user','LoanCapitalController@remindPayMonthNowByUser')->name('remind-pay-month-now-by-user');
			Route::post('remind-pay-all-now-by-user','LoanCapitalController@remindPayAllNowByUser')->name('remind-pay-all-now-by-user');
			Route::post('loan-complete-file','LoanCapitalController@loanCompleteFile')->name('loan-complete-file');
			Route::post('approved-file','LoanCapitalController@approvedFile');
			Route::post('droponejs-file','LoanCapitalController@droponeJs')->name('droponejs-file');
			Route::post('list-loan-capital-history','LoanCapitalController@listLoanCapitalHistory')->name('list-loan-capital-history');
			Route::post('money-final-settlement','LoanCapitalController@moneyFinalSettlement')->name('money-final-settlement');
			Route::post('send-email-only-salary', 'SalaryController@sendEmailOnlySalary')->name('send-email-only-salary');
			Route::post('delete-only-salary', 'SalaryController@deleteOnlySalary')->name('delete-only-salary');
		});

		Route::group(['prefix' => 'page'],function () {
			Route::get('list',['as' => 'getPageList', 'uses' => 'PagesController@getPageList']);
			Route::get('add',['as' => 'getPageAdd', 'uses' => 'PagesController@getPageAdd']);
	        Route::post('add',['as' => 'postPageAdd', 'uses' => 'PagesController@postPageAdd']);
	        Route::get('edit/{id}',['as' => 'getPageEdit', 'uses' => 'PagesController@getPageEdit']);
	        Route::put('edit/{id}',['as' => 'putPageEdit', 'uses' => 'PagesController@putPageEdit']);
	        Route::delete('del/{id}',['as' => 'deletePageDel', 'uses' => 'PagesController@deletePageDel'])->where('id', '[0-9]+');
		});

		Route::group(['prefix' => 'page-detail'],function () {
			Route::get('/{cat}', ['as'  => 'getCategories', 'uses' =>'PagesController@getCategories']);
		});



		Route::get('demo-2829123',function(){
		    $data = \App\Models\Personnel::whereIn('department_id', [18,25,29,30,31,40])->where('date_out', '=', NULL)->get();

		    foreach ($data as  $value) {
		        echo $value->fullname . " - " . $value->salary_frequency . " (năm)" . "<br>";
		    }
		});
	});
});

