 <div class="row">
     <div class="col">
         <table id="table1" class="table table-striped mt-3 table-sm">
             <thead class="table-group-divider">
                 <tr>
                     <th>Project ID</th>
                     <th>Project Name</th>
                     <th>Customer</th>
                     <th>Taken By</th>
                     <th>Started At</th>
                     <th>Finished At</th>
                     <th>Status</th>
                     <th>Aging</th>
                     <th class="text-end" style="width: 15%">Action</th>
                 </tr>
             </thead>
             <tbody class="table-group-divider">
                 @if ($project_offer->count() == 0)
                     <tr>
                         <td colspan="100%" class="text-center">No data displayed</td>
                     </tr>
                 @else
                     @foreach ($project_offer as $d)
                         <tr>
                             <td><a
                                     href="{{ route('task_board.show', ['project' => $d->project_id, 'assignee' => $assignee]) }}">{{ $d->project->proj_number }}</a>
                             </td>
                             <td>{{ $d->project->proj_name }}</td>
                             <td>{{ $d->project->proj_customer }}</td>
                             <td>{{ $d->user->username ?? '-' }}</td>
                             <td>{{ $d->projoff_started_at ?? '-' }}</td>
                             <td>{{ $d->projoff_finished_at ?? '-' }}</td>
                             <td>{{ $d->projoff_status }}</td>
                             <td>
                                 @if (in_array($d->projoff_status, ['Started', 'Hold']))
                                     @php
                                         $now = \Carbon\Carbon::now();
                                         $started_at = \Carbon\Carbon::parse($d->projoff_started_at);
                                         $aging = '-';
                                         if ($d->projoff_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($now);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @elseif($d->projoff_status == 'Done')
                                     @php
                                         $started_at = \Carbon\Carbon::parse($d->projoff_started_at);
                                         $finished_at = \Carbon\Carbon::parse($d->projoff_finished_at);
                                         $aging = '-';
                                         if ($d->projoff_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($finished_at);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @else
                                     -
                                 @endif
                             </td>
                             <td class="text-end">
                                 @if ($d->projoff_status == 'Open')
                                     <form class="d-inline" action="{{ route('task_board.take_offer', $d->id) }}"
                                         method="POST" id="form-take{{ $d->id }}">
                                         @csrf
                                         @method('PUT')
                                         <a class="btn btn-warning btn-sm" href="#" role="button"
                                             onclick="take_task({{ $d->id }}); return false;">Pick
                                             Up</a>
                                     </form>
                                 @else
                                     @if ($d->user_id == auth()->user()->id)
                                         @if ($d->projoff_status != 'Done')
                                             <div class="btn-group" role="group"
                                                 aria-label="Button group with nested dropdown">
                                                 <div class="btn-group" role="group">
                                                     <button type="button"
                                                         class="btn btn-primary btn-sm dropdown-toggle"
                                                         data-bs-toggle="dropdown" aria-expanded="false">
                                                         Action
                                                     </button>
                                                     <ul class="dropdown-menu">
                                                         <li>
                                                             <a class="dropdown-item"
                                                                 href="{{ route('task_board.document_offer', $d->id) }}">
                                                                 Document Upload
                                                             </a>
                                                         </li>
                                                         <li>
                                                             @if ($d->projoff_status == 'Started')
                                                                 <form class="d-inline"
                                                                     action="{{ route('task_board.hold_offer', $d->id) }}"
                                                                     method="POST" id="form-hold{{ $d->id }}">
                                                                     @csrf
                                                                     @method('PUT')
                                                                     <input type="hidden"
                                                                         id="hold-message{{ $d->id }}"
                                                                         name="message">
                                                                     <a class="dropdown-item" href="#"
                                                                         data-id="{{ $d->id }}"
                                                                         onclick="hold({{ $d->id }}); return false;">
                                                                         Hold
                                                                     </a>
                                                                 </form>
                                                                 <form class="d-inline"
                                                                     action="{{ route('task_board.finish_offer', $d->id) }}"
                                                                     method="POST"
                                                                     id="form-finish{{ $d->id }}">
                                                                     @csrf
                                                                     @method('PUT')
                                                                     <input type="hidden"
                                                                         id="finish-message{{ $d->id }}"
                                                                         name="projoff_offer_number">
                                                                     <a class="dropdown-item" href="#"
                                                                         data-id="{{ $d->id }}"
                                                                         onclick="finish({{ $d->id }}); return false;">
                                                                         Finish
                                                                     </a>
                                                                 </form>
                                                             @elseif($d->projoff_status == 'Hold')
                                                                 <form class="d-inline"
                                                                     action="{{ route('task_board.continue_survey', $d->id) }}"
                                                                     method="POST"
                                                                     id="form-continue{{ $d->id }}">
                                                                     @csrf
                                                                     @method('PUT')
                                                                     <a class="dropdown-item" href="#"
                                                                         data-id="{{ $d->id }}"
                                                                         onclick="continue_({{ $d->id }}); return false;">
                                                                         Continue
                                                                     </a>
                                                                 </form>
                                                             @elseif($d->projoff_status == 'Approval')
                                                                 <form class="d-inline"
                                                                     action="{{ route('task_board.finish_offer', $d->id) }}"
                                                                     method="POST"
                                                                     id="form-finish{{ $d->id }}">
                                                                     @csrf
                                                                     @method('PUT')
                                                                     <input type="hidden"
                                                                         id="finish-message{{ $d->id }}"
                                                                         name="projoff_offer_number">
                                                                     <a class="dropdown-item" href="#"
                                                                         data-id="{{ $d->id }}"
                                                                         onclick="finish({{ $d->id }}); return false;">
                                                                         Finish
                                                                     </a>
                                                                 </form>
                                                             @endif
                                                         </li>
                                                     </ul>
                                                 </div>
                                             </div>
                                         @endif
                                     @else
                                         @if ($d->projoff_status == 'Cancelled')
                                             Cancelled
                                         @elseif ($d->projoff_status == 'Done')
                                             Done
                                         @else
                                             Already Taken
                                         @endif
                                     @endif
                                 @endif
                             </td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
         <nav>
             {{ $project_offer->links('pagination::bootstrap-5') }}
         </nav>
     </div>
 </div>
