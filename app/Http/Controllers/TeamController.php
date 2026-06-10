<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Services\Team\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct(private TeamService $teamService) {}

    public function index()
    {
        $members = $this->teamService->members(Auth::id());

        return view('user.team.index', compact('members'));
    }

    public function addMember(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'role'  => ['required', 'string', 'max:50'],
        ]);

        $this->teamService->addMember(Auth::id(), $data);

        return redirect()->route('team.index')->with('success', 'Member added successfully.');
    }

    public function removeMember(TeamMember $member)
    {
        abort_if($member->owner_id !== Auth::id(), 403);

        $this->teamService->removeMember($member->id, Auth::id());

        return redirect()->route('team.index')->with('success', 'Member removed.');
    }
}
