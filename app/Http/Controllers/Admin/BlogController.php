<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blog;

class BlogController extends Controller
{
    public function index(){


        return view('backEnd.blog.index');
    }



    public function store(Request $request){


        $blog = new Blog();
        $blog->b_title = $request->b_title;
        $blog->b_short_des = $request->b_short_des;
        $blog->b_long_des = $request->b_long_des;
        if( $request->hasFile('b_image') ){
            $image = $request->file('b_image');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/image/blog/';
            $image->move($imagePath, $imageName);

            $blog->b_image   = $imagePath . $imageName;
        }
        $blog->b_author = $request->b_author;
        if( $request->hasFile('b_author_image') ){
            $image = $request->file('b_author_image');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/image/blog/';
            $image->move($imagePath, $imageName);

            $blog->b_author_image   = $imagePath . $imageName;
        }
        $blog->b_date = $request->b_date;
        $blog->status = $request->status;
        $blog->save();
        return redirect()->route('blog_manager')->with('success','Blog Added Successfully');


   }

   public function show(){
    $blog = Blog::all();
    return view('backEnd.blog.show', compact('blog'));

      }

      public function edit($id){
      $edit =Blog::find($id);
       return view('backEnd.blog.edit',compact('edit'));
      }


      public function update(Request $request){
        $id=$request->id;

     $blog = Blog::find($id);
        $blog->b_title = $request->b_title;
        $blog->b_short_des = $request->b_short_des;
        $blog->b_long_des = $request->b_long_des;
        if( $request->hasFile('b_image') ){
            $image = $request->file('b_image');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/image/blog/';
            $image->move($imagePath, $imageName);

            $blog->b_image   = $imagePath . $imageName;
        }
        $blog->b_author = $request->b_author;
        if( $request->hasFile('b_author_image') ){
            $image = $request->file('b_author_image');

            $imageName          = microtime('.') . '.' . $image->getClientOriginalExtension();
            $imagePath          = 'public/backEnd/image/blog/';
            $image->move($imagePath, $imageName);

            $blog->b_author_image   = $imagePath . $imageName;
        }
        $blog->b_date = $request->b_date;
        $blog->status = $request->status;
        $blog->save();
        return redirect()->route('blog_manager')->with('success', 'Blog updated successfully!');
      }



      public function delete($id){


           $blog = Blog::findOrFail($id);

           if ( !is_null($blog->image) ) {
                if (file_exists($blog->image)) {
                    unlink($blog->image);
                }
            }

            $blog->delete();

            return redirect()->back()->with('message', 'Delete Successfully');
        }






        // front blog

        public function blog($id){
        $resentpost=Blog::latest()->take(4)->get();;
        $blog=Blog::find($id);
        return view('frontEnd.blog.blog',compact('blog','resentpost'));

        }

        public function status($id){

            $blog = Blog::find($id);
            if($blog->status == 1){
                $blog->status = 0;
            }else{
                $blog->status = 1;
            }
            $blog->update();
            return redirect()->back()->with('success', 'Blog Status has been changed successfully');

        }



   }
