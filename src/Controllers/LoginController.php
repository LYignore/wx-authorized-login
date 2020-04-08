<?php

namespace Lyignore\WxAuthorizedLogin\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Lyignore\WxAuthorizedLogin\Repositories\ClientRepository;
use Lyignore\WxAuthorizedLogin\Repositories\UserRepository;

class LoginController extends Controller
{
    public function getTicket(Request $request)
    {
        $ticket = $request->input('ticket');
        $clientRepository = new ClientRepository();
        $ticketObj = $clientRepository->getTicket($ticket);
        $result = $clientRepository->initUserLoginEntry($ticketObj, []);
        return view('websocket::generate_entry', ['result' => $result]);
    }

    public function index(Request $request)
    {
        $ticket = $request->input('ticket');
        $userRepository = new UserRepository();
        $ticketObj = $userRepository->getTicket($ticket);
        $ticketObj->setIdentify($ticket);
        $result = $userRepository->authorizedLogin($ticketObj, ['phone'=> '15641566789', 'user' => 'ly']);
        return response()->json($result);
    }
}
