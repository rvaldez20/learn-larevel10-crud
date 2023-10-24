<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chirps.index', [
            // 'chirps' => Chirp::orderBy('created_at', 'desc')->get()
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([]) -> retorna un array
        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255']
        ]);


        // inserte in DB the message
        $request->user()->chirps()->create($validated);


        // Chirp::create([
        //     'message' => $request->get('message'),
        //     'user_id' => auth()->id(),
        // ]);

        // show alert that message save in data base!!
        // session()->flash('status', 'Chirp created successfully!'); // mismo que ->with()

        return to_route('chirps.index')
            ->with('status', 'Chirp created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function edit(Chirp $chirp)
    {
        // if(auth()->user()->isNot($chirp->user)) {
        //     abort(403);
        // }

        // politicy update
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chirp $chirp)
    {
        // politicy update
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255']
        ]);

        $chirp->update($validated);

        return to_route('chirps.index')
            ->with('status', 'Chirp updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chirp  $chirp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return to_route('chirps.index')
            ->with('status', 'Chirp deleted successfully!');
    }
}
