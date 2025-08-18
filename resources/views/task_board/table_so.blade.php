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
                 @if ($project_sales_order->count() == 0)
                     <tr>
                         <td colspan="100%" class="text-center">No data displayed</td>
                     </tr>
                 @else
                     @foreach ($project_sales_order as $d)
                         @php
                             $isMoreThan36Hours = false;
                             $isMoreThan48Hours = false;
                             $now = \Carbon\Carbon::now();
                             $started_at = \Carbon\Carbon::parse($d->projso_started_at);
                             if ($d->projso_started_at) {
                                 $started_at = \Carbon\Carbon::parse($d->projso_started_at);
                                 $diffInHours = $started_at->diffInHours($now);

                                 $isMoreThan36Hours = $diffInHours >= 36 && $diffInHours < 48;
                                 $isMoreThan48Hours = $diffInHours >= 48;
                             }
                         @endphp
                         <tr
                             class="
                        @if ($d->projso_status == 'Done') table-success
                        @elseif(in_array($d->projso_status, ['Started', 'On Going', 'Hold', 'Revisi Mesin']))
                            @if ($isMoreThan36Hours == true) table-warning @endif
                            @if ($isMoreThan48Hours == true) table-danger @endif
                        @endif
                        ">
                             <td>{{ $d->projso_number }}</td>
                             <td><a
                                     href="{{ route('task_board.show', ['project' => $d->project_id, 'assignee' => $assignee, 'doc_type' => $doc_type]) }}">{{ $d->project->proj_number }}</a>
                             </td>
                             <td>{{ $d->project->proj_name }}</td>
                             <td>{{ $d->project->customer->cust_name }}</td>
                             <td>{{ $d->user->name ?? '-' }}</td>
                             <td>{{ $d->projso_started_at ?? '-' }}</td>
                             <td>{{ $d->projso_finished_at ?? '-' }}</td>
                             <td>{{ $d->projso_status }}</td>
                             <td>
                                 @if (in_array($d->projso_status, ['Started', 'Hold']))
                                     @php
                                         $now = \Carbon\Carbon::now();
                                         $started_at = \Carbon\Carbon::parse($d->projso_started_at);
                                         $aging = '-';
                                         if ($d->projso_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($now);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @elseif($d->projso_status == 'Done')
                                     @php
                                         $started_at = \Carbon\Carbon::parse($d->projso_started_at);
                                         $finished_at = \Carbon\Carbon::parse($d->projso_finished_at);
                                         $aging = '-';
                                         if ($d->projso_started_at) {
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
                                     @if ($d->projso_status == 'Open')
                                         <form class="d-inline"
                                             action="{{ route('task_board.take_sales_order', $d->id) }}" method="POST"
                                             id="form-take{{ $d->id }}">
                                             @csrf
                                             @method('PUT')
                                             <a class="btn btn-warning btn-sm" href="#" role="button"
                                                 onclick="take_task({{ $d->id }}); return false;">Pick
                                                 Up</a>
                                         </form>
                                     @else
                                         @if ($d->user_id == auth()->user()->id)
                                             @if ($d->projso_status != 'Done')
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
                                                                     href="{{ route('task_board.document_sales_order', $d->id) }}">
                                                                     Document Upload
                                                                 </a>
                                                             </li>

                                                             @if ($d->projso_status == 'Started')
                                                                 <li>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.hold_sales_order', $d->id) }}"
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
                                                                 </li>
                                                                 <li>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.finish_sales_order', $d->id) }}"
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
                                                                 </li>
                                                             @elseif($d->projso_status == 'Hold')
                                                                 <li>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.continue_sales_order', $d->id) }}"
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
                                                                 </li>
                                                             @elseif($d->projso_status == 'Approval')
                                                                 <li>
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
                                                                 </li>
                                                             @endif
                                                             @if (Auth::user()->hasRole('superadmin'))
                                                                 <li>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.cancel', ['assignee' => 'pre-sales', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
                                                                         method="POST"
                                                                         id="form-cancel{{ $d->id }}">
                                                                         @csrf
                                                                         @method('PUT')
                                                                         <a class="dropdown-item" href="#"
                                                                             data-id="{{ $d->id }}"
                                                                             onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                                                                         </a>
                                                                     </form>
                                                                 </li>
                                                                 <li>
                                                                     <form class="d-inline"
                                                                         action="{{ route('task_board.delete', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
                                                                         method="POST"
                                                                         id="form-delete{{ $d->id }}">
                                                                         @csrf
                                                                         @method('DELETE')
                                                                         <a class="dropdown-item" href="#"
                                                                             data-id="{{ $d->id }}"
                                                                             onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                                                                         </a>
                                                                     </form>
                                                                 </li>
                                                             @endif
                                                         </ul>
                                                     </div>
                                                 </div>
                                             @endif
                                         @else
                                             @if ($d->projso_status == 'Cancelled')
                                                 Cancelled
                                             @elseif ($d->projso_status == 'Done')
                                                 Done
                                             @else
                                                 Already Taken
                                             @endif
                                         @endif
                                     @endif

                                     {{-- @if (Auth::user()->hasRole('superadmin'))
                                         <form class="d-inline"
                                             action="{{ route('task_board.cancel', ['assignee' => 'pre-sales', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
                                             method="POST" id="form-cancel{{ $d->id }}">
                                             @csrf
                                             @method('PUT')
                                             <a class="btn btn-secondary btn-sm" href="#" role="button"
                                                 onclick="cancel({{ $d->id }}); return false;">Cancel</a>
                                         </form>
                                         <form class="d-inline"
                                             action="{{ route('task_board.delete', ['assignee' => 'sales-admin', 'id' => $d->id, 'doc_type' => 'sales-order']) }}"
                                             method="POST" id="form-delete{{ $d->id }}">
                                             @csrf
                                             @method('DELETE')
                                             <a class="btn btn-danger btn-sm" href="#" role="button"
                                                 onclick="delete_data({{ $d->id }}); return false;">Delete</a>
                                         </form>
                                     @endif --}}
                                 </div>
                             </td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
         <nav>
             {{ $project_sales_order->links('pagination::bootstrap-5') }}
         </nav>
     </div>
 </div>
