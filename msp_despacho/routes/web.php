<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Mail\TestEmail;

 
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


Route::get('/prueba/correo', 'userController@pruebaCorreo')->name('Prueba.Correo');

Route::get('/documentos/registro/externo', function(){
	return view('upload_externa');
});




// Con esto limpiamos el cache:
Route::get('/clearcache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// RUTAS PARA LOGIN Y REGISTRO FINAL
Route::get('/', 'dashboardController@index')->name('Dashboard.Index');
Route::get('/home', 'HomeController@index')->name('Dashboard.Home');
Route::post('/home/registrofinal', 'HomeController@registrofinal')->name('Login.RegistroFinal');



// AQUI VAN LAS RUTAS LOGUEDAS
Auth::routes();
Route::group(['middleware' => ['web']], function () {

	Route::post('/search/all', 'HomeController@search')->name('Search.All');

	// User Routes:
	Route::get('/users', 'userController@index')->name('Users.Index');
	Route::get('/users/create', 'userController@create')->name('Users.Create');
	Route::post('/user', 'userController@store')->name('Users.Store');
	Route::get('/users/{user}/edit', 'userController@edit')->name('Users.Edit');
	Route::patch('/users/{user}', 'userController@update')->name('Users.Update');
	Route::get('/users/delete/{id}', 'userController@destroy')->name('Users.Delete');
	Route::get('/users/status/{id}/{status}', 'userController@status')->name('Users.Status');
	Route::get('/users/profile/{id}', 'userController@profile')->name('Users.Profile');
	Route::post('/users/edit/profile', 'userController@profile_edit')->name('Users.Edit.Admin');
	Route::post('/users/password', 'userController@passwordChange')->name('Users.Edit.Password');
	Route::get('/user/verificationEmail/{id}', 'userController@sendEmailVerification')->name('Email.Verification.Send');



	// Roles Routes:

	Route::get('/roles', 'rolesController@index')->name('Roles.Index');
	/*

	Route::get('/roles/create', 'rolesController@create')->name('Roles.create');
	Route::post('/roles', 'rolesController@store')->name('Roles.store');
	Route::get('/roles/{role}/edit', 'rolesController@edit')->name('Roles.edit');
	Route::patch('/roles/{role}', 'rolesController@update')->name('Roles.update');
	Route::delete('/roles/{role}', 'rolesController@destroy')->name('Roles.destroy');
	*/



	// DEPARTAMENTOS ROUTES:
	Route::get('/departamentos', 'departamentosController@index')->name('Departamentos.Index');
	Route::post('/departamentos', 'departamentosController@store')->name('Departamentos.Store');
	Route::post('/departamentos/update', 'departamentosController@update')->name('Departamentos.Update');

	Route::get('/departamentos/status/{id}/{status}', 'departamentosController@status')->name('Departamentos.Status');
	Route::get('/departamentos/delete/{id}', 'departamentosController@destroy')->name('Departamentos.Delete');
	Route::get('/departamentos/desvincular/{id}/{encargado}', 'departamentosController@desvincular')->name('Departamentos.Desvincular');


	// DOCUMENTOS
	Route::get('/documentos', 'documentosController@index')->name('Documentos.Index');
	Route::post('/documentos', 'documentosController@store')->name('Documentos.Store');
	Route::post('/documentos/agendar', 'documentosController@agendar')->name('Documentos.Agendar');
	Route::get('/documentos/edit/{id}', 'documentosController@edit')->name('Documentos.Edit');
	Route::post('/documentos/update', 'documentosController@update')->name('Documentos.Update');
	Route::get('/documentos/delete/{id}', 'documentosController@destroy')->name('Documentos.Delete');
	Route::get('/documentos/send/{id}', 'documentosController@send')->name('Documentos.Send');
	Route::get('/documentos/status/{id}/{status}', 'documentosController@send')->name('Documentos.Status');
	Route::get('/documentos/download/{id}', 'documentosController@download')->name('Documentos.download');


	Route::get('/likes/{id}/{status}/{document_id}', 'documentosController@Likes')->name('Documentos.Likes');

	Route::post('/folder/create', 'folderController@CreateFolder')->name('Folder.Store');
	Route::get('/folder/edit/{id}', 'folderController@edit')->name('Folder.Edit');
	Route::get('/folder/delete/{id}', 'folderController@delete')->name('Folder.Delete');
	Route::post('/folder/upload/', 'folderController@upload')->name('Folder.Upload');
	Route::get('/folder/move', 'folderController@move')->name('Folder.Move');
	Route::post('/folder/move', 'folderController@moveDocument')->name('Folder.MoveDocument');




// CALENDAR ROUTES:
	Route::get('/calendar', 'calendarController@index')->name('Calendar.Index');
	Route::get('/calendar/search', 'calendarController@search')->name('Calendar.Search');
	Route::post('/calendar', 'calendarController@store')->name('Calendar.Store');
	Route::get('/calendar/delete/{id}', 'calendarController@destroy')->name('Calendar.Delete');
	Route::get('/calendar/status/{id}/{status}', 'calendarController@send')->name('Calendar.Status');
	Route::get('/calendar/send/{id}', 'calendarController@send')->name('Calendar.Send');
	Route::get('/calendar/edit/{id}', 'calendarController@edit')->name('Calendar.Edit');
	Route::get('/calendar/show/{id}', 'calendarController@show')->name('Calendar.Show');
	Route::post('/calendar/update', 'calendarController@update')->name('Calendar.Update');
	Route::get('/calendar/agendar/{id}', 'calendarController@agendarWithDocument')->name('Calendar.Agendar.Document');
	Route::post('/calendar/find/date', 'calendarController@find')->name('Calendar.Find.Date');

	Route::get('/confirmacion/{id}/{email}', 'calendarController@confirmation')->name('Calendar.Confirmation.Event');
	Route::get('/calendar/user/trash/{id}/{evento_id}', 'calendarController@usertrash')->name('Calendar.User.Trash');
	Route::get('/calendar/user/sendmessage/{id}/{evento_id}', 'calendarController@sendmessage')->name('Calendar.Send.Message');



});




////  ////////////////////////////////////////////////////////////////////////////////
////  ////////////////////////////////////////////////////////////////////////////////
////  ////////////////////////////////////////////////////////////////////////////////

// INBOX ROUTES:
Route::get('/inbox', 'inboxController@index')->name('Inbox.Index');




if (env('APP_ENV') === 'production') {
    //URL::forceSchema('https');
    URL::forceScheme('https');

}

//URL::forceScheme('http');