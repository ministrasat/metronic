<?php
/*
Dari View API v3.0
links examples : 
constants : 
*base64_encoded("mesterbahaddou2020")= bWVzdGVyYmFoYWRkb3UyMDIw
*email : mesterbahaddou2020@gmail.com

=> create new user : http://127.0.0.1:89/create/bWVzdGVyYmFoYWRkb3UyMDIw
=> get infos user :  http://127.0.0.1:89/infos/bWVzdGVyYmFoYWRkb3UyMDIw
=> get products : http://127.0.0.1:89/lp/0000/0000/1/
=> get categories : http://127.0.0.1:89/categories/0000/0000/


*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Users extends Controller
{
    function index($id,$tk){
        if ($tk == '5cJY9ZycJVFaPkA2AgOo5cJY9ZycJVFaPkA2AgOo') {
            return [
                'username'=>'BAHADDOU mohammed',
                'id'=>$id,
                'token'=>$tk
                
            ];
        }else{
            return ['status'=>'error'];
        }
    }
    //Get Categories from Platform (it should be 3 main categories) @ http://127.0.0.1:89/categories/0000/0000/
    function categories($u,$p)
    {
        $jsonurl = env('APP_SRV')."/player_api.php?username=".$u."&password=".$p."&action=get_vod_categories";
        $json = file_get_contents($jsonurl);
        return $json;
    }
    //Get Articles by Categories ==> that will lisst all products existed in a specific category @ http://127.0.0.1:89/lp/0000/0000/1/
    function listproducts($u,$p,$id)
    {
            $jsonurl = env('APP_SRV')."/player_api.php?username=".$u."&password=".$p."&action=get_vod_streams&category_id=".$id;
            $json = file_get_contents($jsonurl);
            $value = json_decode($json, true);
            $count = substr_count($json , '{');
            
            $data = json_decode($json, TRUE);
            $data = ['status'=>'success',
                'data'=>[]
                ];
           for ($i=0; $i < $count; $i++) { 
           
           
                $data['data'][] = [
                    'id'=>$value[$i]['num'],
                    'titre'=>$value[$i]['name'],
                    'rating'=>$value[$i]['rating_5based'],
                    'added'=>$value[$i]['added'],
                    'category_id'=>$value[$i]['category_id'],
                    'source'=>env('APP_SRV').'/movie/'.$u.'/'.$p.'/'.$value[$i]['stream_id'].'.mp4'
                    
                ];
            };
            return $data;
              
            
    }
    function create($email) //create new user @ http://127.0.0.1:89/create/bWVzdGVyYmFoYWRkb3UyMDIw             Note : username is the same of gmail encrypted base64
    {
        $panel_url = env('APP_SRV').'/';
        $username = base64_decode($email);
        $password = base64_encode($email);
        $max_connections = 1;
        $reseller = 1;
        $bouquet_ids = array(1, 2,3, 4,5, 6, 7 );
        $expire_date = strtotime( "+12 month" );

        ###############################################################################
        $post_data = array( 'user_data' => array(
                'username' => $username,
                'password' => $password,
                'max_connections' => $max_connections,
                'is_restreamer' => $reseller,
                'exp_date' => $expire_date,
                'bouquet' => json_encode( $bouquet_ids ),
                'admin_notes' => $username.'@gmail.com'
                 ) );

        $opts = array( 'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query( $post_data ) ) );

        $context = stream_context_create( $opts );
        $api_result = json_decode( file_get_contents( $panel_url . "api.php?action=user&sub=create", false, $context ) );
        echo json_encode($api_result);
    }
    function infos($nickename) //create new user @ http://127.0.0.1:89/infos/bWVzdGVyYmFoYWRkb3UyMDIw             Note : username is the same of gmail encrypted base64
    {
        $panel_url = env('APP_SRV').'/';
        $username = base64_decode($nickename);
        $password = base64_encode($nickename);

        ###############################################################################
        $post_data = array( 'username' => $username, 'password' => $password );
        $opts = array( 'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query( $post_data ) ) );

        $context = stream_context_create( $opts );
        $api_result = json_decode( file_get_contents( $panel_url . "api.php?action=user&sub=info", false, $context ), true );
        $json = json_encode($api_result);
            //return $json;
        return ['status'=>'success',
                'data'=>[
                    'id'=>$api_result['user_info']['id'],
                    'username'=>$api_result['user_info']['username'],
                    'password'=>$api_result['user_info']['password'],
                    'created_at'=>$api_result['user_info']['created_at'],
                    'exp_date'=>$api_result['user_info']['exp_date'],
                    'enabled'=>$api_result['user_info']['enabled'],
                    'email'=>$api_result['user_info']['admin_notes']
                ]
        ];
    }
}
