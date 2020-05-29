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
use App\Documento;
use App\Carpeta;
use App\Like;
use Session;
use Cookie;  
use DB;
use File; 


class folderController extends Controller{




    public function CreateFolder(Request $Request){


        $validator = Validator::make($Request->all(), [
            'folder_name'            => 'required|string|max:70|unique:carpetas,name',
            'tipo_carpeta'           => 'required|string|max:11',
            'access'                 => 'array',
            'access.*'               => 'string|max:89',
        ]); 


        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {

            $messages = $validator->messages();
            return back()->with('error', $messages);

        } else{

            $status = 0;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');


            if ($Request->access !== NULL) {
                $access = $Request->access;
                $access = implode(',', $access); 

                $access = $access.','. Auth::user()->email;

            } else{
                $access = 'all';
            }
            
            $Carpeta = new Carpeta;
            $Carpeta ->name = $Request->folder_name;
            $Carpeta ->tipo = $Request->tipo_carpeta;
            $Carpeta ->access = $access;
            $Carpeta ->department = Auth::user()->department;
            $Carpeta ->status = $status;
            $Carpeta ->created_at = $created_at; 
            $Carpeta ->updated_at = $updated_at; 
            $Carpeta ->register_id = Auth::user()->id; 
            $Carpeta->save();


            

             if (!$Carpeta->save()) {
                $messages = 'Error al crear carpeta, intente de nuevo';
                return back()->with('success', $messages);
            }else{
                $messages = 'La carpeta '.$Request->folder_name.' ha sido creada exitosamente';
                return back()->with('success', $messages);
            } 


        }

    }


    public function edit($id){
    	$data = Carpeta::where('id', $id)->get()->all();

    	return view('carpeta/edit', [
    		"data" => $data
        ]); 
    }



    public function move(Request $Request){

    	$id = $Request->id;

    	return view('carpeta/move', [
    		"id" => $id
        ]); 
    }



    public function moveDocument(Request $Request){
    	$id = $Request->id;
    	$carpeta = $Request->carpeta;


    	if (Carpeta::where('name', '=', $carpeta)->exists()) {

    		$carpeta = Carpeta::where('name', $carpeta)->get();


    		foreach ($carpeta as $key => $value) {
	    		 $id_carpeta = $value->id;
	    	}

	    	$Documento = Documento::find($id);
	    	$Documento ->carpeta_id = $id_carpeta;
	    	$Documento->save();


	    	 // Enviamos un correo en donde recibíra las credenciales
	        if(!$Documento->save()){

	            $messages = 'Error al mover el documento, por favor cargue de nuevo el navegador';
	            return back()->with('error', $messages);

	        } else{

	           $messages = 'Se ha movido el documento exitosamente';
	           return redirect()->route('Documentos.Index')->with('success', $messages);
	     
	        // Cierre Else de si se guardo la data
	        }  


		} else{
			$messages = 'La carpeta que deseas mover el documento no existe, seleccionar otra.';
            return back()->with('error', $messages);
		}


 


    }




    public function upload(Request $Request){


    	 $validator = Validator::make($Request->all(), [
            'name'            			=> 'required|string|max:70',
            'tipo'           			=> 'required|string|max:89',
            'access'            		=> 'array',
            'access.*'          		=> 'string|max:70',
            
            
        ]); 


        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {

            $messages = $validator->messages();
            return back()->with('error', $messages);

        } else{
            // Guardamos los datos en la base de datos inicial
            $updated_at = date('Y-m-d H:i:s');



            $Carpeta = Carpeta::find($Request->id);
            $Carpeta ->name = $Request->name;
            $Carpeta ->tipo = $Request->tipo;
            

            if ($Request->access !== NULL) {
                $access = $Request->access;
                $access = implode(',', $access); 

                $access = $access.','. Auth::user()->email;
                $Carpeta ->access = $access;
            } else{
                $access = 'all';

            }


            $Carpeta ->updated_at = $updated_at; 
            $Carpeta->save();


            // Enviamos un correo en donde recibíra las credenciales
            if(!$Carpeta->save()){

                $messages = 'Error al modificar la data, por favor cargue de nuevo el navegador';
                return back()->with('error', $messages);

            } else{

                $messages = 'Carpeta registrada exitosamente';
                return redirect()->route('Documentos.Index')->with('success', $messages);
     
            // Cierre Else de si se guardo la data
            }  

        // Cierre Else validator
        }



    }











    public function delete($id){


        if ($documentos = Documento::where('carpeta_id', $id)->exists()) {
           

            $messages = "Existen documentos dentro de esta carpeta, por favor mover o eliminar los archivos primero.";
            return back()->with('error', $messages);

        } else{

            Carpeta::find($id)->delete();
            $messages = 'Carpeta eliminada exitosamente';
            return back()->with('success', $messages);


        }


      

    }






}
 