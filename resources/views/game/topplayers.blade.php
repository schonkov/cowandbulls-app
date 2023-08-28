<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Top Players') }}
        </h2>
    </x-slot>

    <div class="py-12">
      <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">
          <div class="col-md-8 col-xl-6 chat">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Avg Score</th>
                    <th scope="col">Total Score</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($top_players as $index => $player)
                  <tr>
                    <th scope="row">{{ $index+1 }}</th>
                    <td>{{ $player->user->name }}</td>
                    <td>{{ $player->avg_score }}</td>
                    <td>{{ $player->sum_score }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>
