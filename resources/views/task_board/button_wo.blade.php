 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
     <div class="btn-group" role="group">
         <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
             aria-expanded="false">
             Action
         </button>
         <ul class="dropdown-menu">
             <li>
                 <a class="dropdown-item" href="{{ route('task_board.document_work_order', $d->id) }}">
                     Document Upload
                 </a>
             </li>

             @if ($d->projwo_status == 'Started')
                 <li>
                     <a class="dropdown-item" href="{{ route('task_board.create_work_order', $d->id) }}">Create Work
                         Order
                     </a>
                 </li>
                 <li>
                     <form class="d-inline" action="{{ route('task_board.hold_work_order', $d->id) }}" method="POST"
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
                     <form class="d-inline" action="{{ route('task_board.finish_work_order', $d->id) }}" method="POST"
                         id="form-finish{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="finish-message{{ $d->id }}" name="projwo_wo_number">
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="finish({{ $d->id }}); return false;">
                             Finish
                         </a>
                     </form>
                 </li>
             @elseif($d->projwo_status == 'Hold')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.continue_work_order', $d->id) }}"
                         method="POST" id="form-continue{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="continue_({{ $d->id }}); return false;">
                             Continue
                         </a>
                     </form>
                 </li>
             @elseif($d->projwo_status == 'Approval')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.finish_work_order', $d->id) }}" method="POST"
                         id="form-finish{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="finish-message{{ $d->id }}" name="projwo_wo_number">
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="finish({{ $d->id }}); return false;">
                             Finish
                         </a>
                     </form>
                 </li>
             @endif

             {{-- @if (Auth::user()->hasRole('superadmin'))
                 <hr class="my-1">
                 <li>
                     <form class="d-inline"
                         action="{{ route('task_board.cancel', ['assignee' => 'operation', 'id' => $d->id, 'doc_type' => 'work-order']) }}"
                         method="POST" id="form-cancel{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                         </a>
                     </form>
                 </li>
                 <li>
                     <form class="d-inline"
                         action="{{ route('task_board.delete', ['assignee' => 'operation', 'id' => $d->id, 'doc_type' => 'work-order']) }}"
                         method="POST" id="form-delete{{ $d->id }}">
                         @csrf
                         @method('DELETE')
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                         </a>
                     </form>
                 </li>
             @endif --}}
         </ul>
     </div>
 </div>
