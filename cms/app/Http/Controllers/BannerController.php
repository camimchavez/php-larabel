<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Administradores;

class BannerController extends Controller
{
    public function index(){

		 if(request()->ajax()){

            return datatables()->of(Banner::all())          
            ->addColumn('acciones', function($data){

                $acciones = '<div class="btn-group">
                            <a href="'.url()->current().'/'.$data->id_banner.'" class="btn btn-warning btn-sm">
                              <i class="fas fa-pencil-alt text-white"></i>
                            </a>
                         
                            <button class="btn btn-danger btn-sm eliminarRegistro" action="'.url()->current().'/'.$data->id_banner.'" method="DELETE" token="'.csrf_token().'" pagina="banner"> 
                            <i class="fas fa-trash-alt"></i>
                            </button>

                          </div>';
               
                return $acciones;

            })
            ->rawColumns(['acciones'])
            ->make(true);

        }

		$blog = Blog::all();
		$administradores = Administradores::all();
  // $banner = Banner::all();
		return view("paginas.banner", array("blog"=>$blog, "administradores"=>$administradores));

	}

  
	/*=============================================
    Crear un banner
    =============================================*/

    public function store(Request $request){

      // Recoger los datos

      $datos = array( "pagina_banner"=>$request->input("pagina_banner"),
                      "titulo_banner"=>$request->input("titulo_banner"),
                      "descripcion_banner"=>$request->input("descripcion_banner"),
                      "imagen_temporal"=>$request->file("img_banner"));  

      // Recoger datos de la BD blog para las rutas de imágenes 

      $blog = Blog::all();

       // Validar los datos
      // https://laravel.com/docs/5.7/validation
      if(!empty($datos)){
          
          $validar = \Validator::make($datos,[

              "pagina_banner" => "required|regex:/^[a-zA-Z ]+$/i",
              "imagen_temporal" => "required|image|mimes:jpg,jpeg,png|max:2000000"
        
          ]);

          //Guardar artículo

          if(!$datos["imagen_temporal"] || $validar->fails()){
 
              return redirect("banner")->with("no-validacion", "");

          }else{

              //Creamos el directorio donde guardaremos las imágenes del artículo

              $directorio = "img/banner";

              if(!file_exists($directorio)){  

                  mkdir($directorio, 0755);

              }   

              $aleatorio = mt_rand(100,999);

        $ruta = $directorio."/".$aleatorio.".".$datos["imagen_temporal"]->guessExtension();

        //Redimensionar Imágen

              list($ancho, $alto) = getimagesize($datos["imagen_temporal"]);

              $nuevoAncho = 1400;
              $nuevoAlto = 440;

              if($datos["imagen_temporal"]->guessExtension() == "jpeg"){

                  $origen = imagecreatefromjpeg($datos["imagen_temporal"]);
                  $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                  imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                  imagejpeg($destino, $ruta);

              }

              if($datos["imagen_temporal"]->guessExtension() == "png"){

                  $origen = imagecreatefrompng($datos["imagen_temporal"]);
                  $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
                  imagealphablending($destino, FALSE); 
                  imagesavealpha($destino, TRUE);
                  imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                  imagepng($destino, $ruta);
                  
              }

              // Mover todos los ficheros temporales al destino final
              $origen = glob('img/temp/banner/*'); 
                     
              foreach($origen as $fichero){

                  copy($fichero, $directorio."/".substr($fichero, 16));
                  unlink($fichero); 
                  
              } 
         
              $banner = new Banner();
              $banner->pagina_banner = $datos["pagina_banner"];
              $banner->titulo_banner = $datos["titulo_banner"];
              $banner->descripcion_banner = $datos["descripcion_banner"];
              $banner->img_banner = $ruta;
           

              $banner->save();    

              return redirect("banner")->with("ok-crear", "");
          }

      }else{
       
          return redirect("banner")->with("error", "");

      }

  }

  /*=============================================
  Mostrar un solo registro
  =============================================*/

  public function show($id){    

      $banner = Banner::where('id_banner', $id)->get();
      $blog = Blog::all();
      $administradores = Administradores::all();

      if(count($banner) != 0){
          return view("paginas.banner", array("status"=>200, "banner"=>$banner, "blog"=>$blog, "administradores"=>$administradores));
      
      }else{
          
          return view("paginas.banner", array("status"=>404, "blog"=>$blog, "administradores"=>$administradores));
      
      }

  }

  /*=============================================
  Editar un banner
  =============================================*/

  public function update($id, Request $request){

      // Recoger los datos

  
      $datos = array( "pagina_banner"=>$request->input("pagina_banner"),
                      "titulo_banner"=>$request->input("titulo_banner"),
                      "descripcion_banner"=>$request->input("descripcion_banner"),
                    
                    "imagen_actual"=>$request->input("imagen_actual"));  

      // Recoger datos de la BD blog para las rutas de imágenes 

      $blog = Blog::all();

      $directorio = "img/banner"; 

      // Recoger Imagen

      $imagen = array("imagen_temporal"=>$request->file("img_banner"));

      // Validar los datos
      // https://laravel.com/docs/5.7/validation
      if(!empty($datos)){
          
        $validar = \Validator::make($datos,[

          "pagina_banner" => "required|regex:/^[a-zA-Z ]+$/i",
          "imagen_actual" => "required"
    
      ]);
      if($imagen["imagen_temporal"] != ""){

        $validarImagen = \Validator::make($imagen,[

            "imagen_temporal" => "required|image|mimes:jpg,jpeg,png|max:2000000"

        ]);

        if($validarImagen->fails()){
       
         return redirect("banner")->with("no-validacion", "");

        }

    }

    //Guardar banner

    if($validar->fails()){
       
        return redirect("banner")->with("no-validacion", "");

    }else{

        if($imagen["imagen_temporal"] != ""){

            unlink($datos["imagen_actual"]);

            $aleatorio = mt_rand(100,999);

            $ruta = $directorio."/".$aleatorio.".".$imagen["imagen_temporal"]->guessExtension();

            //Redimensionar Imágen

          list($ancho, $alto) = getimagesize($imagen["imagen_temporal"]);

          $nuevoAncho = 1300;
          $nuevoAlto = 300;

          if($imagen["imagen_temporal"]->guessExtension() == "jpeg"){

              $origen = imagecreatefromjpeg($imagen["imagen_temporal"]);
              $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
              imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
              imagejpeg($destino, $ruta);

          }

          if($imagen["imagen_temporal"]->guessExtension() == "png"){

              $origen = imagecreatefrompng($imagen["imagen_temporal"]);
              $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
              imagealphablending($destino, FALSE); 
              imagesavealpha($destino, TRUE);
              imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
              imagepng($destino, $ruta);
              
          }

        }else{

            $ruta = $datos["imagen_actual"];
        }

        // Mover todos los ficheros temporales al destino final
        $origen = glob('img/temp/banner/*'); 
               
        foreach($origen as $fichero){

            copy($fichero, $directorio."/".substr($fichero, 19));
            unlink($fichero); 
            
        } 

              $datos = array("pagina_banner" => $datos["pagina_banner"],
                              "titulo_banner" => $datos["titulo_banner"],
                              "descripcion_banner" => $datos["descripcion_banner"],
                              "img_banner" => $ruta);

              $banner = Banner::where('id_banner', $id)->update($datos); 

              return redirect("banner")->with("ok-editar", "");
      }

      }else{

           return redirect("banner")->with("error", "");

      }

  }

  /*=============================================
  Eliminar un banner
  =============================================*/

  public function destroy($id, Request $request){

      $validar = Banner::where("id_banner", $id)->get();
          // echo '<pre>'; print_r($validar[0]["id_banner"]); echo  '<pre>';
          // echo '<pre>'; print_r($validar[0]["img_banner"]); echo  '<pre>';
  
      if(!empty($validar)){

         //Eliminamos directorio
        if(!empty($validar[0]["img_banner"])){
          unlink($validar[0]["img_banner"]);
        }
        //   // capturamos los archivos para eliminarlos uno por uno
        //  $origen = glob('img/banner/'.$validar[0]["img_banner"].'/*'); 
        //   echo $origen;
        // foreach($origen as
        //       unlink($fichero); 
              
        //   } 

        //   //Eliminamos directorio

        //   rmdir('img/banner/'.$validar[0]["img_banner"]);


          $banner = Banner::where("id_banner",$validar[0]["id_banner"])->delete();
       
          //Responder al AJAX de JS
          return "ok";
      
      }else{

          return redirect("banner")->with("no-borrar", "");   

      }

  }
}
