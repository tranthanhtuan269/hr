<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Cache;
use App\Article;
use App\ArticleCategory;
use App\PostCategory;
use Illuminate\Http\Request;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Helpers\AschoolHelper;
use App\Tag;
use App\ArticleTag;

class ArticleController extends Controller
{
    public function index()
    {
        $categories = ArticleCategory::getAll();
        return view('backend.article.index', compact('categories'));
    }

    public function create()
    {
        $data_cat = ArticleCategory::getAll();
        $myfunc =  new AschoolHelper();
        $categories = $myfunc->listCategories($data_cat,0,'',0);
        return view('backend.article.form', compact('categories'));
    }

    public function store(StoreArticleRequest $request)
    {
        $user_id = Auth::id();
        $date_current = date('Y-m-d H:i:s');
        $title         = $request->title;
        $slug = \Str::slug($title , '-');

        $item               = new Article;
        $item->title         = $title;
        $item->slug         = $slug;
        $item->description        = $request->description;
        $item->content  = $request->content;
        $item->cat_id    = $request->cat_id;
        $item->keywords     = $request->keywords;
        $item->seo_title        = $request->seo_title;
        $item->seo_description        = $request->seo_description;
        $item->status  = $request->status;
        $item->created_by   = $user_id;
        $item->created_at   = $date_current;
        $item->updated_at   = $date_current;
        $item->updated_by   = $user_id;

        $image = $request->image;

        if ($image != '') {
            $image = explode("/filemanager/data-images/", $image);
            $item->image = $image[1];
        }

        $public_date = $request->public_date;

        if (!empty($public_date)) {
            $item->public_date = AschoolHelper::formatDate('d/m/Y H:i', $public_date, 'Y-m-d H:i:s');
        } else {
            $item->public_date = $date_current;
        }

        $item->save();

        $tags = $request->tags;

        if (!empty($tags)) {
            $tag_slug = [];
            $article_tag_arr = [];

            foreach ($tags as $key => $tag) {
                $tag = trim($tag);
                $tag_slug[$key] = \Str::slug($tag, '-');
                if ( isset(Tag::where('slug', $tag_slug[$key])->first()->id) ){
                    if ( !isset(ArticleTag::where( 'tag_id', Tag::where('slug', $tag_slug[$key])->first()->id )->where('article_id', $item->id)->first()->id) ){
                        $article_tag = new ArticleTag;
                        $article_tag->article_id = $item->id;
                        $article_tag->tag_id = Tag::where('slug', $tag_slug[$key])->first()->id;
                        $article_tag->save();
                        $article_tag_arr[$key] = $article_tag->tag_id;
                    }
                }else{
                    $new_tag = new Tag;
                    $new_tag->name = $tag;
                    $new_tag->slug = $tag_slug[$key];
                    $new_tag->save();

                    $article_tag = new ArticleTag;
                    $article_tag->article_id = $item->id;
                    $article_tag->tag_id = $new_tag->id;
                    $article_tag->save();
                    $article_tag_arr[$key] = $new_tag->id;
                }
            }
        }

        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function edit($id)
    {
        $data = Article::leftJoin('article_tag', 'article_tag.article_id', '=', 'articles.id')
                        ->leftJoin('tags', 'tags.id', '=', 'article_tag.tag_id')
                        ->selectRaw('GROUP_CONCAT(DISTINCT tags.name) as list_tags,articles.*')
                        ->where('articles.id', $id)
                        ->groupBy('articles.id')
                        ->first();
        $categories = AschoolHelper::callProcessSelect(ArticleCategory::getAll(), 0, '', $data->cat_id);
        return view('backend.article.form', compact('data', 'categories'));
    }

    public function update($id, UpdateArticleRequest $request)
    {
        $user_id = \Auth::id();
        $date_current = date('Y-m-d H:i:s');

        $item               = Article::find($id);
        $item->title         = $request->title;
        $item->slug         = \Str::slug($request->title, '-');
        $item->description        = $request->description;
        $item->content  = $request->content;
        $item->cat_id    = $request->cat_id;
        $item->keywords     = $request->keywords;
        $item->seo_title        = $request->seo_title;
        $item->seo_description        = $request->seo_description;
        $item->status  = $request->status;
        $item->updated_at   = $date_current;
        $image = $request->image;

        if ($image != '') {
            $image = explode("/filemanager/data-images/", $image);
            $item->image = $image[1];
        }

        $public_date = $request->public_date;

        if (!empty($public_date)) {
            $item->public_date = AschoolHelper::formatDate('d/m/Y H:i', $public_date, 'Y-m-d H:i:s');
        } else {
            $item->public_date = $date_current;
        }

        $tags = $request->tags;
        $tag_slug = [];
        $article_tag_arr = [];
        ArticleTag::where('article_tag.article_id', $id)->delete();

        if (!empty($tags)){
            foreach ($tags as $key => $tag) {
                $tag = trim($tag);
                $tag_slug[$key] = \Str::slug($tag, '-');

                if ( isset(Tag::where('slug', $tag_slug[$key])->first()->id) ){
                    if ( !isset(ArticleTag::where( 'tag_id', Tag::where('slug', $tag_slug[$key])->first()->id )->where('article_id', $id)->first()->id) ){
                        $article_tag = new ArticleTag;
                        $article_tag->article_id = $item->id;
                        $article_tag->tag_id = Tag::where('slug', $tag_slug[$key])->first()->id;
                        $article_tag->save();
                        $article_tag_arr[$key] = $article_tag->tag_id;
                    }
                }else{
                    $new_tag = new Tag;
                    $new_tag->name = $tag;
                    $new_tag->slug = $tag_slug[$key];
                    $new_tag->save();

                    $article_tag = new ArticleTag;
                    $article_tag->article_id = $item->id;
                    $article_tag->tag_id = $new_tag->id;
                    $article_tag->save();
                    $article_tag_arr[$key] = $new_tag->id;
                }
            }
        }

        $item->save();
        Cache::forget('cache_showArticle_' . $item->id);
        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function destroy($id)
    {
        Article::destroy($id);
        return response()->json(['message' => 'Xóa thông tin thành công!', 'status' => 200]);
    }

    public function getDataAjax(Request $request)
    {
        $articles = Article::getDataForDatatable($request);
        return datatables()->of($articles)
                ->addColumn('action', function ($article) {
                    return $article->id;
                })
                ->addColumn('rows', function ($article) {
                    return $article->id;
                })
                ->removeColumn('id')->make(true);
    }

    public function delMulti(Request $request){
        if(isset($request) && $request->input('id_list')){
            $id_list = $request->input('id_list');
            $id_list = rtrim($id_list, ',');

            if(Article::delMulti($id_list)){
                $res=array('status'=>200,"Message"=>"Đã xóa lựa chọn thành công");
            }else{
                $res=array('status'=>"204","Message"=>"Có lỗi trong quá trình xủ lý !");
            }
            echo json_encode($res);
        }
    }


    public function showArticle($slug, $article_id)
    {
        $data = Cache::rememberForever('cache_showArticle_' . $article_id, function () use ($article_id) {
                    return Article::leftJoin('article_categories', 'article_categories.id', '=', 'articles.cat_id')
                                    ->where('articles.id', $article_id)
                                    ->where('articles.status', 1)
                                    ->selectRaw('articles.*,article_categories.title as cat_title,article_categories.slug as cat_slug')
                                    ->first();
                });

        $list_blog_others = Article::select('id', 'title', 'slug', 'created_at', 'image')
                                    ->where('id','<>', $article_id)
                                    ->where('status', 1)
                                    ->where('public_date', '<=', date('Y-m-d H:i:s'))
                                    ->inRandomOrder()
                                    ->groupBy('id')
                                    ->limit(3)
                                    ->get();
        AschoolHelper::countVisitedBlog($article_id);
        return view('frontend.layouts.blog.detail', compact('data', 'list_blog_others'));
    }
}
