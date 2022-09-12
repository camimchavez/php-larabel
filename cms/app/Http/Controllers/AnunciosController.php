<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anuncios;
use App\Models\Blog;
use App\Models\Administradores;

class AnunciosController extends Controller
{
    public function index(){

		 if(request()->ajax()){

            return datatables()->of(Anuncios::all())
             ->addColumn('codigo_anuncio', function($data){

                $codigo_anuncio = '<div class="card collapsed-card">

							        <div class="card-header">

							          <h3 class="card-title">Ver Anuncio</h3>

							          <div class="card-tools">

							            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
							              <i class="fas fa-minus"></i>
							            </button>
							           
							          </div>

							        </div>

							        <div class="card-body">'.$data->codigo_anuncio.'</div>

							      </div>';
                
                return $codigo_anuncio;

            })
            ->addColumn('acciones', function($data){

                $acciones = '<div class="btn-group">
                            <a href="'.url()->current().'/'.$data->id_anuncio.'" class="btn btn-warning btn-sm">
                              <i class="fas fa-pencil-alt text-white"></i>
                            </a>
                         
                            <button class="btn btn-danger btn-sm eliminarRegistro" action="'.url()->current().'/'.$data->id_anuncio.'" method="DELETE" token="'.csrf_token().'" pagina="anuncios"> 
                            <i class="fas fa-trash-alt"></i>
                            </button>

                          </div>';
               
                return $acciones;

            })
            ->rawColumns(['codigo_anuncio','acciones'])
            ->make(true);

        };

		$blog = Blog::all();
		$administradores = Administradores::all();
    // $anuncios = Anuncios::all();
		return view("paginas.anuncios", array("blog"=>$blog, "administradores"=>$administradores));

	}

  /*=============================================
    Crear un anuncio
    =============================================*/

    public function store(Request $request){

      // Recoger los datos

      $datos = array("pagina_anuncio"=>$request->input("pagina_anuncio"),
      "codigo_anuncio"=>$request->input("codigo_anuncio")); 

      // Recoger datos de la BD blog para las rutas de imágenes 

      $blog = Blog::all();

       // Validar los datos
      // https://laravel.com/docs/5.7/validation
      if(!empty($datos)){
          
        $validar = \Validator::make($datos,[

          "pagina_anuncio" => "required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
          "codigo_anuncio" => 'required|regex:/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i'
   
      ]);

          //Guardar artículo

          if($validar->fails()){
 
              return redirect("anuncios")->with("no-validacion", "");

          }else{

               //Creamos el directorio donde guardaremos las imágenes del artículo

               $directorio = "img/anuncios";    

       
         
              $anuncio = new Anuncios();
              $anuncio->pagina_anuncio = $datos["pagina_anuncio"];
              $anuncio->codigo_anuncio = str_replace('src="'.$blog[0]["servidor"].'img/temp/anuncios', 'src="'.$blog[0]["servidor"].$directorio, $datos["codigo_anuncio"]);

              $anuncio->save();

              return redirect("anuncios")->with("ok-crear", "");
          }

      }else{
       
          return redirect("anuncios")->with("error", "");

      }

  }

  /*=============================================
  Mostrar un solo anuncio
  =============================================*/

  public function show($id){    

      $anuncios = Anuncios::where('id_anuncio', $id)->get();

      $blog = Blog::all();
      $administradores = Administradores::all();

      if(count($anuncios) != 0){

          return view("paginas.anuncios", array("status"=>200, "anuncios"=>$anuncios, "blog"=>$blog, "administradores"=>$administradores));
      
      }else{
          
          return view("paginas.anuncios", array("status"=>404, "blog"=>$blog, "administradores"=>$administradores));
      
      }

  }

  /*=============================================
  Editar un anuncio
  =============================================*/

  public function update($id, Request $request){

      // Recoger los datos

      $datos = array("pagina_anuncio"=>$request->input("pagina_anuncio"),
                      "codigo_anuncio"=>$request->input("codigo_anuncio"));

      $blog = Blog::all();
      // Validar los datos
      // https://laravel.com/docs/5.7/validation
      if(!empty($datos)){
          
         $validar = \Validator::make($datos,[

              "pagina_anuncio" => "required|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i",
              "codigo_anuncio" => 'required|regex:/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/i'
       
          ]);


          //Guardar articulo

          if($validar->fails()){
             
              return redirect("anuncios")->with("no-validacion", "");

          }else{

            $directorio = "img/anuncios";

            
              $datos = array(
                              "pagina_anuncio" => $datos["pagina_anuncio"],
                               "codigo_anuncio" => str_replace('src="'.$blog[0]["servidor"].'img/temp/anuncios', 'src="'.$blog[0]["servidor"].$directorio, $datos["codigo_anuncio"]));

              $anuncio = Anuncios::where('id_anuncio', $id)->update($datos); 

              return redirect("anuncios")->with("ok-editar", "");
          }

      }else{

           return redirect("anuncios")->with("error", "");

      }

  }

  /*=============================================
  Eliminar un anuncio
  =============================================*/

  public function destroy($id, Request $request){

      $validar = Anuncios::where("id_anuncio", $id)->get();
      
      if(!empty($validar)){


          $anuncio = Anuncios::where("id_anuncio",$validar[0]["id_anuncio"])->delete();

          //Responder al AJAX de JS
          return "ok";
      
      }else{

          return redirect("categorias")->with("no-borrar", "");   

      }

  }



}
