<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Departamento;
use Session;
use Cookie; 
use DB; 

class departamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('departamentos/index', [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'nombre'              => 'required|string|max:80',
           // 'encargado'          => 'required|string|max:80|unique:departamentos,encargado',
            
        ]); 

        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {
           
           $messages = $validator->messages();
           return back()->with('error', $messages);
            

        } else{

            if ($Request->encargado == NULL ) {


                    $created_at = date('Y-m-d H:i:s');
                    $updated_at = date('Y-m-d H:i:s');
                    $status = 0;
                    
                    $Departamento = new Departamento;
                    $Departamento ->name = $Request->nombre;
                    $Departamento ->display_name = $Request->nombre;
                    $Departamento ->encargado = '-';
                    $Departamento ->status = $status;
                    $Departamento ->created_at = $created_at;
                    $Departamento ->updated_at = $updated_at; 
                    $Departamento ->register_id = $Request->register_id;
                    $Departamento->save();



                    // Enviamos un correo en donde recibíra las credenciales
                    if(!$Departamento->save()){
                        $messages = "Error de conexión, por favor intentar más tarde";
                        return back()->with('error', $messages);
                    } else{
                        $messages = "Datos guardados satisfactoriamente";
                        return back()->with('success', $messages);
                    }




            } else{


                if (Departamento::where('encargado', $Request->encargado)->firts()->get()) {
                    $created_at = date('Y-m-d H:i:s');
                    $updated_at = date('Y-m-d H:i:s');
                    $status = 0;
                    
                    $Departamento = new Departamento;
                    $Departamento ->name = $Request->nombre;
                    $Departamento ->display_name = $Request->nombre;
                    $Departamento ->encargado = $Request->encargado;
                    $Departamento ->status = $status;
                    $Departamento ->created_at = $created_at;
                    $Departamento ->updated_at = $updated_at; 
                    $Departamento ->register_id = Auth::user()->id;
                    $Departamento->save();



                    // Enviamos un correo en donde recibíra las credenciales
                    if(!$Departamento->save()){
                        $messages = "Error de conexión, por favor intentar más tarde";
                        return back()->with('error', $messages);
                    } else{
                        $messages = "Datos guardados satisfactoriamente";
                        return back()->with('success', $messages);
                    }
                } else{
                    $messages = "El usuario ya está registrado como director en otro departamento";
                    return back()->with('success', $messages);
                }




                
            }

        // Cierre Else validator
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    } 

   


    public function update(Request $Request)
    {
         //VALIDAMOS LOS DATOS
        $validator = Validator::make($Request->all(), [
            'id'              => 'required|string|max:80',
            'encargado'          => 'required|string|max:80|unique:departamentos,encargado',
            
        ]); 

        // Verificamos si hay un error:
        $errors = $validator->errors();
        if ($validator->fails()) {
           
           $messages = $validator->messages();

           return back()->with('error', $messages);
          
        } else{

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            $status = 0;
            
            $Departamento = Departamento::find($Request->id);

            $Departamento ->encargado = $Request->encargado;
            $Departamento ->updated_at = $updated_at; 
            $Departamento->save();



           // Enviamos un correo en donde recibíra las credenciales
            if(!$Departamento->save()){
                $messages = "Error de conexión, por favor intentar más tarde";
                return back()->with('error', $messages);
            } else{
                $messages = "Datos guardados satisfactoriamente";
                return back()->with('success', $messages);
            }





        // Cierre Else validator
        }
    }






    
    public function destroy($id){

       $departamento = Departamento::where('id', $id)->get()->all();
       foreach ($departamento as $key => $value) {
            $encargado = $value->encargado;

            if ($encargado === '-') {
                Departamento::find($id)->delete();
                //Creamos los mensajes de sucess y fail:
                $messages = 'Departamento eliminado exitosamente';
                return back()->with('success', $messages);
            } else{
                $messages = 'Debe desvincular el usuario encargado del departamento para proseguir';
                return back()->with('errors', $messages);
            }
       }

        

          
    }




    public function desvincular(Request $Request){

        $id = $Request->id;
        $encargado = $Request->encargado;


        $Documentos = Departamento::where('id', $Request->id)->get()->all();

        foreach ($Documentos as $key => $value) {

            if ($value->encargado == $encargado) {

                $updated_at = date('Y-m-d H:i:s');
                $Departamento = Departamento::find($Request->id);
                $Departamento ->encargado = '-';
                $Departamento ->updated_at = $updated_at; 
                $Departamento->save(); 


                if (!$Departamento->save()) {
                    $messages = 'Error al cambiar el estado del departamento';
                    return back()->with('success', $messages);
                }else{
                    $messages = 'Usuario desvinculado exitosamente';
                    return back()->with('success', $messages);
                }


            } else{
                $messages = 'No coinciden la data del encargado';

                return back()->with('success', $messages);
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


        $Documento = Departamento::find($Request->id);
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









}
