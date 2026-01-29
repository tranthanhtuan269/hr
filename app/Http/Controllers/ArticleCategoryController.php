<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\AschoolHelper;
use App\Article;
use App\ArticleCategory;
use App\PostCategory;
use App\Http\Requests\StoreArticleCategoryRequest;
use App\Http\Requests\UpdateArticleCategoryRequest;
use Illuminate\Http\Request;
use Session;
use Cache;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $dataCate = ArticleCategory::get();
        $myfunc =  new AschoolHelper();
        $listCategoriesAction = $myfunc->listCategoriesAction($dataCate,0,'', $type = 'articlecategories');
        return view("backend.articlecategory.index", compact('listCategoriesAction'));

    }

    public function create()
    {
        $dataCate = ArticleCategory::get();
        $myfunc =  new AschoolHelper();
        $categories = $myfunc->listCategories($dataCate,0,'',0);
        return view("backend.articlecategory.form", compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreArticleCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleCategoryRequest $request)
    {
        $item               = new ArticleCategory;
        $item->title         = $request->title;
        $item->slug         = \Str::slug($request->title, '-');
        $item->description  = $request->description;
        $item->parent_id    =$request->parent_id;
        $item->keywords     = $request->keywords;
        $item->seo_title        = $request->seo_title;
        $item->seo_description        = $request->seo_description;
        $item->updated_at   = date('Y-m-d H:i:s');

        $image = $request->image;

        if ($image != '') {
            $image = explode("/filemanager/data-images/", $image);
            $item->image = $image[1];
        }

        $item->save();
        Cache::forget('cache_list_category_blog');
        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $arrCategoriesChild = AschoolHelper::arrCategoriesChild($id,'article_categories');
        $arrCategoriesChild[] = $id;
        $dataCate = ArticleCategory::whereNotIn('id', $arrCategoriesChild)->get();
        $data = ArticleCategory::findOrFail($id);
        $myfunc =  new AschoolHelper();
        $categories = $myfunc->listCategories($dataCate,0,'',$data->parent_id);
        return view("backend.articlecategory.form", compact('categories', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleCategoryRequest $request, $id)
    {
        $item               = ArticleCategory::find($id);
        $item->title         = $request->title;
        // $item->slug         = \Str::slug($request->title, '-');
        $item->description  = $request->description;
        $item->parent_id    = $request->parent_id;
        $item->keywords     = $request->keywords;
        $item->seo_title        = $request->seo_title;
        $item->seo_description  = $request->seo_description;
        $item->updated_at   = date('Y-m-d H:i:s');

        $image = $request->image;

        if ($image != '') {
            $image = explode("/filemanager/data-images/", $image);
            $item->image = $image[1];
        }

        $item->save();
        Cache::forget('cache_list_category_blog');
        return response()->json(['message' => 'Lưu thông tin thành công!', 'status' => 200]);
    }

    public function destroy($id)
    {
        ArticleCategory::destroy($id);
        return response()->json(['message' => 'Xóa thông tin thành công!', 'status' => 200]);
    }

    public function showCatArticle($slug)
    {
        $info_cat = ArticleCategory::where('slug', $slug)->first();

        if ($info_cat) {
            $cat_id = $info_cat->id;
            $id_child = AschoolHelper::arrCategoriesChild_v2($cat_id, 'article_categories');
            $arr_cat_id = AschoolHelper::array_keys_multi($id_child);
            $arr_cat_id[] = (int)$cat_id;

            $data = Article::leftJoin('article_categories', 'article_categories.id', '=', 'articles.cat_id')
                            ->select('article_categories.title as cat_title', 'article_categories.slug as cat_slug', 'articles.id', 'articles.title', 'articles.slug', 'articles.created_at', 'articles.image', 'articles.description')
                            ->whereIn('articles.cat_id', $arr_cat_id)
                            ->where('articles.public_date', '<=', date('Y-m-d H:i:s'))
                            ->where('articles.status', 1)
                            ->orderBy('articles.public_date', 'DESC')
                            ->paginate(10);
            return view('frontend.layouts.blog.category', compact('data', 'info_cat'));
        }

        return view('errors.404');
    }
}
