<?php

namespace App\Http\Controllers;

use App\Models\AccessToken;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Torann\GeoIP\GeoIP as GeoIPGeoIP;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register','login', 'refresh', 'logout']]);
    }

    /**
     * Create new User.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|regex:/(^([a-zA-Z]+)(\d+)?$)/u|unique:users,username',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|regex:/(62)[0-9]{9}/',
            'full_address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'zipcode' => 'required|min:5'
        ]);

        if($request->hasFile('image')){
            $filename = $request->image->getClientOriginalName();
            $request->image->storeAs('images',$filename,'public');
        }

        $createUser = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']),
            'image' => $filename
        ]);

        $createUserAddress = UserAddress::create([
            'user_id' => $createUser->id,
            'full_address' => $request['full_address'],
            'province' => $request['province'],
            'city' => $request['city'],
            'district' => $request['district'],
            'zipcode' => $request['zipcode']
        ]);
        
        if($createUser && $createUserAddress){
            $response = [
                'success' => true,
                'message' => 'Successfully create user',
            ];
        } else{
            $response = [
                'success' => false,
                'message' => 'Failed to create user',
            ];
        }

        return response()->json($response);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)? 'email' : 'username';

        $request->merge([$loginField => $request->input('login')]);

        $this->validate($request, [
            'email' => 'required_without:username|email|exists:users,email',
            'username' => 'required_without:email|string|exists:users,username'
        ]);

        $credentials = $request->only([$loginField, 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $response = $this->respondWithToken($token, 'logged in');

        $access = [
            'user_id' => auth()->user()->id,
            'access_token' => $token,
            'expired_at' => Carbon::now()->timestamp + $response['data']['expires_in']
        ];

        AccessToken::create($access);
        
        return response()->json($response);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        
        $userAddress = UserAddress::where('user_id', $user->id)->select('full_address', 'province', 'city', 'district', 'zipcode')->first();

        $response = [
            'success' => true,
            'message' => 'Successfully get user data',
            'data' => [
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => url().'/storage/app/public/images/'.$user->image,
                'address' => $userAddress
            ]
            
        ];

        return response()->json($response);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), 'refresh token');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return Response
     */
    protected function respondWithToken($token, $source)
    {
        return [
            'success' => true,
            'message' => 'Successfully '.$source,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60 * 24
            ]
        ];
    }
}