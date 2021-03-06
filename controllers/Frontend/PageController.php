<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Tag;
use App\Models\StaticPage;
use LiveCMS\Models\Permalink;
use LiveCMS\Controllers\FrontendController;
use ReflectionClass;
use Illuminate\Http\Request;

class PageController extends FrontendController
{
    public function home()
    {
        // if set launching time
        $launchingDateTime = globalParams('launching_datetime') ?
        new Carbon(globalParams('launching_datetime')) : Carbon::now();


        // check if has home permalink
        $permalink = Permalink::withDependencies()->whereIn('permalink', ['/', ''])->first();

        // if home exist or not yet launch
        if ($permalink == null || $launchingDateTime->isFuture()) {
            return redirect('coming-soon');
        }

        $post = $permalink->postable;

        $title = globalParams('home_title', config('livecms.home_title', 'Home'));

        return view(theme('front', 'home'), compact('post', 'title'));
    }

    public function getArticle($redirect = true, $slug = null, $with = [])
    {
        $article = new Article;

        view()->share($with);

        foreach ($with as $key => $value) {
            
            $article = $article->whereHas($key, function ($query) use ($value) {
                $query->where($query->getModel()->getKeyName(), $value);
            });
        }

        if ($slug == null) {

            $articles = $article->orderBy('published_at', 'DESC')->simplePaginate(12);

            return view(theme('front', (request()->ajax() ? 'partials.articles' : 'articles')), compact('articles'));

        }

        $post = $article = $article->where('slug', $slug)->firstOrFail();
        $title = $post->title;

        if ($redirect && $post->permalink) {
            
            return redirect($post->url);
        }

        return view(theme('front', 'article'), compact('post', 'article', 'title'));
    }

    public function getGallery($redirect = true, $slug = null, $with = [])
    {
        $gallery = new Gallery;

        view()->share($with);

        foreach ($with as $key => $value) {
            
            $gallery = $gallery->whereHas($key, function ($query) use ($value) {
                $query->where($query->getModel()->getKeyName(), $value);
            });
        }

        if ($slug == null) {

            $galleries = $gallery->orderBy('published_at', 'DESC')->simplePaginate(12);

            return view(theme('front', (request()->ajax() ? 'partials.galleries' : 'galleries')), compact('galleries'));

        }

        $post = $gallery = $gallery->where('slug', $slug)->firstOrFail();
        $title = $post->title;

        if ($redirect && $post->permalink) {
            
            return redirect($post->url);
        }

        return view(theme('front', 'gallery'), compact('post', 'gallery', 'title'));
    }

    public function getStaticPage($redirect = true, $slug = null)
    {
        $post = $statis = StaticPage::where('slug', $slug)->firstOrFail();
        $title = $post->title;

        if ($redirect && $post->permalink) {
            
            return redirect($post->url);
        }

        return view(theme('front', 'staticpage'), compact('post', 'statis', 'title'));
    }

    public function getByPermalink($permalink)
    {
        $page = Permalink::where('permalink', $permalink)->firstOrFail();

        $type = (new ReflectionClass($post = $page->postable))->getShortName();

        return view()->exists(theme('front', $permalink)) ? view(theme('front', $permalink), compact('post')) : $this->{'get'.$type}(false, $post->slug);
    }

    public function routes()
    {
        $parameters = func_get_args();

        // get static
        $statisSlug = getSlug('staticpage');

        if ($parameters[0] == $statisSlug) {
            view()->share('routeBy', 'static');
            return $this->getStaticPage(true, $parameters[1]);
        }

        // get article category
        $category = Category::where('slug', $parameters[0])->first();
        if ($category || $parameters[0] == 'category') {
            view()->share('routeBy', 'category');
            $param = isset($parameters[1]) ? $parameters[1] : null;
            if (!$category) {
                $category = Category::where('slug', $param)->first();
                $param = null;
            }
            view()->share('title', $category->category);
            return $this->getArticle(true, $param, $category ? ['categories' => $category->id] : []);
        }

        // get article tag
        if ($parameters[0] == 'tag') {
            view()->share('routeBy', 'tag');
            $tag = Tag::where('slug', $param)->first();
            return $this->getArticle(true, null, $tag ? ['tags' => $tag->id] : []);
        }

        // get article
        $articleSlug = getSlug('article');

        if ($parameters[0] == $articleSlug) {
            view()->share('routeBy', 'article');
            $param = isset($parameters[1]) ? $parameters[1] : null;
            return $this->getArticle(true, $param);
        }

        // get gallery
        $gallerySlug = getSlug('gallery');

        if ($parameters[0] == $gallerySlug) {
            view()->share('routeBy', 'gallery');
            $param = isset($parameters[1]) ? $parameters[1] : null;
            return $this->getGallery(true, $param);
        }

        $permalink = implode('/', $parameters);

        return $this->getByPermalink($permalink);
    }
}
