<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function index(){
      //  $posts = Post::all();
       // $msg = ['ok'];

        $posts = PostResource::collection(post::all());
        return $this->apiResponse($posts , 'OK' , 200);


        // before trait using
         //return response($posts , 200 , $msg );

    }


    public function show($id){
      // $post = new PostResource(Post::find($id));

        $post =  Post::find($id);

        if($post){
            return $this->apiResponse(new PostResource($post), 'OK' , 200);
        }

        return $this->apiResponse('null' , 'the post not found' , 401);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

       if($validator->fails()) {
           return $this->apiResponse('null' , $validator->errors(), 400);
       }

        $post = Post::create($request->all());
        if($post){
            return $this->apiResponse(new PostResource($post), 'OK' , 201);
        }

        return $this->apiResponse('null' , 'the post not created' , 400);


    }


    public function update(Request $request ,$id){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if($validator->fails()) {
            return $this->apiResponse('null' , $validator->errors(), 400);
        }
        $post = Post::find($id);
        if($post){
            $post->update($request->all());
            return $this->apiResponse(new PostResource($post), 'the post updated' , 200);
        }
        else {
            return $this->apiResponse('null' , 'the post not exist to updated' , 401);

        }
    }

  public function destroy($id){
        $post = Post::find($id);
        if($post){
            $post->delete();
            if($post)
            return $this->apiResponse(null, 'the post deleted' , 200);
            else{
                return $this->apiResponse(null, 'error on deleting' , 400);
            }
        } else{
            return $this->apiResponse('null' , 'the post not exist to deleted' , 401);

        }
  }

}
