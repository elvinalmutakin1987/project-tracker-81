 <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
     <div class="btn-group" role="group">
         <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
             aria-expanded="false">
             Action
         </button>
         <ul class="dropdown-menu">
             <li>
                 <a class="dropdown-item" href="{{ route('task_board.document_invoice_dp', $d->id) }}">
                     Document Upload
                 </a>
             </li>

             @if ($d->projinvdp_status == 'Started')
                 @if ($d->projinvdp_permit_wo == 0)
                     <li>
                         <form class="d-inline" action="{{ route('task_board.permit_to_wo', $d->id) }}" method="POST"
                             id="form-permit{{ $d->id }}">
                             @csrf
                             @method('PUT')
                             <input type="hidden" id="permit-message{{ $d->id }}"
                                 name="projinvdp_invoice_number">
                             <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                                 onclick="permit({{ $d->id }}); return false;">
                                 Permit To Work Order
                             </a>
                         </form>
                     </li>
                 @endif
                 <li>
                     <form class="d-inline" action="{{ route('task_board.hold_invoice_dp', $d->id) }}" method="POST"
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
                     <form class="d-inline" action="{{ route('task_board.finish_invoice_dp', $d->id) }}" method="POST"
                         id="form-finish{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="finish-message{{ $d->id }}" name="projinvdp_invoice_number">
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="finish({{ $d->id }}); return false;">
                             Finish
                         </a>
                     </form>
                 </li>
             @elseif($d->projinvdp_status == 'Hold')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.continue_invoice_dp', $d->id) }}"
                         method="POST" id="form-continue{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <a class="dropdown-item" href="#" data-id="{{ $d->id }}"
                             onclick="continue_({{ $d->id }}); return false;">
                             Continue
                         </a>
                     </form>
                 </li>
             @elseif($d->projinvdp_status == 'Approval')
                 <li>
                     <form class="d-inline" action="{{ route('task_board.finish_invoice_dp', $d->id) }}" method="POST"
                         id="form-finish{{ $d->id }}">
                         @csrf
                         @method('PUT')
                         <input type="hidden" id="finish-message{{ $d->id }}" name="projinvdp_invoice_number">
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
                         action="{{ route('task_board.cancel', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
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
                         action="{{ route('task_board.delete', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
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
