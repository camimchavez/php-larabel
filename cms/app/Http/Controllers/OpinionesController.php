<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opiniones;
use App\Models\Blog;
use App\Models\Administradores;
use App\Models\Articulos;
use Illuminate\Support\Facades\DB;


class OpinionesController extends Controller
{
    public function index(){

    	 $join = DB::table('opiniones')
         ->join('users','opiniones.id_adm','=','users.id')
         ->join('articulos', 'opiniones.id_art', '=', 'articulos.id_articulo')
         ->select('opiniones.*','users.*','articulos.*')->get();   

        if(request()->ajax()){

            return datatables()->of($join)
            ->addColumn('aprobacion_opinion', function($data){

                if($data->aprobacion_opinion == 1){

                    $aprobacion = '<button class="btn btn-success btn-sm">Aprobado</button>';

                }else{

                    $aprobacion = '<button class="btn btn-danger btn-sm">Por Aprobar</button>';

                }
               
                return $aprobacion;

            })
            ->addColumn('acciones', function($data){

                $acciones = '<div class="btn-group">
                            <a href="'.url()->current().'/'.$data->id_opinion.'" class="btn btn-warning btn-sm">
                              <i class="fas fa-pencil-alt text-white"></i>
                            </a>

                            <button class="btn btn-danger btn-sm eliminarRegistro" action="'.url()->current().'/'.$data->id_opinion.'" method="DELETE" token="'.csrf_token().'" pagina="opiniones"> 
                            <i class="fas fa-trash-alt"></i>
                            </button>

                          </div>';
               
                return $acciones;

            })
            ->rawColumns(['aprobacion_opinion','acciones'])
            ->make(true);

        }

		$blog = Blog::all();
		$administradores = Administradores::all();

		return view("paginas.opiniones", array("blog"=>$blog, "administradores"=>$administradores));

	}

    
    /*=============================================
    Mostrar un sola Opinion
    =============================================*/

    public function show($id){    

        $opiniones = Opiniones::where('id_opinion', $id)->get();
        $blog = Blog::all();
        $administradores = Administradores::all();
        $articulos = Articulos::all();

        if(count($opiniones) != 0){

            return view("paginas.opiniones", array("status"=>200, "blog"=>$blog, "administradores"=>$administradores, "articulos"=>$articulos, "opiniones"=>$opiniones));
        
        }else{
            
            return view("paginas.opiniones", array("status"=>404, "blog"=>$blog, "administradores"=>$administradores));
        
        }

    }


      /*=============================================
    Editar una opinion
    =============================================*/

    public function update($id, Request $request){

        // Recoger los datos

        $datos = array("aprobacion_opinion"=>$request->input("aprobacion_opinion"),
                        "id_adm"=>$request->input("id_adm"),
                        "respuesta_opinion"=>$request->input("respuesta_opinion"),
                        "fecha_respuesta"=>$request->input("fecha_respuesta")); 

    

        // Validar los datos
        // https://laravel.com/docs/5.7/validation
        if(!empty($datos)){
            
           $validar = \Validator::make($datos,[

                "aprobacion_opinion" => "required|regex:/^[0-9]+$/i",
                "id_adm" => '"required|regex:/^[0-9]+$/i',
                "respuesta_opinion" => 'required|regex:/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i'
            ]);

                $datos = array("aprobacion_opinion" => $datos["aprobacion_opinion"],
                                "id_adm" => $datos["id_adm"],
                                "respuesta_opinion" => $datos["respuesta_opinion"],
                                "fecha_respuesta" => $datos["fecha_respuesta"]);
                             
                $opinion = Opiniones::where('id_opinion', $id)->update($datos); 

                return redirect("opiniones")->with("ok-editar", "");
            

        }else{

             return redirect("opiniones")->with("error", "");

        }

    }

    /*=============================================
    Eliminar una opinion
    =============================================*/

    public function destroy($id, Request $request){

        $validar = Opiniones::where("id_opinion", $id)->get();
        
        if(!empty($validar)){


            $opinion = Opiniones::where("id_opinion",$validar[0]["id_opinion"])->delete();

            //Responder al AJAX de JS
            return "ok";
        
        }else{

            return redirect("opiniones")->with("no-borrar", "");   

        }

    }
}
