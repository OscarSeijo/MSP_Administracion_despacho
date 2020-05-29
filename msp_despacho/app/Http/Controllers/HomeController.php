<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Auth;

use Auth;
use App\User;
use App\Carpeta;
use App\Departamento;
use Session;
use Cookie;
use DB;
use File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){


        
        $user = Auth()->user(); 

        if ($user->status == 0) {

            $online = '0';
            $last_login = date('Y-m-d H:i:s');

            $User = User::find($user->id);
            $User ->online = $online;
            $User ->last_login = $last_login;
            $User->save();

            if ($user->first_time === 0) {
                return view('auth.finish_register');

            } else{
                //echo "Estamos aquí";
                return back();


            }

//Request::isSecure(); 

        } else{

            Auth::logout();
            $messages = 'Cuenta inactiva, por favor hablar con el encargado de area para habilitar su cuenta.';
            return back()->with('error', $messages);


            //return redirect('/login');

        } 

        // Aquí verificamos si el usuario está por primera vez:
        //return view('home');

    }












    public function registrofinal(Request $Request){

          //VALIDAMOS LOS DATOS
        $validator = Validator::make($Request->all(), [
            'name'                      => 'required|string|max:30',
            'lastname'                  => 'required|string|max:30',
            'cedula'                    => 'required|string|max:15|unique:users,cedula',
            'phone'                     => 'required|string|max:16',
            'password'                  => 'required|string|min:8|max:20',
            'confirm_password'          => 'required|string|min:8|max:20'

        ]);


            // Verificamos si hay un error:
            $errors = $validator->errors();
            if ($validator->fails()) {

           return response()->json(['errors'=>$validator->errors()->all()]);

        } else{


            $uppercase = preg_match('@[A-Z]@', $Request->password);
            $lowercase = preg_match('@[a-z]@', $Request->password);
            $number    = preg_match('@[0-9]@', $Request->password);


            if(!$uppercase || !$lowercase || !$number || strlen($Request->password) < 8) {

                $messages = "La contraseña no tiene los requerimientos necesarios";
                $errors = array (
                    '0' => $messages,
                );
                return response()->json(['errors'=>$errors]);


            // En caso de que este validado el campo password
            } else{

                if ($Request->password === $Request->confirm_password) {

                    // Encriptamos el nuevo password
                    $password = Hash::make($Request->password);

                    // Actualizamos el updated_at
                    $updated_at = date('Y-m-d H:i:s');
                    $first_time = 1;

                    $month  = date('M');
                    $year = date('Y');
                    $actual = $month.$year;
                    $filename = $Request->imagen;

                    // Verificamos si el campo imagen esta vacio:
                    if ($filename === NULL) {
                        $filePath = 'avatar.png';


                    // Verificamos que el archivo existe y preparado para guardar en el storage:
                    } elseif ($Request->hasFile('imagen')) {

                        $imagen = $Request->file('imagen');
                        $extension = $imagen->getClientOriginalExtension();
                        $location = 'users/'.$actual.'/';
                        $filePath = $location.$imagen->getFilename().'.'.$extension;
                        Storage::disk('public')->put($filePath,  File::get($imagen));

                    // Campo validación de imagen
                    }

                    $user = Auth()->user();
                    // Actualizamos los datos
                    $user = User::find($user->id);
                    $user ->name = $Request->name;
                    $user ->lastname = $Request->lastname;
                    $user ->cedula = $Request->cedula;
                    $user ->phone = $Request->phone;
                    $user ->Secundary_phone = $Request->secundary_phone;
                    $user ->password = $password;
                    $user ->avatar = $filePath;
                    $user ->first_time = $first_time;
                    $user ->save();


                    // Enviamos un correo en donde recibíra las credenciales
                    if(!$user->save()){

                        $messages = "Error al guardar la data, por favor cargue de nuevo el navegador";
                        $errors = array (
                            '0' => $messages,
                        );
                        return response()->json(['errors'=>$errors]);

                    } else{

                        $messages = "Aqui todo está bien";
                        $success = array (
                            '0' => $messages,
                        );
                        return response()->json(['success'=>$success]);

                    // Cierre Else de si se guardo la data
                    }



                // En caso de que las contraseñas no sean las mismas:
                }else{

                    $messages = "Las contraseñas no coinciden, verificar nuevamente";
                    $errors = array (
                        '0' => $messages,
                    );
                    return response()->json(['errors'=>$errors]);
                }

            // cierre de verificación
            }


        }



    }




    public function search(Request $Request){



          //VALIDAMOS LOS DATOS
        $validator = Validator::make($Request->all(), [
            'search'                      => 'required|string|max:60',
        ]);

         // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {

        }else{

            $mensaje = "";

            // users search
            $users=DB::table('users')
                ->where('name','LIKE','%'.$Request->search."%")
                ->orWhere('lastname','LIKE','%'.$Request->search."%")
                ->orWhere('cedula','LIKE','%'.$Request->search."%")
                ->orWhere('department','LIKE','%'.$Request->search."%")
                ->orWhere('cargo','LIKE','%'.$Request->search."%")
                ->orWhere('phone','LIKE','%'.$Request->search."%")

                ->where('status', 0)
                ->get();

            if ($users !== null) {

                $mensaje.= "<div class='resultado titulos'>
                                <h6>USUARIOS</h6>
                            </div>
                        ";

                foreach ($users as $key => $value) {

                    $imagen = public_path()."/storage/".$value->avatar;

                    $mensaje.="
                        <a href='/users/profile/".$value->id."'>
                            <div class='resultado'>
                                <div class='Username'>
                                    <div class='imagen'>
                                        <img src=".$imagen." >
                                    </div>
                                    <div class='datos nombre'>
                                         <h6>".$value->name.' '.$value->lastname.' ('.$value->cedula.")</h6>
                                    </div>
                                </div>

                                <div class='datos'>
                                    <p>".$value->department."</p>
                                    <p>/</p>
                                    <p>".$value->cargo."</p>
                                    <p>/</p>
                                    <p>".$value->email."</p>
                                </div>

                            </div>
                        </a>

                    ";


                }

            }







            // Documentos Search

            $documents=DB::table('documentos')
                ->where('name','LIKE','%'.$Request->search."%")
                ->orWhere('tipo','LIKE','%'.$Request->search."%")
                ->orWhere('entregado','LIKE','%'.$Request->search."%")
                ->where('status', 0)
                ->get();

            if ($documents !== null) {

                $mensaje.= "<div class='resultado titulos'>
                                <h6>DOCUMENTOS</h6>
                            </div>
                        ";


                foreach ($documents as $key => $value_document) {
                    $carpeta = Carpeta::where('id', $value_document->carpeta_id)->get()->all();

                    foreach ($carpeta as $key => $value_carpeta) {
                        if ($value_carpeta->tipo == 'Publico') {


                            if(strpos( $value_document->send, Auth::user()->email ) !== false){
                                $mensaje.="
                                    <a href='/documentos/download/".$value_document->id."'>
                                        <div class='resultado'>

                                            <h6>".$value_document->name."</h6>
                                            <div class='datos'>

                                                <p>".$value_document->tipo."</p>
                                                <p>/</p>
                                                <p>".$value_carpeta->name."</p>
                                            </div>

                                        </div>
                                    </a>

                                ";
                            }elseif($value_document->send === 'all'){
                                $mensaje.="
                                    <a href='/documentos/download/".$value_document->id."'>
                                        <div class='resultado'>

                                            <h6>".$value_document->name."</h6>
                                            <div class='datos'>

                                                <p>".$value_document->tipo."</p>
                                                <p>/</p>
                                                <p>".$value_carpeta->name."</p>
                                            </div>

                                        </div>
                                    </a>

                                ";
                            }




                        // VERIFICAMOS SI EL DOCUMENTO ES PRIVADO
                        } else{

                            if($value_document->register_id === Auth::user()->id){
                                 $mensaje.="
                                    <a href='/documentos/download/".$value_document->id."'>
                                        <div class='resultado'>

                                            <h6>".$value_document->name."</h6>
                                            <div class='datos'>

                                                <p>".$value_document->tipo."</p>
                                                <p>/</p>
                                                <p>".$value_carpeta->name."</p>
                                            </div>

                                        </div>
                                    </a>

                                ";
                            }


                        }

                    // Cierre foreach carpeta
                    }

                 // Cierre foreach documento   
                }

            // CIerre del if si documento es nulo
            }






            // Calendarios

            $events=DB::table('calendarios')
                ->where('title','LIKE','%'.$Request->search."%")
                ->orWhere('document','LIKE','%'.$Request->search."%")
                ->orWhere('location','LIKE','%'.$Request->search."%")
                ->orWhere('type','LIKE','%'.$Request->search."%")
                ->orWhere('date','LIKE','%'.$Request->search."%")
                ->orWhere('hour','LIKE','%'.$Request->search."%")
                ->where('status', 0)
                ->get();

            if ($events !== null) {


                $mensaje.= "<div class='resultado titulos'>
                            <h6>EVENTOS</h6>
                        </div>
                    ";

                foreach ($events as $key => $value) {

                    $mensaje.="
                        <a href='/calendar/show/".$value->id."'>
                            <div class='resultado'>

                                <h6>".$value->title."</h6>
                                 <div class='datos'>
                                    <p>".$value->type."</p>
                                    <p>/</p>
                                    <p>".$value->date."</p>
                                    <p>/</p>
                                    <p>".$value->hour."</p>
                                </div>

                            </div>
                        </a>

                    ";


                } 



            // Cierre del If evento
            }











            return response($mensaje);

        }


    }





}
