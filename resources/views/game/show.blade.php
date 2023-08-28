<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Games') }}
        </h2>
    </x-slot>

    <div class="py-12">
      <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">
          <div class="col-md-4 col-xl-3 chat chat_left"><div class="card mb-sm-3 mb-md-0 contacts_card">
            <div class="card-header">
              <p>Statistics per game: </p>
              <input type='hidden' id='current_game_id' value='{{ $current_game->id }}' />
                <p>Guesses count: {{ $current_game->guesses()->count() }}</p> 
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
                @foreach ($games as $game)
                @if ($game->id == $current_game->id)
                  <li class="active">
                @else
                  <li class="">
                @endif
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
          
          <div class="col-md-8 col-xl-6 chat chat_right">
            <div class="card">
              <div class="card-header msg_head">
                <div class="d-flex bd-highlight">
                  <div class="img_cont">
                    <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img">
                    <span class="online_icon"></span>
                  </div>
                  <div class="user_info">
                    {{-- @dd($current_game->guesses) --}}
                    <span>Game #{{ $current_game->id }}</span> 
                    @if ($current_game->ended && $current_game->success == 0)
                      <span style="font-size: 1em; color: Tomato;" title="Failed">
                        <i class="fa fa-window-close" aria-hidden="true"></i> 
                      </span>
                      <p>Gave Up</p>
                    @elseif($current_game->ended && $current_game->success)
                      <span style="font-size: 1em; color: Green;" title="Success">
                        <i class="fa fa-check" aria-hidden="true"></i> 
                      </span>
                      <p>Win</p>
                    @elseif($current_game->ended == 0 && $current_game->success == 0)
                      <p>In Progress</p>
                    @endif
                  </div>
                  <div class="video_cam">
                    {{-- <span><i class="fas fa-video"></i></span> --}}
                    {{-- <span><i class="fas fa-phone"></i></span> --}}
                  </div>
                </div>
                <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                <div class="action_menu">
                  <ul>
                    {{-- <li><i class="fas fa-user-circle"></i> View profile</li> --}}
                    {{-- <li><i class="fas fa-users"></i> Add to close friends</li> --}}
                    {{-- <li><i class="fas fa-plus"></i> Add to group</li> --}}
                    {{-- <li><i class="fas fa-ban"></i> Block</li> --}}
                  </ul>
                </div>
              </div>
              <div class="card-body msg_card_body" id="messages_container">
                <div class="d-flex justify-content-start mb-4">
                  <div class="img_cont_msg">
                    <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img_msg">
                    
                  </div>
                  <div class="msg_cotainer">
                    Ok. I am ready. You can try to guess my number.
                    <span class="msg_time">{{ $current_game->created_at->format('d-m-Y') }}</span>
                  </div>
                </div>

                {{-- player template --}}
                <div class="d-flex justify-content-end mb-4 player_row_template hidden-imp">
                  <div class="msg_cotainer_send">
                    <span class="msg_cotainer_text">  </span>
                    <span class="msg_time_send"></span>
                  </div>
                  <div class="img_cont_msg">
                    <img src="{{ asset('images/player.jpg') }}" class="rounded-circle user_img_msg">
                  </div>
                </div>

                 {{-- pc response template --}}
                 <div class="d-flex justify-content-start mb-4 pc_row_template hidden-imp">
                  <div class="img_cont_msg">
                    <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img_msg">
                  </div>
                  <div class="msg_cotainer">
                    <span class="msg_cotainer_text">  </span>
                    <span class="msg_time_send"></span>
                  </div>
                </div>

                @foreach ($current_game->guesses as $guess)
                {{-- player --}}
                  <div class="d-flex justify-content-end mb-4">
                    <div class="msg_cotainer_send">
                      <span class="msg_cotainer_text"> {{ $guess->user_guess }} </span>
                      <span class="msg_time_send">{{ $guess->created_at->format('d-m-Y') }}</span>
                    </div>
                    <div class="img_cont_msg">
                      <img src="{{ asset('images/player.jpg') }}" class="rounded-circle user_img_msg">
                    </div>
                  </div>

                  {{-- pc response --}}
                <div class="d-flex justify-content-start mb-4">
                  <div class="img_cont_msg">
                    <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img_msg">
                  </div>
                  <div class="msg_cotainer">
                    <span class="msg_cotainer_text"> {{ $guess->response_sentence }} </span>
                    <span class="msg_time_send">{{ $guess->updated_at->format('d-m-Y') }}</span>
                  </div>
                </div>
                @endforeach
                
                @if ($current_game->ended && $current_game->success == 0)
                <div class="d-flex justify-content-start mb-4">
                  <div class="img_cont_msg">
                    <img src="{{ asset('images/robot.jpg') }}" class="rounded-circle user_img_msg">
                  </div>
                  <div class="msg_cotainer">
                    OK. I see you gave up. The answer is 
                    {{ $current_game->answer }}
                  </div>
                </div>

                @endif
              </div>
              <div class="card-footer">
                @if($current_game->ended == 0 && $current_game->success == 0)
                <div class="input-group">
                  <div class="input-group-append">
                      <span class="input-group-text attach_btn " id="end_game_button"><i class="fa fa-window-close"></i></span>
                  </div>
                  {{-- <textarea name="" class="form-control type_msg " id="user_guess" placeholder="Type your message..."></textarea> --}}
                  <input type="number"  name="" class="form-control type_msg " id="user_guess" placeholder="Type your guess..." />
                  <div class="input-group-append">
                    <span class="input-group-text " id="send_guess_button" ><i class="fas fa-location-arrow"></i></span>
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>
