<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Games') }}
        </h2>
    </x-slot>

    <div class="py-12">
      <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">
          <div class="col-md-4 col-xl-3 chat">
            <div class="card mb-sm-3 mb-md-0 contacts_card">
            <a class="btn btn-info " id="new_game_button" role="button" >
              <span style="font-size: 1em; color: Green;" title="Success">
                <i class="fa fa-plus" aria-hidden="true"></i> 
              </span>
              New Game </a>
            <div class="card-header align-items-center">
              <p>Total games played: {{ $games->count() }}</p> 
                <p>Registered : {{ $current_user->created_at->format('d-m-Y') }}</p>
                <p>Average score: {{  round($guesses_avg_score, 2) }}</p>
                <p>Total score: {{ round($guesses_sum_score, 2) }}</p>
              <div class="input-group">
                
                {{-- <input type="text" placeholder="Search..." name="" class="form-control search">
                <div class="input-group-prepend">
                  <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                </div> --}}
                
              </div>
            </div>
            <div class="card-body contacts_body">
              <ul class="contacts">
                {{-- @dd($games); --}}
                @foreach ($games as $game)
                <li class="">
                  <a href="/game/{{ $game->id }}">
                  <div class="d-flex bd-highlight">
                    <div class="img_cont">
                      <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img">
                    </div>
                    <div class="user_info">
                        <span>Game #{{ $game->id }}</span> 
                        @if ($game->ended && $game->success == 0)
                          <span style="font-size: 1em; color: Tomato;" title="Failed">
                            <i class="fa fa-window-close" aria-hidden="true"></i> 
                          </span>
                          <p>Gave Up</p>
                        @elseif($game->ended && $game->success)
                          <span style="font-size: 1em; color: Green;" title="Success">
                            <i class="fa fa-check" aria-hidden="true"></i> 
                          </span>
                          <p>Win</p>
                        @elseif($game->ended == 0 && $game->success == 0)
                          <p>In Progress</p>
                        @endif
                    </div>
                  </div>
                </a>
                </li>
                    
                @endforeach
              </ul>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
        </div>
      </div>
    </div>
</x-app-layout>
