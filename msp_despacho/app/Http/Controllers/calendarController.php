<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\User;
use App\Documento;
use App\Calendario;
use App\Confirmation;
use App\Notification;
use Session;
use Cookie; 
use DB;
use File;

 
class calendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

            $data = Calendario::get()->all();
            $dataCount = count($data);

            $Inactivos = Calendario::where('status', '1')->get()->all();
            $InactivosCount = count($Inactivos);

            $success = Calendario::where('condicion', '1')->get()->all();
            $successCount = count($success);

            $cancelEvents = Calendario::where('condicion', '2')->get()->all();
            $cancelEventsCount = count($cancelEvents);

       return view('calendario/index', [ 
            "dataCount" => $dataCount,
            "InactivosCount" => $InactivosCount,
            "successCount" => $successCount,
            "cancelEventsCount" => $cancelEventsCount

       ]);
    }



   

    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function store(Request $Request){


        $type = $Request->type;

        switch ($type) {
            case 'recordatorio':

                    //VALIDAMOS LOS DATOS
                    $validator = Validator::make($Request->all(), [
                        //'title'                     => 'required|string|unique:calendarios,title',
                        'comentario'                => 'required|string',
                        'date'                      => 'required|date|max:30',
                        'hour'                      => 'required|string|max:30'
                        
                    ]); 
                

                break;


            case 'evento':



                    //VALIDAMOS LOS DATOS
                    $validator = Validator::make($Request->all(), [
                        //'title'                     => 'required|string|unique:calendarios,title',
                        'location'                  => 'required|string',
                        'usuarios_registrados'      => 'array',
                        'usuarios_registrados.*'    => 'string',
                        'comentario'                => 'required|string',
                        'date'                      => 'required|date|max:30',
                        'hour'                      => 'required|string|max:30'
                        
                    ]); 
                    

                    
                break;
           
        }



        // Verificamos si hay un error:
        $errors = $validator->errors(); 
        if ($validator->fails()) {

            $messages = $validator->messages();
            return back()->with('error', $messages);

        } else{
            // Guardamos los datos en la base de datos inicial
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $status = 0;
            $condicion = 'Pendiente';

            $longitud = 9;
            $id_confirmation = substr( md5(microtime()), 1, $longitud);

            

            switch ($type) {

                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////
                case 'recordatorio':


                    if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour, 'id_register' => Auth::user()->id])->exists()) {

                        $messages = "Lo sentimos, ya estas registrado un recordatorio para esa hora";
                        return back()->with('error', $messages);

                    } else{
                        $Calendario = new Calendario;
                        $Calendario ->title = $Request->title;
                        $Calendario ->comentario = $Request->comentario;

                        $Calendario ->date = $Request->date;
                        $Calendario ->hour = $Request->hour;


                        $Calendario ->status = $status;
                        $Calendario ->condicion = $condicion;
                        $Calendario ->type = $type;
                        $Calendario ->id_confirmation = $id_confirmation;
                        $Calendario ->created_at = $created_at;
                        $Calendario ->updated_at = $updated_at; 
                        $Calendario ->id_register = Auth::user()->register_id;
                        $Calendario->save();
                    }
                
                    
                
                    
                break;
                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////
                case 'evento':


                    $email = Auth::user()->email;

                    // Verificamos si existe un evento en la misma fecha y hora, con el usuario 
                    if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour])->where('usuarios_registrados', 'LIKE', '%'.$email.'%')->exists()) {

                        $messages = "Lo sentimos, ya estas registrado un evento para esa hora";
                        return back()->with('error', $messages);

                    } else{

                        if ($Request->input('usuarios_registrados') !== NULL) {
                            $usuarios_registrados = $Request->input('usuarios_registrados');
                            $usuarios_registrados = implode(',', $usuarios_registrados);
                            $usuarios_registrados =  $usuarios_registrados.','.$email;
                        } else{
                            $usuarios_registrados =  $email;

                        }

                        // Verificamos que los usuarios están disponible para esa hora tambien.
                        if ($calendario = Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour])->where('usuarios_registrados', 'LIKE', '%'.$usuarios_registrados.'%')->first()) {

                            $correos = $calendario['usuarios_registrados'];
                            $correos = explode(',', $correos);


                            foreach ($correos as $key => $value) {

                                if ($value === Auth::user()->email) {

                                } else{
                                    $messages = "Lo sentimos, el usuario ".$value." estas registrado un evento para esa hora";
                                    return back()->with('error', $messages);
                                }

                            }

                            /*
                            if (Str::contains($correos, Auth::user()->email) ) {
                                echo "Existe";
                            }*/  
                                
                        } else{

                             if ($Request->documento !== NULL) {
                                $activar_documento = 1;
                            } else{
                                $activar_documento = 0;
                            }

                            $Calendario = new Calendario;
                            $Calendario ->title = $Request->title;
                            $Calendario ->location = $Request->location;
                            $Calendario ->comentario = $Request->comentario;
                            $Calendario ->usuarios_registrados = $usuarios_registrados;
                            $Calendario ->date = $Request->date;
                            $Calendario ->hour = $Request->hour;
                            $Calendario ->document = $Request->documento;

                            $Calendario ->status = $status;
                            $Calendario ->condicion = $condicion;
                            $Calendario ->type = $type;
                            $Calendario ->id_confirmation = $id_confirmation;
                            $Calendario ->created_at = $created_at;
                            $Calendario ->updated_at = $updated_at; 
                            $Calendario ->id_register = Auth::user()->id;
                            $Calendario->save();
                        }



                    // Cierre del else verificacion fecha y hora
                    } 



                break;
            }
                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////
                // //////////////////////////////////////////////////////////////////////////////////////////



            if(!$Calendario->save()){


                    $messages = 'Error al guardar la data, por favor cargue de nuevo el navegador';
                    return back()->with('error', $messages);

            } else{


                    switch ($type) {
                        case 'recordatorio':


                            $messages = 'Recordatorio registrado exitosamente';
                            return back()->with('success', $messages);

                        break;


                        case 'evento':

                             // Aqui guardamos la notificación:
                                $channel = 'evento';
                                $title = Auth::user()->name.' agrego un nuevo evento';
                                $text= 'El evento '.$Request->title.' ha sido creado, esperando confirmación';

                                $Notification = new Notification;
                                $Notification ->channel = $channel;
                                $Notification ->title = $title;
                                $Notification ->text = $text;
                                $Notification ->role_id = Auth::user()->role_id;
                                $Notification ->send_to = $usuarios_registrados;
                                $Notification ->status = 0;
                                $Notification ->created_at = $created_at;
                                $Notification ->updated_at = $created_at;
                                $Notification->save(); 




                            $website = URL::to('/');
                            $usuarios_registrados = explode(',', $usuarios_registrados);
                            foreach ($usuarios_registrados as $key => $value) {
                                $correo = $value;

                                if ($correo === $email) {
                                     $confirmation = 0;
                                } else{
                                     $confirmation = 1;
                                }


                                $Confirmation = new Confirmation;
                                $Confirmation ->id_calendario = $id_confirmation;
                                $Confirmation ->correo = $correo;
                                $Confirmation ->confirmation = $confirmation;
                                $Confirmation ->status = 0;
                                $Confirmation ->created_at = $created_at;
                                $Confirmation->save();


                                Mail::send('Email.email_registro_evento', [ 'Request' => $Request, 'correo' => $correo, 'activar_documento' => $activar_documento, 'confirmation' => $Confirmation, 'website' => $website ], function ($m) use ($Request, $correo){
                                        $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                                        $m->to($correo)->subject('Se ha registrado un nuevo evento: '.$Request->title.'');
                                    }
                                );   

                            } 

                            $messages = 'Evento registrado exitosamente';
                            return back()->with('success', $messages); 
                              
  
                        break;
                    
                    }
                    

                   
            // Cierre Else de si se guardo la data
            }  

          

        // Cierre de verificación de data 
        }


    /// cierre de la función
    }


    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function confirmation(Request $Request){
        $id = $Request->id;
        $correo = $Request->email;


        $confirmation = Confirmation::where(['id' => $id, 'correo' => $correo ])->get()->all();
        foreach ($confirmation as $key => $value) {

            if ($value->confirmation === 0) {

                echo '<script language="javascript">';
                echo 'alert("El usuario ya esta operativo en este evento")';
                echo '</script>';

                echo "<script>window.close();</script>";


            } else{

                $value->confirmation = 0;

                $updated_at = date('Y-m-d H:i:s');

                $data = Confirmation::find($id);
                $data ->confirmation = $value->confirmation;
                $data ->confirmation_date = $updated_at;
                $data ->updated_at = $updated_at; 
                $data->save();

                if (!$data->save()) {
                    echo '<script language="javascript">';
                    echo 'alert("Error al cambiar la confirmación del evento, volver a intentarlo")';
                    echo '</script>';

                    echo "<script>window.close();</script>";
                    
                }else{
                    echo '<script language="javascript">';
                    echo 'alert("Se ha confirmado su presencia en el evento.")';
                    echo '</script>';

                    echo "<script>window.close();</script>";
                    
                }
            }
        }


    }


    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function show($id){


        $evento = Calendario::where('id', $id)->get()->all();
        return view('calendario.show')->with(['evento' => $evento]);

    }





    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function edit(Request $Request){

        $data = Calendario::find($Request->id);


        return view('calendario/edit', [
            "data" => $data
        ]); 

    }


    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function update(Request $Request){


        $type = $Request->type;

        switch ($type) {
            case 'recordatorio':

                    //VALIDAMOS LOS DATOS
                    $validator = Validator::make($Request->all(), [
                        //'title'                     => 'required|string',
                        'comentario'                => 'required|string',
                        'date'                      => 'required|date|max:30',
                        'hour'                      => 'required|string|max:30'
                        
                    ]); 
                
                break;

            case 'evento':

                    //VALIDAMOS LOS DATOS
                    $validator = Validator::make($Request->all(), [
                        //'title'                     => 'required|string',
                        'location'                  => 'required|string',
                        'comentario'                => 'required|string',
                        'date'                      => 'required|date|max:30',
                        'hour'                      => 'required|string|max:30'
                        
                    ]); 
                    
                break;
           
        }


        // Verificamos si hay un error:
        $errors = $validator->errors(); 
        if ($validator->fails()) {

            $messages = $validator->messages();
            return back()->with('error', $messages);

        } else{

            if ($Request->title !== NULL) {

                $updated_at = date('Y-m-d H:i:s');


                switch ($type) {

                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    case 'recordatorio':


                        if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour, 'id_register' => Auth::user()->id])->exists()) {

                            $messages = "Lo sentimos, ya estas registrado un recordatorio para esa hora";
                            return back()->with('error', $messages);

                        } else{
                            $Calendario = Calendario::find($Request->id);
                            $Calendario ->title = $Request->title;
                            $Calendario ->comentario = $Request->comentario;

                            $Calendario ->date = $Request->date;
                            $Calendario ->hour = $Request->hour;
                            $Calendario ->updated_at = $updated_at; 
                            $Calendario->save();
                        }
                    
                        
                    
                        
                    break;
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    case 'evento':


                        
                        $email = Auth::user()->email;

                        // Verificamos si existe un evento en la misma fecha y hora, con el usuario 
                        if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour, 'id' => $Request->id])->where('usuarios_registrados', 'LIKE', '%'.$email.'%')->exists()) {

                            

                              if ($Request->input('usuarios_registrados') !== NULL) {
                                $usuarios_registrados = $Request->input('usuarios_registrados');
                                $usuarios_registrados = implode(',', $usuarios_registrados);
                                $usuarios_registrados =  $usuarios_registrados.','.$email;
                            } else{
                                $usuarios_registrados =  $email;

                            }

                            // Verificamos que los usuarios están disponible para esa hora tambien.
                            if ($calendario = Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour])->where('usuarios_registrados', 'LIKE', '%'.$usuarios_registrados.'%')->first()) {

                                $correos = $calendario['usuarios_registrados'];
                                $correos = explode(',', $correos);

                                $id = $calendario['id'];

                                // VERIFICAMOS SI EL ID ES EL MISMO
                                if ($id == $Request->id) {

                                    if ($Request->documento !== NULL) {
                                        $activar_documento = 1;
                                    } else{
                                        $activar_documento = 0;
                                    }

                                    $Calendario = Calendario::find($Request->id);
                                    $Calendario ->title = $Request->title;
                                    $Calendario ->location = $Request->location;
                                    $Calendario ->comentario = $Request->comentario;
                                    $Calendario ->usuarios_registrados = $usuarios_registrados;
                                    $Calendario ->date = $Request->date;
                                    $Calendario ->hour = $Request->hour;
                                    $Calendario ->document = $Request->documento;

                                    $Calendario ->updated_at = $updated_at; 
                                    $Calendario ->id_register = Auth::user()->id;
                                    $Calendario->save();



                                // EN CASO DE QUE EL ID NO SEA EL MISMO QUE EL REQUEST
                                } else{
                                    foreach ($correos as $key => $value) {

                                        if ($value === Auth::user()->email) {

                                        } else{
                                            $messages = "Lo sentimos, el usuario ".$value." estas registrado un evento para esa hora";
                                            return back()->with('error', $messages);
                                        }

                                    }
                                }


                                    
                                    
                            } else{

                                if ($Request->documento !== NULL) {
                                    $activar_documento = 1;
                                } else{
                                    $activar_documento = 0;
                                }

                                    $Calendario = Calendario::find($Request->id);
                                    $Calendario ->title = $Request->title;
                                    $Calendario ->location = $Request->location;
                                    $Calendario ->comentario = $Request->comentario;
                                    $Calendario ->usuarios_registrados = $usuarios_registrados;
                                    $Calendario ->date = $Request->date;
                                    $Calendario ->hour = $Request->hour;
                                    $Calendario ->document = $Request->documento;

                                    $Calendario ->updated_at = $updated_at; 
                                    $Calendario ->id_register = Auth::user()->id;
                                    $Calendario->save();

                            }



                        } else{

                            $messages = "Lo sentimos, ya estas registrado un evento para esa hora";
                            return back()->with('error', $messages);



                        // Cierre del else verificacion fecha y hora
                        } 



                    break;
                }
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////
                    // //////////////////////////////////////////////////////////////////////////////////////////


                
                if(!$Calendario->save()){

                    $messages = 'Error al guardar la data, por favor cargue de nuevo el navegador';
                    return back()->with('success', $messages); 

                } else{


                        switch ($type) {
                            case 'recordatorio':
                                $messages = 'Recordatorio registrado exitosamente';
                                
                            break;


                            case 'evento':

                                $id_confirmation = Calendario::find($Request->id)->get('id_confirmation');

                                // Aqui guardamos la notificación:
                                $channel = 'evento';
                                $title = Auth::user()->name.' agrego un nuevo evento';
                                $text= 'El evento '.$Request->title.' ha sido creado, esperando confirmación';

                                $Notification = new Notification;
                                $Notification ->channel = $channel;
                                $Notification ->title = $title;
                                $Notification ->text = $text;
                                $Notification ->role_id = Auth::user()->role_id;
                                $Notification ->send_to = $usuarios_registrados;
                                $Notification ->status = 0;
                                $Notification ->created_at = $updated_at;
                                $Notification->save(); 



                                $website = URL::to('/');
                                $usuarios_registrados = explode(',', $usuarios_registrados);
                                foreach ($usuarios_registrados as $key => $value) {
                                    $correo = $value;

                                    if ($correo === $email) {
                                         $confirmation = 0;
                                    } else{
                                         $confirmation = 1;
                                    }

                                    $Confirmation = new Confirmation;
                                    $Confirmation ->id_calendario = $Request->id_confirmation;
                                    $Confirmation ->correo = $correo;
                                    $Confirmation ->confirmation = $confirmation;
                                    $Confirmation ->status = 0;
                                    $Confirmation ->created_at = $updated_at;
                                    $Confirmation->save();


                                    Mail::send('Email.email_registro_evento', [ 'Request' => $Request, 'correo' => $correo, 'activar_documento' => $activar_documento, 'confirmation' => $Confirmation, 'website' => $website ], function ($m) use ($Request, $correo){
                                            $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                                            $m->to($correo)->subject('Se ha registrado un nuevo evento: '.$Request->title.'');
                                        }
                                    );  

                                } 

                                $messages = 'Evento registrado exitosamente';
                            break;
                        
                        }

                        return redirect()->route('Calendar.Index')->with('success', $messages);
                        

                       
                // Cierre Else de si se guardo la data
                }

            // CIERRE DE VERIFCIADOR DE TITULO
            } else{
                $messages = "Lo sentimos, el campo titulo no debe estar vacio";
                return back()->with('error', $messages);
            }
             



        }



    }
  

    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////






    public function Send($id){


        $Calendario = Calendario::find($id)->get();

        foreach ($Calendario as $key => $value) {

            $send = $value->usuarios_registrados;
            $send = explode(',', $send); 
            $Request = $value;
                
            foreach ($send as $key => $value) {

                $correo = $value;

                 Mail::send('Email.email_registro_evento', ['Request' => $Request, 'correo' => $correo], function ($m) use ($Request, $correo){
                        $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                        $m->to($correo)->subject('Se ha registrado un nuevo documento '.$Request->nombre.'');
                    }
                );  


            }
        } 

        $messages = 'Mensaje reenviado exitosamente';
        return back()->with('success', $messages);

    }

    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function destroy($id){

        Calendario::find($id)->delete();
        //Creamos los mensajes de sucess y fail:
        $messages = 'Eliminado exitosamente';
        return back()->with('success', $messages);

          
    }

    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


     public function status(Request $Request){

        $id = $Request->id;
        $status = $Request->status;

        if ($status === '0') {
            $status = '1';
        } else{
            $status = '0';
        }
        $updated_at = date('Y-m-d H:i:s');


        $Documento = Calendar::find($Request->id);
        $Documento ->status = $status;
        $Documento ->updated_at = $updated_at; 
        $Documento->save();

        if (!$Documento->save()) {
            $messages = 'Error al cambiar el estado del documento';
            return back()->with('success', $messages);
        }else{
            $messages = 'Estado del documento cambiado exitosamente';
            return back()->with('success', $messages);
        }
    }


    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function agendarWithDocument($id){
        
    }



    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function usertrash(Request $Request){
        echo "Trash";
    }

    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////



    public function sendmessage(Request $Request){

        $activar_documento = '';
        $website = URL::to('/');
        // Llamamos los datos del usuario
        $user = User::where('id', $Request->id)->get()->all();
        foreach ($user as $key => $value) {
            $correo = $value->email;
        }

        $evento = Calendario::where('id', $Request->evento_id)->get()->all();

        foreach ($evento as $key => $value) {
            $Confirmation = Confirmation::where('id_calendario', $value->id_confirmation)->get()->all();
        }



        /*
        return view('Email.email_registro_evento_modify')->with([
            "evento" => $evento,
            "correo" => $correo,
            'activar_documento' => $activar_documento, 
            'confirmation' => $Confirmation,
             'website' => $website
        ]);

        */


         Mail::send('Email.email_registro_evento_modify', [ 'evento' => $evento, 'correo' => $correo, 'activar_documento' => $activar_documento, 'confirmation' => $Confirmation, 'website' => $website ], function ($m) use ($Request, $correo){
                $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                $m->to($correo)->subject('Se ha registrado un nuevo evento: '.$Request->title.'');
            }
        );



        
          $messages = 'Se ha enviado el correo exitosamente';
            return back()->with('success', $messages);


   
    }



    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////  ////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function find(Request $Request){
        $date = $Request->date;
        $email = Auth::user()->email;


        $mensaje = '

            <div class="grid-1">

        ';


        // EVENTO DATA
        $mensaje.='
            <div class="col-8_sm-12_xs-12 contenedor_resultado_eventos">
                <h5>Eventos registrados</h5>

        ';


       

        // VERIFICAMOS SI HAY EVENTOS EN LA FECHA 
        if (Calendario::where(['date' => $Request->date, 'type' => 'evento'])->count() > 0) {

            $eventos = Calendario::where(['date' => $Request->date, 'type' => 'evento'])->get()->all();

            foreach ($eventos as $key => $value) {



                $usuarios_registrados = $value->usuarios_registrados;

                // Aqui verificamos si el usuario esta registrado al evento:
                if (strpos($usuarios_registrados, $email) !== false) {

                    // AQUI CARGAMOS EL NOMBRE DEL CREADOR DEL EVENTO
                    $registrado = User::where('id', $value->id_register)->get('name')->all();
                    foreach ($registrado as $key => $value_created) {

                        $created_by = '
                            <div class="dat col-4_lg-4_md-6_sm-6_xs-12">
                                <i class="far fa-user"></i>
                                <p><b>Creado por:</b> '.$value_created->name.'</p>
                            </div>
                        ';
                    }



                    // CARGAMOS LOS DATOS DE APROBACION
                    //$aprobacion = Confirmation::where(['id_calendario' => $value->id_confirmation, 'correo' => $usuarios_value])->get()->all();
                    $aprobacion_total = Confirmation::where('id_calendario', $value->id_confirmation)->count();
                    $aprobacion_aprobadas = Confirmation::where(['id_calendario' => $value->id_confirmation, 'confirmation' => '0'])->count();



                    // AQUI CARGAMOS TODOS LOS USUARIOS ASIGNADOS AL EVENTO:
                    $usuarios_registrados = explode(',', $usuarios_registrados);

                    $usuarios = '';
                    foreach ($usuarios_registrados as $key => $usuarios_value) {

                        $user = User::where('email', $usuarios_value)->get()->all();
                        foreach ($user as $key => $value_user) {

                            

                            if ($usuarios_value === $email) {
                                # code...
                            } else{

                                $aprobacion = Confirmation::where(['id_calendario' => $value->id_confirmation, 'correo' => $usuarios_value])
                                            ->get('confirmation');


                                $messageLink = '';
                                $confirmation = '';
                                foreach ($aprobacion as $key => $value_aprobacion) {

                                    if ($value_aprobacion['confirmation'] === 0) {
                                        $confirmation.= 'Confirmada';



                                    } else{
                                        $confirmation.= 'Pendiente';
                                        $messageLink.= '
                                            <a href="/calendar/user/sendmessage/'.$value_user->id.'/'.$value->id.'"><i class="far fa-envelope"></i></i></a>
                                            ';
                                    }
                                }


                                if ($value->id_register === Auth::user()->id) {
                                     $usuarios.= '
                                        <div class="usuario_info grid-1-noGutter">
                                                                
                                                <div class="col-7_sm-12">
                                                    <div class="info">
                                                        <h6>'.$value_user->name.' '.$value_user->lastname.'</h6>
                                                        <p>'.$value_user->cargo.' / '.$value_user->department.'</p>
                                                        <p>'.$value_user->email.'</p>
                                                        <p>'.$value_user->cedula.'</p>
                                                    </div>
                                                </div>

                                                <div class="col-3_sm-6">
                                                    <div class="status_aprobación">
                                                        <h6>Estado</h6>
                                                        <p>'.$confirmation.'</p>
                                                    </div>
                                                 </div>
                                                                            

                                                <div class="col-2_sm-6">
                                                    <div class="status_aprobación">
                                                        <h6>Opciones</h6>
                                                        '.$messageLink.'
                                                        <a href="/calendar/user/trash/'.$value_user->id.'/'.$value->id.'"><i class="far fa-trash-alt"></i></a>
                                                    </div>
                                                </div>

                                                                            
                                            </div>

                                        ';
                                } else{
                                    $usuarios.= '
                                        <div class="usuario_info grid-1-noGutter">
                                                                
                                                <div class="col-9_sm-12">
                                                    <div class="info">
                                                        <h6><b>'.$value_user->name.' '.$value_user->lastname.'</b></h6>
                                                        <p>'.$value_user->cargo.' / '.$value_user->department.'</p>
                                                        <p>'.$value_user->email.'</p>
                                                        <p>'.$value_user->cedula.'</p>
                                                    </div>
                                                </div>

                                                <div class="col-3_sm-6">
                                                    <div class="status_aprobación">
                                                        <h6>Estado</h6>
                                                        <p>'.$confirmation.'</p>
                                                    </div>
                                                 </div>
                                                                         
                                            </div>

                                        ';
                                }

                            // if usuario Value
                            }


                        // Cierre del Foreach user
                        }


                    // Cierre del Foreach usuarios_registrados
                    }
                    

                    if ($value->document === null) {
                         $documento_link = '';
                        
                    } else{

                        // Aqui cargamos el documento
                        if (Documento::where('name', $value->document)->get('id')->count() > 0) {


                            $documento = Documento::where('name', $value->document)->get('id');
                            foreach ($documento as $key => $value_documento) {

                                $documento_link = '
                                    <p><a href="/documentos/download/'.$value_documento->id.'">'.$value->document.'</a></p>
                                ';
                            }
                       

                        } else{
                            $documento_link = '<p>No existe documento registrado</p>';
                        }

                       
                       
                    }

                   


                     // AQUI CARGAMOS EL MENSAJE FINAL
                    $mensaje.= '


                        <div class="evento_show">

                            <div class="opciones_up">
                                <a href="/calendar/show/'.$value->id.'"><i class="far fa-eye"></i> </a>
                                <a href="/calendar/edit/'.$value->id.'" title="edit"><i class="far fa-edit"></i></a>
                                <a href="/calendar/delete/'.$value->id.'" title="trash"><i class="far fa-trash-alt"></i></a>
                            </div>



                            <div class="grid-1-noGutter">
                                <div class="col-12">
                                    <h5>'.$value->title.'</h5>
                                    <p>'.$value->comentario.'</p>
                                </div>

                                <div class="col-12">
                                    <div class="datos grid-1-noGutter">
                                        <div class="dat col-3_lg-3_md-6_sm-4_xs-12">
                                            <i class="far fa-clock"></i>
                                            <p><b>Hora:</b> '.$value->hour.' pm</p>
                                        </div>
                                        <div class="dat col-12_lg-5_md-6_sm-6_xs-12">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p><b>Locación:</b> '.$value->location.'</p>
                                        </div>

                                        '.$created_by.'
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="show_more_button">Ver datos de este evento</button>
                                </div>
                            </div>

                            <div class="more_info">

                                <div class="usuarios_invitados">
                                    <h6 class="titulo">Usuarios asignados al evento: <b>( '.$aprobacion_aprobadas.' / '.$aprobacion_total.' aprobados )</b></h6>

                                    <div class="usuarios">
                                        '.$usuarios.'
                                    </div>
                                </div>

                                <div class="documento_link">
                                    <h6>Documento adjunto al evento:</h6> 
                                    '.$documento_link.'
                                </div>

                            </div>
                        </div>
                    ';





                // Cierre del if de verificación de usuario
                } else{
                     $mensaje.= '<h6>No se encuentra registrado un evento en esta fecha</h6>';
                }
            // Cierre del foreach
            }




        // Cierre del verificador de eventos
        } else{

            $mensaje.= '<h6>No se encuentra registrado un evento en esta fecha</h6>';
        
        }
        


        $mensaje.='</div>';



        // AQUI VA LO DE RECORDATORIO
        $mensaje.='
                <div class="col-4_sm-12_xs-12 contenedor_resultado_recordatorios">
                    <h5>Recordatorios</h5>
                    <div class="contenedor_recordatorios">
                                    
        ';


         // VERIFICAMOS SI HAY EVENTOS EN LA FECHA 
        if (Calendario::where(['date' => $Request->date, 'type' => 'recordatorio', 'id_register' => Auth::user()->id])->count() > 0) {

            $recordatorio = Calendario::where(['date' => $Request->date, 'type' => 'recordatorio', 'id_register' => Auth::user()->id])->get()->all();

            foreach ($recordatorio as $key => $value_recordatorio) {


                $mensaje.='
                    <div class="recordatorios">
                        <div class="opciones_up">
                            <a href="/calendar/show/'.$value_recordatorio->id.'"><i class="far fa-eye"></i> </a>
                            <a href="/calendar/edit/'.$value_recordatorio->id.'" title="edit"><i class="far fa-edit"></i></a>
                            <a href="/calendar/delete/'.$value_recordatorio->id.'" title="trash"><i class="far fa-trash-alt"  onclick="return confirm("¿Desea borrarlo?");"></i></a>
                        </div>
                        <h5>'.$value_recordatorio->hour.'</h5>
                        <small>'.$value_recordatorio->title.'</small>
                        <p>'.$value_recordatorio->comentario.'</p>

                    </div>
                                        
                '; 
            }
        } else{
            $mensaje.= '<h6>No se encuentra registrado recordatorio en esta fecha</h6>';
        }




        $mensaje.= '
                    </div>
                </div>
                ';



        // Cierre grid-1
        $mensaje.= '

            </div>

        ';


        return $mensaje;


    // cierre function
    }






}
