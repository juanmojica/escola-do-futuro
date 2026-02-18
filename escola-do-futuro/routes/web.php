<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    
    Route::resource('courses', 'Admin\CourseController');
    
    Route::resource('teachers', 'Admin\TeacherController');
    
    Route::resource('students', 'Admin\StudentController');
    
    Route::resource('subjects', 'Admin\SubjectController');
    
    Route::resource('enrollments', 'Admin\EnrollmentController');
    
    Route::get('/reports', 'Admin\ReportController@index')->name('reports.index');
    Route::get('/reports/export/pdf', 'Admin\ReportController@exportPdf')->name('reports.export.pdf');
    Route::get('/reports/{course}', 'Admin\ReportController@show')->name('reports.show');
});

Route::prefix('student')->name('student.')->middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', 'Student\DashboardController@index')->name('dashboard');
    
    Route::get('/profile', 'Student\ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'Student\ProfileController@update')->name('profile.update');
    
    Route::get('/enrollments', 'Student\EnrollmentController@index')->name('enrollments.index');
});
