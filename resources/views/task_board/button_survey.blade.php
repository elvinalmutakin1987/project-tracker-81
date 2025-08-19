 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
     <div class="btn-group" role="group">
         <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
             aria-expanded="false">
             Action
         </button>
         <ul class="dropdown-menu">
             <li>
                 <a class="dropdown-item" href="{{ route('task_board.document_survey', $d->id) }}">
                     Document Upload
                 </a>
             </li>
             @if ($d->projsur_status == 'Started')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.hold_survey', $d->id) }}" method="POST"
                         id="form-hold{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="hold-message{{ $d->id }}" name="message">
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="hold({{ $d->id }}); return false;">
                             Hold
                         </a>
                     </form>
                 </li>
                 <li>
                     <form class="d-inline" action="{{ route('task_board.finish_survey', $d->id) }}" method="POST"
                         id="form-finish{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="finih-message{{ $d->id }}" name="message">
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="finish({{ $d->id }}); return false;">
                             Finish
                         </a>
                     </form>
                 </li>
             @elseif($d->projsur_status == 'Hold')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.continue_survey', $d->id) }}" method="POST"
                         id="form-continue{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="continue_({{ $d->id }}); return false;">
                             Continue
                         </a>
                     </form>
                 </li>
             @endif

             {{-- @if (Auth::user()->hasRole('superadmin'))
                 <hr class="my-1">
                 <li>
                     <form class="d-inline"
                         action="{{ route('task_board.cancel', ['assignee' => 'pre-sales', 'id' => $d->id]) }}"
                         method="POST" id="form-cancel{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <a class="dropdown-item" href="#" href="#" role="button"
                             onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                         </a>
                     </form>
                 </li>
                 <li>
                     <form class="d-inline"
                         action="{{ route('task_board.delete', ['assignee' => 'pre-sales', 'id' => $d->id]) }}"
                         method="POST" id="form-delete{{ $d->id }}">
                         @csrf
                         @method('DELETE')
                         <a class="dropdown-item" href="#" href="#" role="button"
                             onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                         </a>
                     </form>
                 </li>
             @endif --}}
         </ul>
     </div>
 </div>
