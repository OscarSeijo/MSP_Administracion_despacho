<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


use App\User;
use App\Notification;
use Session;
use Cookie;
use DB; 



class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $data = User::where('status', '0')->get()->all();
        
        return view('Users/index', [
           "data" => $data
        ]); 

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
                $link = '/Users';
                return view('Users.create', [
                    'link' => $link
                ]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $Request)
    {
         //VALIDAMOS LOS DATOS

        $validator = Validator::make($Request->all(), [
            'name'              => 'required|string|max:30',
            'lastname'          => 'required|string|max:30',
            'email'             => 'required|string|email|max:89|unique:users,email',
            'phone'             => 'required|string|max:22|unique:users,phone',
            'role_id'           => 'required|string|max:3'

        ]);



        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {


            $messages = $validator->messages();
            echo $messages;
           //return view('Users/index')->with('errors', $messages);

        } else{

             // creamos un password para acceder la primera vez
                $longitud = 9;
                $codigo = substr( md5(microtime()), 1, $longitud);
                $password = Hash::make($codigo);

            // Guardamos los datos en la base de datos inicial
                $avatar = 'avatar_base.png';
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');
                $first_time = 0;
                $status = 0;


                if ($Request->company !== NULL) {
                     $type = 'externo';
                } else{
                     $type = 'interno';
                }



                $User = new User;
                $User ->name = $Request->name;
                $User ->lastname = $Request->lastname;
                $User ->role_id = $Request->role_id;
                $User ->tipo = $type;
                $User ->phone = $Request->phone;
                $User ->department = $Request->department;
                $User ->cargo = $Request->cargo;
                $User ->password = $password;
                $User ->email = $Request->email;
                $User ->avatar = $avatar;
                $User ->status = $status;
                $User ->first_time = $first_time;
                $User ->created_at = $created_at;
                $User ->updated_at = $updated_at;
                $User ->register_id = $Request->register_id;
                $User->save(); 




                // Enviamos un correo en donde recibíra las credenciales
                if(!$User->save()){
                    $messages = 'Error al registrar nuevo usuario, por favor intentar más tarde';
                    return view('Users/index')->with('errors', $messages);
                } else{



                     Mail::send('Email.email_registro_usuario', ['Request' => $Request, 'codigo' => $codigo, 'type' => $type], function ($m) use ($Request){
                        $m->from( env('MAIL_FROM_ADDRESS'), 'MSP ASAP');
                        $m->to($Request->email)->subject('Bienvenido a MSP ASAP '.$Request->nombre.'');
                      });  


                     // Aqui guardamos la notificación:
                    $channel = 'user';
                    $title = Auth::user()->name.' agrego un nuevo usuario';
                    $text= 'El usuario '.$Request->name.' ha sido agregado exitosamente';

                    $Notification = new Notification;
                    $Notification ->channel = $channel;
                    $Notification ->title = $title;
                    $Notification ->text = $text;
                    $Notification ->role_id = $Request->role_id;
                    $Notification ->status = 0;
                    $Notification ->created_at = $created_at;
                    $Notification ->updated_at = $created_at;
                    $Notification->save(); 


                    

                     // Presentamos en pantalla al usuario los datos para muestras y screenshot
                    $mensaje = '
                        <div class="contenedor_respuesta_registro_user">
                            <h4>¡DATOS DE NUEVO USUARIO REGISTRADO!</h4>
                            <p>La nueva cuenta de <b>'.$Request->name.' '.$Request->apellido.'</b> ha sido registrada como <b>'.$type.'</b>, las siguientes etapas lo debe manejar el nuevo usuario, asignamos una contraseña aleatoria y enviada por correo electrónico para fines de que el usuario termine el proceso de registro.</p>

                            <h6>A continuación presentamos los datos del nuevo usuario:</h6>


                            <div class="grid-1-center">

                                <div class="col-6_sm-12">
                                    <label>Correo electrónico</label>
                                    <input type="text" class="form-control" placeholder="'.$Request->email.'" readonly>
                                </div>


                                <div class="col-12">
                                    <label>CONTRASEÑA GENERADA</label>
                                    <h4>'.$codigo.'</h4>
                                    <small  class="form-text text-muted">No debes compartir con nadie está contraseña salvo que sea el usuario</small>
                                </div>

                            </div>
                        </div>


                    ';

                    echo $mensaje;
                    

                // Cierre Else de si se guardo la data
                } 


        // Cierre Else validator
        }

    }




    public function pruebaCorreo(){
       
        $data = array('mensaje' => 'todo bien por ahora');

        $email = 'oscarulisesseijoveloz@gmail.com';
        $subject = 'Esto es una prueba';

        Mail::send('Email.email_test', $data, function($message) use ($email, $subject) {
            $message->from('oscarseijo@outlook.com', 'MSP ASAP');
            $message->to($email)->subject('PRUEBA DE CORREO');
            $message->to($email)->subject($subject);
        });
    }





    public function profile(Request $Request){

        $id = $Request->id;


        $usuario = User::where('id', $id)->take(1)->get()->all();


        $link = '/Users';
        return view('Users.profile', [
            'link' => $link,
            'usuario' => $usuario
        ]);

    }




    public function sendEmailVerification(Request $Request){
        $id = $Request->id;
        $usuario = User::where('id', $id)->get()->all();

        foreach ($usuario as $key => $value) {

            $correo = $value->email;
            $type = $value->type;
            



            // Creamos el nuevo password:
            $longitud = 9;
            $codigo = substr( md5(microtime()), 1, $longitud);
            $password = Hash::make($codigo);


            // guardamos la nueva contraseña
            $User = User::where('id', $id)->first();
            $User ->password = $password;
            $User->save();

             // Enviamos un correo en donde recibíra las credenciales
            if(!$User->save()){

                $messages = 'Error al registrar nuevo usuario, por favor intentar más tarde';
                //return back()->with('errors', $messages);

            } else{

                // Enviamos el correo con la nueva contaseña
                Mail::send('Email.email_registro_usuario', ['Request' => $User, 'codigo' => $codigo, 'type' => $type], function ($m) use ($User){
                        $m->from( env('MAIL_FROM_ADDRESS'), 'MSP ASAP');
                        $m->to($User->email)->subject('Bienvenido a MSP ASAP '.$User->name.'');
                      }); 



                $messages = 'Correo de verificación enviado exitosamente';
                return back()->with('success', $messages); 

            }


            
        }

    }






    public function profile_edit(Request $Request){

        $validator = Validator::make($Request->all(), [
            'name'                        => 'required|string|max:30',
            'lastname'                    => 'required|string|max:30',
            //'email'                       => 'required|string|email|max:89|unique:users,email',
            'phone'                       => 'required|string|max:22',
            'secundary_phone'             => 'required|string|max:22',
            'cedula'                      => 'required|string|max:13'
        ]);

        $id = Auth::user()->id;
        $User = Auth::user();

        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->with('errors', $messages);

        } else{

            // Verificamos si existe otro usuario con la misma cedula:
            if (User::where('cedula', $Request->cedula)->exists()) {

                if (User::where('id', $id)->exists()) {

                } else{
                    $messages = "Otro usuario tiene inscrito ese codigo de identidad (Cedula)";
                    return back()->with('errors', $messages);
                }
            } else{

            }


            /// Verificamos el numero de telefono primario:
            if (User::where('phone', $Request->phone)->exists()) {

                if (User::where('id', $id)->exists()) {

                } else{
                    $messages = "Otro usuario tiene el telefono primario registrado";
                    return back()->with('errors', $messages);
                }
            } else{
            }



            // Actualizamos la data:
            $updated_at = date('Y-m-d H:i:s');

            $User ->name = $Request->name;
            $User ->lastname = $Request->lastname;
            $User ->cedula = $Request->cedula;
            $User ->phone = $Request->phone;
            $User ->secundary_phone = $Request->secundary_phone;
            $User ->updated_at = $updated_at;
            $User->save();


            // Enviamos un correo en donde recibíra las credenciales
            if(!$User->save()){

                $messages = 'Error al actualizar el perfil, por favor cargue de nuevo el navegador';
                return back()->with('error', $messages);

            } else{

                $messages = 'Perfil modificado exitosamente';
                return back()->with('success', $messages);

                // Cierre Else de si se guardo la data
            }



        }

    }





    public function passwordChange(Request $Request){


        $validator = Validator::make($Request->all(), [
            'current_password'          => 'required|string|min:8|max:20',
            'password'                  => 'required|string|min:8|max:20',
            'confirm_password'          => 'required|string|min:8|max:20'
        ]);

        $user = auth()->user();


        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->with('errors', $messages);

        } else {

            if (Hash::check($Request->current_password, Auth::user()->password, [])) {

                if($Request->password === $Request->current_password){
                    $messages = 'La nueva contraseña no debe ser la misma que la actual';
                    return back()->with('error', $messages);
                } else{

                    if ($Request->password === $Request->confirm_password){

                        $user->password = bcrypt($Request->password);
                        $user->save();


                        // Enviamos un correo en donde recibíra las credenciales
                        if(!$user->save()){

                            $messages = 'Error al actualizar la contraseña, por favor cargue de nuevo el navegador';
                            return back()->with('error', $messages);

                        } else{

                            $messages = 'Cambio de contraseña aplicado';
                            return back()->with('success', $messages);

                            // Cierre Else de si se guardo la data
                        }


                    } else{
                        $messages = 'No coinciden los campos de contraseña y nueva contraseña';
                        return back()->with('error', $messages);
                    }
                }


            } else {
                $messages = 'La contraseña actual no coincide';
                return back()->with('error', $messages);
            }
        }


    }



    public function status(Request $Request){

        $id = $Request->id;
        $status = $Request->status;

        if ($status === '0') {
            $status = '1';
        } else{
            $status = '0';
        }
        $updated_at = date('Y-m-d H:i:s');


        $User = User::find($Request->id);
        $User ->status = $status;
        $User ->updated_at = $updated_at;
        $User->save();

        if (!$User->save()) {
            $messages = 'Error al cambiar el estado del documento';
            return back()->with('success', $messages);
        }else{
            $messages = 'Estado del documento cambiado exitosamente';
            return back()->with('success', $messages);
        }
    }


    public function destroy($id){

        User::find($id)->delete();
        $messages = 'El usuario ha sido eliminado exitosamente';
        return back()->with('success', $messages);


        if (!$user->delete()) {
            $messages = 'Error al eliminar el usuario';
            return back()->with('errors', $messages);
        }else{
            $messages = 'El usuario ha sido eliminado exitosamente';
            return back()->with('success', $messages);
        }


    }
}
