<?php

namespace App\Http\Controllers;


use App\Exports\PostExport;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;



class PostController extends Controller
{
    //prikaz svih postova
    

    public function index()
    {
        $posts = Cache::remember('all_posts', now()->addDay(), function () {
            return Post::all();
        });
    
        return response()->json([
            'posts' => $posts
        ], 200);
    }

    //funkcija za prikaz kesiranih postova
    public function showCachedPosts()
{
    $cachedPosts = Cache::get('all_posts');
    
    return response()->json([
        'cached_posts' => $cachedPosts
    ], 200);
}

//kreiranje novog
    public function store(PostStoreRequest $request) //Kao argument prima instancu PostStoreRequest,
                                                    // koja vrši validaciju podataka koje korisnik šalje 
    {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
     
            // Create Post
            $post = Post::create([
                'name' => $request->name,
                'image' => $imageName,
                'description' => $request->description
            ]);
     
            // Save Image in Storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
     
            // Return Json Response
            return response()->json([
                'message' => "Post successfully created.",
                'post' => $post
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    } 


    //prikaz po idu
    public function show($id)
    {
       // Post Detail 
       $post = Post::find($id);
       if(!$post){
         return response()->json([
            'message'=>'Post Not Found.'
         ],404);
       }
     
       // Return Json Response
       return response()->json([
          'post' => $post
       ],200);
    }
 
    //update posta
    public function update(Request $request, $id)
    {
        try {
            // Find post
            $post = Post::find($id);
            if(!$post){
              return response()->json([
                'message'=>'Post Not Found.'
              ],404);
            }

            $post->name = $request->name;
            $post->description = $request->description;
     
            if($request->image) {
                // Public storage
                $storage = Storage::disk('public');
     
                // Old image delete
                if($storage->exists($post->image))
                    $storage->delete($post->image);
     
                // Image name
                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $post->image = $imageName;
     
                // Image save in public folder
                $storage->put($imageName, file_get_contents($request->image));
            }
     
            // Update Post
            $post->save();
     
            // Return Json Response
            return response()->json([
                'message' => "Post successfully updated.",
                'post' => $post
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }
 

    //brisanje posta
    public function destroy($id)
    {
        // Detail 
        $post = Post::find($id);
        if(!$post){
          return response()->json([
             'message'=>'Post Not Found.'

          ],404);
        }
     
        // Public storage
        $storage = Storage::disk('public');
     
        // Iamge delete
        if($storage->exists($post->image))
            $storage->delete($post->image);
     
        // Delete Post
        $post->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "Post successfully deleted."
        ],200);
    }

    //search radi i za slovo, i za celu rec i vraca i vise postova ako odgovaraju
    //radi ako ima 2 reci u sebi
    public function search($name)
    {
    $posts = Post::where('name', 'LIKE', '%' . $name . '%')->get();

    if ($posts->isEmpty()) {
        return response()->json(['message' => 'No posts match the searched criteria.'], 404);
        }

        return response()->json([
            'message' => "Search results are:",
            'posts' => $posts
        ], 200);
    }

    //export funkcija
    public function exportToCSV(){
        return Excel::download(new PostExport, 'post-csv.csv');
    }

    public function exportToExcel(){
        return Excel::download(new PostExport, 'post-excel.xlsx');
    }

    



}
