<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administradores;
use App\Models\Blog;
use Illuminate\Support\Facades\Hash;


class AdministradoresController extends Controller
{
   /* =====================================
    Mostrar todos los registros
    ====================================== */
    public function index(){

        $blog = Blog::all();
        $administradores = Administradores::all();

        // return view("paginas.administradores", array("administradores"=>$administradores,"blog"=>$blog));
        if(request()->ajax()){
            return datatables()->of(Administradores::all())
            ->addColumn('acciones', function($data){

                $acciones = '<div class="btn-group">
                
                <a href="'.url()->current().'/'.$data->id.'" class="btn btn-warning btn-sm">
                <i class="fas fa-pencil-alt text-white"></i>
                </a>

                <button class="btn btn-danger btn-sm eliminarRegistro" action="'.url()->current().'/'.$data->id.'" method="DELETE" pagina="administradores" token="'.csrf_token().'">
                <i class="fas fa-trash-alt text-white"></i>
                </button>
                
               </div> ';

                return $acciones;
            })
            ->rawColumns(['acciones'])
            ->make(true);

        }
        return view("paginas.administradores",array("blog"=>$blog, "administradores"=> $administradores));
    }

     /* =====================================
    Mostrar un solo registro
    ====================================== */

    public function show($id){
        $blog = Blog::all();
        $administrador = Administradores::where("id",$id)->get();
        $administradores = Administradores::all();

        if(count($administrador) != 0){
            return view("paginas.administradores", array("status"=>200, "administrador" => $administrador,"blog"=>$blog, "administradores"=> $administradores));

        }
        else{
            return view("paginas.administradores", array("status"=>400,"blog"=>$blog, "administradores"=> $administradores));
        }

    }

       /* =====================================
    Editar un registro
    ====================================== */

    public function update($id, Request $request){
        $blog = Blog::all();

        // REcoger datos
        $datos = array("name" => $request->input("name"),
        "email"=> $request->input("email"),
        "password_actual"=> $request->input("password_actual"),
        "rol"=> $request->input("rol"),
        "imagen_actual"=> $request->input("imagen_actual"));


        $password = array("password"=>$request->input("password"));
        $imagen = array("foto"=>$request->file("foto"));

        if(!empty($datos)){
            $validar = \Validator::make($datos,[
                'name' => 'required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i',
                'email' => 'required|regex:/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/i'

            ]); 	 
        
            if($password["password"] != ""){

                $validarPassword = \Validator::make($password,[

                    "password" => "required|regex:/^[0-9a-zA-Z]+$/i"

                ]);
                if($validarPassword->fails()){
                    return redirect("/administradores")->with("no-validacion","");
                }else{
                    $nuevaPassword = Hash::make($password['password']);
                }
            }else{
                $nuevaPassword = $datos['password_actual'];
            }
        
            if($imagen["foto"] != ""){
                $validarFoto = \Validator::make($imagen,[
                    "foto"=>"required|image|mimes:jpg,jpeg,png|max:2000000"
                ]);
                if($validarFoto->fails()){
                    return redirect("/administradores")->with("no-validacion","");
                }
            }

            if($validar->fails()){
                return redirect("/administradores")->with("no-validacion","");
            }else{

                if($imagen["foto"] != ""){
                    if(!empty($datos["imagen_actual"])){

                        if($datos["imagen_actual"] != "img/administradores/admin.png"){
                            unlink($datos["imagen_actual"]);
                        }
                    }
                    $aleatorio = mt_rand(100,999);
                    $ruta = "img/administradores/".$aleatorio.".".$imagen["foto"]->guessExtension();

                    move_uploaded_file($imagen["foto"],$ruta);
                }else{
                    $ruta = $datos["imagen_actual"];
                }

                $datos = array("name"=> $datos["name"],
                "email"=> $datos["email"],
                "password"=> $nuevaPassword,
                "rol"=> $datos["rol"],
                "foto"=> $ruta);

                $administrador = Administradores::where("id", $id)->update($datos);

                return redirect("/administradores")->with("ok-editar","");
            }
        }else{
            return redirect("/administradores")->with("error","");
            
        }
    }

           /* =====================================
    Eliminar un registro
    ====================================== */

    public function destroy($id, Request $request){

        $validar = Administradores::where("id", $id)->get();
        // echo '<pre>'; print_r($validar["id"]); echo  '<pre>';
        if(!empty($validar) && $id != 1){

            if(!empty($validar[0]["foto"])){
                unlink($validar[0]["foto"]);
            }



            $administrador = Administradores::where("id", $validar[0]["id"])->delete();

            // return redirect("/administradores")->with("ok-eliminar","");

            //Responder al ajax de js

            return "ok";
        }else{
            return redirect("/administradores")->with("no-borrar","");
        }

    }
}
