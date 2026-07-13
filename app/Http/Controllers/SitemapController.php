<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Story;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    /** Generate an XML sitemap for SEO. */
    public function index()
    {
        $content = Cache::remember('sitemap_xml', 3600, function () {
            $projects = Project::active()->get();
            $stories = Story::active()->get();
            $posts = Post::published()->get();

            return view('sitemap', compact('projects', 'stories', 'posts'))->render();
        });

        return Response::make($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
