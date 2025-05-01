<?php

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);

Route::group(['prefix' => '/admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Services
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::resource('services', 'ServicesController');

    // Employees
    Route::delete('employees/destroy', 'EmployeesController@massDestroy')->name('employees.massDestroy');
    Route::post('employees/media', 'EmployeesController@storeMedia')->name('employees.storeMedia');
    Route::resource('employees', 'EmployeesController');

    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');

    // Appointments
    Route::delete('appointments/destroy', 'AppointmentsController@massDestroy')->name('appointments.massDestroy');
    Route::resource('appointments', 'AppointmentsController');

    // Janji Temu
    Route::delete('janjitemu/destroy', 'JanjiTemuController@massDestroy')->name('janjitemu.massDestroy');
    Route::get('/janjitemu/pasien/{id}', 'JanjiTemuController@pasienShow')->name('janjitemu.pasien.show');
    Route::resource('janjitemu', 'JanjiTemuController');
    // Route::get('janjitemu/pasien', function (JanjiTemu $janjitemu) {
    //     // This function will be called when the route is accessed
    //     // You can access the user details using $user variable
    //     return view('admin.janjitemu.pasien.show', compact('janjitemu'));
    //   })->name('janjitemu.pasien');

    // Data Pasien
    // Route::delete('janjitemu/destroy', 'JanjiTemuController@massDestroy')->name('janjitemu.massDestroy');
    Route::resource('pasien', 'PasienController');
    Route::delete('pasien/destroy', 'PasienController@massDestroy')->name('pasien.massDestroy');
    Route::post('pasien/media', 'PasienController@storeMedia')->name('pasien.storeMedia');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
});
