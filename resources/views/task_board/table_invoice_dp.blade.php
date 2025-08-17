 <div class="row">
     <div class="col">
         <table id="table1" class="table table-striped mt-3 table-sm">
             <thead class="table-group-divider">
                 <tr>
                     <th>Task ID</th>
                     <th>Project ID</th>
                     <th>Project Name</th>
                     <th>Customer</th>
                     <th>Taken By</th>
                     <th>Started At</th>
                     <th>Finished At</th>
                     <th>Status</th>
                     <th>Aging</th>
                     <th class="text-end">Action</th>
                 </tr>
             </thead>
             <tbody class="table-group-divider">
                 @if ($project_invoice_dp->count() == 0)
                     <tr>
                         <td colspan="100%" class="text-center">No data displayed</td>
                     </tr>
                 @else
                     @foreach ($project_invoice_dp as $d)
                         @php
                             $isMoreThan36Hours = false;
                             $isMoreThan48Hours = false;
                             $now = \Carbon\Carbon::now();
                             $started_at = \Carbon\Carbon::parse($d->projoff_started_at);
                             if ($d->projoff_started_at) {
                                 $started_at = \Carbon\Carbon::parse($d->projoff_started_at);
                                 $diffInHours = $started_at->diffInHours($now);

                                 // Cek apakah lebih dari 36 jam
                                 $isMoreThan36Hours = $diffInHours >= 36 && $diffInHours < 48;
                                 $isMoreThan48Hours = $diffInHours >= 48;
                             }
                         @endphp
                         <tr
                             class="
                        @if ($d->projinvdp_status == 'Done') table-success
                        @elseif(in_array($d->projinvdp_status, ['Started', 'On Going', 'Hold', 'Revisi Mesin']))
                            @if ($isMoreThan36Hours == true) table-warning @endif
                            @if ($isMoreThan48Hours == true) table-danger @endif
                        @endif
                        ">

                             <td>{{ $d->projinvdp_number }}</td>
                             <td><a
                                     href="{{ route('task_board.show', ['project' => $d->project_id, 'assignee' => $assignee, 'doc_type' => $doc_type]) }}">{{ $d->project->proj_number }}</a>
                             </td>
                             <td>{{ $d->project->proj_name }}</td>
                             <td>{{ $d->project->customer->cust_name }}</td>
                             <td>{{ $d->user->name ?? '-' }}</td>
                             <td>{{ $d->projinvdp_started_at ?? '-' }}</td>
                             <td>{{ $d->projinvdp_finished_at ?? '-' }}</td>
                             <td>{{ $d->projinvdp_status }}</td>
                             <td>
                                 @if (in_array($d->projinvdp_status, ['Started', 'Hold']))
                                     @php
                                         $now = \Carbon\Carbon::now();
                                         $started_at = \Carbon\Carbon::parse($d->projinvdp_started_at);
                                         $aging = '-';
                                         if ($d->projinvdp_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($now);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @elseif($d->projinvdp_status == 'Done')
                                     @php
                                         $started_at = \Carbon\Carbon::parse($d->projinvdp_started_at);
                                         $finished_at = \Carbon\Carbon::parse($d->projinvdp_finished_at);
                                         $aging = '-';
                                         if ($d->projinvdp_started_at) {
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
                                 <div class="d-inline-flex gap-1">
                                     @if ($d->projinvdp_status == 'Open')
                                         <form class="d-inline"
                                             action="{{ route('task_board.take_invoice_dp', $d->id) }}" method="POST"
                                             id="form-take{{ $d->id }}">
                                             @csrf
                                             @method('PUT')
                                             <a class="btn btn-warning btn-sm" href="#" role="button"
                                                 onclick="take_task({{ $d->id }}); return false;">Pick
                                                 Up</a>
                                         </form>
                                     @else
                                         @if ($d->user_id == auth()->user()->id)
                                             @if ($d->projinvdp_status != 'Done')
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
                                                                     href="{{ route('task_board.document_invoice_dp', $d->id) }}">
                                                                     Document Upload
                                                                 </a>
                                                             </li>
                                                             <li>
                                                                 @if ($d->projinvdp_status == 'Started')
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.permit_to_wo', $d->id) }}"
                                                                         method="POST"
                                                                         id="form-permit{{ $d->id }}">
                                                                         @csrf
                                                                         @method('PUT')
                                                                         <input type="hidden"
                                                                             id="permit-message{{ $d->id }}"
                                                                             name="message">
                                                                         <a class="dropdown-item" href="#"
                                                                             data-id="{{ $d->id }}"
                                                                             onclick="permit({{ $d->id }}); return false;">
                                                                             Permit To WO
                                                                         </a>
                                                                     </form>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.hold_invoice_dp', $d->id) }}"
                                                                         method="POST"
                                                                         id="form-hold{{ $d->id }}">
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
                                                                         action="{{ route('task_board.finish_invoice_dp', $d->id) }}"
                                                                         method="POST"
                                                                         id="form-finish{{ $d->id }}">
                                                                         @csrf
                                                                         @method('PUT')
                                                                         <input type="hidden"
                                                                             id="finish-message{{ $d->id }}"
                                                                             name="projso_so_number">
                                                                         <a class="dropdown-item" href="#"
                                                                             data-id="{{ $d->id }}"
                                                                             onclick="finish({{ $d->id }}); return false;">
                                                                             Finish
                                                                         </a>
                                                                     </form>
                                                                 @elseif($d->projinvdp_status == 'Hold')
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.continue_invoice_dp', $d->id) }}"
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
                                                                 @elseif($d->projinvdp_status == 'Approval')
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.finish_invoice_dp', $d->id) }}"
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
                                             @if ($d->projinvdp_status == 'Cancelled')
                                                 Cancelled
                                             @elseif ($d->projinvdp_status == 'Done')
                                                 Done
                                             @else
                                                 Already Taken
                                             @endif
                                         @endif
                                     @endif

                                     @if (Auth::user()->hasRole('superadmin'))
                                         <form class="d-inline"
                                             action="{{ route('task_board.cancel', ['assignee' => 'finance-accounting', 'id' => $d->id, 'doc_type' => 'invoice-dp']) }}"
                                             method="POST" id="form-cancel{{ $d->id }}">
                                             @csrf
                                             @method('PUT')
                                             <a class="btn btn-secondary btn-sm" href="#" role="button"
                                                 onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                                         </form>
                                         <form class="d-inline"
                                             action="{{ route('task_board.delete', ['assignee' => 'finance-accounting', 'id' => $d->id, 'doc_type' => 'invoice-dp']) }}"
                                             method="POST" id="form-delete{{ $d->id }}">
                                             @csrf
                                             @method('DELETE')
                                             <a class="btn btn-danger btn-sm" href="#" role="button"
                                                 onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                                         </form>
                                     @endif
                                 </div>
                             </td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
         <nav>
             {{ $project_invoice_dp->links('pagination::bootstrap-5') }}
         </nav>
     </div>
 </div>
