<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:2000'
        ]);

        try {
            // Ensure storage directories exist
            $this->ensureStorageDirectories();
            
            $image = $request->file('image');
            
            // Generate unique filename
            $filename = 'post_' . auth()->id() . '_' . time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            
            // Create full path
            $storagePath = storage_path('app/public/posts');
            $fullPath = $storagePath . '/' . $filename;
            
            // Ensure directory exists
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }
            
            // Move the uploaded file
            if ($image->move($storagePath, $filename)) {
                $imagePath = 'posts/' . $filename;
                
                Post::create([
                    'user_id' => auth()->id(),
                    'image_path' => $imagePath,
                    'caption' => $request->caption
                ]);

                return redirect()->route('home')->with('success', 'Postarea a fost creată cu succes!');
            } else {
                return back()->with('error', 'Eroare la încărcarea imaginii. Te rugăm să încerci din nou.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error creating post: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'A apărut o eroare la crearea postării: ' . $e->getMessage());
        }
    }

    private function ensureStorageDirectories()
    {
        $directories = [
            storage_path('app/public'),
            storage_path('app/public/posts'),
            storage_path('app/public/avatars'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        // Ensure storage link exists
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (!File::exists($linkPath)) {
            try {
                if (PHP_OS_FAMILY === 'Windows') {
                    // For Windows, create a junction
                    exec("mklink /J \"$linkPath\" \"$targetPath\"");
                } else {
                    // For Unix-like systems
                    symlink($targetPath, $linkPath);
                }
            } catch (\Exception $e) {
                \Log::error('Could not create storage link: ' . $e->getMessage());
            }
        }
    }

    public function like(Post $post)
    {
        try {
            $like = Like::where('user_id', auth()->id())
                ->where('post_id', $post->id)
                ->first();

            if ($like) {
                $like->delete();
                $post->decrement('likes_count');
                $liked = false;
            } else {
                Like::create([
                    'user_id' => auth()->id(),
                    'post_id' => $post->id
                ]);
                $post->increment('likes_count');
                $liked = true;
            }

            return response()->json([
                'liked' => $liked, 
                'likes_count' => $post->fresh()->likes_count
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Eroare la procesarea like-ului'], 500);
        }
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:500'
        ]);

        try {
            Comment::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'comment' => $request->comment
            ]);

            $post->increment('comments_count');

            return back()->with('success', 'Comentariul a fost adăugat!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Eroare la adăugarea comentariului.');
        }
    }
}
