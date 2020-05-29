<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
 
use App\User;
use App\Notification;
use App\Documento;
use App\Carpeta;
use App\Like;
use Session;
use Cookie;  
use DB;
use File; 

class documentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('documentos/index', [ 

        ]);
    }

    
    

 

    public function store(Request $Request) {
    
         //VALIDAMOS LOS DATOS

        $validator = Validator::make($Request->all(), [
            'nombre'            => 'required|string|max:70',
            'send'              => 'array',
            'send.*'            => 'string|max:70',
            //'empresa'           => 'required|string|max:89',
            //'entregado'         => 'required|string|max:40',
            'tipo'              => 'required|string|max:30',
            'carpeta'           => 'required|string'
        ]); 
 
    
        
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


            if ($Request->send !== NULL) {
                $send = $Request->send;
                $send = implode(',', $send); 

                $send = $send.','. Auth::user()->email;

            } else{
                $send = 'all';
            }


    

            $month  = date('M');
            $year = date('Y');
            $actual = $month.$year;
            $filename = $Request->imagen;

            // Verificamos si el campo imagen esta vacio:
            if ($filename == '') {

                $messages = 'No existe documento adjunto';
                return back()->with('error', $messages);

            // Verificamos que el archivo existe y preparado para guardar en el storage:
            } elseif ($Request->hasFile('imagen')) {

                
                $imagen = $Request->file('imagen');
                $extension = $imagen->getClientOriginalExtension();
                $location = 'documentos/'.$actual.'/';
                $filePath = $location.$imagen->getFilename().'.'.$extension;
                Storage::disk('public')->put($filePath,  File::get($imagen)); 

                
                
                $Documento = new Documento;
                $Documento ->name = $Request->nombre;
                $Documento ->send = $send;
                $Documento ->empresa = $Request->empresa;
                $Documento ->entregado = $Request->entregado;
                $Documento ->imagen = $filePath;
                $Documento ->tipo = $Request->tipo;
                $Documento ->situacion = 0;
                $Documento ->carpeta_id = $Request->carpeta;
                $Documento ->status = $status;
                $Documento ->created_at = $created_at;
                $Documento ->updated_at = $updated_at; 
                $Documento ->register_id = $Request->register_id;
                $Documento->save(); 
                

                // Enviamos un correo en donde recibíra las credenciales
                if(!$Documento->save()){

                    $messages = 'Error al guardar la data, por favor cargue de nuevo el navegador';
                    return back()->with('error', $messages);

                } else{


                    if($send === 'all'){

                    } else{

                        foreach ($Request->send as $key => $value) {

                            $correo = $value;
                
                            Mail::send('Email.email_registro_documento', ['Request' => $Request, 'correo' => $correo], function ($m) use ($Request, $correo){
                                    $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                                    $m->to($correo)->subject('Se ha registrado un nuevo documento '.$Request->nombre.'');
                                }
                          );  

                        } 


                    } 



                    // Aqui guardamos la notificación:
                    $channel = 'documentos';
                    $title = Auth::user()->name.' agrego un nuevo documento';
                    $text= 'El documento '.$Request->nombre.' ha sido agregado exitosamente';

                    $Notification = new Notification;
                    $Notification ->channel = $channel;
                    $Notification ->title = $title;
                    $Notification ->text = $text;
                    $Notification ->role_id = $Request->role_id;
                    $Notification ->status = 0;
                    $Notification ->created_at = $created_at;
                    $Notification ->updated_at = $created_at;
                    $Notification->save(); 


                    $messages = 'Documento registrado exitosamente';
                    return back()->with('success', $messages);

                    
                // Cierre Else de si se guardo la data
                }  





            // Campo validación de imagen
            }




        // Cierre Else validator
        }

    // STORE FUNCTION
    }


    public function edit(Request $Request){

        $data = Documento::find($Request->id);

        return view('documentos/edit', [
            "data" => $data
        ]); 

    }
  







    public function update(Request $Request){
         $validator = Validator::make($Request->all(), [
            'nombre'            => 'required|string|max:70',
            'send'              => 'array',
            'send.*'            => 'required|string|max:70',
            //'empresa'           => 'required|string|max:89',
            //'entregado'         => 'required|string|max:40',
            'tipo'              => 'required|string|max:30'
            
        ]); 


        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {

            $messages = $validator->messages();
            return back()->with('error', $messages);

        } else{
            // Guardamos los datos en la base de datos inicial
            $updated_at = date('Y-m-d H:i:s');

            if ($Request->send !== NULL) {
                $send = $Request->send;
                $send = implode(',', $send); 

                $send = $send.','. Auth::user()->email;

            } else{
                $send = 'all';
            }



                

                $Documento = Documento::find($Request->id);
                $Documento ->name = $Request->nombre;
                $Documento ->send = $send;
                $Documento ->empresa = $Request->empresa;
                $Documento ->entregado = $Request->entregado;
                $Documento ->tipo = $Request->tipo;
                $Documento ->updated_at = $updated_at; 
                $Documento ->register_id = $Request->register_id;
                $Documento->save();


                // Enviamos un correo en donde recibíra las credenciales
                if(!$Documento->save()){

                    $messages = 'Error al guardar la data, por favor cargue de nuevo el navegador';
                    return back()->with('error', $messages);

                } else{

                    if($send === 'all'){

                    } else{

                        foreach ($Request->send as $key => $value) {

                            $correo = $value;
                
                            Mail::send('Email.email_registro_documento', ['Request' => $Request, 'correo' => $correo], function ($m) use ($Request, $correo){
                                    $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                                    $m->to($correo)->subject('Se ha registrado un nuevo documento '.$Request->nombre.'');
                                }
                          );  

                        } 


                    }  

                    $messages = 'Documento registrado exitosamente';
                    return redirect()->route('Documentos.Index')->with('success', $messages);

                   
                // Cierre Else de si se guardo la data
                }



        // Cierre Else validator
        }


    // FUNCTION UPDATE
    }





    public function Send($id){


        $documento = Documento::find($id)->get();

        foreach ($documento as $key => $value) {

            $send = $value->send;
            $send = explode(',', $send); 
            $Request = $value;
                
            foreach ($send as $key => $value) {

                $correo = $value;

                 Mail::send('Email.email_registro_documento', ['Request' => $Request, 'correo' => $correo], function ($m) use ($Request, $correo){
                        $m->from('oscarseijo@outlook.com', 'MSP ASAP');
                        $m->to($correo)->subject('Se ha registrado un nuevo documento '.$Request->nombre.'');
                    }
                );  

                
               
            }


        } 

        $messages = 'Mensaje reenviado exitosamente';
        return back()->with('success', $messages);

    }
    







    public function destroy($id){

        Documento::find($id)->delete();
        //Creamos los mensajes de sucess y fail:
        $messages = 'Documento eliminado exitosamente';
        return back()->with('success', $messages);

          
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


        $Documento = Documento::find($Request->id);
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






    public function download($id){

        //PDF file is stored under project/public/download/info.pdf
        //$file= public_path(). "/download/info.pdf";

        $data = Documento::where('id', $id)->get();

        foreach($data as $key => $value){

            $documento = public_path()."/storage/".$value->imagen;
            $ext = pathinfo($documento, PATHINFO_EXTENSION);
            $name = $value->name.'.'.$ext;
            return response()->download($documento);     

           // $headers = array( 'Content-Type: application/pdf' );
           // return Response::download($documento, $name, $headers);
        } 

        

        //return Response::download($file, 'filename.pdf', $headers);

    }
 















    public function Likes(Request $Request){

        if ($Request->status === '0') {
            $status = '1';
        } else{
            $status = '0';
        }

        $updated_at = date('Y-m-d H:i:s');

        if (Like::where('document_id', '=', $Request->document_id)->exists()) {
            $Like = Like::find($Request->id);
            $Like ->status = $status;
            $Like ->updated_at = $updated_at; 
            $Like->save();
        } else{

            $id_document = $Request->document_id;

            $Like = new Like;
            $Like ->document_id     = $id_document; 
            $Like ->register_id     = Auth::user()->id;
            $Like ->status          = $status;
            $Like ->created_at      = $updated_at; 
            $Like->save();
        }


        
        if (!$Like->save()) {
            $messages = 'Error al cambiar el estado del documento';
            return back()->with('success', $messages);
        }else{
            $messages = 'El documento ha sido agregado a favoritos';
            return back()->with('success', $messages);
        } 
    } 


















































}
