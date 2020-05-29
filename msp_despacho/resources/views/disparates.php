
                    $email = Auth::user()->email;
                    //$consulta = Calendario::where(['date' => $Request->date, 'hour' => $Request->hour])->exists()->get()->all();
                    if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour])->exists()) {


                        if (Calendario::where( 'usuarios_registrados', '%'.$email."%")->exists()) {
                            echo "Estas registrado en un evento";
                        }else{

                            foreach
                           print_r($Request->usuarios_registrados);
                        }

                        //$messages = "Lo sentimos, ya existe un evento para esa hora";
                        //return back()->with('error', $messages);

                    } else{
                        echo "Los usuarios estan disponibles";
                        /*
                        if ($Request->input('usuarios_registrados') !== NULL) {
                            $usuarios_registrados = $Request->input('usuarios_registrados');
                            $usuarios_registrados = implode(',', $usuarios_registrados);
                            $usuarios_registrados =  $usuarios_registrados.','.$email;
                        } else{
                            $usuarios_registrados =  $email;

                        }

                        

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
                        */
                    }


























                    
                        $email = Auth::user()->email;
                        //$consulta = Calendario::where(['date' => $Request->date, 'hour' => $Request->hour])->exists()->get()->all();
                        if (Calendario::where([ 'date' => $Request->date, 'hour' => $Request->hour, 'usuarios_registrados' => '%'.$email."%" ])->exists()) {


                            $messages = "Lo sentimos, ya existe un evento para esa hora";
                            return back()->with('error', $messages);

                        } else{

                            if ($Request->input('usuarios_registrados') !== NULL) {
                                $usuarios_registrados = $Request->input('usuarios_registrados');
                                $usuarios_registrados = implode(',', $usuarios_registrados);
                                $usuarios_registrados =  $usuarios_registrados.','.$email;
                            } else{
                                $usuarios_registrados =  $email;

                            }

                            

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
                            $Calendario->save();
                        }