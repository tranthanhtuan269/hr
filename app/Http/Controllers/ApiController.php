<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\PostTag;
use App\Post;
use App\PostDetail;
use App\Helpers\AschoolHelper;

class ApiController extends Controller
{
    public function blogger()
    {
        $redirct_url = AschoolHelper::NAME_DOMAIN . "/api/blogger";
        $google_clnt = new \Google_Client();
        $google_clnt->setApplicationName("Auto post blogger Novel");
        $google_clnt->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY'));
        $google_clnt->setAccessType('online');
        $google_clnt->setClientId(env('GOOGLE_CLIENT_ID'));
        $google_clnt->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $google_clnt->setRedirectUri($redirct_url);
        $google_clnt->setScopes(array('https://www.googleapis.com/auth/blogger')); //since we are going to use blogger services

        $bloggerService = new \Google_Service_Blogger($google_clnt);

        if (isset($_GET['logout'])) { // Google logout: Your destroy token
            unset($_SESSION['token']);
            die('Good Luck Logged out.');
        }

        if (isset($_GET['code'])) { // we get the positive google auth callback, get the simple google auth token and some store it in session
            $google_clnt->authenticate($_GET['code']);
            $_SESSION['token'] = $google_clnt->getAccessToken();
        }

        if (isset($_SESSION['token'])) { // extract get token from session and configure google_clnt
            $get_token = $_SESSION['token'];
            $google_clnt->setAccessToken($get_token);
        }

        if (!$google_clnt->getAccessToken()) { // Your auth call to google
            $authUrl = $google_clnt->createAuthUrl();
            header("Location: " . $authUrl);
            die;
        }

        $posts = $bloggerService->posts;
        $date_get_post = date('Y-m-d', strtotime("-1 day"));
        $list_book_public = \App\Post::leftJoin('cmm_post_topic', 'cmm_post_topic.post_id', '=', 'cmm_posts.id')
            ->leftJoin('tags', 'tags.id', '=', 'cmm_post_topic.tag_id')
            ->whereNotNull('cmm_posts.description')
            ->whereBetween('cmm_posts.public_date', [$date_get_post . ' 00:00:00', $date_get_post . ' 23:59:59'])
            ->selectRaw('GROUP_CONCAT(DISTINCT tags.name) as list_tags,cmm_posts.id,cmm_posts.title,cmm_posts.slug,cmm_posts.description')
            ->limit(2)
            ->groupBy('cmm_posts.id')
            ->get();

        try {
            $minute = (int) round((15 / (count($list_book_public))) * 60);

            foreach ($list_book_public as $key => $value) {
                $mypost = new \Google_Service_Blogger_Post();
                $mypost->setTitle($value->title);

                $str_list_tags = "";
                $arr_list_tags = [];
                if (!empty($value->list_tags)) {
                    $list_tags = explode(",", $value->list_tags);

                    foreach ($list_tags as $tag) {
                        if (mb_strlen($str_list_tags) > 150) {
                            break;
                        }

                        $arr_list_tags[] = $tag;
                        $str_list_tags .= $tag;
                    }

                    if (mb_strlen($str_list_tags) > 200) {
                        unset($arr_list_tags[count($arr_list_tags) - 1]);
                    }

                    $mypost->setLabels($arr_list_tags);
                }

                $mypost->setContent($value->description);
                // $mypost->setCustomMetaData($value->description);
                $datetime = new \DateTime(date("Y-m-d H:i:s", strtotime("+" . (($key + 1) * $minute) . " minutes")));
                $datetime->setTimezone(new \DateTimeZone('America/Los_Angeles'));
                $publish_date = $datetime->format(DATE_RFC3339);
                $mypost->setPublished($publish_date);
                $posts->insert("3355582636303040757", $mypost);
            }
        } catch (Exception $e) {
            //generate Error
            print_r($e);
        }
    }

    public function twitter()
    {
        $settings = array(
            'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
            'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
        );

        // twitter api endpoint
        $url = 'https://api.twitter.com/1.1/statuses/update.json';

        // twitter api endpoint request type
        $requestMethod = 'POST';






        $list_book_public = \App\Post::leftJoin('cmm_post_topic', 'cmm_post_topic.post_id', '=', 'cmm_posts.id')
            ->leftJoin('tags', 'tags.id', '=', 'cmm_post_topic.tag_id')
            ->whereNotNull('cmm_posts.description')
            // ->whereBetween('cmm_posts.public_date', [$date_get_post .' 00:00:00', $date_get_post .' 23:59:59'])
            ->selectRaw('GROUP_CONCAT(DISTINCT tags.name) as list_tags,cmm_posts.id,cmm_posts.title,cmm_posts.slug,cmm_posts.description')
            ->limit(2)
            ->groupBy('cmm_posts.id')
            ->get();

        if (count($list_book_public) > 0) {
            foreach ($list_book_public as $key => $value) {
                $message = AschoolHelper::smartStr(AschoolHelper::formatContentSocialBeforePostApi($value->description), 200);
                $link = AschoolHelper::replaceDomainLocal(route('client.show-post', ['slug' => $value->slug, 'post_id' => $value->id]));

                if (!empty($value->list_tags)) {
                    $str_list_tags = '';
                    $list_tags = explode(",", $value->list_tags);

                    foreach ($list_tags as $k_tag => $tag) {
                        if ($k_tag == 3) {
                            break;
                        }

                        $str_list_tags .= "#" . str_replace(" ", "_", $tag) . " ";
                    }

                    $apiData = array(
                        'status' => $message . ' ' . $str_list_tags . ' ' . $link,
                    );
                } else {
                    $apiData = array(
                        'status' => $message . ' ' . $link,
                    );
                }

                $twitter = new \TwitterAPIExchange($settings);
                $twitter->buildOauth($url, $requestMethod);
                $twitter->setPostfields($apiData);
                $response = $twitter->performRequest(true, array(CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0));
            }
        }
    }

    public function convertTag()
    {
        // var str_list_tag = '';
        // $('#showtags .genre').each(function(key) {
        //     var content_tag = $(this).text();

        //     if (content_tag.indexOf("*") === -1) {
        //         str_list_tag += content_tag + "\n";

        //     }

        //     if (key == $('#showtags .genre').length - 1) {
        //         console.log(str_list_tag)
        //     }
        // });

        $data = Post::leftJoin('cmm_post_topic', 'cmm_post_topic.post_id', '=', 'cmm_posts.id')
            ->leftJoin('tags', 'tags.id', '=', 'cmm_post_topic.tag_id')
            ->leftJoin('posts_tag', 'posts_tag.slug', '=', 'cmm_posts.slug')
            ->select('cmm_posts.id', 'cmm_posts.title', 'tags.id as tag_id', 'posts_tag.tags')
            ->groupBy('cmm_posts.id')
            ->chunk(1000, function ($data) {
                foreach ($data as $value) {
                    if (empty($value->tag_id)) {
                        $tags = explode(",", $value->tags);
                        $tag_slug = [];

                        foreach ($tags as $key => $tag) {
                            $tag = trim($tag);

                            if (!empty($tag) && strpos($tag, '*') === false) {
                                $slug_tag = \Str::slug($tag, '-');
                                $tag_old = Tag::select('id')->where('slug', $slug_tag)->first();

                                if ($tag_old) {
                                    $post_tag = new PostTag;
                                    $post_tag->post_id = $value->id;
                                    $post_tag->tag_id = $tag_old->id;
                                    $post_tag->save();
                                } else {
                                    $new_tag = new Tag;
                                    $new_tag->name = $tag;
                                    $new_tag->slug = $slug_tag;
                                    $new_tag->save();

                                    $post_tag = new PostTag;
                                    $post_tag->post_id = $value->id;
                                    $post_tag->tag_id = $new_tag->id;
                                    $post_tag->save();
                                }
                            }
                        }
                    }
                }
            });
    }

    public function clearChapter()
    {
        \DB::table('posts_detail_ongoing')->where('created_at', '<', \Carbon\Carbon::now()->subHours(20)->toDateTimeString())->delete();
    }

    public function dataChapterOnGoing()
    {
        $data = \DB::table('posts_detail_ongoing')
            ->where('status', 0)
            ->whereDate('created_at', '<=', date('Y-m-d H:i:s'))
            ->limit(200)
            ->get();

        if (count($data) > 0) {
            return response()->json(['status' => 200, 'data' => $data]);
        }

        return response()->json(['status' => 404]);
    }


    public function updateStatusChapterOnGoing($list_id)
    {
        $list_id = explode(",", $list_id);
        \DB::table('posts_detail_ongoing')
            ->where('status', 0)
            ->whereBetween('id', [(int) $list_id[0], (int) $list_id[1]])
            ->update(['status' => 1]);

        return response()->json(['status' => 200]);
    }


    public function dataConvertNovelDone()
    {
        $data = Post::select('slug')->whereBetween('created_at', [date("Y-m-d H:i:s", strtotime('-1 days')), date("Y-m-d H:i:s", strtotime('+1 days'))])->get();
        return response()->json(['status' => 200, 'data' => $data]);
    }


    public function dataNovelComplete()
    {
        $data = Post::select('slug')->where('status', 1)->get();
        return response()->json(['status' => 200, 'data' => $data]);
    }















    public function dataConvert()
    {
        $data = \DB::table('posts')->leftJoin('posts_clone', 'posts_clone.slug', '=', 'posts.slug')
            ->select('posts.*')
            ->where('posts_clone.status', 1)
            ->get();

        if (count($data) > 0) {
            return response()->json(['status' => 200, 'data' => $data]);
        }

        return response()->json(['status' => 404]);
    }

    public function dataUpdateChapter($post_id_system_clone)
    {
        $data = \DB::table('posts_detail_ongoing')
            ->leftJoin('posts_clone', 'posts_clone.slug', '=', 'posts_detail_ongoing.slug_novel')
            ->where('posts_clone.status', 2)
            ->where('posts_detail_ongoing.post_id', $post_id_system_clone)
            ->select('posts_detail_ongoing.title', 'posts_detail_ongoing.slug_novel', 'posts_detail_ongoing.author', 'posts_detail_ongoing.slug', 'posts_detail_ongoing.content', 'posts_detail_ongoing.post_id')
            ->get();

        if (count($data) > 0) {
            return response()->json(['status' => 200, 'data' => $data]);
        }

        return response()->json(['status' => 404]);
    }

    public function dataChapterSpecial()
    {
        ini_set('MAX_EXECUTION_TIME', '-1');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $data = \DB::table('posts')->leftJoin('posts_clone', 'posts_clone.slug', '=', 'posts.slug')
            ->select('posts.*')
            ->where('posts_clone.status', 1)
            ->first();

        if ($data) {
            return response()->json(['status' => 200, 'data' => $data]);
        }

        return response()->json(['status' => 404]);
    }

    public function updateStatus($list_id)
    {
        $list_id = explode(",", $list_id);
        $id_from = (int) $list_id[0];
        $id_to = (int) $list_id[1];
        \DB::table('posts_clone')->whereBetween('age', [$ageFrom, $ageTo])->update(['status' => 2]); // 2: đã insert vào DB web golive
        $post_id = \DB::table('posts')->where('slug', $post_slug)->value('id');
        \DB::table('posts')->where('id', $post_id)->delete();
        \DB::table('posts_detail')->where('post_id', $post_id)->delete();
    }

    public function checkChapterSpecialStatus($post_id)
    {
        return \DB::table('posts_detail')->where('post_id', $post_id)->count();
    }



}
