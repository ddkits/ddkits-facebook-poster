<?php

namespace Ddkits\Ddkitsfacebook\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ddkits\Ddkitsfacebook\Models\Ddkitsfacebook;
use Facebook\Facebook;
use Session;
use Illuminate\Support\Facades\Auth;

class DdkitsfacebookCont extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        if (!$id) {
            Session::flash('Error', 'Must be logged in!!');

            return view('ddkitsfacebook::index')->with([
                'settings' => [],
                'userid' => $id,
            ]);
        } else {
            $adminSettings = Ddkitsfacebook::where('uid', Auth::id())->get();

            return view('ddkitsfacebook::index')->with([
                'userid' => $id,
                'settings' => $adminSettings,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'appId' => 'required',
            'appSecret' => 'required',
            'userAccessToken' => 'required',
            'pageId' => 'required',
        ]);
        if ($request) :
        $foreverPageAccessToken = $this->getPageAccessToken($request->appId, $request->appSecret, $request->userAccessToken, $request->pageId);

        $createFB = new Ddkitsfacebook();
        $createFB->status = 1;
        $createFB->uid = $request->uid;
        $createFB->appId = $request->appId;
        $createFB->title = $request->title;
        $createFB->pageId = $request->pageId;
        $createFB->appSecret = $request->appSecret;
        $createFB->foreverPageAccessToken = $foreverPageAccessToken;
        $createFB->userAccessToken = $request->userAccessToken;
        $createFB->save();

        Session::flash('Success', 'New Facebook Page Created');
        endif;

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getPageAccessToken($appId, $appSecret, $userAccessToken, $pageId)
    {
        $fb = new Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => 'v6.0',
        ]);

        $longLivedToken = $fb->getOAuth2Client()->getLongLivedAccessToken($userAccessToken);

        $fb->setDefaultAccessToken($longLivedToken);

        $response = $fb->sendRequest('GET', $pageId, ['fields' => 'access_token'])
            ->getDecodedBody();
        if (isset($response['access_token'])) {
            $foreverPageAccessToken = $response['access_token'];
        } else {
            $foreverPageAccessToken = $userAccessToken;
        }

        return  $foreverPageAccessToken;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Build posts to facebook specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function postToPage()
    {
        if (!Auth::user()) {
            $id = 1;
        } else {
            $id = Auth::user()->id;
        }
        $facebookPages = Ddkitsfacebook::where('uid', $id)->get();
        $redirectURL = env('FACEBOOK_REDIRECT'); //Callback URL
        $fbPermissions = array('publish_actions'); //Facebook permission

        foreach ($facebookPages as $key) {
            try {
                $fb = new Facebook([
                    'app_id' => $key->appId,
                    'app_secret' => $key->appSecret,
                    'default_graph_version' => 'v6.0',
                ]);

                $fb->setDefaultAccessToken($key->foreverPageAccessToken);
                // try {
                $fb->post($key->pageId.'/feed', [
                    'message' => env('APP_NAME'),
                    'link' => env('APP_URL'),
                ], $key->foreverPageAccessToken);
            } catch (\FacebookAuthorizationException $th) {
                Session::flash('Error', json_decode($th));

                return redirect()->back();
            }
        }
        Session::flash('Success', 'New Post to Facebook Page Posted');

        return redirect()->back();
    }

    /**
     * Build posts to facebook specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function postToPageLive(Request $request)
    {
        $id = $request->uid;
        $facebookPages = Ddkitsfacebook::where('uid', $id)->get();
        $redirectURL = env('FACEBOOK_REDIRECT'); //Callback URL
        $fbPermissions = array('publish_actions'); //Facebook permission

        foreach ($facebookPages as $key) {
            try {
                $fb = new Facebook([
                    'app_id' => $key->appId,
                    'app_secret' => $key->appSecret,
                    'default_graph_version' => 'v6.0',
                ]);

                $fb->setDefaultAccessToken($key->foreverPageAccessToken);
                // try {
                $fb->post($key->pageId.'/feed', [
                    'message' => $request->message,
                    'link' => $request->link,
                ], $key->foreverPageAccessToken);
            } catch (\FacebookAuthorizationException $th) {
                Session::flash('Error', json_decode($th));

                return redirect()->back();
            }
        }
        Session::flash('Success', 'New Post to Facebook Page Posted');

        return redirect()->back();
    }

    public function publishToPageNew($feed, $id = false)
    {
        /*
        * Configuration and setup Facebook SDK
        */

        $redirectURL = env('FACEBOOK_REDIRECT'); //Callback URL
        $fbPermissions = array('publish_actions'); //Facebook permission

        if (!Auth::user()) {
            $id = 1;
        } elseif ($id) {
            // $id = 1;
        } else {
            $id = Auth::user()->id;
        }
        $facebookPages = Ddkitsfacebook::where('uid', $id)->get();

        //FB post content
        $message = (string) $feed->title;
        $title = (string) $feed->title;
        $link = (string) env('APP_URL').'/'.config('ddkitsfacebook.path').'/'.$feed->path;
        $description = (string) $feed->title;
        $picture = (string) env('APP_URL').'/'.$feed->image;

        $attachment = array(
            'message' => $message.' - '.$link,
            // 'name' => $title,
            'link' => $link,
            // 'description' => $description,
            // 'picture' => $picture,
            'scope' => 'publish_actions',
        );

        foreach ($facebookPages as $key) {
            $fb = new Facebook([
                    'app_id' => $key->appId,
                    'app_secret' => $key->appSecret,
                    'default_graph_version' => 'v6.0',
                ]);

            $fb->setDefaultAccessToken($key->foreverPageAccessToken);
            // try {
            // Post to Facebook
            try {
                $fb->post($key->pageId.'/feed',
                $attachment,
                $key->userAccessToken);

                // Display post submission status
                echo 'The post was published successfully to the Facebook page.';
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: '.$e->getMessage();
                exit;
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Ddkitsfacebook::destroy($id);
        Session::flash('Success', 'New Facebook Page Deleted');

        return redirect()->back();
    }
}
